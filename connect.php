<?php

// Kredensial database
$dbname = 'monitoring_toshin'; // Ganti dengan nama database Anda
$dbuser = 'root';    // User default XAMPP
$dbpass = '';        // Password default XAMPP (biasanya kosong)
$dbhost = 'localhost'; // Host default XAMPP

// Membuat koneksi ke database
$connect = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Periksa koneksi
if(!$connect) {
    echo "Error: " . mysqli_connect_error();
    exit();
}

echo "Connection Success!<br><br>";

// Jika terdapat data dari sensor suhu chiller
if(isset($_GET["suhuchiller1"]) && isset($_GET["suhuchiller2"])) {
    $suhuchiller1 = $_GET["suhuchiller1"];
    $suhuchiller2 = $_GET["suhuchiller2"];

    // Query untuk memasukkan data suhu chiller ke dalam tabel node_chiller
    $query_chiller = "INSERT INTO node_chiller (suhuchiller1, suhuchiller2) VALUES ('$suhuchiller1', '$suhuchiller2')";

    // Melakukan query untuk suhu chiller
    $result_chiller = mysqli_query($connect, $query_chiller);

    if($result_chiller) {
        echo "Chiller Data Insertion Success!<br>";
    } else {
        echo "Chiller Data Insertion Failed: " . mysqli_error($connect) . "<br>";
    }
}

// Jika terdapat data dari keadaan node mesin
if(isset($_POST["keadaan"])) {
    $keadaan = $_POST["keadaan"];

    // Query untuk memasukkan keadaan node mesin ke dalam tabel node_mesin
    $query_mesin = "INSERT INTO node_mesin (keadaan) VALUES ('$keadaan')";

    // Melakukan query untuk keadaan node mesin
    $result_mesin = mysqli_query($connect, $query_mesin);

    if($result_mesin) {
        echo "Node Mesin Data Insertion Success!<br>";
    } else {
        echo "Node Mesin Data Insertion Failed: " . mysqli_error($connect) . "<br>";
    }
}

// Menutup koneksi
mysqli_close($connect);

?>
