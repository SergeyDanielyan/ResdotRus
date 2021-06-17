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
        <!--<link rel='stylesheet' href='style.css' type='text/css'>-->
        <style>
            body {
                background-image: url("backgr.jpg");
                background-attachment: fixed;
                margin: 0;
            }

            .header {
                height: 10%;
                background-color: #9b2d30;
                background-size: cover;
                text-align: center;
                background-attachment: fixed;
            }

            .title {
                color: white;
                font-weight: 700px;
                font-size: 46px;
            }

            .link {
                color: white;
                font-weight: 600;
                text-decoration: none;
                padding: 1%;
            }

            .footer {
                background-color: #9b2d30;
                height: 13%;
                text-align: center;
                background-size: cover;
                background-attachment: fixed;
            }

            .footertext {
                color: white;
                font-style: normal;
            }
        </style>
    </head>
    <body>
        <table width='100%' height='100%' align='center' cellspacing='0'>
            <tr class='header'>
                <td width='30%'><a href='index.php' class='link'><strong class='title'>ResdotRus</string></a></td>
                <td width='30%'><a href='favorites.php' class='link'>Избранные</a></td>
                <?php
                    $user = mysqli_fetch_array($mysqli->query("SELECT * FROM users WHERE id = ".$my_id));
                    echo "<td><p align='right'><a href='user_page.php' class='link'>".$user['name']."</a> <a href='logout.php' class='link'>Выйти</a></p></td>";
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
            <tr class='footer'>
                <td><h3 class='footertext'>ResdotRus, 2021</h3></td>
                <td></td>
                <td><h4 align='center' class='footertext'>Сергей Даниелян, Стефан Погосян </h4></td>
            </tr>
        </table>
    </body>
</html>