<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="index.css">
    <title>Email Extractor</title>
</head>
<body>
    <div id="container">
        <div><h1>Email Extractor</h1></div>
        <div id="cadre">
            <form action="email_extractor.php" method="post"> 
                <div id="divUrl">
                    <input type="url" id="url" name="url" placeholder="Adresse URL">
                </div><br>
                <div>
                    <button type="submit" class="myButton" id="bouton" value="Click!">Récupérer</button>
                </div>
            </form>
        </div>
        <div id="error"><?php 
                            if(!empty($_SESSION['error'])){
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);}
                            ?>
        </div>
    </div>
</body>

</html>