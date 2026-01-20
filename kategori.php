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
<title>Kategori - Inventaris Barang</title>

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<link href="css/styles.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        .sb-sidenav-dark { background-color: #000 !important; }
        .dt-buttons {
            margin-bottom: 20px;
        }
        button.dt-button {
            border-radius: 5px !important;
            border: none !important;
            background-color: #343a40 !important; 
            color: white !important;
        }
        button.buttons-excel {
            background-color: #28a745 !important;
        }
        button.buttons-pdf {
            background-color: #dc3545 !important;
        }
        .sb-topnav {
            height: 70px !important; 
        }

        .sb-nav-fixed #layoutSidenav_content {
            padding-top: 70px;
        }

        .navbar-brand i {
            transition: all 0.3s;
        }
        .navbar-brand:hover i {
            transform: translateY(-3px);
            color: #ffffff !important;
        }

        :root {
            --bg-main: #e3f2fd; 
            --card-bg: #ffffff;
            --text-color: #333333;
            --table-bg: #ffffff;
        }

        [data-theme="dark"] {
            --bg-main: #121212;
            --card-bg: #1e1e1e;
            --text-color: #f1f1f1;
            --table-bg: #1e1e1e;
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

        [data-theme="dark"] table.dataTable, 
        [data-theme="dark"] .dataTables_wrapper .dataTables_length, 
        [data-theme="dark"] .dataTables_wrapper .dataTables_filter, 
        [data-theme="dark"] .dataTables_wrapper .dataTables_info, 
        [data-theme="dark"] .dataTables_wrapper .dataTables_paginate {
            color: #fff !important;
        }

        [data-theme="dark"] .form-control {
            background-color: #2b2b2b;
            border-color: #444;
            color: #fff;
        }
        .profile-name {
            color: inherit; 
        }

        .sb-sidenav-light .fw-bold.text-white {
            color: #0d47a1 !important; 
        }

        .sb-sidenav-light .small.text-secondary {
            color: #373b3fff !important; 
        }

        [data-theme="dark"] .sb-sidenav-dark .fw-bold.text-white {
            color: #ffffff !important;
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
                <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" id="mainNavbar">
                    <a class="navbar-brand d-flex flex-column align-items-center justify-content-center" href="index" 
                    style="width: 200px; margin-left: -10px; height: auto; padding-top: 10px; padding-bottom: 10px;"> 
                <i class="fas fa-boxes-stacked mb-1" style="font-size: 1.4rem; color: #0717ffff;"></i> 
            <span style="font-size: 0.85rem; letter-spacing: 1px; font-weight: bold; line-height: 1; color: #ffffff !important;">INVENTARIS BARANG</span>
        </a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" style="color: rgba(255,255,255,0.8); margin-left: 20px;">
                    <i class="fas fa-bars"></i>
                    </button>
                     <div class="ml-auto"></div>
                      <div class="ms-auto me-3 pr-3" style="margin-left: auto;">
                    <button class="btn btn-outline-light btn-sm" id="themeToggle"><i class="fas fa-moon"></i></button>
                </div>
            </nav>
            <div id="layoutSidenav">
                <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                        <div class="sb-sidenav-menu">
                            <div class="nav">
                                <div class="px-3 py-4 d-flex align-items-center">
                                    <div class="position-relative">
                                        <img src="assets/img/<?= $tampil_foto; ?>" class="rounded-circle border border-2 border-dark" style="width: 50px; height: 50px; object-fit: cover;">
                                        <span class="position-absolute bg-success border border-1 border-dark rounded-circle" style="width: 12px; height: 12px; bottom: 3px; right: 3px;"></span>
                                    </div>
                                    <div class="ms-3">
                                <div class="fw-bold profile-name"><?= ucfirst(explode('@', $user_email)[0]); ?></div>
                            <div class="small text-secondary"><i class="fas fa-circle text-success" style="font-size: 8px;"></i> Online</div>
                        </div>
                            </div>
                        <div class="px-3 py-2 text-uppercase" style="color: #4b646f; font-size: 0.75rem; font-weight: 600;">Main Menu</div>
                        <a class="nav-link" href="index"><div class="sb-nav-link-icon"><i class="fas fa-box"></i></div> Stock Barang</a>
                        <a class="nav-link active" href="kategori"><div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div> Kategori</a>
                        <a class="nav-link" href="masuk"><div class="sb-nav-link-icon"><i class="fas fa-cart-plus"></i></div> Barang Masuk</a>
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
                        <h1 class="mt-4">Kategori</h1>
                        <div class="mb-3">
                    <div class="mb-3">
                <i class="fas fa-calendar-alt"></i> 
            <span class="text-secondary" id="current-date">
            </span>
        </div>
        </span>
        </div>
            <div class="card mb-4">
            <script>
                function updateClock() {
                    const now = new Date();
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                    document.getElementById('current-date').innerText = now.toLocaleDateString('id-ID', options);
                }
                setInterval(updateClock, 1000);
                updateClock(); 
            </script>
                    <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                                    <i class="fas fa-plus"></i> Tambah Kategori
                                </button>
                            </div>
                            <div class="card-body">
                                <table id="maulanaTable" class="table table-bordered table-hover w-100">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kategori</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="modal fade" id="modalKat">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalTitle">Tambah Kategori</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="formKategori">
                        <div class="modal-body">
                            <input type="hidden" name="idkategori" id="idkategori">
                            <label>Nama Kategori</label>
                            <input type="text" name="namakategori" id="namakategori" class="form-control mb-3" required placeholder="Contoh : Elektronik">
                            <label>Deskripsi</label>
                            <input type="text" name="deskripsi" id="desk_kat" class="form-control mb-3" required placeholder="Penjelasan singkat">
                            <button type="submit" class="btn btn-primary btn-block" id="btnSimpan">Simpan</button>
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
                <script src="js/scripts.js"></script>

            <script>
            $(document).ready(function() {
            let table = $('#maulanaTable').DataTable({
                dom: 'Bfrtip',
                retrieve: true, 
                buttons: [
                    { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-success btn-sm' },
                    { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn btn-danger btn-sm' },
                    { extend: 'print', text: '<i class="fas fa-print"></i> Print', className: 'btn btn-info btn-sm' }
                ],
                language: { search: "_INPUT_", searchPlaceholder: "Cari kategori..." }
            });
            function loadKategori() {
                $.ajax({
                    url: 'api/kategori.php',
                    method: 'GET',
                    success: function(res) {
                        if (res.status === 'success') {
                            let rowData = res.data.map((item, i) => [
                                i + 1,
                                `<b>${item.namakategori}</b>`,
                                item.deskripsi,
                                `<button class="btn btn-warning btn-sm" onclick="editKat(${item.idkategori}, '${item.namakategori}', '${item.deskripsi}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="hapusKat(${item.idkategori})">
                                    <i class="fas fa-trash"></i>
                                </button>`
                            ]);
                            table.clear().rows.add(rowData).draw(false);
                        }
                    }
                });
            }
    loadKategori();
            $('#formKategori').off('submit').on('submit', function(e) {
                e.preventDefault();
                const id = $('#idkategori').val();
                const method = id ? 'PUT' : 'POST'; 
                const btn = $('#btnSimpan');
                btn.prop('disabled', true).text('Memproses...');
                $.ajax({
                    url: 'api/kategori.php',
                    method: method,
                    data: $(this).serialize(),
                    success: function(res) {
                        if(res.status === 'success') {
                            $('#modalKat').modal('hide');
                            Swal.fire('Berhasil', 'Data kategori diperbarui', 'success');
                            loadKategori();
                        } else {
                            Swal.fire('Error', 'Gagal memproses data', 'error');
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).text(id ? 'Update Data==' : 'Simpan');
                    }
                });
            });

                window.hapusKat = (id) => {
                    Swal.fire({
                        title: 'Hapus Kategori?',
                        text: "Data dihapus?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'api/kategori.php',
                                method: 'DELETE',
                                data: { idkategori: id },
                                success: function(res) {
                                    if(res.status === 'success') {
                                        Swal.fire('Terhapus', 'Kategori telah dibuang.', 'success');
                                        loadKategori();
                                    }
                                }
                            });
                        }
                    });
                };

                $(document).on('click', '[data-target="#modalTambah"]', function() {
                    $('#formKategori')[0].reset();
                    $('#idkategori').val('');
                    $('#modalTitle').text('Tambah Kategori');
                    $('#btnSimpan').text('Simpan');
                    $('#modalKat').modal('show');
                });

                window.editKat = (id, nama, desc) => {
                    $('#modalTitle').text('Edit Kategori');
                    $('#btnSimpan').text('Update Data');
                    $('#idkategori').val(id);
                    $('#namakategori').val(nama);
                    $('#desk_kat').val(desc);
                    $('#modalKat').modal('show');
                };

                const themeToggle = document.getElementById('themeToggle');
                const htmlElement = document.documentElement;    
                function applyTheme(theme) {
                    const navbar = document.getElementById('mainNavbar');
                    const sidebar = document.getElementById('sidenavAccordion');
                    const themeIcon = themeToggle ? themeToggle.querySelector('i') : null;

                    if (theme === 'dark') {
                        htmlElement.setAttribute('data-theme', 'dark');
                    
                        if (navbar) navbar.className = 'sb-topnav navbar navbar-expand navbar-dark bg-dark';
                        if (sidebar) sidebar.classList.replace('sb-sidenav-light', 'sb-sidenav-dark');
                        $('#themeToggle').html('<i class="fas fa-sun"></i>');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        htmlElement.removeAttribute('data-theme');
                        if (themeIcon) themeIcon.classList.replace('fa-moon');
                        if (navbar) navbar.className = 'sb-topnav navbar navbar-expand navbar-light bg-info';
                        if (sidebar) sidebar.classList.replace('sb-sidenav-dark', 'sb-sidenav-light');
                            $('#themeToggle').html('<i class="fas fa-moon"></i>');
                        localStorage.setItem('theme', 'light');
                    }
                }

                if (themeToggle) {
                    const savedTheme = localStorage.getItem('theme') || 'light';
                    applyTheme(savedTheme);
                    themeToggle.addEventListener('click', () => {
                        const currentTheme = localStorage.getItem('theme') === 'dark' ? 'light' : 'dark';
                        applyTheme(currentTheme);
                    });
                }
            });
    </script>
 </body>
</html>