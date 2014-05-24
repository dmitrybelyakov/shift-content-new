<?php
return array(

    /*
     * API routes
     * This holds routes to content api controllers
     */
    'api-content-new' => array(
        'type' => 'Zend\Mvc\Router\Http\Segment',
        'options' => array(
            'route' => '/api/content-new/',
            'defaults' => array(
                'controller' => 'ShiftContentNew\Api\Api',
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
                        'controller' => 'ShiftContentNew\Api\TypeApi',
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
                                'controller' => 'ShiftContentNew\Api\TypeApi',
                                'action' => 'type',
                                'id' => false
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array()
                    ), //single content type

                )
            ), //content types api

            //Field types api
            'fields' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => 'field-types/',
                    'defaults' => array(
                        'controller' => 'ShiftContentNew\Api\FieldTypesApi',
                        'action' => 'list'
                    ),
                ),
                'may_terminate' => true,
            ), //field types api

        )
    ), //content api


);