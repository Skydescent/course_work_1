<?php
namespace helperContent;

if (isset($_REQUEST['execFunc'])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/helpers/helperDb.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/data/constants.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
    switch ($_REQUEST['execFunc']) {
        case 'makeProducts':
            makeProducts();
            break;
        case 'changeOrdStatus':
            changeOrdStatus();
            break;
        case 'deactiveProduct':
            deactiveProduct();
            break;
        case 'getProudctInfo':
            getProudctInfo();
            break;
         case 'changeProduct':
            manageProduct('changeProduct');
            break;
        case 'addProduct':
            manageProduct('addProduct');
            break;
        case 'newOrder':
            newOrder();
            break;
    }
}

/**
*Функция считвыает запрашиваемый URI и формирует
* контент страницы исходя из структуры /data/route_array.php
*/
function route ()
{
    $route = include $_SERVER['DOCUMENT_ROOT'] . '/data/route_array.php';
    $settings = NULL;
    foreach ($route as $key => $params) {
        if (gettype($key) == 'string' && preg_match($key, $_SERVER['REQUEST_URI']) !== 0) {
            $settings = $params;
            break;
        }
    }
    $settings = is_null($settings) ? $route['#default#'] : $settings;
    $template = isset($settings['template']) ? $settings['template'] : NULL;
    $controller = isset($settings['controller']) ? $settings['controller'] : NULL;

    $userGroup = isset($_SESSION['auth_subsystem']['user_group']) ? $_SESSION['auth_subsystem']['user_group'] : 'guest';
    if (isset($settings['rights']) && array_search($userGroup, $settings['rights']) === false) {
        include $_SERVER['DOCUMENT_ROOT'] . '/template/noRights.php';
        return;
    }
    if (!is_null($controller)) {
        call_user_func($controller, $template);
        return;
    }
    if (!is_null($template)) {
        include $_SERVER['DOCUMENT_ROOT'] . '/template/' . $template;
    }

}

/**
*Функция принимает через супермассив $_REQUEST
* данные нового заказа и добавляет заказ в БД
* в случае ошибки направляет json с ошибкой
*/
function newOrder()
{
    $productId = $_REQUEST['prod_id'];
    $data = array_diff($_REQUEST, [$_REQUEST['execFunc'], $_REQUEST['prod_id']]);
    $data = validateUserData($data);
    if (array_key_exists('error_fields', $data)) {
        die(json_encode(['error' => $data['error_fields']]));
    } elseif (array_key_exists('safe_data', $data)) {

        if (isset($_SESSION['auth_subsystem']['is_authorized']) &&
            $_SESSION['auth_subsystem']['is_authorized'] === true
        ) {
            $data['safe_data']['user_id'] = $_SESSION['auth_subsystem']['user_id'];
        } else {
            $data['safe_data']['user_id'] = null;
        }

        if ($data['safe_data']['delivery'] == 'dev-yes') {
            $data['safe_data']['delivery'] = '1';
            $data['safe_data']['delivery_cost'] = 280;
            $data['safe_data']['order_cost'] = ((float) $data['safe_data']['order_cost']) + DELIVERY_COST;
        } else {
            $data['safe_data']['delivery'] = '0';
            $data['safe_data']['delivery_cost'] = '0';
        }
        $data['safe_data']['created_at'] = date(DB_DATE_FORMAT);
        $data['safe_data']['status'] = '0';
        \helperDb\getQuery(DB_ADD_NEW_ORDER, $data['safe_data']);

        $orderId = \helperDb\getQuery(DB_GET_LAST_ID)[0]["LAST_INSERT_ID()"];
        \helperDb\getQuery(DB_ADD_ORDER_PRODUCT_LINK, ['order_id' => $orderId, 'product_id' => $productId]);
        die(json_encode(['success' => true]));
    }
}

