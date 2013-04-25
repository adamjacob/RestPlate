<?php

# -----------------------------------------------------
# RestPlate - Simple PHP Restful Router - V1
# -----------------------------------------------------
# Adam Stanford <adam@adamstanford.net> (MIT License)
# https://github.com/adamjacob/RestPlate
# -----------------------------------------------------

class RestPlate {

	# Where your class files are stored
	public $classPath = '';

	# JSON encoding
	public $json = true;

	# Base class for index routes
	public $baseClass = 'base';

	protected $request, $requestType, $filters = array();

	# Our basic init method where we setup our environment an get the ball rolling...
	public function init(){

		# Grab the request info
		$this->request = array_values ( array_filter( explode( '/', trim( $_SERVER["REQUEST_URI"] ) ) ) );
		$this->requestType = $_SERVER['REQUEST_METHOD'];

			# Setup some vars...
			$class = NULL;

			if(isset($this->request[0])){

				// Our first key is the class...
				$class = $this->request[0];

			}else{

				$class = $this->baseClass;

			}
			
			if(isset($this->request[1])){

				// Our second key is the method...
				$method = $this->request[1];

			}else{

				// No method, just set as NULL to stop undefined index errors...
				$method = 'root';

			}

		# Check if class file exists
		if( isset ($class) && file_exists( $this->classPath . '/' . $class . '.php' ) ){

			# Include the class file
			include( $this->classPath . '/' . $class . '.php');

			# Create class instance
			$class = new $class();

				// Resolve any filters on this class
				$this->resolveFilters($class);

				// Resolve the requested method
				$this->resolveMethod($class, $method);

		}else{

			// Class was not found, 404
			header("HTTP/1.0 404 Not Found");

		}

	}

	# This is where the magic happens, well some of it.
	protected function resolveMethod($class, $method){

		if( !isset($class) || !isset($method) ){

			// We don't have time for this, 404 this...
			$this->returnError('404');

		}

		# Create array of remaining route items to be passed to method
		$prams = $this->resolveRemainingPrams($this->request);

		# Check if method with 'request type prefix' exists in class
		if( method_exists($class, strtolower( $this->requestType ) . '_' . $method) ){

			// Unset method from prams
			$prams = $this->resolveRemainingPrams($prams);

			// Finally call the method, with request type prefix...
			$this->output( call_user_func_array( array($class, strtolower( $this->requestType ) . '_' . $method),  $prams) );

			return true;

		# Check if method WITHOUT 'request type prefix' exists in class
		}elseif(method_exists($class, $method)){

			// Unset method from prams
			$prams = $this->resolveRemainingPrams($prams);

			// Finally call the method
			$this->output( call_user_func_array( array($class, $method),  $prams) );

			return true;

		}else{

			// How sad...no methods found, 404 this...
			$this->returnError('404');

		}

	}

	# Helper function that prepares remaining route prams for passing to function.
	protected function resolveRemainingPrams($prams){

		// Remove class...
		unset($prams[0]);

		// Reset array keys, an return
		return array_values($prams);

	}

	# Helper function for printing out the data...
	protected function output($data){

		if( isset($data) ){

			if($this->json == true){

				echo json_encode( $data );

			}else{

				echo $data;

			}

		}

	}

	# Simple helper function for error handeling....
	protected function returnError($error){

		switch($error){

			case "404":

				header("HTTP/1.0 404 Not Found");

				die;

			break;

		}

	}

	/*
	|	Route Filters
	|	Helper functions that allow you to execute filters
	|	before a routes destination method is executed.
	*/
		# Checks if filters exist, if so execute them
		protected function resolveFilters($class){

			# Check if this class has filters...
			if( isset($class->filters) && count($class->filters) != 0 ){

				# Loop through the filters
				foreach ($class->filters as $filter) {

					# Check if filter method exists
					if(isset($this->filters[$filter])){

						# Now loop through an execute the filter functions
						foreach ($this->filters[$filter] as $actualFilter) {

							call_user_func($actualFilter);

						}

					}

				}

			}

		}

		# Adds filters to the ($this->filters) array to be executed...
		public function addFilter($filterName, $filterFunction){

			# Add filter to filter array, to be executed...
			$this->filters[$filterName] = array();
			array_push($this->filters[$filterName], $filterFunction);

		}

}