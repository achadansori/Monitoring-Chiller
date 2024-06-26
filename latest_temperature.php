<?php
// Koneksi ke database
$dbhost = 'localhost';
$dbname = 'monitoring_toshin';
$dbuser = 'root';
$dbpass = '';

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data suhu terbaru dari tabel node_chiller
$sql = "SELECT suhuchiller1, suhuchiller2 FROM node_chiller ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $data = array(
        'suhuchiller1' => $row['suhuchiller1'],
        'suhuchiller2' => $row['suhuchiller2']
    );
}

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
