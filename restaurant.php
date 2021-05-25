<?php
    $mysqli = new mysqli("localhost", "SergeyDanielyan", "rfQrBSHLCQHKFwbt", "resdotrus");
    $rest_id = $_COOKIE['rest_id'];
    $restaurant = mysqli_fetch_array($mysqli->query("SELECT * FROM restaurants WHERE id = ".$rest_id));
    $cousine = mysqli_fetch_array($mysqli->query("SELECT * FROM cousines WHERE id = ".$restaurant['cousine_id']));
    $user;
    if (isset($_COOKIE['mode']) && isset($_COOKIE['id'])) {
        $my_id = $_COOKIE['id'];
        $user = mysqli_fetch_array($mysqli->query("SELECT * FROM users WHERE id = ".$my_id));
    }

    if (isset($_POST['reserve'])) {
        setcookie('rest_id', $rest_id);
        header("Location: reservation.php");
        exit();
    }
    $is_favorite;
    if (isset($_COOKIE['mode']) && isset($_COOKIE['id'])) {
        $favorite = $mysqli->query("SELECT * FROM favorites WHERE restaurant_id = ".$rest_id." AND user_id = ".$_COOKIE['id']);
        $is_favorite = mysqli_num_rows($favorite) != 0;
    }

    if ($_POST['fav']) {
        if ($is_favorite) {
            $mysqli->query("DELETE FROM favorites WHERE restaurant_id = ".$rest_id." AND user_id = ".$_COOKIE['id']);
        }
        else {
            $mysqli->query("INSERT INTO favorites (user_id, restaurant_id) VALUES (".$_COOKIE['id'].", ".$rest_id.")");
        }
        header("Location: restaurant.php");
        exit();
    }
?>

<!DOCTYPE html5>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel='stylesheet' href='style.css' type='text/css'> 
        <?php
            echo "<title>".$restaurant['name']." - ResdotRus</title>";
        ?>
    </head>
    <body>
        <table width='100%' height='100%' align='center' cellspacing='0'>
            <tr>
                <td width='30%'><a href='index.php'>ResdotRus</a></td>
                <?php
                    if (isset($_COOKIE['mode']) && isset($_COOKIE['id'])) {
                        echo "<td width='30%'><a href='favorites.php'>Избранные</a></td>
                        <td><p align='right'><a href='user_page.php'>".$user['name']."</a> <a href='logout.php'>Выйти</a></p></td>";
                    }
                    else {
                        echo "<td></td>
                        <td><p align='right'><a href='userauth.php'>Вход</a> <a href='userreg.php'>Регистрация</a> <a href='restauth.php'>Вход от ресторана</a> <a href='restreg.php'>Регистрация ресторана</a></p></td>";
                    }
                ?>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php
                        echo "<h1 align='center'>".$restaurant['name']."</h1>
                        <p>Номер телефона: ".$restaurant['phone_number']."</p>
                        <p>Тип заведения: ".$restaurant['type']."</p>
                        <p>Средний чек: ".$restaurant['average_order_value']." ₽</p>
                        <p>Адрес: ".$restaurant['adress']."</p>";
                        if ($restaurant['music'] == 1) {
                            echo "<p>Есть музыка</p>";
                        }
                        else {
                            echo "<p>Нет музыки</p>";
                        }
                        echo "<p>Время работы: ".$restaurant['working_hours']."</p>
                        <p>Тип кухни: ".$cousine['name']."</p>";
                        if (isset($_COOKIE['mode']) && isset($_COOKIE['id'])) {
                            echo "<form method='POST'>
                                <input type='submit' name='reserve' value='Забронировать место'>
                            </form>";
                            if ($is_favorite) {
                                echo "<form method='POST'><input type='submit' name='fav' value='Удалить из избранных'></form>";
                            }
                            else {
                                echo "<form method='POST'><input type='submit' name='fav' value='Добавить в избранные'></form>";
                            }
                        }
                    ?>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>ResdotRus, 2021</td>
                <td></td>
                <td><p align='right'>Сергей Даниелян, Стефан Погосян</p></td>
            </tr>
        </table>
    </body>
</html>