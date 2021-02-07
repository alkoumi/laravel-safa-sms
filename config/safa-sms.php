<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Account Details
    |--------------------------------------------------------------------------
    |
    | Set your Username and Password used to log in to
    | https://www.safa-sms.com/
    |
    */

    'username' => env('SAFA_SMS_USERNAME'),
    'password' => env('SAFA_SMS_PASSWORD'),

    // Name of Formal Sender & Ads Sender must be apporved by www.safa-sms.com for GCC
    'formal_sender' => env('SAFA_SMS_FORMALSENDER'),
    'ads_sender' => env('SAFA_SMS_ADSSENDER'),

    // Admin Mobile to notify & Balance to Notify Admin when get this Number
    'admin_email' => env('SAFA_SMS_ADMINEMAIL', 'admin@example.com'),

    /*
    |--------------------------------------------------------------------------
    | Universal Settings Required by www.safa-sms.com
    |--------------------------------------------------------------------------
    |
    | You do not need to change any of these settings.
    |
    |
    */

    // The Base Uri of the Api. Don't Change this Value.
    'base_uri' => 'https://www.safa-sms.com/api/',

    // The Send Uri of the Api. Don't Change this Value.
    'sendEndpoient' => 'sendsms.php?',

    // The Balance Uri of the Api. Don't Change this Value.
    'balanceEndpoient' => 'getbalance.php?',

];
