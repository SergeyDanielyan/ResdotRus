<?php
    $mysqli = new mysqli("localhost", "SergeyDanielyan", "rfQrBSHLCQHKFwbt", "resdotrus");
    $my_id = $_COOKIE['id'];
    $reservation_query = $mysqli->query("SELECT reservation.start_time_int AS time, restaurants.name AS rest, reservation.count AS count, reservation.status AS
    status
    FROM reservation
    INNER JOIN restaurants ON reservation.restaurant_id = restaurants.id
    WHERE reservation.start_time_int > ".time()."
    AND reservation.user_id =".$my_id."
    ORDER BY reservation.start_time_int");
    $reservation = array();
    while ($row = mysqli_fetch_array($reservation_query)) {
        $reservation[] = $row;
    }
?>

<!DOCTYPE html5>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Домашняя страница - ResdotRus</title>
        <!--<link rel='stylesheet' href='style.css' type='text/css'> -->
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

            .text {
                font-size: 25px;
            }

            h1 {
                font-size: 35px;
            }
        </style>
    </head>
    <body>
        <table width='100%' height='100%' align='center' cellspacing='0'>
            <tr class='header'>
                <td width='30%'><a href='index.php' class='link'><strong class='title'>ResdotRus</strong></a></td>
                <?php
                    $user = mysqli_fetch_array($mysqli->query("SELECT * FROM users WHERE id = ".$my_id));
                    echo "<td width='30%'><a href='favorites.php' class='link'>Избранные</a></td>
                    <td><p align='right'><a href='user_page.php' class='link'>".$user['name']."</a><a href='logout.php' class='link'>Выйти</a></p></td>";
                ?>
            </tr>
            <tr>
                <td></td>
                <td>
                    <h1 align='center'>Запросы</h1>
                    <?php
                        if (count($reservation) == 0) {
                            echo "У вас ещё нет запросов";
                        }
                        else {
                            echo "<table align='center' cellspacing='10'>
                            <tr>
                                <th class='text'>Время</th>
                                <th class='text'>Ресторан</th>
                                <th class='text'>Количество мест</th>
                                <th class='text'>Статус</th>
                            </tr>";
                            foreach ($reservation as $reserv) {
                                echo "<tr><td class='text'>".date('Y-m-d H:i:s', $reserv['time'] - 3600)."</td>
                                <td class='text'>".$reserv['rest']."</td>
                                <td class='text'>".$reserv['count']."</td>";
                                if ($reserv['status'] == 0) {
                                    echo "<td class='text'>Ожидание</td>";
                                }
                                else if ($reserv['status'] == 1) {
                                    echo "<td class='text'>Одобрено</td>";
                                }
                                else {
                                    echo "<td class='text'>Отказано</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</table>";
                        }
                    ?>
                </td>
                <td>
                </td>
            </tr>
            <tr class='footer'>
                <td><h3 class='footertext'>ResdotRus, 2021</h3></td>
                <td></td>
                <td><h4 align='center' class='footertext'>Сергей Даниелян, Стефан Погосян</h4></td>
            </tr>
        </table>
    </body>
</html>