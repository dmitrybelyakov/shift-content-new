<?php
return array(

    /*
     * API routes
     * This holds routes to content api controllers
     */
    'api-content' => array(
        'type' => 'Zend\Mvc\Router\Http\Segment',
        'options' => array(
            'route' => '/api/content/',
            'defaults' => array(
                'controller' => 'ShiftContentNew\Api\ApiController',
                'action' => 'index'
            ),
            'restrict' => array('shiftkernel.canAccessBackend')
        ),
        'may_terminate' => true,
        'child_routes' => array()
    ), //content backend


);