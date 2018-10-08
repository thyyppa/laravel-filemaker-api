<?php return [
    'file'     => env( 'FILEMAKER_FILE' ),
    'host'     => env( 'FILEMAKER_HOST' ),
    'username' => env( 'FILEMAKER_USER' ),
    'password' => env( 'FILEMAKER_PASS' ),

    'ignore-ssl-errors' => env( 'FILEMAKER_IGNORE_SSL_ERRORS', true ),
];