/**
*Функция принимает массив с данными,
* в том числе с пользовательскими и возвращает обработанный массив
* с экранированным html, а также отфильтрованный по соответствующим полям
* @param array $data - массив с невалидированными данными
* @param string $validKey - в случае если есть вложенный массив с именем, валидация происходит по этому ключу
* @param array $postfilter - массив с фильтрами валидации
* @param array $errorfields - массив с ошибками, возникшими в валидации - в случае если происходит рекурсивный вызов
* @return array массив с валидными данными, либо массив с наименованием полей
* в которых возникла ошибка при валидации и фильтрации
*/
function validateUserData(
    array &$data,
    string $validKey = NULL,
    array $postfilter = NULL,
    array &$errorFields = []
) : array
{
    if (is_null($postfilter)) $postfilter = require_once $_SERVER['DOCUMENT_ROOT'] . '/data/validateFilt.php';
    foreach ($data as $key => &$value) {
        if (gettype($value) == 'array') {
            validateUserData($value, $key, $postfilter, $errorFields);
        } else {
            $value = htmlspecialchars(trim($value));
            $filterKey = (gettype($key) == 'integer' && !is_null($validKey)) ? $validKey : $key;

            if (array_key_exists($filterKey, $postfilter)) {
                if (isset($postfilter[$filterKey]['options'])) {
                    $value = filter_var($value, $postfilter[$filterKey]['filter'], $postfilter[$filterKey]['options']);
                } else {
                    $value = filter_var($value, $postfilter[$filterKey]['filter']);
                }
                if ($value === false) {
                    $errorFields[] = $filterKey;
                }
            }
        }
    }

    if(count($errorFields) !== 0) {
        return ['error_fields' => $errorFields];
    } else {
        return ['safe_data' => $data];
    }
}

/**
*Функция принимает файл, проверяет его тип и загружает
* в директорию IMG_PATH
* @param $file - загружаемый файл
*/
function uploadFile($file)
{
    $fileTypesArr = [
        'image/jpeg',
        'image/png',
        'image/jpg',
    ];

    $isCorrectFileType = array_search(mime_content_type($file['tmp_name']), $fileTypesArr) !== false;

    $errorMsg = '';

    $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/' . IMG_PATH;

    if ($file['error'] !== 0) {
        $errorMsg .= ' В файле ' . $file['name'] . ' произошла ошибка!';
    }

    if (!$isCorrectFileType) {
        $errorMsg .= ' Файл ' . $file['name'][$i] . ' не является изображением';
    }

    if ($errorMsg !== '') return ['file_error' => $errorMsg];

    move_uploaded_file(
        $file['tmp_name'],
        $uploadPath . $file['name']
    );

    return ['file_path' => IMG_PATH . $file['name']];
}

/**
*Функция принимает через супермассив $_REQUEST
* данные категории, фильтров цены, распродажи, новинок
* по которой будет осуществлен запрос массива с товарами,
* для отправления html клиенту
*/
function makeProducts()
{
    $additionSql = '';
    $filterNames = [
        'min_price' => 'p.price > ',
        'max_price' =>'p.price < ',
        'new' => 'p.is_new = ',
        'sale' => 'p.is_sale = '
    ];
    foreach ($filterNames as $name => $sql) {
        if(isset($_REQUEST[$name])) {
            $additionSql .= ' AND ' . $sql . $_REQUEST[$name] . ' ';
        }
    }

    $categoryId = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : '1';

    $products = \helperDb\getQuery(
            DB_GET_PRODUCTS_BY_CATEGORY . $additionSql,
            ['category_id' => $categoryId]
        );
    //return $products;
    include $_SERVER['DOCUMENT_ROOT'] . '/template/products.php';
}

/**
*Функция принимает данные из супермассива $_REQUEST и меняет статус заказа в БД
*/
function changeOrdStatus()
{
    $id = $_REQUEST['id'];
    $status = $_REQUEST['status'];
    \helperDb\getQuery(DB_CHANGE_ORD_STATUS, ['id' => $id, 'status' => $status]);
}

