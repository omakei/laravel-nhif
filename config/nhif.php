<?php

// config for Omakei/LaravelNhif
return [

    'credentials' => [
        'username' => env('NHIF_USERNAME', ''),
        'password' => env('NHIF_PASSWORD', ''),
    ],
    'mode' => 'test', //it maybe test or production
    'url' => [
        'test' => 'http://196.13.105.15/nhifservice/breeze/',
        'production' => 'https://verification.nhif.or.tz/nhifservice/breeze/',
        'token' => 'https://verification.nhif.or.tz/nhifservice/Token/',
        'tariffs' => 'https://verification.nhif.or.tz/claimsserver/api/v1/Packages/',
        'claim' => 'https://verification.nhif.or.tz/claimsserver/api/v1/claims/SubmitFolios',
        'claim_submitted' => 'https://verification.nhif.or.tz/claimsServer/api/v1/claims/getSubmittedClaims',
        'referral' => 'https://verification.nhif.or.tz/nhifservice/breeze/verification/AddReferral',
        'pre_approved' => 'https://verification.nhif.or.tz/nhifservice/breeze/verification/GetReferenceNoStatus',
    ],

];
