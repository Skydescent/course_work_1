<?php
namespace helperAuth;

/**
*Функция страта сессионой COOKIE
*/
function startSession()
{
    session_name('session_id');
    session_cache_limiter('20_minutes');
    session_cache_expire(20);
    session_start();
}

/**
*Функция проверяет аутентифицировался ли пользователь
* сравнивая с данными из БД, создаёт соответствующие
* поля в супермассиве $_SESSION и
* устаналвиливается куки с логином пользователя
* логика аутентификации визуализируется компоментом authBlock.php
*/
function authController()
{

    if (isset($_COOKIE['user_login'])) {
        setcookie('user_login', $_COOKIE['user_login'], time() + 60 * 60 * 24 * 30, '/');
    }

    if (!empty($_REQUEST) && isset($_REQUEST['execFunc'])) {
        if ($_REQUEST['execFunc'] == 'auth') {
            $email = htmlspecialchars($_REQUEST['email']);
            $password = htmlspecialchars($_REQUEST['password']);
            $query = \helperDb\getQuery(DB_GET_USER_DATA, ['email' => $email])[0];
            $isCorrectPassword = $query ? decryptPassword($password, $query['password']) : false;
            if ($isCorrectPassword) {
                $_SESSION['auth_subsystem'] = [
                    'is_authorized' => true,
                    'user_id' => $query['id'],
                    'user_group' => $query['group'],
                    'user_email' => $query['email']
                ];

                //updateMenu();
                setcookie('user_email', $email, time() + 60 * 60 * 24 * 30, '/');
                // $msg = 'Добро пожаловать, ' . $query['name'];

                // ob_start();
                // \helperContent\showMenu();
                // $newMenu = ob_get_contents();
                //ob_end_clean();
                $rights = $query['group'];
                 die(json_encode(['success' => $rights]));
                //header("Location: http://localhost/admin/?orders=yes"); //http://localhost/?execFunc=unAuth
                //exit();    // прерываем работу скрипта, чтобы забыл о прошлом

            } else {
                die(json_encode(['error' => 'Некорректный логин и(или) пароль!']));
            }
        }
        if ($_REQUEST['execFunc'] == 'unAuth') {
             $_SESSION['auth_subsystem'] = [];
        }
    }
}

/**
*Функция шифрования пароля в хеш
* @param string $password - пароль заданный ползьователь
* @return string хэш паролья пользователя
*/
function encryptPassword(string $password) : string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
*Функция дешифрования пароля из хеша
* @param string $password - пароль пользователя
* @param string $hash - хэш пароля
* @return boolean - явялется ли пароль дешифорованным хешем
*/
function decryptPassword(string $password, string $hash) : bool
{
    return password_verify($password, $hash);
}