/**
*Функция принимает данные из супермассива $_REQUEST и деактивирует продукт
*/
function deactiveProduct()
{
    \helperDb\getQuery(DB_DEACTIVE_PRODUCT, ['id' => $_REQUEST['id']]);
}

/**
*Функция принимает данные из супермассива $_REQUEST и возвращает информацию о продукте
*/
function getProudctInfo()
{
    \helperDb\getQuery(DB_GET_PRODUCT_INFO, ['id' => $_REQUEST['id']]);
}

/**
*Функция принимает данные из супермассива $_REQUEST, $_FILES делает валидацию данных
* и изменят данные о товаре либо добавлеяет новый товар в зависимости от $action
* @param string $action принимает аргумент 'addProduct' для добавления товара, 'changeProduct' для изменения товара
*/
function manageProduct(string $action)
{
    $uploadResult = NULL;
    if (isset($_FILES['product-photo']) && $_FILES['product-photo']['name'] !== '') {
        $uploadResult = (uploadFile($_FILES['product-photo']));
    }

    $rowData = validateUserData($_REQUEST);

    if (isset($rowData['error_fields']) || isset($uploadResult['file_error'])) {
        die(json_encode(
            [
                'error' => isset($rowData['error_fields']) ? $rowData['error_fields'] : '' ,
                'file_error' => isset($uploadResult['file_error']) ? $uploadResult['file_error'] : ''
            ]
        ));
    } elseif (array_key_exists('safe_data', $rowData)) {
        $categories = $rowData['safe_data']['category'];
        $data = array_diff(
            $rowData['safe_data'],
            [
                $rowData['safe_data']['execFunc'],
                $rowData['safe_data']['category'],
            ]
        );
        if ($action == 'addProduct') {
        	addProduct($data, $uploadResult, $categories);
        } else if ($action == 'changeProduct') {
        	changeProduct($data, $uploadResult, $categories);
        }
    }
}

/**
*Функция принимает данные об изменяемом товаре и делает запрос в БД для изменения
* @param array $data данные изменяемого товара
* @param $uploadResult - массив с данными о файле фотографии, либо NULL если фотография не была добавлена
* @param array $categories - категории данного товара
*/
function changeProduct(array $data, $uploadResult, array $categories)
{
	if (!is_null($uploadResult)) {
        $data['img_path'] = $uploadResult['file_path'];
    }


    $sql = 'UPDATE products SET ';
    $endSql = ' WHERE id = :product_id';

    foreach ($data as $key => &$value) {
        if ($key == 'product_id') {
            continue;
        }

        $sqlField = $key;

        if (strpos($key, 'product_') !== false) {
            $sqlField = substr($key, 8 - strlen($key));
        }
        if ($key == 'is_new' || $key == 'is_sale') {
            $value = $value == 'on' ? '1' : '0';
        }

        $sql .= $sqlField . ' = ' . ':' . $key;
        $sql .= ', ';
    }

    $sql = substr($sql, 0, -2) . $endSql;

    \helperDb\getQuery($sql, $data);

    \helperDb\getQuery(DB_DELETE_PRODUCT_LINKS, ['product_id' => $data['product_id']]);

    if (!in_array(1, $categories)) {
    	array_push($categories, '1');
    }

    addCategories($categories, $data);
    die(json_encode(['success' => true]));
}

/**
*Функция принимает данные о добавляемом товаре и делает запрос в БД для добавления
* @param array $data данные товара
* @param $uploadResult - массив с данными о файле фотографии, либо NULL если фотография не была добавлена
* @param array $categories - категории данного товара
*/
function addProduct(array $data, $uploadResult, array $categories)
{
	$data['img_path'] = !is_null($uploadResult) ? $uploadResult['file_path'] : 'no_image';
	foreach (['is_sale', 'is_new'] as $key) {
		if(!isset($data[$key])) {
			$data[$key] = '0';
		} else {
			$data[$key] = '1';
		}
	}
	\helperDb\getQuery(DB_ADD_PRODUCT, $data);
	array_push($categories, '1');
	$data['product_id'] = \helperDb\getQuery(DB_GET_LAST_ID)[0]["LAST_INSERT_ID()"];
    addCategories($categories, $data);
    die(json_encode(['success' => true]));
}

