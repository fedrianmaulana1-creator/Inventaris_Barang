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
<title>Kelola Admin - Inventaris Barang</title>

<link href="css/styles.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        .sb-sidenav-dark { background-color: #000 !important; }
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
        [data-theme="dark"] table.dataTable, 
        [data-theme="dark"] .dataTables_wrapper .dataTables_length, 
        [data-theme="dark"] .dataTables_wrapper .dataTables_filter, 
        [data-theme="dark"] .dataTables_wrapper .dataTables_info, 
        [data-theme="dark"] .dataTables_wrapper .dataTables_paginate {
            color: #fff !important;
        }
        .dt-buttons {
        margin-bottom: 15px;
        gap: 5px;
        display: flex;
        }
        button.dt-button {
        border: none !important;
        border-radius: 4px !important;
        }
        button.buttons-excel { background-color: #28a745 !important; color: white !important; }
        button.buttons-pdf { background-color: #dc3545 !important; color: white !important; }
        button.buttons-print { background-color: #343a40 !important; color: white !important; }
        .img-table { width: 35px; height: 35px; object-fit: cover; border-radius: 50%; border: 1px solid #ddd; }
        .sb-sidenav-light .fw-bold.profile-name { color: #161718ff !important; }
        [data-theme="dark"] .profile-name { color: #ffffff !important; }

        #layoutSidenav #layoutSidenav_nav {
        transform: translateX(-225px); 
        transition: transform 0.3s ease;
        }

        body.sb-sidenav-toggled #layoutSidenav #layoutSidenav_nav {
        transform: translateX(0); 
        }

        @media (min-width: 992px) {
        #layoutSidenav #layoutSidenav_nav {
        transform: translateX(0);
         }
        body.sb-sidenav-toggled #layoutSidenav #layoutSidenav_nav {
        transform: translateX(-225px);
        }
        }
        .profile-name { 
            color: #000000 !important; 
        }
        .small.text-secondary {
            color: #212529 !important;
        }
        [data-theme="dark"] .profile-name { 
            color: #ffffff !important; 
        }

        [data-theme="dark"] .small.text-secondary {
            color: #adb5bd !important;
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
                <a class="navbar-brand d-flex flex-column align-items-center justify-content-center" href="index.php" style="width: 200px; margin-left: -10px; height: auto;">
                    <i class="fas fa-boxes-stacked mb-1" style="font-size: 1.4rem; color: #0717ffff;"></i> 
                    <span style="font-size: 0.85rem; letter-spacing: 1px; font-weight: bold; line-height: 1; color: white;">INVENTARIS BARANG</span>
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
                        <a class="nav-link" href="kategori"><div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div> Kategori</a>
                        <a class="nav-link" href="masuk"><div class="sb-nav-link-icon"><i class="fas fa-cart-plus"></i></div> Barang Masuk</a>
                        <a class="nav-link" href="keluar"><div class="sb-nav-link-icon"><i class="fas fa-cart-arrow-down"></i></div> Barang Keluar</a>
                        <a class="nav-link active" href="admin"><div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div> Kelola Admin</a>
                        <a class="nav-link" href="logout"><div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div> Logout</a>
                    </div>
                </div>
            </nav>
        </div>  
            <div id="layoutSidenav_content">
                    <main>
                        <div class="container-fluid px-4">
                            <h1 class="mt-4">Kelola Admin</h1>
                            <div class="mb-3">
                                <i class="fas fa-calendar-alt"></i> 
                                <span id="current-date" class="text-secondary"></span>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAdmin">
                                        <i class="fas fa-user-plus"></i> Tambah Admin
                                    </button>
                                    <span class="small font-weight-bold text-uppercase"><i class="fas fa-shield-alt"></i> Manajemen Akses</span>
                                </div>
                                <div class="card-body">
                                    <table id="tableAdmin" class="table table-bordered table-hover w-100">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Gambar</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="load-admin-data">
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="modal fade" id="modalAdmin">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modalTitle">Tambah Admin</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formAdmin" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="iduser" id="iduser">
                                <input type="hidden" name="_method" id="http_method" value="POST">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" required placeholder="admin@mail.com">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Isi jika ingin ganti password">
                                </div>
                                <div class="form-group">
                                    <label>Gambar</label>
                                    <input type="file" name="file" class="form-control-file border p-1 rounded w-100">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-block" id="btnSubmit">Update data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
                <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
                <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
                <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>

        const API_KEY = 'RahasiaInventaris123';
        $(document).ready(function() {
            function updateClock() {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                document.getElementById('current-date').innerText = now.toLocaleDateString('id-ID', options);
            }
            setInterval(updateClock, 1000); updateClock();
            $("#sidebarToggle").on("click", function(e) {
                e.preventDefault();
                $("body").toggleClass("sb-sidenav-toggled");
                if ($("body").hasClass("sb-sidenav-toggled")) {
                    localStorage.setItem('sb-sidebar-toggle', 'true');
                } else {
                    localStorage.setItem('sb-sidebar-toggle', 'false');
                }
            });
            if (localStorage.getItem('sb-sidebar-toggle') === 'true') {
                $("body").addClass("sb-sidenav-toggled");
            }
        loadData(); 

            const themeToggle = document.getElementById('themeToggle');
            const navbar = document.getElementById('mainNavbar');
            const sidebar = document.getElementById('sidenavAccordion');
            const applyTheme = (theme) => {
                        if (theme === 'dark') {
                            document.documentElement.setAttribute('data-theme', 'dark');
                            $('#mainNavbar').removeClass('bg-info').addClass('bg-dark');
                            $('#sidenavAccordion').removeClass('sb-sidenav-light').addClass('sb-sidenav-dark');
                            $('#themeToggle').html('<i class="fas fa-sun"></i>');
                            localStorage.setItem('theme', 'dark');
                        } else {
                            document.documentElement.removeAttribute('data-theme');
                            $('#mainNavbar').removeClass('bg-dark').addClass('bg-info');
                            $('#sidenavAccordion').removeClass('sb-sidenav-dark').addClass('sb-sidenav-light');
                               $('#themeToggle').html('<i class="fas fa-moon"></i>');
                            localStorage.setItem('theme', 'light');
                        }
                    };
                    applyTheme(localStorage.getItem('theme') || 'light');
                    if (themeToggle) {
                        themeToggle.addEventListener('click', () => {
                            const currentTheme = localStorage.getItem('theme') === 'dark' ? 'light' : 'dark';
                            applyTheme(currentTheme);
                        });
                    }

                $('#formAdmin').submit(function(e) {
                    e.preventDefault();
                    let formData = new FormData(this);
                    $.ajax({
                        url: 'api/admin.php',
                        type: 'POST',
                        headers: { 'Authorization': API_KEY },
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            $('#modalAdmin').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success').then(() => loadData());
                        },
                        error: function(err) {
                            Swal.fire('Gagal', err.responseJSON.message, 'error');
                        }
                    });
                });

                $('#modalAdmin').on('hidden.bs.modal', function () {
                    $('#formAdmin')[0].reset();
                    $('#iduser').val('');
                    $('#http_method').val('POST');
                    $('#modalTitle').text('Tambah Admin');
                });
            });

                function loadData() {
            if ($.fn.DataTable.isDataTable('#tableAdmin')) {
                $('#tableAdmin').DataTable().destroy();
            }
                $.ajax({
                    url: 'api/admin.php',
                    type: 'GET',
                    headers: { 'Authorization': API_KEY },
                    success: function(res) {
                        let rows = '';
                        let i = 1; 
                        res.data.forEach(admin => {
                            let foto = admin.image ? admin.image : 'user.png';
                            rows += `<tr>
                <td>${i++}</td>
                <td class="text-center"><img src="assets/img/${foto}" class="img-table"></td>
                <td><strong>${admin.email}</strong></td>
                <td><small class="text-muted">Encrypted</small></td>
                <td><button class="btn btn-warning btn-sm" onclick="editMode('${admin.iduser}', '${admin.email}')"><i class="fas fa-edit"></i>
                </button><button class="btn btn-danger btn-sm" onclick="deleteData('${admin.iduser}')" title="Hapus"><i class="fas fa-trash"></i>
                </button>
                </td>
            </tr>`;
            });
            $('#load-admin-data').html(rows);
            $('#tableAdmin').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { 
                        extend: 'excelHtml5', 
                        text: '<i class="fas fa-file-excel"></i> Excel', 
                        className: 'btn btn-success btn-sm', 
                        exportOptions: { columns: [0, 2] } 
                    },
                    { 
                        extend: 'pdfHtml5', 
                        text: '<i class="fas fa-file-pdf"></i> PDF', 
                        className: 'btn btn-danger btn-sm', 
                        exportOptions: { columns: [0, 2] } 
                    },
                    { 
                        extend: 'print', 
                        text: '<i class="fas fa-print"></i> Print', 
                        className: 'btn btn-info btn-sm', 
                        exportOptions: { columns: [0, 2] } 
                    }
                ],
                    language: { search: "_INPUT_", searchPlaceholder: "Cari admin..." },
                    columnDefs: [
                        { targets: 0, className: 'text-center' }
                    ]
                });
            }
        });
    }
                    function editMode(id, email) {
                $('#formAdmin')[0].reset();
                $('#modalTitle').text('Edit Admin');
                $('#iduser').val(id);
                $('#email').val(email);
                $('#http_method').val('PUT');
                $('#password').removeAttr('required');
                $('#modalAdmin').modal('show');
            }
                function deleteData(id) {
                console.log("Menghapus ID:", id); 
                Swal.fire({
                    title: 'Hapus Admin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6C757D',
                    confirmButtonText: 'OK',
                    cancelButtonText: 'cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'api/admin.php',
                            type: 'DELETE', 
                            headers: { 
                                'Authorization': API_KEY,
                                'Content-Type': 'application/json' 
                            },
                            data: JSON.stringify({ iduser: id }), 
                            success: function(res) {
                                Swal.fire('Berhasil!', res.message, 'success');
                                loadData();
                            },
                            error: function(err) {
                                console.error("Error Hapus:", err);
                                let msg = err.responseJSON ? err.responseJSON.message : 'Gagal menghapus data';
                                Swal.fire('Gagal!', msg, 'error');
                            }
                        });
                    }
                });
            }
        </script>
    </body>
</html>