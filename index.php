<!DOCTYPE html>
<html>
<head>
    <title>IoT Project Monitoring</title>
    <style>
        /* CSS untuk sidebar */
        .sidebar {
            height: calc(100% - 30px); /* Mengatur tinggi sidebar */
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 10px 16px;
            text-decoration: none;
            font-size: 20px;
            color: #818181;
            display: block;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        /* CSS untuk konten utama */
        .main {
            margin-left: 250px;
            padding: 20px;
        }

        /* CSS untuk tabel dan tombol */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* CSS untuk lampu */
        #lamp {
            width: 100px; /* Mengubah lebar lampu */
            height: 100px; /* Mengubah tinggi lampu */
            border-radius: 40px; /* Menyesuaikan border-radius agar tetap bulat */
            margin-top: 30px;
            margin-left: 20px;
            float: right;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <img src="toshin.png" alt="Logo" style="width: 50px;">
            <h3 style="color: white; margin-left: 10px;">Toshin Prima Fine Blanking</h3>
        </div>
        <a href="#" onclick="showDashboard()">Dashboard</a>
        <a href="#" onclick="showTableChiller()">Tabel Chiller</a>
        <a href="#" onclick="showTableMesin()">Tabel Mesin</a>
        <a style="color: white; margin-bottom: 10px; margin-left: 0px;">by Elka x Meka PENS 21</a>
    </div>

    <!-- Main content -->
    <div class="main">
        <div class="dashboard-container" style="padding: 20px; margin-bottom: 20px;">
            <!-- Print suhu (Dashboard) -->
            <div id="dashboard" style="display:none;">
                <h1>Dashboard Monitoring </h1>
                <br>
                <h2>Monitoring Chiller</h2>
                <div style="display: flex; align-items: center;">
                    <div style="padding: 10px; border: 4px solid #ccc; display: inline-block; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);">
                        <p id="Suhuchiller1" style="margin: 0; font-size: 24px;">Suhu Chiller 1 : <span id="suhuchiller1Value"></span> C</p>
                    </div>
                    <div style="margin-left: 20px; padding: 10px; border: 4px solid #ccc; display: inline-block; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);">
                        <p id="Suhuchiller2" style="margin: 0; font-size: 24px;">Suhu Chiller 2 : <span id="suhuchiller2Value"></span> C</p>
                    </div>
                    <!-- Lampu -->
                    <div id="lamp" style="width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                    <!-- Text will appear here -->
                    </div>
                </div>
                <!-- Input suhu peringatan -->
                <div>
                    <label for="warningTemperature">Suhu Peringatan (°C):</label>
                    <input type="number" id="warningTemperature" value="30" min="0" step="1">
                    <button onclick="setWarningTemperature()">Set</button>
                </div>
                <br>
                <h2>Monitoring Mesin</h2>
            </div>
        </div>

        <!-- Tabel (Tabel Chiller) -->
        <div id="tablechiller" style="display:none;">
            <h2>Tabel</h2>
            <!-- Tambahkan elemen input untuk memilih tanggal -->
            <label for="datepicker">Pilih Tanggal:</label>
            <input type="date" id="datepicker">
            <button onclick="deleteSelected()" class="delete-btn">Delete Selected</button>
            <button onclick="deleteAll()" class="delete-btn">Delete All</button>
            <input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> <label for="selectAll">Select All</label>
            <br><br>
            <table id="dataTableChiller">
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Suhu Chiller 1 (C)</th>
                    <th>Suhu Chiller 2 (C)</th>
                    <th>Timestamp</th>
                </tr>
            </table>
        </div>

        <!-- Tabel (Tabel Chiller) -->
        <div id="tablemesin" style="display:none;">
            <h2>Tabel</h2>
            <!-- Tambahkan elemen input untuk memilih tanggal -->
            <label for="datepicker">Pilih Tanggal:</label>
            <input type="date" id="datepicker">
            <button onclick="deleteSelected()" class="delete-btn">Delete Selected</button>
            <button onclick="deleteAll()" class="delete-btn">Delete All</button>
            <input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> <label for="selectAll">Select All</label>
            <br><br>
            <table id="dataTableMesin">
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Timestamp</th>
                </tr>
            </table>
        </div>
    </div>

    <script>
        // Fungsi untuk memperbarui data tabel dengan data terbaru dari server
        function updateTable(data) {
            $("#dataTableChiller").empty(); // Mengosongkan tabel sebelum menambahkan data baru
            $("#dataTableChiller").append("<tr><th>Select</th><th>ID</th><th>Suhu Chiller 1 (C)</th><th>Suhu Chiller 2 (C)</th><th>Timestamp</th></tr>");
            for (var i = 0; i < data.length; i++) {
                var row = "<tr><td><input type='checkbox' name='selected[]' value='" + data[i].id + "'></td><td>" + data[i].id  + "</td><td>" + data[i].suhuchiller1 + "</td><td>" + data[i].suhuchiller2 + "</td><td>" + data[i].timestamp + "</td></tr>";
                $("#dataTableChiller").append(row);
            }
        }

        // Fungsi untuk memuat data suhu dari server secara berkala
        function loadsuhuchillerData() {
            var selectedDate = $("#datepicker").val(); // Ambil tanggal yang dipilih
            $.ajax({
                url: "temperature.php", // URL script PHP yang akan mengembalikan data suhu dari server
                dataType: "json",
                data: { date: selectedDate }, // Kirim tanggal yang dipilih sebagai parameter
                success: function(data) {
                    // Memperbarui tabel (hanya jika di halaman Tabel)
                    if ($("#tablechiller").is(":visible")) {
                        updateTable(data); // Memperbarui tabel dengan data suhu terbaru
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error loading temperature data: " + error); // Menampilkan pesan kesalahan jika terjadi
                }
            });

            // Memuat data terbaru untuk dashboard
            $.ajax({
                url: "latest_temperature.php",
                dataType: "json",
                success: function(data) {
                    // Memperbarui nilai suhu untuk chiller 1
                    $("#suhuchiller1Value").text(data.suhuchiller1);

                    // Memperbarui nilai suhu untuk chiller 2
                    $("#suhuchiller2Value").text(data.suhuchiller2);

                    // Memperbarui warna lampu berdasarkan suhu
                    var warningTemperature = parseInt($("#warningTemperature").val()); // Ambil nilai suhu peringatan dari input
                    if (parseFloat(data.suhuchiller1) > warningTemperature || parseFloat(data.suhuchiller2) > warningTemperature) {
                        $("#lamp").css("background-color", "red"); // Jika suhu di atas suhu peringatan, warna lampu menjadi merah
                        $("#lamp").text("Bahaya"); // Tampilkan "Bahaya" di tengah lampu
                    } else {
                        $("#lamp").css("background-color", "green"); // Jika suhu di bawah suhu peringatan, warna lampu menjadi hijau
                        $("#lamp").text("Aman"); // Tampilkan "Aman" di tengah lampu
                    }

                },
                error: function(xhr, status, error) {
                    console.error("Error loading latest temperature data: " + error); // Menampilkan pesan kesalahan jika terjadi
                }
            });
        }

        // Fungsi untuk mengatur suhu peringatan
        function setWarningTemperature() {
            loadsuhuchillerData(); // Memuat ulang data untuk menyesuaikan warna lampu
        }

        function sendWarningTemperature() {
            var warningTemperature = $("#warningTemperature").val(); // Ambil suhu peringatan dari input
            $.ajax({
                url: "send_temperature_to_esp.php", // URL script PHP di ESP32
                method: "POST",
                data: { warningTemperature: warningTemperature }, // Kirim suhu peringatan ke ESP32
                success: function(response) {
                    console.log("Data successfully sent to ESP32");
                },
                error: function(xhr, status, error) {
                    console.error("Error sending data to ESP32: " + error); // Menampilkan pesan kesalahan jika terjadi
                }
            });
        }

        // Fungsi untuk menghapus data terpilih
        function deleteSelected() {
            var selected = $("input[name='selected[]']:checked").map(function(){
                return $(this).val();
            }).get();
            
            $.ajax({
                url: "delete.php",
                method: "POST",
                data: { selected: selected },
                success: function() {
                    loadsuhuchillerData();// Memuat ulang data setelah penghapusan berhasil
                },
                error: function(xhr, status, error) {
                    console.error("Error deleting data: " + error); // Menampilkan pesan kesalahan jika terjadi
                }
            });
        }

        function deleteAll() {
            if (confirm("Apakah Anda yakin ingin menghapus semua Data? Tindakan ini tidak bisa dibatalkan.")) {
                $.ajax({
                    url: "delete.php",
                    method: "POST",
                    data: { delete_all: 'true' },
                    success: function() {
                        alert("Semua data berhasil dihapus.");
                        loadsuhuchillerData(); // Refresh data setelah penghapusan
                    },
                    error: function(xhr, status, error) {
                        alert("Penghapusan data error: " + error);
                    }
                });
            }
        }

        // Fungsi untuk membalikkan status checkbox Select All
        function toggleSelectAll() {
            var checkboxes = document.getElementsByName('selected[]');
            var selectAllCheckbox = document.getElementById('selectAll');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = selectAllCheckbox.checked;
            }
        }

        // Fungsi untuk menampilkan menu Dashboard dan menyembunyikan menu Tabel
        function showDashboard() {
            $("#dashboard").show();
            $("#tablechiller").hide();
            $("#tablemesin").hide();
            loadsuhuchillerData(); // Memuat data suhu saat menu Dashboard ditampilkan
        }

        // Fungsi untuk menampilkan menu Tabel dan menyembunyikan menu Dashboard
        function showTableChiller() {
            $("#dashboard").hide();
            $("#tablechiller").show();
            $("#tablemesin").hide();
            loadsuhuchillerData(); // Memuat data suhu saat menu Tabel ditampilkan
        }
        function showTableMesin() {
            $("#dashboard").hide();
            $("#tablechiller").hide();
            $("#tablemesin").show();
            loadsuhuchillerData(); // Memuat data suhu saat menu Tabel ditampilkan
        }

        // Memanggil fungsi untuk memuat data suhu saat halaman dimuat
        $(document).ready(function() {
            showDashboard(); // Secara default, tampilkan menu Dashboard saat halaman dimuat
            // Mengatur interval untuk memperbarui data suhu setiap 5 detik (5000 milidetik)
            setInterval(loadsuhuchillerData, 5000);
        });
    </script>
</body>
</html>
