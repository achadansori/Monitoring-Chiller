<?php

// Kredensial database
$dbname = 'example'; // Ganti dengan nama database Anda
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

// Mengambil data suhu dari query URL
$suhuchiller1 = $_GET["suhuchiller1"];
$suhuchiller2 = $_GET["suhuchiller2"];

// Memastikan bahwa kita menerima data suhu
if(isset($suhuchiller1) && isset($suhuchiller2)) {
    // Query untuk memasukkan data ke dalam tabel
    // Pastikan Anda sudah memiliki tabel `iot_project` dengan kolom `suhuchiller1` dan `suhuchiller2`
    $query = "INSERT INTO iot_project (suhuchiller1, suhuchiller2) VALUES ('$suhuchiller1', '$suhuchiller2')";
    
    // Melakukan query ke database
    $result = mysqli_query($connect, $query);

    if($result) {
        echo "Insertion Success!<br>";
    } else {
        echo "Insertion Failed: " . mysqli_error($connect) . "<br>";
    }
} else {
    echo "Data from sensors are missing";
}

// Menutup koneksi
mysqli_close($connect);

?>