/**
*Функция принимает данные о категоряиях товара и деает запросы в БД для добавления связей товар-категория
* @param array $data данные товара
* @param array $categories - категории данного товара
*/
function addCategories(array $categories, array $data)
{
	foreach ($categories as $category) {
        \helperDb\getQuery(
            DB_ADD_PRODUCT_CATEGORY_LINK,
            [
                'product_id' => $data['product_id'],
                'category_id' => $category
            ]
        );
    }
}

/**
*Функция отображает меню обрабатывая массив route_array.php и добавляя пункты меню если внутри элемента есть ключ 'title'
*/
function showMenu()
{
    $menuPoints = include $_SERVER['DOCUMENT_ROOT'] . '/data/route_array.php';
    $userGroup = isset($_SESSION['auth_subsystem']['user_group']) ? $_SESSION['auth_subsystem']['user_group'] : 'guest';
    include $_SERVER['DOCUMENT_ROOT'] . '/template/menu.php';
}

/**
*Функция делает запрос к базе данных и добавляет шаблон с отображением каталога товаров на главной странице
* @param string $template - шаблон с каталогом продуктов
*/
function showProducts (string $template)
{
    $categories = \helperDb\getQuery(DB_GET_CATEGORIES);
    require_once $_SERVER['DOCUMENT_ROOT'] . '/template/' . $template;
}

/**
*Функция делает запрос к базе данных и добавляет шаблон с отображением заказов в админ панели
* @param string $template - шаблон с заказами
*/
function showOrders(string $template)
{
    $orders = \helperDb\getQuery(DB_GET_ORDERS);
    require_once $_SERVER['DOCUMENT_ROOT'] . '/template/' . $template;
}

/**
*Функция делает запрос к базе данных и добавляет шаблон с отображением товаров в админ панели
* @param string $template - шаблон с продуктами
*/
function showProductList(string $template)
{
	$sqlOrderByDesc = DB_GET_PRODUCTS_BY_CATEGORY . ' ORDER BY p.id DESC';
    $products = \helperDb\getQuery($sqlOrderByDesc, ['category_id' => '1']);
    $categories = \helperDb\getQuery(DB_GET_CATEGORIES_FOR_PRODUCTS);
    require_once $_SERVER['DOCUMENT_ROOT'] . '/template/' . $template;
}



/**
*Функция формирует данные либо для добавления либо для изменения товара исходя из GET запроса
*@param string $template шаблон добавления/изменения товара
*/
function showProduct(string $template)
{
    $categories = \helperDb\getQuery(DB_GET_CATEGORIES);
    if (isset($_REQUEST['chng_product']) && isset($_REQUEST['product_id'])) {
        $product = \helperDb\getQuery(DB_GET_PRODUCT_INFO, ['id' => $_REQUEST['product_id']])[0];
        $prodCat = array_column(\helperDb\getQuery(DB_GET_CATEGORIES_FOR_PRODUCTS . ' WHERE pc.product_id = ' . $_REQUEST['product_id']), 'category_id');
        $data = [
            'title' => 'Изменение товара',
            'action' => 'Изменить товар',
            'success' => 'Товар успешно изменён',
        ];
    } elseif (isset($_REQUEST['add_product']) && $_REQUEST['add_product'] == 'yes') {
        $data = [
            'title' => 'Добавление товара',
            'action' => 'Добавить товар',
            'success' => 'Товар успешно добавлен',
        ];
        $product = false;
    }

    require_once $_SERVER['DOCUMENT_ROOT'] . '/template/' . $template;
}
