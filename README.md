mindless-mvc
============

Lightweight and straightforward MVC framework

Installation

Just copy the contents of mvc into the folder you wish to use. Then set your db details in the 'basemodel.php' file

Actions:

Actions defined in the controller end in 'Action' and the beginning has its first letter capitalized while the rest are lower case.
For example, public function SomeAction(). They can accept parameters, and these come from the url. Actions must be public.

URLS:

The URLs follow this format, assuming it's installed at the doc root: host.com/controller/action/paramValue/paramValue/
The controller and action will be the first two, but they dont need to be there. If a controller is not defined, as in:
host.com/action/value/value, then the HomeController will be callled. If no action is defined, then the IndexAction is called.

Autoloader:

There is an autoloader for models. Models must be named the same as the file, i.e. User must have file name User.php in the models folder.
