<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Temp Password Feature Toggle
    |--------------------------------------------------------------------------
    |
    | This option allows you to enable or disable the temporary password
    | functionality globally. Set the environment variable TEMP_PASS_ENABLED
    | to false to disable it without removing the package.
    |
    */
    'enabled' => env('TEMP_PASS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Temp Password Expiry Time (in Minutes)
    |--------------------------------------------------------------------------
    |
    | This option controls how long a temporary password remains valid
    | after it is generated. After this duration (default: 5 minutes),
    | the temp password will be rejected even if unused.
    |
    */
    'expiry_minutes' => env('TEMP_PASS_EXPIRY_TIME', 5),

    /*
    |--------------------------------------------------------------------------
    | Temp Password Length
    |--------------------------------------------------------------------------
    |
    | This option controls the length of the generated temporary password.
    | The default length is 8 characters. You can adjust this value
    | according to your security requirements.
    |
    */
    'lenght' => env('TEMP_PASS_LENGTH', 8),

    /*
    |--------------------------------------------------------------------------
    | Temp Password Strength
    |--------------------------------------------------------------------------
    |
    | This option controls the strength of the generated temporary password.
    | The default strength is 'medium'. You can set it to 'simple', 'medium',
    | or 'strong' to adjust the complexity of the generated password.
    |
    | Options: simple, medium, strong
    | 'simple' - only lowercase letters
    | 'medium' - letters (upper & lower) + numbers
    | 'strong' - lowercase letters, numbers, and special characters
    */
    'strength' => env('TEMP_PASS_STRENGTH', 'medium'),
];
