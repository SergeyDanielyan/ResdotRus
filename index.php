<?php
    $mysqli = new mysqli("localhost", "SergeyDanielyan", "rfQrBSHLCQHKFwbt", "resdotrus");
    $restaurants_query = $mysqli->query("SELECT * FROM restaurants");
    $restaurants = array();
    while ($row = mysqli_fetch_array($restaurants_query)) {
        $restaurants[] = $row;
    }

    if (isset($_POST['search_subm'])) {
        $val = '%'.$_POST['search'].'%';
        $restaurants_query = $mysqli->query("SELECT * FROM restaurants WHERE name LIKE '$val'");
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
    $mode = $_COOKIE['mode'];
    if ($mode == 2) {
        header("Location: rest_rest.php");
        exit();
    }
?>

<!DOCTYPE html5>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ResdotRus</title>
        <link rel='icon' type='image' href='logo.jpg'>
        <link rel='stylesheet' href='style.css' type='text/css'> 
    </head>
    <body>
        <table width='100%' height='100%' align='center' cellspacing='0'>
            <tr>
                <td width='30%'><a href='index.php'>ResdotRus</a></td>
                <?php
                    if (isset($_COOKIE['mode']) && isset($_COOKIE['id'])) {
                        $id = $_COOKIE['id'];
                        $user = mysqli_fetch_array($mysqli->query("SELECT * FROM users WHERE id = ".$id));
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