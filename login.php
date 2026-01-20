<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4a68f1ff 0%, #6a84f5ff 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header i {
            font-size: 50px;
            color: #194ec2ff;
            margin-bottom: 10px;
        }

        .login-header h2 {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(118, 75, 162, 0.2);
            border-color: #194ec2ff;
        }

        .btn-login {
            background: linear-gradient(to right, #7087eeff, #194ec2ff);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .btn-login:hover {
            opacity: 0.9;
            letter-spacing: 2px;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-boxes-stacked"></i>
            <h2>Inventaris Barang</h2>
            <p class="text-muted">Silakan login untuk mengelola stok</p>
        </div>

        <form id="formLogin">
            <div class="mb-3">
                <label class="form-label small">AlamatEmail</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" name="inputEmail" class="form-control border-start-0" placeholder="Admin@gmail.com" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="inputPassword" class="form-control border-start-0" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" id="btnLogin" class="btn btn-primary btn-login w-100">
                LOGIN
            </button>
        </form>

        <div class="footer-text">
            &copy; 2026 Inventaris Barang System 
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#formLogin').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnLogin');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Loading...');
            $.ajax({
                url: 'api/login.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Selamat datang kembali',
                            showConfirmButton: false,
                            timer: 1500,
                            scrollbarPadding: false,
                            heightAuto: false 
                        }).then(() => {
                            window.location.href = 'index';
                        });
                    } else {
                        $('#btnLogin').prop('disabled', false).text('LOG IN');
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            text: response.message, 
                            scrollbarPadding: false,
                            heightAuto: false
                        });
                    }
                },
                error: function() {
                    $('#btnLogin').prop('disabled', false).text('LOG IN');
                    Swal.fire({
                        icon: 'error',
                        title: 'Sistem Error',
                        text: 'Gagal terhubung ke server!',
                        scrollbarPadding: false,
                        heightAuto: false
                    });
                }
            });
                        });
                    });
        </script>
    </body>
</html>