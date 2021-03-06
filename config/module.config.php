<?php
return array(
    'ShiftContentNew' => array(),


    /*
     * ShiftKernel module additions
     */
    'ShiftKernel' => array(


        /*
         * Execute module migrations as part of postinstall process
         */
        'cli' => array(
            'migrationsSequence' => array(
                'shiftcontentnew' => 'ShiftContentNew',
            )
        ),

        /*
         * Backend navigation
         */
        'backendNavigation' => array(
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

        ), //backend navigation

    ), //kernel


    /*
     * Doctrine module configuration.
     */
    'ShiftDoctrine' => array(

        //dbal
        'dbal' => array(
            'connections' => array(
                'writer' => array(
                    'eventSubscribers' => array(
                        'contentNewListener' => 'ShiftContentNew\Doctrine\DiscriminatorSubscriber'
                    ),
                ),
                'reader' => array(
                    'eventSubscribers' => array(
                        'contentNewListener' => 'ShiftContentNew\Doctrine\DiscriminatorSubscriber'
                    ),
                ),
            )
        ),

        //orm
        'orm' => array(
            'entityManagers' => array(
                'writer' => array(
                    'metadataDrivers' => array(
                        'default' => array(
                            'mappingDirs' => array(
                                'shiftcontentnew' => __DIR__ . '/../src/ShiftContentNew',
                            )
                        )
                    )
                ),
            )
        )
    ), //doctrine

);
