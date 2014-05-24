<?php
/*
 * ShiftKernel module configuration
 */
return array(
    /*
     * ShiftKernel module additions
     */
    'ShiftKernel' => array(


        //execute module migrations as part of postinstall process
        'cli' => array(
            'migrationsSequence' => array(
                'shiftcontentnew' => 'ShiftContentNew',
            )
        ),

    ), //kernel
);