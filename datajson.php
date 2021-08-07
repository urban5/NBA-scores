<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Rezulati na datum</title>

<link rel='stylesheet' type='text/css' href='oblikovanje/oblikovanjeCSS.css' />
<link rel='stylesheet' href='oblikovanje/bootstrap/css/bootstrap.min.css' />
<link rel='stylesheet' href='oblikovanje/bootstrap/css/bootstrap-theme.min.css' />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel='stylesheet' href='https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css'>

<?php 

if(isset($_POST['submit'])){
    $datumPHP = $_POST['datum'];
    echo "ffffffffffffffffff" . strval($datumPHP);
    $novDatum = strval(date("Ymd", strtotime($datumPHP))); //spremeni obliko datuma brez -
    $link="http://data.nba.net/10s/prod/v1/" . $novDatum . "/scoreboard.json";
    $curl = curl_init();
    curl_setopt_array($curl,[
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_URL => $link,
        CURLOPT_USERAGENT => 'NBA API in CURL'
    ]);

    $response = curl_exec($curl); //prekopira rezultate iz APIja v spremenljivko response
    $json = json_decode($response, true); //prevede string v PHP spremenljivko, v asociativni array
    
    $hTeams =  array();
    $vTeams =  array();
    $hTeamsScores =  array();
    $vTeamsScores =  array();
    
    if(isset($json['games'])){
      foreach($json['games'] as $game){
          $vTeams[] = $game['vTeam']['triCode']; //shrani visiting teams v array
          $hTeams[] = $game['hTeam']['triCode'];
          $vTeamsScores[] = $game['vTeam']['score'];
          $hTeamsScores[] = $game['hTeam']['score'];
          $gameID[] = $game['gameId'];
      }
    }
    else{ 
      echo '<script>' . 'window.alert("Niste izbrali datuma ali pa na izbran datum ni bilo tekem. Prosimo vas, da za pravilen prikaz rezultatov izberete ustrezen datum.");';
      echo 'window.location.href = "index.php";' . '</script>';
    }
    //print_r($gameID) . "---";
    curl_close($curl);
    //print_r($json);
    //print_r($gameID[0]);
}
//----------------------------zgoraj je del, ki sparsa json podatke v arraye ekip in rezultatov-------------------------------

$playersFirstName =  array();
$playersLastName =  array();
$playersTeamID =  array();
$playersPoints =  array();
$playersAssists =  array();
$playersRebounds = array();
$playersSteals = array();
$playersMins = array();

