<?php
return array(

    /*
     * Content backend
     */
    'backend-module-content-new' => array(
        'type' => 'Zend\Mvc\Router\Http\Segment',
        'options' => array(
            'route'    => '/backend/modules/content-new/',
            'defaults' => array(
                'controller' => 'ShiftContentNew\Backend\Controller\Index',
                'action' => 'index',
            ),
            'restrict' => array('shiftkernel.canAccessBackend')
        ),
        'may_terminate' => true,
        'child_routes' => array(

            //list
            'list' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => 'list/',
                    'defaults' => array(
                        'controller' => 'ShiftContentNew\Backend\Controller\Index',
                        'action' => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array()
            ), //list

        )
    ), //content backend


);