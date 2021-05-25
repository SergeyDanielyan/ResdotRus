<?php
    $mysqli = new mysqli("localhost", "SergeyDanielyan", "rfQrBSHLCQHKFwbt", "resdotrus");
    $cousines_query = $mysqli->query("SELECT * FROM cousines");
    $cousines = array();
    while ($cousine = mysqli_fetch_array($cousines_query)) {
        $cousines[] = $cousine;
    }
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $phone_number = $_POST['phone_number'];
        $type = $_POST['restaurant_type'];
        $average_order_value = $_POST['average_order_value'];
        $adress = $_POST['adress'];
        $music = $_POST['music'];
        $working_hours = $_POST['working_hours'];
        $cousine_id = $_POST['cousine_id'];
        $login = $_POST['login'];
        $pass = $_POST['password1'];
        $pass2 = $_POST['password2'];

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

        if (!is_numeric($average_order_value)) {
            $str = "Средний чек должен быть числом";
            $err[] = $str;
        }

        $query = $mysqli->query("SELECT name, login, password, adress FROM restaurants WHERE name = '$name' OR login = '$login' OR password = '$pass' OR adress = '$adress'");
        if(mysqli_num_rows($query) > 0) {
            $str = "Пользователь с таким именем пользователя/логином/паролем/адресом уже существует в базе данных";
            $err[] = $str;
        }
        if (count($err) == 0) {
            $passs = $pass;
            $mysqli->query("INSERT INTO restaurants (name, login, password, phone_number, type, average_order_value, adress, music, working_hours, cousine_id) VALUES ('$name', '$login', '$passs', '$phone_number', '$type', '$average_order_value', '$adress', '$music', '$working_hours', '$cousine_id')");
            header("Location: restauth.php");
            $_POST['submit'] = null;
            exit();
        }
    }
?>

<!DOCTYPE html5>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ResdotRus, регистрация ресторана</title>
        <link rel="stylesheet" href="authstyle.css" type="text/css">
        <link rel='stylesheet' href='style.css' type='text/css'> 
    </head>
    <body>
        <form class="form" method="POST">
            <h1 class="form_title">Регистрация</h1>
            <div class="form_grup">
                <label class="form_label">Название</label>
                <input class="form_input" name="name" autocomplete="off" placeholder=" ">
            </div>
            <div class="form_grup">
                <label class="form_label">Номер телефона</label>
                <input class="form_input" name="phone_number" autocomplete="off" placeholder=" ">
            </div>
            <div class="form_grup">
                <label class="form_label">Тип заведения</label>
                <select name='restaurant_type'>
                    <option value="ресторан">ресторан</option>
                    <option value="кафе">кафе</option>
                    <option value="бар">бар</option>
                    <option value="столовая">столовая</option>
                    <option value="закусочная">закусочная</option>
                    <option value="буфет">буфет</option>
                </select>
            </div>
            <div class="form_grup">
                <label class="form_label">Средний чек (в рублях)</label>
                <input class="form_input" name="average_order_value" autocomplete="off" placeholder=" ">
            </div>
            <div class="form_grup">
                <label class="form_label">Адрес</label>
                <input class="form_input" name="adress" autocomplete="off" placeholder=" ">
            </div>
            <div class="form_grup">
                <label class="form_label">Музыка</label>
                <label><input type='radio' name='music' value="1"> Есть</label>
                <label><input type='radio' name='music' value="2"> Нет</label>
            </div>
            <div class="form_grup">
                <label class="form_label">Время работы</label>
                <input class="form_input" name="login" autocomplete="off" placeholder=" ">
            </div>
            <div class="form_grup">
                <label class="form_label">Типы кухни</label>
                <select name="cousine">
                    <?php
                        foreach ($cousines as $cousine) {
                            echo "<option value=".$cousine['id'].">".$cousine['name']."</option>";
                        }
                    ?>
                </select>
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