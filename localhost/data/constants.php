<?php

/**
 * Запрос sql для получения данных о пользователе из БД
 */
define('DB_GET_CATEGORIES', "SELECT * FROM categories");

/**
 * Запрос sql для получения массива категорий товаров из БД
 */
define('DB_GET_CATEGORIES_FOR_PRODUCTS', "SELECT
										      pc.product_id,
										      pc.category_id,
											  c.name AS category
										  FROM
											  product_category AS pc
										  LEFT JOIN
											  categories AS c ON pc.category_id = c.id");

/**
 * Запрос sql для получения активных товаров из БД по категории
 */
define('DB_GET_PRODUCTS_BY_CATEGORY', "SELECT
									       p.name,
									       p.price,
									       p.img_path,
									       p.is_new,
									       p.is_sale,
									       p.id AS p_id,
									       c.id
									   FROM
									       products AS p
									   LEFT JOIN
									       product_category AS pc ON p.id = pc.product_id
									   LEFT JOIN
									       categories AS c ON pc.category_id = c.id
									   WHERE p.is_active = '1' AND c.id = :category_id");

/**
 * Запрос sql для деактивации товаров в БД
 */
define('DB_DEACTIVE_PRODUCT', "UPDATE
									products
		                   		SET
		                       		is_active = '0'
		                   		WHERE
		                       		id = :id");

/**
 * Запрос sql для получения данных о пользователе из БД
 */
define('DB_GET_PRODUCT_INFO', "SELECT
									p.name,
									p.price,
									p.is_new,
									p.is_sale
		                   		FROM
		                       		products AS p
		                   		WHERE
		                       		id = :id");

/**
 * Запрос sql для удаления связей товар-категория из БД кроме категрии Все
 */
define('DB_DELETE_PRODUCT_LINKS', "DELETE
	 							   FROM
	 							  	  product_category
	 							   WHERE
	 							  	  product_id = :product_id");

/**
 * Запрос sql для добавления связи товар-категория
 */
define('DB_ADD_PRODUCT_CATEGORY_LINK', "INSERT INTO
		                                	product_category
		                          		SET
			                            	product_id = :product_id,
			                            	category_id = :category_id");


/**
 * Запрос sql для добавления связи товар-категория
 */
define('DB_ADD_PRODUCT', "INSERT INTO
											products
										SET
											name = :product_name,
											price = :product_price,
											img_path = :img_path,
											is_new = :is_new,
											is_sale = :is_sale,
											is_active = '1'");

/**
 * Запрос sql для получения данных о пользователе из БД
 */
define('DB_GET_USER_DATA', "SELECT
								u.id,
								u.name,
								u.email,
								u.password,
		                        g.name AS 'group'
		                    FROM
		                        users AS u
		                    LEFT JOIN
		                        groups AS g ON u.group_id = g.id
		                    WHERE
		                        email = :email");


/**
 * Запрос sql для добавления заказа в БД
 */
define('DB_ADD_NEW_ORDER', "INSERT INTO
								orders
							SET
								user_id = :user_id,
							    user_name = :name,
							    user_surname = :surname,
							    user_thirdname = :third_name,
							    user_phone = :phone,
							    user_email = :email,
							    delivery = :delivery,
							    delivery_cost = :delivery_cost,
							    city = :city,
							    street = :street,
							    home = :home,
							    aprt = :aprt,
							    pay = :pay,
							    comment = :comment,
							    order_cost = :order_cost,
							    created_at = :created_at,
							    status = :status");

/**
 * Запрос sql для получения последнего id добавленного в БД
 */
define('DB_GET_LAST_ID', "SELECT LAST_INSERT_ID();");

/**
 * Запрос sql для добавления связи заказ - продукт в БД
 */
define('DB_ADD_ORDER_PRODUCT_LINK', "INSERT INTO
		                                order_product
		                          	SET
			                            order_id = :order_id,
			                            product_id = :product_id");
/**
 * Запрос sql для получения заказов отсортированных по статусу и дате создания
 */
define('DB_GET_ORDERS', "SELECT * FROM orders ORDER BY status ASC, created_at DESC");

/**
 * Запрос sql для получения заказов отсортированных по статусу и дате создания
 */
define('DB_CHANGE_ORD_STATUS', "UPDATE
									orders
		                   		SET
		                       		status = :status
		                   		WHERE
		                       		id = :id");

/**
 * Запрос sql для добавления нового пользователя в БД
 */
define('DB_ADD_NEW_USER', "INSERT INTO
                            users
                        SET
	                        is_active = '1',
	                        full_name = :name,
	                        email = :email,
	                        phone = :phone,
	                        password = :password,
	                        is_apply_notifications = :is_apply_notifications");

/**
 * Запрос sql для добавления связи пользователь - группа в БД
 */
define('DB_ADD_GROUP_LINK', "INSERT INTO
                              user_group
                          SET
	                          user_id = :user_id,
	                          group_id = :group_id");
