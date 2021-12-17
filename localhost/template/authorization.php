<main class="page-authorization">
<h1 class="h h--1">Авторизация</h1>
    <form class="custom-form" action="/" method="post">
        <input type="email" name="email" value ="<?= isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : ''; ?>" class="custom-form__input" required="">
        <input type="password" name="password" class="custom-form__input" required="">
        <button class="button" type="submit">Войти в личный кабинет</button>
    </form>
    <p class="error"></p>
</main>
