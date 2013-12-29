Mindless MVC Framework v1.0.0
=============

Only 15 KB, the Lightweight and Straightforward MVC Framework
___________________

## Two Step Intallation

Mindless is incredibly easy to install, and only requires simple file manipulation.

1. Download Mindless MVC as a zip
2. Extract the contents of the "Mindless" folder (The one that contains "index.php" and "app") into the root folder of the directory where you wish to install the framework.

## Configuration

All configuration settings are stored in "app/config.php". Enter your MySQL connection details in the 'db' array, or set 'use_db' to false to prohibit the framework from automatically connecting.

## Routing

Two types of routing are supported, and can be supported simultaneously.

1. Vanilla Routing

	This is the most basic routing available. The framework will attempt to match a pattern to the request URI.
	For example: mysite.com/user/james/

	It will first check if "user" is a valid controller name. If it is, the framework knows that request is specifying the controller name, and will then check if "james" is a valid action on the User controller. If it is, it calls the function JamesAction(). Otherwise, it defaults to IndexAction(). If "user" is not a valid controller, it defaults to the "Home" controller.

	All other parts separated by slashes are treated as parameters and passed to the Action.

2. Defined Routing
	
	Specific routes can be defined in "app/http/routing.php". They also take precedent over vanilla routes.

	To enable ONLY defined routes and disallow vanilla routing, set 'strict_routing' to true in "config.php".

	To define a route, you must call $router->route() like so:
	route(request method, uri string pattern, controller name (excluding the 'Controller' on the end), entire action name)

	For example:

	```php
	$router->route('post', '/signup/:id', 'User', 'newUserFunction');
	```

	This will cause any URI matching the pattern of mysite.com/signup/someRandomParameter to call the "newUserFunction" action on the "User" controller, with "someRandomParameter" provided as an argument.


## Controllers and Actions

All controllers are stored within the "app/controllers" folder. To make a new controller, first name the file "NameController.php". Then, in the file, you must have at least the following:

```php
class NameController extends Controller {
	public function __construct() {
		parent::__construct();
	}
}
```

And that's it.

### Method Types

For Actions, there are two types:

1. Method Agnostic Actions
	These actions do not care for the request method, and are defined like this:

	```php
	class UserController extends Controller {
		public function SomeAction() {
			// More code here
		}
	}
	```

	In vanilla routing, the request for mysite.com/user/some will call this function.

2. Method Specific Actions
	These actions are specified for a certain request method, i.e. Post, Get, Delete, etc. Please note that these actions take precedent over the method agnostic ones.
	To define a method specific action:

	```php
	class UserController extends Controller {
		public function SomeActionPost() {
			// More code here
		}
	}
	```

	If mysite.com/user/some is called using the post method, then this action will be called.

Please Note: Both types of methods can be used. For example:

```php
class UserController extends Controller {
	public function SomeAction() {
		// This function is called as a fallback
	}
	public function SomeActionPost() {
		// This function is called only if the "Some" action is called on the "User" controller
		// using the "POST" method
	}
}
```

Here, if mysite.com/user/some is requested with the POST method, "SomeActionPost" will be called. However, if any other method is used, it defaults to "SomeAction". 

### Parameters

Parameters can be passed to actions by specifying arguments in the function:

```php
public function SomeAction($email, $id) {
	
}
```

Though you must manually check that they are supplied to the function.

### Lazy Loading

There is no need to require models individually since lazy loading is enabled. Hence, when creating actions, you can simply begin to use model classes as though they were already defined:

```php
public function SomeAction() {
	$user = new User();
}
```

The framework will automatically include the "User" class, thus reducing the number of files to only the ones needed per request.

## Models

All models are stored in the "app/models" folder. They have no naming requirements other than the name of the file is the same as the class name.

For example, when constructing a "User" model, first create a "User.php" file in "app/models". Then write in the "User.php" file:

```php
class User extends Model {
	public function __construct() {
		parent::__construct();
	}
}
