<?php

return [
    'email' => [
        'filter' => FILTER_VALIDATE_EMAIL
    ],
    'name' => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => ['options' => [ 'regexp' => '#[[:alnum:]]#u' ]],
    ],
    'surname' => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => ['options' => [ 'regexp' => '#[[:alnum:]]#u' ]],
    ],
    'third_name' => [
        'filter' => FILTER_SANITIZE_STRING,
    ],
    'phone' => [
        'filter' => FILTER_SANITIZE_NUMBER_INT,
    ],
    'city' => [
        'filter' => FILTER_SANITIZE_STRING,
    ],
    'product_name' => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => ['options' => [ 'regexp' => '#[[:alnum:]]#u' ]],
    ],
    'product_price' => [
        'filter' => FILTER_VALIDATE_FLOAT,
    ],
    'category' => [
    	'filter' => FILTER_VALIDATE_INT,
    ],
];
