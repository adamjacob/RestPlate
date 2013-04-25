<?php

// Info controller...all /info/ requests
class Info {
	
	// Apply our filter...
	public $filters = array('myFilter');

	// Root Method (/info/)
	public function root(){

		// Return some info...
		return array('Info Root');

	}

	// Message method [GET](/info/messages/)
	public function get_messages(){

		// Return our messages array, which will be encode into JSON.
		return array( 'messages' => array( array('Hello...'), array('Bye...') ) );

	}

	// Message method [GET](/info/name/[$name])
	// Quick Note: here we set the $name variable to null to stop undefined notice...
	public function get_name($name = null){

		// Return our name variable in an array...
		return array( 'name' => $name );

	}

}