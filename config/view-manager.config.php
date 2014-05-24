<?php
/**
 * View manager config
 * This holds configuration for the view manager
 */
return array(
    'view_manager' => array(
        'template_map' => array(
            'shiftcontent-new.backend.layout'=> __DIR__ . '/../views/layout.phtml',
            'shiftcontent-new.view.empty' => __DIR__ . '/../views-processed/empty.html',


            //grunt-processed parts
            'shiftcontent-new.grunt.shell' => __DIR__ . '/../views-processed/shell.html',
            'shiftcontent-new.grunt.styles' => __DIR__ . '/../views-processed/styles.html',
            'shiftcontent-new.grunt.scripts' => __DIR__ . '/../views-processed/scripts.html',
        )
    )
);