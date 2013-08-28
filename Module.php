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

use Zend\Module\Consumer\AutoloaderProvider;
use Zend\Module\Manager;
use Zend\Module\ModuleEvent;
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
class Module implements AutoloaderProvider
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
        $config = require __DIR__ . '/config/module.config.php';

        //add routes
        $routes = require_once __DIR__ . '/config/routes.config.php';
        $backendRoutes = require_once __DIR__ . '/config/backend.routes.php';
        $apiRoutes = require_once __DIR__ . '/config/api.routes.php';
        $routes = array_merge_recursive($routes, $backendRoutes, $apiRoutes);

        $router = 'Zend\Mvc\Router\RouteStack';
        $routerParams = array('parameters' => array('routes' => $routes));
        $config['di']['instance'][$router] = $routerParams;

        //add templates
        $map = require_once __DIR__ . '/config/views.config.php';
        $resolverParams = array('parameters' => array('map' => $map));
        $resolver = 'Zend\View\Resolver\TemplateMapResolver';
        $config['di']['instance'][$resolver] = $resolverParams;

		//return config
		return $config;
    }

    /**
     * Initialize module
     * Perform some setup tasks like attaching listeners to events.
     *
     * @param \Zend\Module\Manager $moduleManager
     * @return void
     */
    public function init(Manager $moduleManager)
    {
        //initialize config
        $moduleManager->events()->attach(
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
     * @param \Zend\Module\ModuleEvent $moduleEvent
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
        $locator = $event->getParam('application')->getLocator();

        //attach layout listener
        $backendLayout = $locator->get(
            'ShiftContentNew\Listener\BackendLayoutListener'
        );
        $event->getParam('application')
            ->events()
            ->attachAggregate($backendLayout);
    }


} //class ends here

