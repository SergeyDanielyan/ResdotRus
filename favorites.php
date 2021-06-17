<?php
    $mysqli = new mysqli("localhost", "SergeyDanielyan", "rfQrBSHLCQHKFwbt", "resdotrus");
    $my_id = $_COOKIE['id'];
    $restaurants_query = $mysqli
    ->query("SELECT restaurants.id AS id, restaurants.name AS name, restaurants.average_order_value AS average_order_value, 
    restaurants.adress AS adress, restaurants.working_hours AS working_hours
    FROM favorites
    INNER JOIN restaurants ON restaurant_id = restaurants.id
    WHERE favorites.user_id =".$my_id);
    $restaurants = array();
    while ($row = mysqli_fetch_array($restaurants_query)) {
        $restaurants[] = $row;
    }

    $cousines_query = $mysqli->query("SELECT * FROM cousines");
    $cousines = array();
    while ($row = mysqli_fetch_array($cousines_query)) {
        $cousines[] = $row;
    }

    if (isset($_POST['filt'])) {
        $restaurants_query = $mysqli->query("SELECT restaurants.id AS id, restaurants.name AS name, restaurants.average_order_value AS average_order_value, 
        restaurants.adress AS adress, restaurants.working_hours AS working_hours
        FROM favorites
        INNER JOIN restaurants ON restaurant_id = restaurants.id
        WHERE favorites.user_id =".$my_id." AND cousine_id = ".$_POST['cousine']);
        $restaurants = array();
        while ($row = mysqli_fetch_array($restaurants_query)) {
            $restaurants[] = $row;
        }
    }

    if (isset($_POST['search_subm'])) {
        $val = '%'.$_POST['search'].'%';
        $restaurants_query = $mysqli
        ->query("SELECT restaurants.id AS id, restaurants.name AS name, restaurants.average_order_value AS average_order_value, 
        restaurants.adress AS adress, restaurants.working_hours AS working_hours
        FROM favorites
        INNER JOIN restaurants ON restaurant_id = restaurants.id
        WHERE user_id = ".$my_id." AND restaurants.name LIKE '$val'");
        $restaurants = array();
        while ($row = mysqli_fetch_array($restaurants_query)) {
            $restaurants[] = $row;
        }
    }

    if (isset($_POST['rest_submit'])) {
        if (isset($_POST['rest'])) {
            setcookie('rest_id', $_POST['rest']);
            header("Location: restaurant.php");
            exit();
        }
    }
?>

<!DOCTYPE html5>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ResdotRus, избранные</title>
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

            label {
                font-size: 25px;
            }

            h2 {
                font-size: 30px;
            }
        </style>
    </head>
    <body>
        <table width='100%' height='100%' align='center' cellspacing='0'>
            <tr class='header'>
                <td width='30%'><a href='index.php' class='link'><strong class='title'>ResdotRus</strong></a></td>
                <td width='30%'><a href='favorites.php' class='link'>Избранные</a></td>
                <?php
                    $user = mysqli_fetch_array($mysqli->query("SELECT * FROM users WHERE id = ".$my_id));
                    echo "<td><p align='right'><a href='user_page.php' class='link'>".$user['name']."</a> <a href='logout.php' class='link'>Выйти</a></p></td>";
                ?>
            </tr>
            <tr>
                <td></td>
                <td>
                    <form method='POST'>
                        <input type='search' name='search' placeholder='Поиск'>
                        <input type='submit' name='search_subm' value='Найти'>
                    </form>
                    <?php
                        if (count($restaurants) != 0) {
                            if (isset($_POST['rest_submit']) && !isset($_POST['rest'])) {
                                echo "<p align='center'>Пожалуйста, выберите ресторан.</p>";
                            }
                            if (isset($_POST['search_subm'])) {
                                if (count($restaurants) != 0) {
                                    echo "<form method='POST'>
                                        <input type='submit' name='rest_submit' value='Выбрать ресторан'><br><br>";
                                    foreach ($restaurants as $restaurant) {
                                        echo "<label><input type='radio' name='rest' value=".$restaurant['id']."> ".$restaurant['name']."</label><br>";
                                    }
                                    echo "</form>";
                                }
                                else {
                                    echo "<p align='center'>К сожалению, не найдено результатов.</p>";
                                }
                            }
                            else {
                                echo "<form method='POST'>
                                    <input type='submit' name='rest_submit' value='Выбрать ресторан'><br><br>";
                                foreach ($restaurants as $restaurant) {
                                    echo "<label><input type='radio' name='rest' value=".$restaurant['id']."> ".$restaurant['name']."</label><br>";
                                }
                                echo "</form>";
                            }
                        }
                        else {
                            echo "<p align='center'>К сожалению, не найдено результатов.</p>";
                        }
                    ?>
                </td>
                <td>
                    <div align='center'>
                        <h2>Фильтр</h2>
                        <form method='POST'>
                            <label>Кухня:
                            <select name='cousine'>
                            <?php
                                foreach ($cousines as $cousine) {
                                    echo "<option value=".$cousine['id'].">".$cousine['name']."</option>";
                                }
                            ?>
                            </select>
                            </label>
                            <input type='submit' name='filt' value='Выбрать'>
                        </form>
                    </div>
                </td>
            </tr>
            <tr class='footer'>
                <td><h3 class='footertext'>ResdotRus, 2021</h3></td>
                <td></td>
                <td><h4 class='footertext' align='center'>Сергей Даниелян, Стефан Погосян </h4></td>
            </tr>
        </table>
    </body>
</html>