if(isset($gameID)){
for($i=0; $i<=count($gameID)-1; $i++){
$link="http://data.nba.net/10s/prod/v1/" . $novDatum . "/" . $gameID[$i] . "_boxscore.json";
    $curl = curl_init();
    curl_setopt_array($curl,[
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_URL => $link,
        CURLOPT_USERAGENT => 'NBA API in CURL'
    ]);

    $response2 = curl_exec($curl); //prekopira rezultate iz APIja v spremenljivko response2
    $json2 = json_decode($response2, true); //prevede string v PHP spremenljivko, v asociativni array
    
    foreach($json2['stats']['activePlayers'] as $players){
        $playersFirstName[] = $players['firstName']; //shrani imena v array
        $playersLastName[] = $players['lastName'];
        $playersTeamID[] = $players['teamId'];
        $playersPoints[] = $players['points'];
        $playersAssists[] = $players['assists'];
        $playersRebounds[] = $players['totReb'];
        $playersSteals[] = $players['steals'];
        $playersMins[] = $players['min'];
    }
}

curl_close($curl);
}

    //echo count($playersTeamID);
    //print_r($playersTeamID);
    //print_r($playersLastName);
    
    function izpisBoxScore($zacetniIndeks, $koncniIndeks) {
        global $playersLastName;
        global $playersFirstName;
        global $playersPoints;
        global $playersAssists;
        global $playersRebounds;
        global $playersSteals;
        global $playersMins;
        global $playersTeamID;
        
        for($i=$zacetniIndeks; $i<=$koncniIndeks; $i++){
            print strval($playersFirstName[$i]) . " " .
            strval($playersLastName[$i]) . " " .
            strval($playersPoints[$i]) . "--" .
            strval($playersAssists[$i]) . "--" .
            strval($playersRebounds[$i]) . "--" .
            strval($playersSteals[$i]) . "--" . 
            strval($playersMins[$i]) . "--" . 
            strval($playersTeamID[$i]) . "--" . 
            strval($i) . "</br>";
        }
      }

    function najdiMejnaIndeksa($vhodnaTabela){
        $stevecElementov = 1;
        $indeksi = array();
        $indeksi[0]=0;
        if(isset($vhodnaTabela)){
            for($i=0; $i<count($vhodnaTabela)-1; $i++){
                if($vhodnaTabela[$i] == $vhodnaTabela[$i+1]){
                }
                else{
                    $indeksi[] = $i;
                    $indeksi[] = $i + 1;
                }
            }
        }
        $indeksi[] = count($vhodnaTabela)-1;
        return $indeksi;
        //print_r($indeksi);
      }

      //------------------------------------------------------glej dol


      function izpisiTabeloBoxScore2($indeksZaEnoEkipo){
        global $playersLastName;
        global $playersFirstName;
        global $playersPoints;
        global $playersAssists;
        global $playersRebounds;
        global $playersSteals;
        global $playersMins;
        global $playersTeamID;
        if($indeksZaEnoEkipo<=0){$indeksZaEnoEkipo=0;}
        else{$indeksZaEnoEkipo = $indeksZaEnoEkipo * 4;}
        echo '<table class="table table-stiped table-bordered mydatatable" style="width:100%">
          <thead>
            <tr>
              
              <th scope="col">Ime in Priimek igralca</th>
              <th scope="col">Točke</th>
              <th scope="col">Podaje</th>
              <th scope="col">Skoki</th>
              <th scope="col">Ukradene žoge</th>
              <th scope="col">Čas igranja</th>
              <th scope="col">ID ekipe</th>
            </tr>
          </thead>
          <tbody>';
                for($i=najdiMejnaIndeksa($playersTeamID)[$indeksZaEnoEkipo]; $i<najdiMejnaIndeksa($playersTeamID)[$indeksZaEnoEkipo+1]; $i++){
                    echo '<tr><td>' . strval($playersFirstName[$i]) . ' ' . strval($playersLastName[$i]) .'-'. strval($indeksZaEnoEkipo-1) . '</td>';
                    echo '<td>' . strval($playersPoints[$i]) . '</td>';
                    echo '<td>' . strval($playersAssists[$i]) . '</td>';
                    echo '<td>' . strval($playersRebounds[$i]) . '</td>';
                    echo '<td>' . strval($playersSteals[$i]) . '</td>';
                    echo '<td>' . strval($playersMins[$i]) . '</td>';
                    echo '<td>' . strval($playersTeamID[$i]) . '</td></tr>'; 
                }
            echo '</tbody></table>';
         
            echo '<table class="table"><thead class="thead-light"><tr><th scope="col">Ime in Priimek igralca</th><th scope="col">Točke</th><th scope="col">Podaje</th><th scope="col">Skoki</th>
            <th scope="col">Ukradene žoge</th><th scope="col">Čas igranja</th><th scope="col">ID ekipe</th></tr></thead><tbody>';
                for($i=najdiMejnaIndeksa($playersTeamID)[$indeksZaEnoEkipo+2]; $i<najdiMejnaIndeksa($playersTeamID)[$indeksZaEnoEkipo+3]; $i++){
                    echo '<tr><td>' . strval($playersFirstName[$i]) . ' ' . strval($playersLastName[$i]) . '</td>';
                    echo '<td>' . strval($playersPoints[$i]) . '</td>';
                    echo '<td>' . strval($playersAssists[$i]) . '</td>';
                    echo '<td>' . strval($playersRebounds[$i]) . '</td>';
                    echo '<td>' . strval($playersSteals[$i]) . '</td>';
                    echo '<td>' . strval($playersMins[$i]) . '</td>';
                    echo '<td>' . strval($playersTeamID[$i]) . '</td></tr>'; 
                }
            echo '</tbody></table>';
            
      }


      //------------------------------------------------------glej gor

    function izpisiTabeloBoxScore($indeksZaEnoEkipo){
        global $playersLastName;
        global $playersFirstName;
        global $playersPoints;
        global $playersAssists;
        global $playersRebounds;
        global $playersSteals;
        global $playersMins;
        global $playersTeamID;
        if($indeksZaEnoEkipo<=0){$indeksZaEnoEkipo=0;}
        else{$indeksZaEnoEkipo = $indeksZaEnoEkipo * 4;}
        echo '<table class="table">
          <thead class="thead-light">
            <tr>
              
              <th scope="col">Ime in Priimek igralca</th>
              <th scope="col">Točke</th>
              <th scope="col">Podaje</th>
              <th scope="col">Skoki</th>
              <th scope="col">Ukradene žoge</th>
              <th scope="col">Čas igranja</th>
              <th scope="col">ID ekipe</th>
            </tr>
          </thead>
          <tbody>';
                for($i=najdiMejnaIndeksa($playersTeamID)[$indeksZaEnoEkipo]; $i<najdiMejnaIndeksa($playersTeamID)[$indeksZaEnoEkipo+1]; $i++){
                    echo '<tr><td>' . strval($playersFirstName[$i]) . ' ' . strval($playersLastName[$i]) .'-'. strval($indeksZaEnoEkipo-1) . '</td>';
                    echo '<td>' . strval($playersPoints[$i]) . '</td>';
                    echo '<td>' . strval($playersAssists[$i]) . '</td>';
                    echo '<td>' . strval($playersRebounds[$i]) . '</td>';
                    echo '<td>' . strval($playersSteals[$i]) . '</td>';
                    echo '<td>' . strval($playersMins[$i]) . '</td>';
                    echo '<td>' . strval($playersTeamID[$i]) . '</td></tr>'; 
                }
            echo '</tbody></table>';
         
            echo '<table class="table"><thead class="thead-light"><tr><th scope="col">Ime in Priimek igralca</th><th scope="col">Točke</th><th scope="col">Podaje</th><th scope="col">Skoki</th>
            <th scope="col">Ukradene žoge</th><th scope="col">Čas igranja</th><th scope="col">ID ekipe</th></tr></thead><tbody>';
                for($i=najdiMejnaIndeksa($playersTeamID)[$indeksZaEnoEkipo+2]; $i<najdiMejnaIndeksa($playersTeamID)[$indeksZaEnoEkipo+3]; $i++){
                    echo '<tr><td>' . strval($playersFirstName[$i]) . ' ' . strval($playersLastName[$i]) . '</td>';
                    echo '<td>' . strval($playersPoints[$i]) . '</td>';
                    echo '<td>' . strval($playersAssists[$i]) . '</td>';
                    echo '<td>' . strval($playersRebounds[$i]) . '</td>';
                    echo '<td>' . strval($playersSteals[$i]) . '</td>';
                    echo '<td>' . strval($playersMins[$i]) . '</td>';
                    echo '<td>' . strval($playersTeamID[$i]) . '</td></tr>'; 
                }
            echo '</tbody></table>';
            
      }
