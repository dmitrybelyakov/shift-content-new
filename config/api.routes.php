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
                'action' => 'list'
            ),
            //'restrict' => array('shiftkernel.canAccessBackend')
        ),
        'may_terminate' => true,
        'child_routes' => array(

            //Single item
            'item' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => ':id/',
                    'defaults' => array(
                        'controller' => 'ShiftContentNew\Api\ApiController',
                        'action' => 'item',
                        'id' => false
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array()
            ), //single item

        )
    ), //content api


);