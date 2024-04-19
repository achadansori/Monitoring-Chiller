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

// Jika checkbox dipilih
if(isset($_POST['selected'])) {
    $selected = $_POST['selected'];
    foreach($selected as $id) {
        // Query untuk menghapus data dari tabel iot_project
        $sql = "DELETE FROM iot_project WHERE id=$id";
        if ($conn->query($sql) !== TRUE) {
            echo "Error deleting record with ID $id: " . $conn->error;
        }
    }
}

// Set auto_increment kembali ke 1
$sql_reset_auto_increment = "ALTER TABLE iot_project AUTO_INCREMENT = 1";
if ($conn->query($sql_reset_auto_increment) !== TRUE) {
    echo "Error resetting auto_increment: " . $conn->error;
}

$conn->close();
header("Location: index.php"); // Kembali ke halaman index setelah selesai menghapus
exit();
?>
