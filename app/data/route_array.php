<?php

return [
    '#default#' => [
        'title' => 'Главная',
        'path' => '/',
        'template' => 'catalogue.php',
        'controller' => '\helperContent\showProducts',
    ],
	'#^\/\?auth=yes$#' => [
		'template' => 'authorization.php',
	],
	'#^\/admin\/\?orders=yes$#' => [
		'title' => 'Заказы',
        'path' => '/admin/?orders=yes',
        'rights' => [
            'admin',
            'operator'
        ],
		'template' => 'admin/orders.php',
		'controller' => '\helperContent\showOrders'
	],
	'#^\/admin\/\?products=yes$#' => [
		'title' => 'Товары',
        'path' => '/admin/?products=yes',
        'rights' => [
            'admin'
        ],
		'template' => 'admin/productsList.php',
		'controller' => '\helperContent\showProductList'
	],
	'#^\/admin\/\?(chng|add)_product=yes#' => [
		'template' => 'admin/add.php',
        'controller' => '\helperContent\showProduct',
		'rights' => [
            'admin'
        ],
	],
    [
        'title' => 'Новинки',
        'path' => '/?filter=new',
        'rights' => [
            'registered',
            'guest'
        ],
    ],
    [
        'title' => 'Sale',
        'path' => '/?filter=sale',
        'rights' => [
            'registered',
            'guest'
        ],
    ],
	'#^\/delivery$#' => [
        'title' => 'Доставка',
        'path' => '/delivery',
        'rights' => [
            'registered',
            'guest'
        ],
        'template' => 'delivery.php',
    ],
    [
        'title' => 'Войти',
        'path' => '/?auth=yes',
        'rights' => [
            'guest'
        ],
    ],
    [
        'title' => 'Выйти',
        'path' => '/?execFunc=unAuth',
        'rights' => [
            'admin',
            'operator',
            'registered',
        ],
    ],
];