<?php
/** Post manager
 *  Copyright (C) Simon Raichl 2018
 *  MIT License
 *  Use this as you want, share it as you want, do basically whatever you want with this :)
 */

include("core/Core.php");

$core = new Core();

$title = $_GET["new-title"];
$text = $_GET["new-content"];

if ($_GET != [] && $title !== "" && $text !== ""){
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
        <div class="article-container">
        <button class="submit-button create-article-btn" id="js-form-btn">Open 'new post' box</button>
        <div class="input-container" id="js-form" style="display:none">
            <div class="article-new-header">New post</div>
            <form method="GET">
                <input required type="text" name="new-title" class="text-inputs" placeholder="Insert a new title here">
                <textarea required class="text-inputs" name="new-content" placeholder="Insert here a text for your post"></textarea>
                <input type="submit" class="submit-button" value="Submit a new post">
            </form>
        </div>
        <?php

        $results = $core->fetchAll("select title, text from posts");

        foreach ($results as $result)
        {
            echo
                "<div class='article-box'>".
                    "<div class='article-header'>" . htmlentities($result[0]) . "</div>".
                    "<div class='article-content'>" . htmlentities($result[1]) . "</div>".
                "</div>";
        }

        ?>
    </div>

    </body>
</html>
