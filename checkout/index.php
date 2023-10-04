<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
include('../admin/connect.php');

if(isset($_POST['zamawiam'])){ 
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $adres_ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $adres_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
      $adres_ip = $_SERVER['REMOTE_ADDR'];
  }
  
  $query = "SELECT * FROM koszyk_ip WHERE adres_ip LIKE '$adres_ip'";
  $przemioty_z_koszyka = mysqli_query($db, $query);
  $id_przedmiot_array = array();
  for($i = 0; $i < mysqli_num_rows($przemioty_z_koszyka); $i++){
      $row = mysqli_fetch_assoc($przemioty_z_koszyka);
      $id_przedmiot_array[$i] = $row['id_przedmiot'];
  }

  $data = date("Y-m-d H:i:s");
  $imie = $_POST['form-imie'];
  $nazwisko = $_POST['form-nazwisko'];
  $email = $_POST['form-email'];
  $ulica = $_POST['form-ulica'];
  $numer_domu = $_POST['form-numer_domu'];
  $miejscowosc = $_POST['form-miejscowosc'];
  $kod_pocztowy = $_POST['form-kod-pocztowy'];
  $kraj = $_POST['form-kraj'];
  $wysylka = $_POST['form-wysylka'];
  $id_przedmiot_array = implode(", ", $id_przedmiot_array);

  
  
  $query = "INSERT INTO zamowienia (data_zamowienia, adres_ip_zamawiajacego, imie, nazwisko, email, ulica, numer_domu, miejscowosc, kod_pocztowy, kraj, zamowione_produkty, wysylka) VALUES ('$data', '$adres_ip', '$imie', '$nazwisko', '$email', '$ulica', '$numer_domu', '$miejscowosc', '$kod_pocztowy', '$kraj', '$id_przedmiot_array', '$wysylka');";
  mysqli_query($db, $query);
  echo "<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        showCloseButton: true,
      })
      
      Toast.fire({
        icon: 'success',
        title: 'Pomyślnie złożono zamówienie'
      })
  </script>";

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
  <div class='mx-auto' id='form-center'>
    <form method='post' action=''>
      <table>
        <!-- //? DANE OSOBOWE -->
        <tr>
          <td class="personal-info-td">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="form-imie" placeholder="" id="floatingInput">
              <label for="floatingInput">Imię</label>
            </div>
          </td>
          <td class="personal-info-td">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="form-nazwisko" placeholder="" id="floatingInput">
              <label for="floatingInput">Nazwisko</label>
            </div>
          </td>
          <td class="personal-info-td">
            <div class="form-floating mb-3">
              <input type="email" class="form-control" name="form-email" placeholder="" id="floatingInput">
              <label for="floatingInput">Adres email</label>
            </div>
          </td>
        </tr>
        <!--//? ADRES -->
        <tr>
          <table>
            <tr>
              <td class="adres-info-td" style="width: 20%;">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" name="form-ulica" placeholder="" id="floatingInput">
                  <label for="floatingInput">Ulica</label>
                </div>
              </td>
              <td class="adres-info-td" style="width: 20%;">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" name="form-numer_domu" placeholder="" id="floatingInput">
                  <label for="floatingInput">Numer domu</label>
                </div>
              </td>
              <td class="adres-info-td" style="width: 20%;">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" name="form-miejscowosc" placeholder="" id="floatingInput">
                  <label for="floatingInput">Miejscowość</label>
                </div>
              </td>
              <td class="adres-info-td" style="width: 20%;">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" name="form-kod-pocztowy" placeholder="" id="floatingInput">
                  <label for="floatingInput">Kod pocztowy</label>
                </div>
              </td>
              
            </table>
          </tr>
          <!--//? Wysyłka -->
          <tr>
          <td class="transport-info-td">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" name="form-kraj" placeholder="" id="floatingInput">
                  <label for="floatingInput">Kraj</label>
                </div>
              </td>
            <td class="transport-info-td">
              <div class="form-floating mb-3">
                <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="form-wysylka">
                  <option disabled selected>Wybierz</option>
                  <option value="Poczta">Poczta</option>
                  <option value="DPD">DPD</option>
                  <option value="GLS">GLS</option>
                </select>
                <label for="floatingSelect">Wysyłka</label>
              </div>
            </td>
        </tr>
        <tr>
          <td>
            <input type="submit" class="btn btn-primary" value="Zamawiam" name="zamawiam" style="width: 100%; height: 50px;">
          </td>
        </tr>
      </table>
    </form>
  </div>
  <footer>
    <a>Robert Szczotka</a>
  </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
<script>

</script>

</html>
