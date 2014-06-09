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
                                'shiftcontentnew.items' => __DIR__ . '/../src/ShiftContentNew/Item',
                                'shiftcontentnew.type' => __DIR__ . '/../src/ShiftContentNew/Type',
                                'shiftcontentnew.field.types' => __DIR__ . '/../src/ShiftContentNew/FieldType',
                                'shiftcontentnew.field.values' => __DIR__ . '/../src/ShiftContentNew/FieldValue',
                            )
                        )
                    )
                ),
            )
        )
    ), //doctrine
);