<?php
    $mysqli = new mysqli("localhost", "SergeyDanielyan", "rfQrBSHLCQHKFwbt", "resdotrus");
    $rest_id = $_COOKIE['rest_id'];
    $my_id = $_COOKIE['id'];
    if (isset($_POST['subm']) && is_numeric($_POST['count'])) {
        $start_time = $_POST['start_time'];
        $z = strtotime("$start_time") + 3600;
        $m = $mysqli->query("INSERT INTO reservation (user_id, restaurant_id, count, start_time_int, status) VALUES ('$my_id', '$rest_id', ".$_POST['count'].", ".$z.", 0)");
        header("Location: user_page.php");
    }
?>

<!DOCTYPE html5>
<html>
    <head>
        <meta charset='UTF-8'>
        <title>Бронирование - ResdotRus</title>
        <link rel='stylesheet' href='style.css' type='text/css'> 
    </head>
    <body>
        <table width='100%' height='100%' align='center' cellspacing='0'>
            <tr>
                <td width='30%'><a href='index.php'>ResdotRus</a></td>
                <td width='30%'><a href='favorites.php'>Избранные</a></td>
                <?php
                    if (isset($_COOKIE['mode']) && isset($_COOKIE['id'])) {

                    }
                    $user = mysqli_fetch_array($mysqli->query("SELECT * FROM users WHERE id = ".$my_id));
                    echo "<td><p align='right'><a href='user_page.php'>".$user['name']."</a> <a href='logout.php'>Выйти</a></p></td>";
                ?>
            </tr>
            <tr>
                <td></td>
                <td>
                    <h1 align='center'>Бронирование</h1>
                    <form method='POST'>
                        <label>Время: <input type='datetime-local' name='start_time'></label><br>
                        <label>Необходимое количество мест: <input name='count'></label><br>
                        <input type='submit' name='subm' value='Отправить запрос'>
                    </form>
                    <?php 
                        if (isset($_POST['subm']) && !is_numeric($_POST['count'])) {
                            echo "Пожалуйста, введите число";
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