?>

<?php
$teamPairs = array(); //ustvari pravilne kombinacije nasporotnih ekip na tekmah
foreach($hTeams as $key => $row){
    foreach($vTeams as $key2 => $row2){
        if($key== $key2){
           $teamPairs[]= $row2 ." : ". $row;}
    }
}

$scorePairs = array(); //ustvari pravilen rezultat nasprotnih ekip npr 103:108
foreach($hTeamsScores as $key => $row){
    foreach($vTeamsScores as $key2 => $row2){
        if($key== $key2){
           $scorePairs[] = $row2." : ".$row;}
    }
}

foreach($teamPairs as $key => $row){ //izpiše v tabelo ustezne kombinacije naprotnih ekip z rezultatom
    foreach($scorePairs as $key2 => $row2){
        if($key == $key2){
            echo '<tr>'.'<td>'.$teamPairs[$key].'</td>'.'<td>'.$scorePairs[$key].'</td>'.'</tr>';
        }
    }
}
?>
</head>

<body>

<div class="containter">
    <div class="row">
        <div class="col-xs-12">
            <table class='table'>
                <tr>----primer</tr>
                <td>--še neki</td>
            </table>
        </div>
    </div>
</div>

<div class="parent" id="prvi"> houhouhou otroci!
    <div class="child-one"><?php echo $teamPairs[1]; ?></div>
    <div class="child-two"><?php echo $teamPairs[1]; ?></div>
    <div class="child-three"><?php echo $teamPairs[1]; ?></div>
