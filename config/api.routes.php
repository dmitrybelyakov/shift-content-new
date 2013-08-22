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
            //'restrict' => array('shiftkernel.canAccessBackend')
        ),
        'may_terminate' => true,
        'child_routes' => array(

            //Content types api
            'types' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => 'types/',
                    'defaults' => array(
                        'controller' => 'ShiftContentNew\Api\ContentTypeApiController',
                        'action' => 'list'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(

                    //Single content type
                    'type' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => ':id/',
                            'defaults' => array(
                                'controller' => 'ShiftContentNew\Api\ContentTypeApiController',
                                'action' => 'type',
                                'id' => false
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array()
                    ), //single content type

                )
            ), //content types api

        )
    ), //content api


);