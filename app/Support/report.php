<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Issue Types & Status Mapping
    |--------------------------------------------------------------------------
    | Define the list of issues that can be reported on equipment or units.
    | Each issue automatically maps to a status update in your system.
    |--------------------------------------------------------------------------
    */

    'issues' => [
        'Not Turning On' => 'Defective',
        'Slow Performance' => 'Under Maintenance',
        'Overheating' => 'Under Maintenance',
        'Peripheral Not Detected' => 'Defective',
        'Physical Damage' => 'Defective',
        'Other' => 'Under Maintenance',
    ],

];
