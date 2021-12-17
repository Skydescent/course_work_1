<?php
namespace helperDb;

/**
*Функция возвращает соедиенение с базовй данных
* @param string $dsn для подключения к БД через объект PDO
* @param string $user - логин подключения к БД
* @param string $password - пароль подключения к БД
* @return объект PDO для подключения к БД
*/
function connectDb(
    string $dsn = DB_DSN,
    string $user = DB_LOGIN,
    string $password = DB_PASSWORD
)
{
    static $connection;
    if (null === $connection) {
        try {

            $connection = new \PDO($dsn, $user, $password);
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            include $_SERVER['DOCUMENT_ROOT'] . '/template/dbConnectError.php';
            die();
        }

        //$connection = new \PDO($dsn, $user, $password);
    }
    return $connection;
}

/**
*Функция делает запрос в базу данных
* @param string sql шаблон sql запроса
* @param array data данные в форме массива для использования в запросе
* @return array PDO statement
*/
function execSqlQuery(string $sql, $data)
{
    if (connectDb()) {
        $stmt = connectDb()->prepare($sql);
        $result = is_null($data) ? $stmt->execute() : $stmt->execute($data);
        if (!$result) {
            print "Ошибка! запроса к БД" . "<br/>";
            var_dump($stmt->errorInfo());
            return false;
        } else {
            return $stmt;
        }
    }
}

/**
*Функция возвращает boolean значение есть ли такой пользователь в БД
* @param string $userMail - электронная почта пользователя
* @param string $password - пароль пользователя
* @param string $key - ключ шифрования для паролей пользователей
* @return bool присутствуют ли введённая пара логин и пароль в БД
*/

function chngUserActiveStatus(string $userMail, string $isActive)
{
    $sql = "
        UPDATE
            users
        SET
            is_active = :activity
        WHERE
            email = :email";
    $data = ['activity' => $isActive, 'email' => $userMail];
    execSqlQuery($sql, $data);
}

/**
*Функция возвращает результат запроса на получение данных из БД
* @param string $sql - запрос записанный как константа
* в constants.php
* @param $data - данные по которым будет производится сортировка в БД
* @return array результат запроса из БД либо false, если результат пустой,
* либо запрос не удался
*/
function getQuery
(
    string $sql,
    $data = NULL,
    $sortMeth = \PDO::FETCH_ASSOC
)
{
    if (connectDb()) {
        $stmt = connectDb()->prepare($sql);
        if (is_array($data)) {
            $result = $stmt->execute($data);
        } elseif ( is_null($data)) {
            $result = $stmt->execute();
        } else {
            $result = $stmt->execute(['id' => $data]);
        }
        if (!$result) {
            $_REQUEST['db_request_status'] = ['error' => $stmt->errorInfo()];
            return $stmt->errorInfo();
        } else {
            $_REQUEST['db_request_status'] = 'success';
            if (preg_match('#^(update|insert|delete)#i', $sql) === 0) {
                $result = $stmt->fetchAll($sortMeth);
                if (is_array($result) && count($result) === 0) {
                    $_REQUEST['db_request_status'] = ['error' => 'Нет таких данных в БД'];
                    return false;
                }
            }
            return $result;
        }
    }
}