<?php
/**
 * Projectshift
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file license/projectshift.mit.txt
 * It is also available through the world-wide-web at this URL:
 * http://projectshift.eu/license/mit
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@projectshift.eu so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2010 Webcomplex LLC (http://www.projectshift.eu)
 * @license    http://projectshift.eu/license/mit     MIT License
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 */

/**
 * @namespace
 */
namespace ShiftContentNew;

use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleEvent;
use Zend\EventManager\StaticEventManager;
use Zend\EventManager\Event;


/**
 * Module manifest
 * This is consumed by ModuleManager to provide configuration and autoloader
 * settings.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 */
class Module
{
    /**
     * Module config
     * Is retrieved upon modules loading from config listener.
     *
     * @var \Zend\Config\Config
     */
    protected static $config;


    /**
     * Get autoloader config
     * Returns configuration for module autoloading
     * @return array
     */
    public function getAutoloaderConfig()
    {
        $dir = __DIR__;
        $namespace = __NAMESPACE__;
        $config = array();

        $config['Zend\Loader\StandardAutoloader'] = array(
            'namespaces' => array($namespace => $dir . '/src/' .$namespace)
        );

        return $config;
    }


    /**
     * Get config
     * Returns module configuration
     * @return array
     */
    public function getConfig()
    {
        $path = __DIR__ . '/config/';

        //kernel
        $module = include $path . 'module.config.php';
        $controllers = include $path . 'controllers.config.php';
        $viewManager = include $path . 'view-manager.config.php';

        //navigation
        $backend = include $path . 'navigation/backend.config.php';
        $backend = array('ShiftKernel' => array(
            'backendNavigation' => $backend
        ));
        $navigation = $backend;

        //modules
        $kernel_module = include $path . 'module.kernel.config.php';
        $doctrine_module = include $path . 'module.doctrine.config.php';

        //routes
        $frontend = include $path . 'routes/frontend.config.php';
        $backend = include $path . 'routes/backend.config.php';
        $api = include $path . 'routes/api.config.php';
        $routes = array_merge_recursive($frontend, $backend, $api);
        $routes = array('router' => array('routes' => $routes));

        $config = array_merge_recursive(
            $controllers,
            $viewManager,
            $routes,
            $module,
            $navigation,
            $doctrine_module,
            $kernel_module
        );


		//return config
		return $config;
    }

    /**
     * Initialize module
     * Perform some setup tasks like attaching listeners to events.
     *
     * @param \Zend\ModuleManager\ModuleManager $moduleManager
     * @return void
     */
    public function init(ModuleManager $moduleManager)
    {
        //initialize config
        $moduleManager->getEventManager()->attach(
            'loadModules.post',
            array($this, 'initializeConfig')
        );

        //run on bootstrap
        StaticEventManager::getInstance()->attach(
            'bootstrap',
            'bootstrap',
            array($this, 'onBootstrap'),
            100
        );
    }

    /**
     * Initialize config
     * This handler gets run once all application modules are loaded to
     * grab merged configuration
     *
     * @param \Zend\ModuleManager\ModuleEvent $moduleEvent
     * @return void
     */
    public function initializeConfig(ModuleEvent $moduleEvent)
    {
        //grab module config
        $config = $moduleEvent->getConfigListener()->getMergedConfig();
        static::$config = $config->ShiftContentNew;
    }


    /**
     * Get module config
     * Returns merged module config
     * @return \Zend\Config\Config
     */
    public static function getModuleConfig()
    {
        return self::$config;
    }


    /**
     * On bootstrap
     * This gets triggered on application bootstrap and is used to configure
     * several aspects like view rendering and dispatch events triggering.
     *
     * @param \Zend\EventManager\Event $event
     * @return void
     */
    public function onBootstrap(Event $event)
    {
    }


} //class ends here

