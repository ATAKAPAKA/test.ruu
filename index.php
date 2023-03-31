<?
session_start();

if (isset($_GET["url"])) {
    if ($_GET["url"] == "short") {
        if ($_POST["user_url"] != "") {
            $newUrl = shortenUrl($_POST["user_url"]);
            $_SESSION["new_url"] = $_SERVER["HTTP_HOST"] . '/' . $newUrl;
            include_once "form.php";
            die;
        }
        $_SESSION["error"] = "Вы не указали ссылку";
        include_once "form.php";
        die;
    }
    redirectUrl($_GET["url"]);
} else {
    include_once "page.php";
}


//функция редиректа
function redirectUrl($shortUrl) {
    $pdo = newPDO();
    $query = "SELECT original_url FROM urls WHERE short_url = :short_url";
    $statement = $pdo->prepare($query);
    $statement->execute([':short_url' => $shortUrl]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        header('Location: ' . $result['original_url']);
        exit();
    } else {
        echo 'URL not found.';
    }
}


//генерция ключа
function generateShortUrl()
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $shortUrl = '';

    for ($i = 0; $i < 5; $i++) {
        $shortUrl .= $chars[mt_rand(0, strlen($chars) - 1)];
    }

    return $shortUrl;
}

//создаёт экземпляр PDO
function newPDO()
{
    if (!extension_loaded('pdo_mysql')) {
        die('PDO MySQL is not installed');
    }
    $dsn = 'mysql:host=192.168.1.211;dbname=test';
    $username = 'root';
    $password = 'root';

    try {
        $pdo = new PDO($dsn, $username, $password);
        return $pdo;
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }
}

//создаёт сокращённый URL или находит в базе
function shortenUrl($originalUrl)
{
    $pdo = newPDO();
    $query = "SELECT short_url FROM urls WHERE original_url = :original_url";
    $statement = $pdo->prepare($query);
    $statement->execute([':original_url' => $originalUrl]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result['short_url'];
    }

    do {
        $shortUrl = generateShortUrl();
        $query = "SELECT short_url FROM urls WHERE short_url = :short_url";
        $statement = $pdo->prepare($query);
        $statement->execute([':short_url' => $shortUrl]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
    } while ($result);

    $query = "INSERT INTO urls (original_url, short_url) VALUES (:original_url, :short_url)";
    $statement = $pdo->prepare($query);
    $statement->execute([':original_url' => $originalUrl, ':short_url' => $shortUrl]);

    return $shortUrl;
}
