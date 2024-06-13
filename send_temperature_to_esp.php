<?php
// Mendapatkan nilai suhu dari parameter yang diterima
$temperature = $_GET['temperature'];

// URL perangkat ESP yang akan menerima data
$esp_url = 'http://10.100.4.60/temperature_receiver';

// Data yang akan dikirim dalam format JSON
$data = array('temperature' => $temperature);

// Konversi data ke format JSON
$post_data = json_encode($data);

// Konfigurasi cURL untuk melakukan permintaan HTTP POST ke perangkat ESP
$ch = curl_init($esp_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($post_data))
);

// Eksekusi permintaan HTTP
$response = curl_exec($ch);

// Cek apakah permintaan berhasil
if ($response === FALSE) {
    die('Error: ' . curl_error($ch));
}

// Menampilkan respons dari perangkat ESP
echo $response;

// Menutup koneksi cURL
curl_close($ch);
?>
