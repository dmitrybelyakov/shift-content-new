<?php
/**
 * Services configuration
 */
return array(
    'aliases' => array(
            'ContentService' => 'ShiftContent\ContentService'
        ),


        'shared' => array(
            'ShiftContentNew\Type\TypeValidator' => false,
            'ShiftContentNew\Type\Field\FieldValidator' => false,
            'ShiftContentNew\Type\Field\Validator\FieldStateValidator' => false,
            'ShiftContentNew\Type\Field\Validator\FieldSettingsValidator' => false,
            'ShiftContentNew\Type\Field\Attribute\AttributeValidator' => false,
            'ShiftContentNew\Type\Field\Attribute\Validator\AttributeStateValidator' => false,
            'ShiftContentNew\Type\Field\Attribute\AttributeOptionValidator' => false,
            'ShiftContentNew\Type\Validator\UniqueNameValidator' => false,
            'ShiftContentNew\FieldType\File\FileSettingsValidator' => false,
            'ShiftContentNew\Type\Field\Validator\FieldTypeValidator' => false,
            'ShiftContentNew\Item\ItemValidator' => false,
        ),



        'factories' => array(

            /*
             * Navigation
             */
            'BackendNavigation' => 'ShiftKernel\Navigation\BackendNavigationFactory',

            /*
             * Services
             */
            'ShiftContentNew\ContentService' => function($sm) {
                $service = new \ShiftContentNew\ContentService($sm);
                return $service;
            },
            'ShiftContentNew\Type\TypeService' => function($sm) {
                $service = new \ShiftContentNew\Type\TypeService($sm);
                return $service;
            },
            'ShiftContentNew\FieldType\FieldTypeFactory' => function($sm) {
                $service = new \ShiftContentNew\FieldType\FieldTypeFactory($sm);
                return $service;
            },
            'ShiftContentNew\FieldType\File\FileSettingsValidator' => function($sm) {
                $service = new \ShiftContentNew\FieldType\File\FileSettingsValidator($sm);
                return $service;
            },
            'ShiftContentNew\Type\TypeApiService' => function($sm) {
                $service = new \ShiftContentNew\Type\TypeApiService($sm);
                return $service;
            },
            'ShiftContentNew\Type\TypeValidator' => function($sm) {
                $service = new \ShiftContentNew\Type\TypeValidator($sm);
                return $service;
            },
            'ShiftContentNew\Type\Field\Attribute\AttributeFactory' => function($sm) {
                $service = new \ShiftContentNew\Type\Field\Attribute\AttributeFactory($sm);
                return $service;
            },
            'ShiftContentNew\Type\Field\FieldValidator' => function($sm) {
                $service = new \ShiftContentNew\Type\Field\FieldValidator($sm);
                return $service;
            },
            'ShiftContentNew\Type\Field\Validator\FieldTypeValidator' => function($sm) {
                $service = new \ShiftContentNew\Type\Field\Validator\FieldTypeValidator;
                $service->setServiceManager($sm);
                return $service;
            },
            'ShiftContentNew\Type\Field\Validator\FieldStateValidator' => function($sm) {
                $service = new \ShiftContentNew\Type\Field\Validator\FieldStateValidator;
                $service->setServiceManager($sm);
                return $service;
            },
            'ShiftContentNew\Type\Field\Validator\FieldSettingsValidator' => function($sm) {
                $service = new \ShiftContentNew\Type\Field\Validator\FieldSettingsValidator($sm);
                return $service;
            },
            'ShiftContentNew\Type\Field\Attribute\AttributeValidator' => function($sm) {
                $service = new \ShiftContentNew\Type\Field\Attribute\AttributeValidator($sm);
                return $service;
            },
            'ShiftContentNew\Type\Field\Attribute\Validator\AttributeStateValidator' => function($sm) {
                $service = new \ShiftContentNew\Type\Field\Attribute\Validator\AttributeStateValidator();
                $service->setServiceManager($sm);
                return $service;
            },
            'ShiftContentNew\Type\Field\Attribute\AttributeOptionValidator' => function($sm) {
                $service = new \ShiftContentNew\Type\Field\Attribute\AttributeOptionValidator($sm);
                return $service;
            },
            'ShiftContentNew\Type\Validator\UniqueNameValidator' => function($sm) {
                $service = new \ShiftContentNew\Type\Validator\UniqueNameValidator;
                $service->setServiceManager($sm);
                return $service;
            },
            'ShiftContentNew\Type\Field\FieldFactory' => function($sm) {
                $service = new \ShiftContentNew\Type\Field\FieldFactory($sm);
                return $service;
            },
            'ShiftContentNew\Item\ItemFactory' => function($sm) {
                $service = new \ShiftContentNew\Item\ItemFactory($sm);
                return $service;
            },
            'ShiftContentNew\Item\ItemValidator' => function($sm) {
                $service = new \ShiftContentNew\Item\ItemValidator($sm);
                return $service;
            },

        ),
);
