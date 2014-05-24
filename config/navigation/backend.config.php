<?php
/*
 * Backend navigation
 */
return array(
    'modules' => array(
        'pages' => array(
            'content-new' => array(
                'label' => 'Content New',
                'route' => 'backend-module-content-new',
                'pages' => array(
                    'media' => array(
                        'label' => 'Manage content',
                        'uri' => '',
                        'pages' => array(
                            'list' => array(
                                'label' => 'List',
                                'route' => 'backend-module-content-new/list',
                            ),
                        )
                    ),
                ),
            ),
        ),
    ),
);