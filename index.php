<?php 
session_start();
if(isset($_SESSION["sejaUporabniskoIme"])){
    echo "Prijavljeni ste v račun z uporabniškim imenom: " . $_SESSION["sejaUporabniskoIme"] . "<br>" . "Vaše najljubše ekipe so:";
}
?>

<!DOCTYPE html>
<html>
<head> 
    <meta charset="UTF-8">
    <title>NBA rezultati</title>
    <link rel='stylesheet' type='text/css' href='oblikovanje/oblikovanjeCSS.css' />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Varela+Round" />
</head>

<body> 
    
    <ul class="topnav">
        <li><a class="active" href="#home">Domov</a></li>
        <li><a href="#news">Drevo končnice</a></li>
        <li><a href="#contact">Objave NBA Slovenija</a></li>
        <li class="right"><a href="login.php">Prijava</a></li>
      </ul>
    
    <form action="datajson.php" method="post">
  
    <div class="box">
        <div class="datum">
            <div id="napis">Tekme na poljuben datum:</div>
            <input type="date" id="date-input" name="datum" min='2019-01-01' max>
            <input id="submit-date" type="submit" name="submit" value="potrdi">
        </div>
    </div>
    
    <script>
        var today = new Date();
        var dd = today.getDate() - 1;
        var mm = today.getMonth() + 1; 
        var yyyy = today.getFullYear();
        if(dd<10){
            dd='0'+dd
        } 
        if(mm<10){
            mm='0'+mm
        } 
        today = yyyy+'-'+mm+'-'+dd;
        document.getElementById("date-input").setAttribute("max", today);
    </script>

</body>
    
</html>
