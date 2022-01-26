<?php

return [
	'class_level' => [
		1 => 'I',
		2 => 'II',
		3 => 'III',
		4 => 'IV',
		5 => 'V',
		6 => 'VI',
		7 => 'VII',
		8 => 'VIII',
		9 => 'IX',
		10 => 'X',
		11 => 'XI',
		12 => 'XII',
	],
	'sms_details' => [
		'api_key' => 'c4d3ef07-49ff-48a1-a585-a40b5a4ec0e2',
		'api_secret' => '0dySQa3utxa67cfU',
		'username' => 'nookesh'
	],
	'OTP_TIME_LEFT' => '10:00', /* OTP_TIME_LEFT - Time in min and sec*/
	'FEATURED_SCHOOL_LIMIT' => 10,
	'BASIC_SCHOOL' => 1,
	'SENIOR_HIGH' => 2,
	'UNIVERSITY' => 5,
        'themes' => ['air', 'corporate', 'cotton', 'gradient', 'paper', 'shadow', 'soft', 'sunrise', 'twitlight', 'vibrant'],
        'default_theme' => 'corporate',
    
        'TWILIO' => [
            'SID' => env('TWILIO_SID', ''),
            'TOKEN' => env('TWILIO_AUTH_TOKEN', ''),
            'SERVICE_SID' => env('TWILIO_SERVICE_SID', ''),
            'NUMBER' => env('TWILIO_NUMBER', ''),
        ],

		'currency' => '<i class="fas fa-dollar-sign"></i>',
];
