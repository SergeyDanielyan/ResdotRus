<?php
    $mysqli = new mysqli("localhost", "SergeyDanielyan", "rfQrBSHLCQHKFwbt", "resdotrus");
    if (isset($_POST['submit'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        if ($mysqli->connect_errno) {
            printf("Не удалось подключиться: %s\n", $mysqli->connect_error);
            exit();
        }
        
        $query = $mysqli->query("SELECT id, password FROM users WHERE login='$login'");
        $data = mysqli_fetch_array($query);
        if ($data['password'] == $password) {
            setcookie("mode", 1);
            setcookie("id", $data['id']);
            header("Location: index.php");
            exit();
        }
    }
?>

<!DOCTYPE html5>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ResdotRus, вход пользователя</title>
        <link rel="stylesheet" href="authstyle.css" type="text/css">
    </head>
    <body>
        <form class="form" method="POST">
            <h1 class="form_title">Вход</h1>
            <div class="form_grup">
                <label class="form_label">Логин</label>
                <input class="form_input" name="login" autocomplete="off" placeholder=" ">
            </div>
            <div class="form_grup">
                <label class="form_label">Пароль</label>
                <input class="form_input" type="password" name="password">
            </div>
            <input class="form_button" type="submit" name="submit" value="Войти" placeholder=" ">
            <?php
                if (isset($_POST['submit']) && $data['password'] != $password) {
                    echo "<br><p>Вы ввели неправильный логин/пароль</p>";
                }
            ?>
        </form>
    </body>
</html>