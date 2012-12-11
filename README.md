zf2
===

Below is a hack to bypass extending so many of the controllers just to Authenticate the user. What i have used is as below

In your Module.php have a public function getControllerConfig() which will be called by the ModuleManager to
grab any configurations related to the Controller. In my case i had Dashboard controller. I wanted to limit 
the access to the this controller without login. 

The problem was preDispatch gets called after your Controllers __construct() so it is useless to check user identity
either in the controller or in the preDispatch . The only place is the getControllerConfig() . The problem with 
getControllerConfig is it only provides you the instance of ModuleManager through which you can get the ServiceManager
but you can not redirect using ServiceManager->get(Zend\Mvc\Controller\PluginManager')->get('redirect')->toRoute()..

So the hack was to have both the preDispatch and getControllerConfig() . 

Check the Module.php and i have left comments there. Feel free to suggest a solution to this problem....

