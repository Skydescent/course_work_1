<section class="shop__sorting">
    <div class="shop__sorting-item custom-form__select-wrapper">
          <select class="custom-form__select" name="category">
                <option hidden="">Сортировка</option>
                <option value="price">По цене</option>
                <option value="name">По названию</option>
          </select>
    </div>
    <div class="shop__sorting-item custom-form__select-wrapper">
          <select class="custom-form__select" name="order">
                <option hidden="">Порядок</option>
                <option value="asc">По возрастанию</option>
                <option value="desc">По убыванию</option>
          </select>
    </div>
    <p class="shop__sorting-res">Найдено <span class="res-sort">"<?= count($products); ?>"</span> моделей</p>
</section>
<section class="shop__list">
    <?php foreach ($products as $product) : ?>
        <article class="shop__item product" data-is_new="<?= $product['is_new']; ?>" data-is_sale="<?= $product['is_sale']; ?>" data-product_id="<?= $product['p_id']; ?>" tabindex="0">
          <div class="product__image">
            <img src="<?= $product['img_path']; ?>" alt="product-photo">
          </div>
          <p class="product__name"><?= $product['name']; ?></p>
          <span class="product__price"><?= $product['price'] . ' руб.'; ?></span>
        </article>
    <?php endforeach; ?>
</section>
<ul class="shop__paginator paginator">
    <? for ($i = 1; $i <= ceil(count($products) / 9); $i++) : ?>
        <li>
            <a class="paginator__item" <?= $i == 1 ? "" : "href='#'" ?>><?= $i?></a>
        </li>
    <?php endfor; ?>
</ul>
<a href="/?product_id=<?= $product['id']; ?>"></a>