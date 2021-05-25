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
        <link rel='stylesheet' href='style.css' type='text/css'> 
    </head>
    <body>
        <table width='100%' height='100%' align='center' cellspacing='0'>
            <tr>
                <td width='30%'><a href='index.php'>ResdotRus</a></td>
                <td width='30%'><a href='favorites.php'>Избранные</a></td>
                <?php
                    $user = mysqli_fetch_array($mysqli->query("SELECT * FROM users WHERE id = ".$my_id));
                    echo "<td><p align='right'><a href='user_page.php'>".$user['name']."</a> <a href='logout.php'>Выйти</a></p></td>";
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
                        if (isset($_POST['rest_submit']) && !isset($_POST['rest'])) {
                            echo "<p align='center'>Пожалуйста, выберите ресторан.</p>";
                        }
                        if (isset($_POST['search_subm'])) {
                            if (count($restaurants) != 0) {
                                echo "<form method='POST'>
                                    <input type='submit' name='rest_submit' value='Выбрать ресторан'><br><br>";
                                foreach ($restaurants as $restaurant) {
                                    echo "<label><input type='radio' name='rest' value=".$restaurant['id']."> ".$restaurant['name']." | ".$restaurant['average_order_value']." ₽ | ".$restaurant['adress']." | ".$restaurant['working_hours']."</label><br>";
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
                                echo "<label><input type='radio' name='rest' value=".$restaurant['id']."> ".$restaurant['name']." | ".$restaurant['average_order_value']." ₽ | ".$restaurant['adress']." | ".$restaurant['working_hours']."</label><br>";
                            }
                            echo "</form>";
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