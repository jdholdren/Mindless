mindless-mvc
============

Lightweight and straightforward MVC framework

Usage:

The urls are by default set to: host.com/controller/action/firstParam/paramValue/secondParam/secondValue/
The framework will also figure out if the first two slashes are controller names or action names. If no action is specified, then the IndexAction is used. If no
controller is specified the HomeController will be used.

All user callable actions in the controller must start with a capital letter and end in "Action". All controllers must start with a capital letter as well and end in "Controller".
Do not use $_GET or $_POST in the controller, as they are set as properties in the controller, allowing for more encapsulation.

You can check that parameters are provided using the 'checkVars' function found in the 'Controller' class.
