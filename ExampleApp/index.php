<?php

// Include RestPlate
require '../RestPlate.php';

	// Create RestPlate instance
	$app = new RestPlate();

	// Tell RestPlate where to find our classes (controllers)
	$app->classPath = 'controllers';

	// Add a dummy filter...
	$app->addFilter('myFilter', function(){

		// Do some filtering stuff here...

	});

	// Init the app...
	$app->init();
