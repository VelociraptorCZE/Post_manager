<?php
/** Post manager
 *  Copyright (C) Simon Raichl 2018
 *  MIT License
 *  Use this as you want, share it as you want, do basically whatever you want with this :)
 */

session_start();

include("core/Core.php");

$core = new Core();

$auth = $_SESSION ? $_SESSION["auth"] : false;

$title = $_POST["new-title"];
$text = $_POST["new-content"];
$_POST["username"] ? $name = $_POST["username"] : $name = $_SESSION["name"];
$pass = $_POST["password"];

if ($_POST !== [] && !$auth) {
    $auth = $core->fetchAll("select * from users where username='". addslashes($name) ."' and pass='" . addslashes($pass) ."'") !== [];
    if ($auth){
        $_SESSION["auth"] = $auth;
        $_SESSION["name"] = $name;
    }
    header("location: public/redirect.php");
}

if ($_POST !== [] && $title !== "" && $text !== "" && $auth){
    $connection = $core->connect();
    $insert = $connection->prepare("insert into posts (title, text) values (?, ?)");
    $insert->bind_param("ss", $title, $text);
    $insert->execute();
    $insert->close();
    $connection->close();
    header("location: public/redirect.php");
}

?>

<!DOCTYPE html>
<html lang="cs-cz">
    <head>
        <title>Post manager</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="iso-8859-15">
        <link rel="stylesheet" href="public/css/core.css">
        <script src = "public/js/app.js"></script>
    </head>
    <body>
        <div class="nav">
            <?php
            if ($auth){
                echo '<span class="nav-item">'. $core->escape($name) . '</span>';
                echo '<span class="nav-item" id="js-logout-btn">Log out</span>';
            }
            else{
                echo '<span class="nav-item" id="js-login-box-btn">Log in</span>';
            }
            ?>
        </div>
        <?php
            if (!$auth) {
                echo '<div class="login-box" id="js-login-box" style="display:none">
                    <form method="POST">
                        <input required type="text" name="username" class="text-inputs" placeholder="Username" autocomplete="username">
                        <input required type="password" name="password" class="text-inputs" placeholder="Password" autocomplete="current-password">
                        <input type="submit" class="submit-button" value="Log in">
                    </form>
                </div>';
            }
         ?>

        <div class="article-container">

        <?php

        if ($auth){
            echo '<button class="submit-button create-article-btn" id="js-form-btn">Open &quot;new post&quot; box</button>
            <div class="input-container" id="js-form" style="display:none">
                <div class="article-new-header">New post</div>
                <form method="POST">
                    <input required type="text" name="new-title" class="text-inputs" placeholder="Insert a new title here">
                    <textarea required class="text-inputs" name="new-content" placeholder="Insert here a text for your post"></textarea>
                    <input type="submit" class="submit-button" value="Submit a new post">
                </form>
            </div>';
        }

        $results = $core->fetchAll("select title, text from posts");

        foreach ($results as $result)
        {
            echo
                "<div class='article-box'>".
                    "<div class='article-header'>" . $core->escape($result[0]) . "</div>".
                    "<div class='article-content'>" . $core->escape($result[1]) . "</div>".
                "</div>";
        }

        ?>
    </div>

    </body>
</html>
