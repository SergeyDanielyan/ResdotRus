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
    </head>
    <body>
        <table width='100%' height='100%' align='center' cellspacing='0'>
            <tr>
                <td width='30%'><a href='index.php'>ResdotRus</a></td>
                <?php
                    $user = mysqli_fetch_array($mysqli->query("SELECT * FROM users WHERE id = ".$my_id));
                    echo "<td width='30%'><a href='favorites.php'>Избранные</a></td>
                    <td><p align='right'><a href='user_page.php'>".$user['name']."</a> <a href='logout.php'>Выйти</a></p></td>";
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
                                <th>Время</th>
                                <th>Ресторан</th>
                                <th>Количество мест</th>
                                <th>Статус</th>
                            </tr>";
                            foreach ($reservation as $reserv) {
                                echo "<tr><td>".date('Y-m-d H:i:s', $reserv['time'] - 3600)."</td>
                                <td>".$reserv['rest']."</td>
                                <td>".$reserv['count']."</td>";
                                if ($reserv['status'] == 0) {
                                    echo "<td>Ожидание</td>";
                                }
                                else if ($reserv['status'] == 1) {
                                    echo "<td>Одобрено</td>";
                                }
                                else {
                                    echo "<td>Отказано</td>";
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
            <tr>
                <td>ResdotRus, 2021</td>
                <td></td>
                <td><p align='right'>Сергей Даниелян, Стефан Погосян</p></td>
            </tr>
        </table>
    </body>
</html>