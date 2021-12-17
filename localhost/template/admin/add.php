<main class="page-add">
    <h1 class="h h--1"><?= $data['title']; ?></h1>
    <div class = 'errors-box' style="color:red;">
        <p class="result-of-query"></p>
    </div>
    <form class="custom-form" action="#" method="post" enctype="multipart/form-data" data-id="<?= isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : 'new_product'; ?>">
        <fieldset class="page-add__group custom-form__group">
            <legend class="page-add__small-title custom-form__title">Данные о товаре</legend>
            <label for="product-name" class="custom-form__input-wrapper page-add__first-wrapper">
                <input value ="<?= $product ? $product['name'] : ''; ?>" type="text" class="custom-form__input" name="product_name" id="product-name">
                <p class="custom-form__input-label">
                  <?= $product ? '' : 'Название товара'; ?>
                </p>
            </label>
            <label for="product-price" class="custom-form__input-wrapper">
                <input value ="<?= $product ? $product['price'] : ''; ?>" type="text" class="custom-form__input" name="product_price" id="product-price">
                <p class="custom-form__input-label">
                  <?= $product ? '' : 'Цена товара'; ?>
                </p>
            </label>
        </fieldset>
        <fieldset class="page-add__group custom-form__group">
            <legend class="page-add__small-title custom-form__title">Фотография товара</legend>
            <ul class="add-list">
                <li class="add-list__item add-list__item--add">
                    <input type="file" name="product-photo" id="product-photo" accept="image/*" hidden="">
                    <label for="product-photo">Добавить фотографию</label>
                </li>
            </ul>
        </fieldset>
        <fieldset class="page-add__group custom-form__group">
            <legend class="page-add__small-title custom-form__title">Раздел</legend>
            <div class="page-add__select">
            <select name="category[]" class="custom-form__select" multiple="multiple">
                <option hidden="">Название раздела</option>
                <?php foreach($categories as $category) : ?>
                    <?php $isSelected = isset($_REQUEST['product_id']) && array_search($category['id'], $prodCat) !== false; ?>
                    <option value="<?= $category['id']; ?>" <?= $isSelected ? 'selected' : ''; ?>><?= $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
            </div>
            <input type="checkbox" name="is_new" id="new" class="custom-form__checkbox" <?= $product && $product['is_new'] == '1' ? 'checked' : '' ?>>
            <label for="new" class="custom-form__checkbox-label">Новинка</label>
            <input type="checkbox" name="is_sale" id="sale" class="custom-form__checkbox" <?= $product && $product['is_sale'] == '1' ? 'checked' : '' ?>>
            <label for="sale" class="custom-form__checkbox-label">Распродажа</label>
        </fieldset>
        <button class="button" type="submit"><?= $data['action']; ?></button>
    </form>
    <section class="shop-page__popup-end page-add__popup-end" hidden="">
        <div class="shop-page__wrapper shop-page__wrapper--popup-end">
            <h2 class="h h--1 h--icon shop-page__end-title"><?= $data['success']; ?></h2>
        </div>
    </section>
</main>