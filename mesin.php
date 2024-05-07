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

// Menerima tanggal yang dipilih dari permintaan AJAX
// Menerima tanggal yang dipilih dari permintaan AJAX
$date = $_GET["date"];

// Validasi tanggal
if (!empty($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    // Modifikasi query SQL untuk memfilter data berdasarkan tanggal
    $sql = "SELECT id, keadaan, timestamp FROM node_mesin WHERE DATE(timestamp) = '$date'";
    $result = $conn->query($sql);

    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = array(
                'id' => $row['id'],
                'keadaan' => $row['keadaan'],
                'timestamp' => $row['timestamp']
            );
        }
    }

    // Mengembalikan data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // Mengembalikan respons error jika tanggal tidak valid
    echo json_encode(array('error' => 'Invalid date format'));
}

