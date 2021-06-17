<?php
    $mysqli = new mysqli("localhost", "SergeyDanielyan", "rfQrBSHLCQHKFwbt", "resdotrus");
    $restaurants_query = $mysqli->query("SELECT * FROM restaurants");
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
        $restaurants_query = $mysqli->query("SELECT * FROM restaurants WHERE cousine_id = ".$_POST['cousine']);
        $restaurants = array();
        while ($row = mysqli_fetch_array($restaurants_query)) {
            $restaurants[] = $row;
        }
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
        <!--<link rel='stylesheet' type='text/css' href='style.css'>-->
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
                <?php
                    if (isset($_COOKIE['mode']) && isset($_COOKIE['id'])) {
                        $id = $_COOKIE['id'];
                        $user = mysqli_fetch_array($mysqli->query("SELECT * FROM users WHERE id = ".$id));
                        echo "<td width='30%'><a href='favorites.php' class='link'>Избранные</a></td>
                        <td><p align='right'><a href='user_page.php' class='link'>".$user['name']."</a> <a href='logout.php' class='link'>Выйти</a></p></td>";
                    }
                    else {
                        echo "<td></td>
                        <td><p align='right'><a href='userauth.php' class='link'>Вход</a> <a href='userreg.php' class='link'>Регистрация</a> <a href='restauth.php' class='link'>Вход от ресторана</a> <a href='restreg.php' class='link'>Регистрация ресторана</a></p></td>";
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
                <td><h4 align='center' class='footertext'>Сергей Даниелян, Стефан Погосян </h4></td>
            </tr>
        </table>
    </body>
</html>