<?php
include('../admin/connect.php');

if(isset($_POST['usunZkoszyka'])){ 
  $id_koszyka = $_POST['id_koszyka']; 
  $query = "DELETE FROM koszyk_ip WHERE id = $id_koszyka;";  
  mysqli_query($db, $query);
} 
if(isset($_POST['awaryjnyResetKoszyka'])){ 
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $adres_ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $adres_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $adres_ip = $_SERVER['REMOTE_ADDR'];
}
  $query = "DELETE FROM koszyk_ip WHERE `adres_ip` = '$adres_ip';";
  
  mysqli_query($db, $query);
}     
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>

    </style>

</head>
<body>
  <header>
    <h1>Sklep</h1>
    <nav>
      <ul>
      <li><a href="../index.php">Strona główna</a></li>
        <li><a href="#">Koszyk</a></li>
      </ul>
    </nav>
  </header>
  <?php
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $adres_ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $adres_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
      $adres_ip = $_SERVER['REMOTE_ADDR'];
  }
  $koszyk_query = "SELECT * FROM koszyk_ip WHERE adres_ip LIKE '$adres_ip'";
  if ($koszyk_result = $db->query($koszyk_query)) {

    if ($koszyk_result->num_rows > 0) {
      echo"<table class='table'>";
      echo"<h1 style='text-align: center'>W twoim koszyku znajduje się: </h1>";
          while ($koszyk_row = $koszyk_result->fetch_object()) {
            echo"<tr>";
            $przedmioty_query = "SELECT * FROM przedmioty WHERE id = ".$koszyk_row->id_przedmiot."";
            if ($przedmioty_result = $db->query($przedmioty_query)) {
              if ($przedmioty_result->num_rows > 0) {
                while ($przedmioty_row = $przedmioty_result->fetch_object()) {
                  echo"<td>".$przedmioty_row->nazwa."</td>";
                  echo"<td>".$przedmioty_row->opis."</td>";
                  echo"<td class='price'>".$przedmioty_row->cena." zł</td>";
                  if (!empty($przedmioty_row->grafika)) {
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($przedmioty_row->grafika) ."' class='card-img-top' alt='' id='obraz'></td>";                  
                  }
                  else{
                    echo "<div id='obraz'></div>";
                  }
                  echo"<td>";
                    echo"<form method='post' action=''>";
                      echo"<input type='text' value=".$koszyk_row->id." hidden name='id_koszyka'>";
                      echo"<input type='submit' class='btn btn-danger' class='delete-from-cart-btn' value='Usuń z koszyka' name='usunZkoszyka'>";
                    echo"</form>";
                  echo"</td>";
                }

              } else {
                echo "<div class='alert alert-danger' role='alert'>Przedmiot zapisany w koszyku nie istnieje w bazie danych!<form method='post' action=''><input type='submit' class='btn btn-danger' name='awaryjnyResetKoszyka' value='Resetuj całą zawartość koszyka'></form></div>";
              }
            }
            
          }
          echo"<tr>";
          echo"<td></td>";
          echo"<td></td>";
          echo"<td id='priceSummary'>Razem: </td>";
          echo"<td></td>";
          echo"<td><button type='button' class='btn btn-primary' onclick='checkout()'>Przejdź do dostawy</button></td>";
          echo"</tr>";

          echo"</table>";
          
    } else {
        echo "<div class='alert alert-info' role='alert'>Koszyk jest pusty! Dodaj przedmioty do koszyka i zajrzyj tu później</div>";
    }
} else {
    echo "Error: " . $db->error;
}
$db->close();
?>
  <footer>
    <a>Robert Szczotka</a>
  </footer> 
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
<script>
  function checkout(){
    location.href='../checkout/index.php'
  }
  //Once site loaded get values from td's with class price and sum them up and display in td with id priceSummary
  $(document).ready(function(){
    var sum = 0;
    $('.price').each(function() {
      sum += parseFloat($(this).text());
    });
    $('#priceSummary').append(sum + " zł");
  });
</script>

</html>
