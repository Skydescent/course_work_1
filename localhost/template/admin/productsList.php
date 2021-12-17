<main class="page-products">
    <h1 class="h h--1">Товары</h1>
    <a class="page-products__button button" href="/admin/?add_product=yes">Добавить товар</a>
    <div class="page-products__header">
        <span class="page-products__header-field">Название товара</span>
        <span class="page-products__header-field">ID</span>
        <span class="page-products__header-field">Цена</span>
        <span class="page-products__header-field">Категория</span>
        <span class="page-products__header-field">Новинка</span>
        <span class="page-products__header-field">Sale</span>
    </div>
    <ul class="page-products__list">
        <?php foreach ($products as $product) :?>
            <li class="product-item page-products__item">
                <b class="product-item__name"><?= $product['name']; ?></b>
                <span class="product-item__field"><?= $product['p_id']; ?></span>
                <span class="product-item__field"><?= $product['price'] . ' руб.'; ?></span>
                <span class="product-item__field">
                    <?php foreach ($categories as $category) : ?>
                        <?php if ($category['product_id'] == $product['p_id']) : ?>
                            <?= '- ' . $category['category']; ?>
                            </br>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </span>
                <span class="product-item__field"><?= $product['is_new'] == '1' ? 'Да' : 'Нет'; ?></span>
                <span class="product-item__field"><?= $product['is_sale'] == '1' ? 'Да' : 'Нет'; ?></span>
                <a href="/admin/?chng_product=yes&product_id=<?= $product['p_id']; ?>" class="product-item__edit" aria-label="Редактировать"></a>
                <button class="product-item__delete" data-id="<?= $product['p_id']; ?>"></button>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