</div>

<?php
foreach($teamPairs as $key => $row){ //izpiše v tabelo ustezne kombinacije naprotnih ekip z rezultatom
    foreach($scorePairs as $key2 => $row2){
        if($key == $key2){
            echo '<div class="expanding" id="prvi">'.'</br>'.$teamPairs[$key].'</br>'.$scorePairs[$key].'</div>'.'<div class="content">neki</div>';
        }
    }
}
?>

<?php

foreach($teamPairs as $key => $row){ //izpiše v tabelo ustezne kombinacije naprotnih ekip z rezultatom
    foreach($scorePairs as $key2 => $row2){
        if($key == $key2){
            echo '<div class="collapsible">'.'</br>' . $teamPairs[$key].'</br>'. '  ' . $scorePairs[$key].'</div>'.'<div class="content">'. print(izpisiTabeloBoxScore($key-1)) .'</div>';
            //echo '<div class="collapsible">'.'</br>'.$teamPairs[$key].'</br>'.$scorePairs[$key].'</div>'.'<div class="content">'. print(izpisiTabeloBoxScore($key-1)) .'</div>';
            //echo '<div class="collapsible">'.'</br>'.$teamPairs[$key].'</br>'.$scorePairs[$key].'</div>'.'<div class="content">'. print(izpisBoxScore(najdiMejnaIndeksa($playersTeamID)[($key-1)*4], najdiMejnaIndeksa($playersTeamID)[3+($key-1)*4])) .'</div>';
              }
    }
}echo '</div><div>hejej</div>';

?>

<script>
src="oblikovanje/bootstrap/js/bootstrap.min.js"


var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active"); //this se nanaša na globalni object, ki trenutno izvaja funkcijo; classList.toggle(active) povzroči odprtje novega div okvirčka, argument active pa se nanaša na css class, ki se priredi novemu divu
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  })
}


var coll2 = document.getElementsByClassName("expanding");
var i;

for (i = 0; i < coll2.length; i++) {
  coll2[i].addEventListener("mouseover", function() {
    this.classList.toggle("active"); //this se nanaša na globalni object, ki trenutno izvaja funkcijo; classList.toggle(active) povzroči odprtje novega div okvirčka, argument active pa se nanaša na css class, ki se priredi novemu divu
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  })
  
  coll2[i].addEventListener("mouseleave", function() {
    this.classList.toggle("active"); //this se nanaša na globalni object, ki trenutno izvaja funkcijo; classList.toggle(active) povzroči odprtje novega div okvirčka, argument active pa se nanaša na css class, ki se priredi novemu divu
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  })
  
  ;
}
</script>

<script>src="https://code.jquery.com/jquery-3.3.1.min.js"</script>
<script>src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"</script>
<script>src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"</script>
<script>src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"</script>
<script>src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"</script>
<script>$('.mydatatable').DataTable();</script>

</body>
</html>