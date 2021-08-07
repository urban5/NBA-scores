<?php 
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "demo";

$con = mysqli_connect($host, $user, $password, $db);
mysqli_select_db(mysqli_connect($host, $user, $password, $db),$db);

if(isset($_POST['username'])){
    $uname = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM loginform WHERE loginform.user='".$uname."' AND pass='".$password."' LIMIT 1";
    $result = mysqli_query($con, $sql);
    if(mysqli_num_rows($result)==1){
        echo "Prijava je uspešna. ";
		$_SESSION["sejaUporabniskoIme"] = $uname; //v tej vrstici se nastavi php sejna spremenljivka sejaUporabniskoIme na vnešeno vrednost
    }
    else{
		echo "Prijava ni bila uspešna.";
		session_unset();
	}
    exit();
}

?>



<!DOCTYPE html>
<html>
<head>
	<title>Prijava</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" a href="oblikovanje/cssPrijava.css">
	<link rel="stylesheet" a href="oblikovanje/prijava/fontawesome.min.css">
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
			<input type="submit" type="submit" value="PRIJAVA" class="btn-login"/>
		</form>
	</div>

</body>
</html>