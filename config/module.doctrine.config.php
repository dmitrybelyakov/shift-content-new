<?php
/*
 * Doctrine module configuration
 */
return array(

    'ShiftDoctrine' => array(

        //dbal
        'dbal' => array(
            'connections' => array(
                'writer' => array(
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