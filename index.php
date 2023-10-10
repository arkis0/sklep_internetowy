<?php
include('admin/connect.php');

if(isset($_POST['dodajDoKoszyka'])){ //check if form was submitted
    $id_przedmiot = $_POST['id_przedmiot']; //get input text
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $adres_ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $adres_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
      $adres_ip = $_SERVER['REMOTE_ADDR'];
  }

    $query = "INSERT INTO koszyk_ip (adres_ip, id_przedmiot) VALUES ('$adres_ip', '$id_przedmiot');";
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
    <link rel="stylesheet" href="style.css">

</head>
<body>
  <header>
    <h1>Sklep</h1>
    <nav>
      <ul>
      <li><a href="#">Strona główna</a></li>
        <li><a href="koszyk/index.php">Koszyk</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <?php
    $wyswietlane_przedmioty = 0;
    $query = "SELECT * FROM przedmioty";
    if ($result = $db->query($query)) {
        if ($result->num_rows > 0) {
            echo "<table class='mx-auto'>";
            while ($row = $result->fetch_object()) {
                if ($wyswietlane_przedmioty % 3 == 0) {
                    echo "<tr>";
                }
                echo "<td>";
                echo "<div class='col-2' id='karta'>";
                echo "  <div class='cardy'>";
                echo "      <div class='card' style='width: 18rem;'>";
                if (!empty($row->grafika)) {
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row->grafika) . "' class='card-img-top' alt='' id='obraz'>";
                } else {
                    echo "<div id='obraz'></div>";
                }
                echo "          <div class='card-body'>";
                echo "                  <h5 class='card-title'>" . $row->nazwa . "</h5>";
                echo "                    <h6 class='card-subtitle mb-2 text-muted'>". $row->cena ." zł</h6>";
                echo "                  <p class='card-text'>" . $row->opis . "</p>";
                echo "                    <form method='post' action=''>";
                echo "                    <input type='number'value=". $row->id ." hidden name='id_przedmiot'></input>";
                echo "                    <input type='submit'class='btn btn-primary' value='Dodaj do koszyka' name='dodajDoKoszyka'></input>";
                echo "                    </form>";
                echo "          </div>";
                echo "     </div>";
                echo "   </div>";
                echo "</div>";
                echo "</td>"; // End the table cell
                if (($wyswietlane_przedmioty + 1) % 3 == 0) {
                    echo "</tr>"; // End the table row after every 3 items
                }
                $wyswietlane_przedmioty++;
            }
            // Close any remaining table row
            if ($wyswietlane_przedmioty % 3 != 0) {
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No results to display!";
        }
    } else {
        echo "Error: " . $db->error;
    }

    $db->close();
    ?>
  </div>

  <footer>
    <a>Robert Szczotka</a>
  </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</html>