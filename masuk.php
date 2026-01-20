<?php
require 'function.php';
require 'cek.php';

$user_email = $_SESSION['email'];
$get_user = mysqli_query($conn, "SELECT * FROM login WHERE email='$user_email'");
$user_data = mysqli_fetch_array($get_user);
$foto_profil = $user_data['image'] ?? 'user.png';
$tampil_foto = ($foto_profil == '' || !file_exists("assets/img/".$foto_profil)) ? 'user.png' : $foto_profil;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>Barang Masuk - Inventaris Barang</title>

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<link href="css/styles.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        .sb-sidenav-dark { background-color: #000 !important; }
        .dt-buttons { margin-bottom: 20px; }
        
        .sb-topnav { height: 70px !important; }
        .sb-nav-fixed #layoutSidenav_content { padding-top: 70px; }
        
        .navbar-brand i { transition: all 0.3s; }
        .navbar-brand:hover i { transform: translateY(-3px); color: #ffffff !important; }

        :root {
            --bg-main: #e3f2fd; 
            --card-bg: #ffffff;
            --text-color: #333333;
        }

        [data-theme="dark"] {
            --bg-main: #121212;
            --card-bg: #1e1e1e;
            --text-color: #f1f1f1;
        }

        body {
            background-color: var(--bg-main) !important;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .card {
            background-color: var(--card-bg) !important;
            color: var(--text-color);
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card-stat { border: none; border-radius: 12px; transition: transform 0.3s; color: white !important; }
        .card-stat:hover { transform: translateY(-5px); }

        [data-theme="dark"] table.dataTable, 
        [data-theme="dark"] .dataTables_wrapper { color: #fff !important; }
        
        .profile-name { font-weight: bold; }
        [data-theme="dark"] .profile-name { color: #ffffff !important; }

        .bg-custom-total { 
            background: linear-gradient(45deg, #4e73df 0%, #224abe 100%) !important; 
        }
        .bg-custom-masuk { 
            background: linear-gradient(45deg, #1cc88a 0%, #13855c 100%) !important; 
        }
        .bg-custom-keluar { 
            background: linear-gradient(45deg, #e74a3b 0%, #be2617 100%) !important; 
        }
        .bg-custom-kategori { 
            background: linear-gradient(45deg, #36b9cc 0%, #258391 100%) !important; 
        }

        .card-stat { 
            border: none; 
            border-radius: 12px; 
            transition: transform 0.3s; 
            color: white !important; 
        }
        .card-stat:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .card-stat h6 { 
            font-size: 0.85rem; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            font-weight: bold; 
            opacity: 0.8; 
        }
        .card-stat h2 { 
            font-weight: 700; 
            margin: 0; 
        }

        [data-theme="dark"] .card-header {
            background-color: #1e1e1e !important;
            border-bottom: 1px solid #333;
            color: #fff !important;
        }

        [data-theme="dark"] .card {
            background-color: #1e1e1e !important;
        }


        [data-theme="dark"] .dataTables_wrapper .dataTables_filter input,
        [data-theme="dark"] .dataTables_wrapper .dataTables_length select {
            background-color: #2b2b2b !important;
            color: #fff !important;
            border: 1px solid #444;
        }

        #maulanaTable {
            border-collapse: collapse !important;
            width: 100% !important;
            border: 1px solid #dee2e6;
        }

        #maulanaTable thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6 !important;
            border-right: 1px solid #dee2e6;
            padding: 12px;
        }

        #maulanaTable tbody td {
            border: 1px solid #dee2e6 !important;
            padding: 10px;
            vertical-align: middle;
        }

        [data-theme="dark"] #maulanaTable {
            border-color: #333 !important;
        }

        [data-theme="dark"] #maulanaTable thead th {
            background-color: #2b2b2b !important;
            border-color: #444 !important;
            color: #fff;
        }

        [data-theme="dark"] #maulanaTable tbody td {
            border-color: #333 !important;
            color: #ccc;
        }

        .dataTables_wrapper .dataTables_filter {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em !important;
            display: inline-block;
            width: auto;
            height: 31px; 
            vertical-align: middle;
        }

        .dt-buttons {
            margin-bottom: 0 !important;
        }

        .table-controls-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px; 
        }
         .filter-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .btn-export-excel { background-color: #28a745 !important; color: white !important; border: none !important; font-weight: 400 !important; }
        .btn-export-pdf { background-color: #dc3545 !important; color: white !important; border: none !important; font-weight: 400 !important; }
        .btn-export-print { background-color: #343a40 !important; color: white !important; border: none !important; font-weight: 400 !important; }
        .dt-button:hover {
            opacity: 0.8;
            background-image: none !important; 
        }
        .sb-sidenav-light .sb-sidenav-menu .nav-link:hover {
            background-color: #ffffffff !important; 
            color: #007bff !important;           
            transition: all 0.3s ease;            
            padding-left: 20px;                  
        }
        .sb-sidenav-light .sb-sidenav-menu .nav-link:hover .sb-nav-link-icon {
            color: #007bff !important;
            transform: scale(1.1);               
        }
        #maulanaTable thead th {
            background-color: #343a40 !important;
            color: white !important;
        }
        [data-theme="dark"] #maulanaTable {
            border-color: #444 !important;
            color: #ffffff !important; 
        }
        [data-theme="dark"] #maulanaTable thead th {
            background-color: #2b2b2b !important; 
            color: #ffffff !important;           
            border-color: #444 !important;
        }
        [data-theme="dark"] #maulanaTable tbody td {
            border-color: #333 !important;
            color: #ffffff !important; 
        }
        [data-theme="dark"] #maulanaTable tbody td b, 
        [data-theme="dark"] #maulanaTable tbody td strong {
            color: #ffffff !important;
        }
        #mainNavbar {
            background-color: #00a8b5 !important; 
            transition: all 0.3s ease;
        }

        #mainNavbar .navbar-brand, 
        #mainNavbar .nav-link, 
        #mainNavbar #sidebarToggle {
            color: white !important; 
        }


        [data-theme="dark"] #mainNavbar {
            background-color: #343a40 !important; 
        }
        [data-theme="dark"] #mainNavbar .navbar-brand, 
        [data-theme="dark"] #mainNavbar .nav-link, 
        [data-theme="dark"] #mainNavbar #sidebarToggle {
            color: rgba(255, 255, 255, 0.8) !important;
        }


        .table-controls-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .dataTables_filter input {
            margin-bottom: 0 !important;
            height: 31px !important;
        }

        [data-theme="dark"] .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #b8b8b8 !important; 
            background-color: #2b2b2b; 
            color: #ffffff !important; 
            outline: none;
        }


        [data-theme="dark"] .dataTables_wrapper .dataTables_filter input:focus {
            border: 1px solid #00a8b5 !important;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }

        [data-theme="dark"] .modal-content {
            background-color: #1e1e1e !important; 
            color: #ffffff !important;
            border: 1px solid #444;
        }

        [data-theme="dark"] .modal-header {
            border-bottom: 1px solid #333;
        }

        [data-theme="dark"] .modal-header .close {
            color: #ffffff;
            text-shadow: none;
            opacity: 0.8;
        }

        [data-theme="dark"] .modal-body label {
            color: #ffffff;
        }


        [data-theme="dark"] .modal-body .form-control {
            background-color: #2b2b2b !important;
            border: 1px solid #555 !important;
            color: #ffffff !important;
        }


        [data-theme="dark"] .modal-body input[readonly] {
            background-color: #121212 !important; 
            border: 1px solid #444 !important;
            color: #00a8b5 !important; 
        }

        [data-theme="dark"] ::placeholder {
            color: #888 !important;
            opacity: 1;
        }
    </style>

</head>

        <body class="sb-nav-fixed">
            <nav class="sb-topnav navbar navbar-expand" id="mainNavbar">
                <a class="navbar-brand d-flex flex-column align-items-center justify-content-center" href="index.php" style="width: 200px; margin-left: -10px; height: auto;">
                    <i class="fas fa-boxes-stacked mb-1" style="font-size: 1.4rem; color: #0717ffff;"></i> 
                    <span style="font-size: 0.85rem; letter-spacing: 1px; font-weight: bold; line-height: 1;">INVENTARIS BARANG</span>
                </a>
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" style="color: rgba(255,255,255,0.8); margin-left: 20px;">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ms-auto me-3 pr-3" style="margin-left: auto;">
                    <button class="btn btn-outline-light btn-sm" id="themeToggle"><i class="fas fa-moon"></i></button>
                </div>
            </nav>
    
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="px-3 py-4 d-flex align-items-center">
                                <div class="position-relative">
                                    <img src="assets/img/<?= $tampil_foto; ?>" class="rounded-circle border border-2 border-dark" style="width: 50px; height: 50px; object-fit: cover;">
                                        <span class="position-absolute bg-success border border-1 border-dark rounded-circle" style="width: 12px; height: 12px; bottom: 3px; right: 3px;"></span>
                                        </div>
                                            <div class="ml-3">
                                        <div class="profile-name"><?= ucfirst(explode('@', $user_email)[0]); ?></div>
                                    <div class="small text-secondary"><i class="fas fa-circle text-success" style="font-size: 8px;"></i> Online</div>
                                </div>
                            </div>
                        <div class="px-3 py-2 text-uppercase" style="color: #4b646f; font-size: 0.75rem; font-weight: 600;">Main Menu</div>
                        <a class="nav-link" href="index"><div class="sb-nav-link-icon"><i class="fas fa-box"></i></div> Stock Barang</a>
                        <a class="nav-link" href="kategori"><div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div> Kategori</a>
                        <a class="nav-link active" href="masuk"><div class="sb-nav-link-icon"><i class="fas fa-cart-plus"></i></div> Barang Masuk</a>
                        <a class="nav-link" href="keluar"><div class="sb-nav-link-icon"><i class="fas fa-cart-arrow-down"></i></div> Barang Keluar</a>
                        <a class="nav-link" href="admin"><div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div> Kelola Admin</a>
                        <a class="nav-link" href="logout"><div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div> Logout</a>
                    </div>
                </div>
            </nav>
        </div>
        
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Barang Masuk</h1>
                    <div class="mb-3"><i class="fas fa-calendar-alt"></i> <span id="current-date" class="text-secondary"></span></div>

                    <div class="card mb-4">
                        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahMasuk">
                                <i class="fas fa-plus"></i> Tambah Barang Masuk
                            </button>

                            <div class="filter-row m-0 p-0" style="background: none; display: flex; align-items: center; gap: 8px;">
                                <input type="date" id="minDate" class="form-control form-control-sm" style="width: 130px;">
                                <span class="small">s/d</span>
                                <input type="date" id="maxDate" class="form-control form-control-sm" style="width: 130px;">
                                <button id="btnFilter" class="btn btn-info btn-sm"><i class="fas fa-filter"></i></button>
                                <button id="btnReset" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="maulanaTableMasuk" class="table table-bordered table-hover w-100">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                        <th>Penerima</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
            <div class="modal fade" id="modalTambahMasuk">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Barang Masuk</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambahMasuk">
                            <div class="modal-body">
                                <label>Nama Barang</label>
                                <select name="barangnya" id="selectBarang" class="form-control mb-3" required>
                                    <option value="">Pilih Barang</option>
                                </select> 
                                <label>Kategori</label>
                                <select id="filterKategoriForm" name="idkategori" class="form-control mb-3" style="background-color: #e9ecef; pointer-events: none;">
                                    <option value="">Terisi Otomatis...</option>
                                </select>     
                                <label>Jumlah</label>
                                <input type="number" name="qty" class="form-control mb-3" required min="1" placeholder="0">
                                <label>Penerima / Keterangan</label>
                                <input type="text" name="keterangan" class="form-control mb-3" required placeholder="">
                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalEditMasuk">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Barang Masuk</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="formEditMasuk">
                        <div class="modal-body">
                            <input type="hidden" name="idmasuk" id="edit_idmasuk">
                            <input type="hidden" name="qty_lama" id="edit_qty_lama">

                            <label>Nama Barang</label>
                            <select name="idbarang" id="edit_selectBarang" class="form-control mb-3" required>
                                <option value="">Pilih Barang</option>
                            </select> 

                            <label>Kategori</label>
                            <select id="edit_filterKategoriForm" class="form-control mb-3" style="background-color: #e9ecef; pointer-events: none;" readonly>
                                <option value="">Terisi Otomatis...</option>
                            </select>


                            <label>Jumlah Masuk</label>
                            <input type="number" name="qty" id="edit_qty" class="form-control mb-3" required min="1">
                            
                            <label>Penerima / Keterangan</label>
                            <input type="text" name="keterangan" id="edit_keterangan" class="form-control mb-3" required>
                            
                            <button type="submit" class="btn btn-primary btn-block">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

     <script>
    $(document).ready(function() {
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#minDate').val();
            var max = $('#maxDate').val();
            var date = data[0].split(' ')[0]; 
            if (
                (min === "" && max === "") ||
                (min === "" && date <= max) ||
                (min <= date && max === "") ||
                (min <= date && date <= max)
            ) {
                return true;
            }
            return false;
        }
    );

    $('#btnFilter').click(function() {
        table.draw();
    });

    $('#btnReset').click(function() {
        $('#minDate').val('');
        $('#maxDate').val('');
        table.draw();
    });

    function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        $('#current-date').text(now.toLocaleDateString('id-ID', options));
    }
    setInterval(updateClock, 1000); updateClock();
    let table = $('#maulanaTableMasuk').DataTable({
        dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-export-excel btn-sm'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-export-pdf btn-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-export-print btn-sm'
            }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari barang masuk..."
        }
    });
        window.dataBarangGlobal = []; 
        window.loadData = function() {
            fetch('api/kategori.php')
            .then(res => res.json())
            .then(res => {
                let opt = '<option value="">Terisi Otomatis...</option>';
                if(res.data) {
                    res.data.forEach(k => { 
                        opt += `<option value="${k.idkategori}">${k.namakategori}</option>`; 
                    });
                }
                $('#filterKategoriForm').html(opt);
                return fetch('api/dashboard.php');
            })
            .then(res => res.json())
            .then(res => {
                window.dataBarangGlobal = res.data.barang; 
                
                let options = '<option value="">Pilih Barang</option>';
                window.dataBarangGlobal.forEach(b => {
                    let idKategoriAsli = b.idkategori || b.id_kategori || b.idk || b.kategori;
                    let idBarangAsli = b.idb || b.idbarang;
                    let namaBarangAsli = b.nama || b.namabarang;
                    let stokAsli = b.stok || b.stock || 0;

                    options += `<option value="${idBarangAsli}" data-idk="${idKategoriAsli}">${namaBarangAsli} (Stok: ${stokAsli})</option>`;
                });
                $('#selectBarang').html(options);
            });

        fetch('api/barang_masuk.php').then(res => res.json()).then(res => {
            table.clear();
            res.data.forEach(item => {
                table.row.add([
                    item.tanggal,
                    `<strong>${item.namabarang}</strong>`,
                    `<span class="badge badge-info">${item.namakategori || '-'}</span>`,
                    `<b class="text-success">+${item.qty}</b>`,
                    item.keterangan,
                    `<button class="btn btn-warning btn-sm" onclick="editData('${item.idmasuk}', '${item.idbarang}', '${item.qty}', '${item.keterangan}')"><i class="fas fa-edit"></i></button>
                     <button class="btn btn-danger btn-sm" onclick="deleteData('${item.idmasuk}', '${item.idbarang}', '${item.qty}', '${item.namabarang}')"><i class="fas fa-trash"></i></button>`
                ]);
            });
            table.draw();
        });
    }

        $(document).on('change', '#selectBarang', function() {
            const selectedOption = $(this).find(':selected');
            const idk = selectedOption.data('idk'); 
            
            console.log("ID Kategori Terpilih:", idk);

            if (idk && idk !== "undefined") {
                $('#filterKategoriForm').val(idk);
                $('#filterKategoriForm').css({
                    'background-color': '#d4edda',
                    'border': '2px solid #a3aca5ff'
                });
            } else {
                $('#filterKategoriForm').val("");
                $('#filterKategoriForm').css('background-color', '#e9ecef');
            }
        });

        $('#formTambahMasuk').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'api/barang_masuk.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if(res.status === 'success') {
                        $('#modalTambahMasuk').modal('hide');
                        $('#formTambahMasuk')[0].reset();
                        $('#filterKategoriForm').css('background-color', '#e9ecef');
                        Swal.fire('Berhasil!', 'Data barang masuk telah disimpan', 'success');
                        loadData();
                    } else {
                        Swal.fire('Gagal!', res.message || 'Terjadi kesalahan', 'error');
                    }
                }
            });
        });

        window.editData = function(idmasuk, idbarang, qty, keterangan) {
            const barang = window.dataBarangGlobal.find(b => (b.idb || b.idbarang) == idbarang);
            const namaBarang = barang ? (barang.nama || barang.namabarang) : 'Barang tidak ditemukan';
            $('#edit_idmasuk').val(idmasuk);
            $('#edit_idbarang').val(idbarang);
            $('#edit_qty_lama').val(qty);
            $('#edit_qty').val(qty);
            $('#edit_keterangan').val(keterangan);
            $('#edit_namabarang').val(namaBarang);
            $('#modalEditMasuk').modal('show');
        }

        window.editData = function(idmasuk, idbarang, qty, keterangan) {
            $('#edit_idmasuk').val(idmasuk);
            $('#edit_idbarang').val(idbarang);
            $('#edit_qty').val(qty);
            $('#edit_keterangan').val(keterangan);
            $('#modalEditMasuk').modal('show');
        }

        $('#formEditMasuk').on('submit', function(e) {
            e.preventDefault();
            console.log($(this).serialize()); 
            $.ajax({
                url: 'api/barang_masuk.php',
                type: 'PUT',
                data: $(this).serialize(), 
                success: function(res) {
                    if(res.status === 'success') {
                        $('#modalEditMasuk').modal('hide');
                        Swal.fire('Berhasil!', res.message, 'success');
                        loadData();
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                    }
                }
            });
        });

        $('#modalEditMasuk').on('hidden.bs.modal', function () {
            $('#edit_filterKategoriForm').css({
                'background-color': '#e9ecef',
                'border': '1px solid #ced4da'
            });
        });

        $('#modalTambahMasuk').on('hidden.bs.modal', function () {
            $('#formTambahMasuk')[0].reset();
            $('#filterKategoriForm').css({
                'background-color': '#e9ecef',
                'border': '1px solid #ced4da'
            });
        });

        window.editData = function(idmasuk, idbarang, qty, keterangan) {
            const options = $('#selectBarang').html();
            $('#edit_selectBarang').html(options);
            const kategoriOptions = $('#filterKategoriForm').html();
            $('#edit_filterKategoriForm').html(kategoriOptions);
            $('#edit_idmasuk').val(idmasuk);
            $('#edit_idbarang').val(idbarang).trigger('change'); 
            $('#edit_qty_lama').val(qty);
            $('#edit_qty').val(qty);
            $('#edit_keterangan').val(keterangan);
            $('#modalEditMasuk').modal('show');
        }

        $(document).on('change', '#edit_selectBarang', function() {
            const selectedOption = $(this).find(':selected');
            const idk = selectedOption.data('idk'); 
            if (idk && idk !== "undefined") {
                $('#edit_filterKategoriForm').val(idk);
                $('#edit_filterKategoriForm').css({'background-color': '#d4edda', 'border': '2px solid #28a745'});
            } else {
                $('#edit_filterKategoriForm').val("");
                $('#edit_filterKategoriForm').css('background-color', '#e9ecef');
            }
        });

        $('#formEditMasuk').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'api/barang_masuk.php',
                type: 'PUT',
                data: $(this).serialize(),
                success: function(res) {
                    if(res.status === 'success') {
                        $('#modalEditMasuk').modal('hide');
                        Swal.fire('Berhasil!', res.message, 'success');
                        loadData(); 
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                    }
                }
            });
        });

            loadData();

        function applyTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                $('#sidenavAccordion').removeClass('sb-sidenav-light').addClass('sb-sidenav-dark');
                $('#themeToggle').html('<i class="fas fa-sun"></i>');
            } else {
                document.documentElement.removeAttribute('data-theme');
                $('#sidenavAccordion').removeClass('sb-sidenav-dark').addClass('sb-sidenav-light');
                $('#themeToggle').html('<i class="fas fa-moon"></i>');
            }
        }
            $('#themeToggle').on('click', function(e) {
            e.preventDefault();
            const currentTheme = document.documentElement.getAttribute('data-theme');
            if (currentTheme === 'dark') {
                applyTheme('light');
            } else {
                applyTheme('dark');
            }
        });

        const savedTheme = localStorage.getItem('theme') || 'light';
        applyTheme(savedTheme);
        });
        $("#sidebarToggle").on("click", function(e) {
            e.preventDefault();
            $("body").toggleClass("sb-sidenav-toggled");
            

            localStorage.setItem('sb|sidebar-toggle', $("body").hasClass('sb-sidenav-toggled'));
        });

        window.deleteData = function(idm, idb, qty, nama) {
            Swal.fire({
                title: 'Hapus Data?',
                text: "Stok barang " + nama + " akan dikurangi kembali.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({ 
                        url: 'api/barang_masuk.php', 
                        type: 'DELETE', 
                        data: { idm: idm, idb: idb, qty: qty }, 
                        success: function() { 
                            Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                            loadData(); 
                        } 
                    });
                }
            });
        }

        let table; 
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#maulanaTableMasuk')) {
                table = $('#maulanaTableMasuk').DataTable({
                    dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rtip',
                    buttons: [
                        { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-export-excel btn-sm' },
                        { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn btn-export-pdf btn-sm' },
                        { extend: 'print', text: '<i class="fas fa-print"></i> Print', className: 'btn btn-export-print btn-sm' }
                    ],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Cari barang masuk..."
                    },
                    retrieve: true 
                });
            }
            loadData();
        });

     </script>
    </body>
</html>