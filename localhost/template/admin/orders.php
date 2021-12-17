<main class="page-order">
    <h1 class="h h--1">Список заказов</h1>
    <ul class="page-order__list">
        <?php foreach ($orders as $order) : ?>
            <li class="order-item page-order__item">
                <div class="order-item__wrapper">
                    <div class="order-item__group order-item__group--id">
                        <span class="order-item__title">Номер заказа</span>
                        <span class="order-item__info order-item__info--id"><?= $order['id']; ?></span>
                    </div>
                    <div class="order-item__group">
                        <span class="order-item__title">Сумма заказа</span>
                        <?= $order['order_cost'] . ' руб.'; ?>
                    </div>
                    <button class="order-item__toggle"></button>
                </div>
                <div class="order-item__wrapper">
                    <div class="order-item__group order-item__group--margin">
                        <span class="order-item__title">Заказчик</span>
                        <span class="order-item__info"><?= $order['user_surname'] . ' ' . $order['user_name'] . ' ' . $order['user_thirdname'] ?></span>
                    </div>
                    <div class="order-item__group">
                        <span class="order-item__title">Номер телефона</span>
                        <span class="order-item__info"><?= $order['user_phone'] ?></span>
                    </div>
                    <div class="order-item__group">
                        <span class="order-item__title">Способ доставки</span>
                        <span class="order-item__info"><?= $order['delivery'] == '1' ?  'Доставка' : 'Самовывоз'; ?></span>
                    </div>
                    <div class="order-item__group">
                        <span class="order-item__title">Способ оплаты</span>
                        <span class="order-item__info"><?= $order['pay'] == 'cash' ?  'Наличными' : 'Банковской картой'; ?></span>
                    </div>
                    <div class="order-item__group order-item__group--status">
                        <span class="order-item__title">Статус заказа</span>
                        <span class="order-item__info order-item__info--<?= $order['status'] == '1' ? 'yes' : 'no';  ?>" data-id="<?= $order['id']; ?>"><?= $order['status'] == '1' ? 'Обработан' : 'Не обработан'; ?></span>
                        <button class="order-item__btn">Изменить</button>
                    </div>
                </div>
                <?php if ($order['delivery'] === '1') :?>
                    <div class="order-item__wrapper">
                        <div class="order-item__group">
                            <span class="order-item__title">Адрес доставки</span>
                            <span class="order-item__info"><?= 'г. ' . $order['city'] . ', ул. ' . $order['street'] . ', д.' . $order['home'] . ', кв. ' . $order['aprt']; ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="order-item__wrapper">
                    <div class="order-item__group">
                        <span class="order-item__title">Комментарий к заказу</span>
                        <span class="order-item__info"><?= $order['comment']; ?></span>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

