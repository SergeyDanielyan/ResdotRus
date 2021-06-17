<?php
    $mysqli = new mysqli("localhost", "SergeyDanielyan", "rfQrBSHLCQHKFwbt", "resdotrus");
    $rest_id = $_COOKIE['id'];
    $reservation_non_query = $mysqli
    ->query("SELECT reservation.start_time_int AS time, 
    users.name AS user, reservation.count AS count
    FROM reservation
    INNER JOIN users ON reservation.user_id = users.id
    WHERE reservation.status = 1 AND reservation.start_time_int > ".time()." AND reservation.restaurant_id = ".$rest_id." ORDER BY reservation.start_time_int");
    $reservation_non = array();
    while ($row = mysqli_fetch_array($reservation_non_query)) {
        $reservation_non[] = $row;
    }
    $reservation_query = $mysqli->query("SELECT reservation.id AS id, reservation.start_time_int AS time, 
        users.name AS user, reservation.count AS count
        FROM reservation
        INNER JOIN users ON reservation.user_id = users.id
        WHERE reservation.status = 0 AND reservation.start_time_int > ".time()." AND reservation.restaurant_id = ".$rest_id." ORDER BY reservation.start_time_int");
    $reservation = array();
    while ($row = mysqli_fetch_array($reservation_query)) {
        $reservation[] = $row;
    }
    if (isset($_POST['subm'])) {
        foreach ($reservation as $res) {
            $str = $res['id'];
            if ($_POST[$str] == 1) {
                $mysqli->query("UPDATE reservation SET status=1 WHERE id = ".$str);
            }
            else {
                $mysqli->query("UPDATE reservation SET status=2 WHERE id = ".$str);
            }
        }
        header("Location: rest_rest.php");
        exit();
    }
    if (isset($_POST['filter']) && isset($_POST['stdate']) && isset($_POST['fidate'])) {
        $stdate = strtotime($_POST['stdate']) + 3600;
        $fidate = strtotime($_POST['fidate']) + 3600;
        $reservation_non_query = $mysqli
        ->query("SELECT reservation.start_time_int AS time, 
        users.name AS user, reservation.count AS count
        FROM reservation
        INNER JOIN users ON reservation.user_id = users.id
        WHERE reservation.status = 1 AND reservation.start_time_int > ".time().
        " AND reservation.restaurant_id = ".$rest_id." AND reservation.start_time_int > ".$stdate.
        " AND reservation.start_time_int < ".$fidate." ORDER BY reservation.start_time_int");
        $reservation_non = array();
        while ($row = mysqli_fetch_array($reservation_non_query)) {
            $reservation_non[] = $row;
        }
    }
?>

<!DOCTYPE html5>
<html>
    <head>
        <meta charset='UTF-8'>
        <title>Страница ресторана - ResdotRus</title>
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
                <td width='30%'><strong class='title'>ResdotRus</strong></td>
                <td width='30%'></td>
                <?php
                    $rest = mysqli_fetch_array($mysqli->query("SELECT * FROM restaurants WHERE id = ".$rest_id));
                    echo "<td><p align='right' class='link'>".$rest['name']."<a href='logout.php' class='link'>Выйти</a></p></td>";
                ?>
            </tr>
            <tr>
                <td></td>
                <td>
                    <h1 align='center'>Непрочитанные</h1>
                    <form method='POST'>
                            <?php
                                if (count($reservation) == 0) {
                                    echo "<p>На данный момент нет запросов</p>";
                                }
                                else {
                                    echo "<p>Выберите те, которые одобряете:</p>";
                                    foreach ($reservation as $row) {
                                        $q = $row['id'];
                                        echo "<p>".date('Y-m-d H:i:s', $row['time'] - 3600)."    ".$row['user']."    ".$row['count']." человек <input type='checkbox' name='$q' value='1'></p><br>";
                                    }
                                    echo "<input type='submit' name='subm' value='Отправить'>";
                                }
                            ?>
                    </form>
                    <h1 align='center'>Брони</h1>
                    <table align='center' cellspacing='10'>
                        <tr>
                            <th>Время</th>
                            <th>Пользователь</th>
                            <th>Количество мест</th>
                        </tr>
                        <?php
                            if (isset($_POST['filter'])) {
                                if (count($reservation_non) == 0) {
                                    echo "Для данного периода нет броней";
                                } 
                                else {
                                    foreach ($reservation_non as $reserv) {
                                        echo "<tr><td>".date('Y-m-d H:i:s', $reserv['time'] - 3600)."</td>
                                        <td>".$reserv['user']."</td>
                                        <td>".$reserv['count']."</td></tr>";
                                    }
                                }
                            }
                            else {
                                if (count($reservation_non) == 0) {
                                    echo "Для данного периода нет броней";
                                } 
                                else {
                                    foreach ($reservation_non as $reserv) {
                                        echo "<tr><td>".date('Y-m-d H:i:s', $reserv['time'] - 3600)."</td>
                                        <td>".$reserv['user']."</td>
                                        <td>".$reserv['count']."</td></tr>";
                                    }
                                }
                            }
                        ?>
                    </table>
                </td>
                <td>
                    <h2>Фильтр</h2>
                    <form method='POST'>
                        <label> c <input type='datetime-local' name='stdate'>
                        до <input type='datetime-local' name='fidate'></label>
                        <input type='submit' name='filter' value='Отфильтровать'>
                    </form>
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