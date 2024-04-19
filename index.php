<!DOCTYPE html>
<html>
<head>
    <title>IoT Project Monitoring</title>
    <style>
        /* CSS untuk sidebar */
        .sidebar {
            height: 100%;
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
        <a href="#" onclick="showTable()">Tabel</a>
        <a href="#" onclick="showTable()">Grafik</a>
    </div>

    <!-- Main content -->
    <div class="main">
        <div class="dashboard-container" style="padding: 20px; margin-bottom: 20px;">
            <!-- Print suhu (Dashboard) -->
            <div id="dashboard" style="display:none;">
                <h1>Dasboard Monitoring Chiller</h1>
                <div style="display: flex; align-items: center;">
                    <div style="padding: 10px; border: 4px solid #ccc; display: inline-block; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);">
                        <p id="Suhuchiller1" style="margin: 0; font-size: 24px;">Suhu Chiller 1 : <span id="suhuchiller1Value"></span> C</p>
                    </div>
                    <div style="margin-left: 20px; padding: 10px; border: 4px solid #ccc; display: inline-block; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);">
                        <p id="Suhuchiller2" style="margin: 0; font-size: 24px;">Suhu Chiller 2 : <span id="suhuchiller2Value"></span> C</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel (Tabel) -->
        <div id="table" style="display:none;">
            <h2>Tabel</h2>
            <button onclick="deleteSelected()" class="delete-btn">Delete Selected</button>
            <input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> <label for="selectAll">Select All</label>
            <br><br>
            <table id="dataTable">
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Suhu Chiller 1 (C)</th>
                    <th>Suhu Chiller 2 (C)</th>
                    <th>Timestamp</th>
                </tr>
            </table>
        </div>
    </div>








    <script>
        // Fungsi untuk memperbarui data tabel dengan data terbaru dari server
        function updateTable(data) {
            $("#dataTable").empty(); // Mengosongkan tabel sebelum menambahkan data baru
            $("#dataTable").append("<tr><th>Select</th><th>ID</th><th>Suhu Chiller 1 (C)</th><th>Suhu Chiller 1 (C)</th><th>Timestamp</th></tr>");
            for (var i = 0; i < data.length; i++) {
                var row = "<tr><td><input type='checkbox' name='selected[]' value='" + data[i].id + "'></td><td>" + data[i].id  + "</td><td>" + data[i].suhuchiller1 + "</td><td>" + data[i].suhuchiller2 + "</td><td>" + data[i].timestamp + "</td></tr>";
                $("#dataTable").append(row);
            }
        }

        // Fungsi untuk memuat data suhu dari server secara berkala
    // Fungsi untuk memuat data suhu dari server secara berkala
    function loadsuhuchillerData() {
        $.ajax({
            url: "temperature.php", // URL script PHP yang akan mengembalikan data suhu dari server
            dataType: "json",
            success: function(data) {
                // Memperbarui tabel (hanya jika di halaman Tabel)
                if ($("#table").is(":visible")) {
                    updateTable(data); // Memperbarui tabel dengan data suhu terbaru
                }
                // Memperbarui print suhu (hanya jika di halaman Dashboard)
                if ($("#dashboard").is(":visible")) {
                    // Memperbarui nilai suhu untuk chiller 1
                    var latestsuhuchiller1 = data.length > 0 ? data[data.length - 1].suhuchiller1 : "N/A";
                    $("#suhuchiller1Value").text(latestsuhuchiller1);

                    // Memperbarui nilai suhu untuk chiller 2
                    var latestsuhuchiller2 = data.length > 0 ? data[data.length - 1].suhuchiller2 : "N/A";
                    $("#suhuchiller2Value").text(latestsuhuchiller2);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading temperature data: " + error); // Menampilkan pesan kesalahan jika terjadi
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
            $("#table").hide();
            loadsuhuchillerData(); // Memuat data suhu saat menu Dashboard ditampilkan
        }

        // Fungsi untuk menampilkan menu Tabel dan menyembunyikan menu Dashboard
        function showTable() {
            $("#dashboard").hide();
            $("#table").show();
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
