<?php
    $mysqli = new mysqli("localhost", "SergeyDanielyan", "rfQrBSHLCQHKFwbt", "resdotrus");
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $login = $_POST['login'];
        $pass = trim($_POST['password1']);
        $pass2 = trim($_POST['password2']);

        $err = array();

        if (strlen($name) < 3 || strlen($name) > 30) {
            $str = "Имя пользователя должно быть не меньше 3 символов и не больше 30";
            $err[] = $str;
        }

        if (strlen($login) < 3 || strlen($login) > 30) {
            $str = "Логин должен быть не меньше 3 символов и не больше 30";
            $err[] = $str;
        }

        if ($pass != $pass2) {
            $str = "Пароли не совпадают";
            $err[] = $str;
        }

        if (strlen($pass) < 6) {
            $str = "Пароль должен быть не меньше 6 символов";
            $err[] = $str;
        }
        $query = $mysqli->query("SELECT name, login, password FROM users WHERE name = '$name' OR login = '$login' OR password = '$pass'");
        if(mysqli_num_rows($query) > 0) {
            $str = "Пользователь с таким именем пользователя/логином/паролем уже существует в базе данных";
            $err[] = $str;
        }
        if(count($err) == 0) {
            $passs = $pass;
            $mysqli->query("INSERT INTO users (name, login, password) VALUES ('$name', '$login', '$passs')");
            header("Location: userauth.php");
            $_POST['submit'] = null;
            exit();
        }
    }
?>

<!DOCTYPE html5>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ResdotRus, регистрация пользователя</title>
        <link rel="stylesheet" href="authstyle.css" type="text/css">
        <link rel='stylesheet' href='style.css' type='text/css'> 
    </head>
    <body>
        <form class="form" method="POST">
            <h1 class="form_title">Регистрация</h1>
            <div class="form_grup">
                <label class="form_label">Имя пользователя</label>
                <input class="form_input" name="name" autocomplete="off" placeholder=" ">
            </div>
            <div class="form_grup">
                <label class="form_label">Логин</label>
                <input class="form_input" name="login" autocomplete="off" placeholder=" ">
            </div>
            <div class="form_grup">
                <label class="form_label">Пароль</label>
                <input class="form_input" type="password" name="password1">
            </div>
            <div class="form_grup">
                <label class="form_label">Повторите пароль</label>
                <input class="form_input" type="password" name="password2">
            </div>
            <input class="form_button" type="submit" name="submit" value="Зарегистрироваться" placeholder=" ">
            <?php
                if (isset($_POST['submit']) && count($err) > 0) {
                    echo "<br>При регистрации произошли следующие ошибки:";
                    foreach ($err as $error) {
                        echo "<br>".$error;
                    }
                }
            ?>
        </form>
    </body>
</html>