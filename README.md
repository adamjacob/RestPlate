#RestPlate

A super simple PHP Restful Router. RestPlate takes a route (URL) an executes the appropriate method (controller) automatically. RestPlate was build with a RESTful API approach in mind, but not limited to. More specifically build for client side apps using javascript frameworks like [Backbone](https://github.com/documentcloud/backbone) etc.

## How it works
RestPlate takes a route (URL) an turns it into segments. It uses those segments to navigate itself to the correct method (controller).

`[GET] example.com/user/profile/1819`
RestPlate will navigate to the **user** class (user.php) an look for a **get_profile()** method. It will pass the last segment of the url (1819) to the method.

`[POST] example.com/user/edit/1819`
RestPlate will navigate to the **user** class (user.php) an look for a **post_edit()** method. It will pass the last segment of the url (1819) to the method.

`[GET] example.com/posts/` RestPlate will navigate to the **posts** class (posts.php) an look for a **get_root()** function.

`[GET] example.com` RestPlate will navigate to the **base** class (base.php) an look for a **get_root()** method. Info on changing the **base** classname see options section below.

### Notes
Anytime a method with the prefix request type `get_user()` is not found, RestPlate will default to looking for `user()` before returning a 404.

All requests without a method will automatically get forward to a root method.

## Getting Started
1. Setup a URL rewrite to point all requests to your app router, (.htaccess) included.
2. Include RestPlate `require 'RestPlate.php';`
3. Define your app `$app = new RestPlate();`
4. Initiate your app `$app->init();`

## Usage

```php
<?php

require 'RestPlate.php';

	$app = new RestPlate();
	$app->init();

```

```php
<?php

class Great {

	function me($name){

		return array( 'Hello, ' . $name );

	}

}

```
Now if we browse to `example.com/great/me/Adam` we will see "Hello, Adam". Which will be encode in JSON.


## Filters
RestPlate supports before execution filters.  These are functions that will be executed before a method is executed.  These can be used for anything from checking users credentials to logging users requests.

### Filter Usage
Using filters is pretty simple.  First add the filter to the RestPlate instance by using the **addFilter()** method as shown below. Then in the class where you want the filter to be applied, add a public **$filter** variable on the class.
```php
<?php

// Add the filter to the RestPlate instance.

	$app->addFilter("authCheck", function(){

		// Check users credentials...

	});

```

```php
<?php

// Add the filters array to our class.

	class User {
	
		public $filters = array('authCheck');

		public function edit(){

		}

	}

```
The **$filter** variable is an array, this allows you to apply multiple filters to a single class.

## Options
RestPlate has a few options built in that you can change at runtime.

`$classPath` Where your class files are stored. *Default Null*

`$json` Encode output in JSON format. *Default TRUE*

`$baseClass` The class that index routes should resolve to. *Default 'base'*

### Changing Options
```php
<?php

// Our instance is defined as $app

	$app->classPath = '/controllers/';

```
Note: options must be changed before init() method is called.

## Security Note
Its recommended that you use the **classPath** option an store all your classes in their own directory. Since RestPlates router is fully automated, this opens the door for someone to execute a method you do not want ran from a web request. By storing all your classes (controllers) in a specific directory, RestPlate will only execute classes from that directory. Stopping any unwanted method executions.

## License
RestPlate is open-sourced software licensed under the MIT License.