<?php
return array(

    /*
     * Module templates
     */
    'shiftcontent-new.backend.layout'=> __DIR__ . '/../views/layout.phtml',


    /*
     * Views
     */
    'shiftcontent-new.view.empty' => __DIR__ . '/../views-processed/empty.html',


    /*
     * Grunt-processed parts
     */

    'shiftcontent-new.grunt.shell' => __DIR__ . '/../views-processed/shell.html',
    'shiftcontent-new.grunt.styles' => __DIR__ . '/../views-processed/styles.html',
    'shiftcontent-new.grunt.scripts' => __DIR__ . '/../views-processed/scripts.html',


    /*
     * API response template
     * Used to view API responses in browser.
     */
    'shiftcontent-new.api.response' => __DIR__ . '/../views/api/response.phtml',
);