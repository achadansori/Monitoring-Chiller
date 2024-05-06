<?php
// Koneksi ke database
$dbhost = 'localhost';
$dbname = 'example';
$dbuser = 'root';
$dbpass = '';

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Menerima tanggal yang dipilih dari permintaan AJAX
$date = $_GET["date"];

// Modifikasi query SQL untuk memfilter data berdasarkan tanggal
$sql = "SELECT id, suhuchiller1, suhuchiller2, timestamp FROM iot_project WHERE DATE(timestamp) = '$date'";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = array(
            'id' => $row['id'],
            'suhuchiller1' => $row['suhuchiller1'],
            'suhuchiller2' => $row['suhuchiller2'],
            'timestamp' => $row['timestamp']
        );
    }
}

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
