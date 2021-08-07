<?php

    echo "kaj ma";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link rel="stylesheet" a href="oblikovanje/cssPrijava.css">
</head>
<body>
    <div class="container">
        <form method="POST" action="#">
            <div class="form-input">
                <input id="username" type="text" name="username" placeholder="uporabniško ime"/>	
            </div>
            <div class="form-input">
                <input id="password" type="password" name="password" placeholder="geslo"/>
            </div>
            <div class="form-input">
                <input type="checkbox" id="team1" name="team1" value="favTeams">
                <label for="vehicle1" class="labelList"> LAL </label><br>
                
                <input type="checkbox" id="team2" name="team1" value="favTeams">
                <label for="vehicle1" class="labelList"> DAL </label><br>
            </div>

            <input type="submit" type="submit" value="PRIJAVA" class="btn-login"/>
        </form>
    </div>

</body>
</html>