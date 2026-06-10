<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - RS Pendaftaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('<?php echo base_url('assets/'); ?>hospital.jpeg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
        }
        
        /* Overlay gelap agar teks lebih mudah dibaca */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }
        
        .container {
            position: relative;
            z-index: 1;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            border: none;
        }
        
        .card-header {
            background: linear-gradient(135deg, #0d6efd, #0a58ca) !important;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-white text-center py-4 rounded-top-4">
                    <h4 class="mb-0">Sistem Pendaftaran Pasien</h4>
                    <small>Dyy Hospital</small>
                </div>
                <div class="card-body p-4">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('success'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

                    <form method="post" action="<?php echo site_url('auth/login'); ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control form-control-lg" value="<?php echo set_value('username'); ?>" placeholder="Masukkan username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
                    </form>

                    <div class="text-center mt-4">
                        <span>Belum punya akun?</span>
                        <a href="<?php echo site_url('auth/register'); ?>" class="text-decoration-none fw-bold">Daftar Pasien</a>
                    </div>
                    <hr class="my-3">
                    <div class="small text-muted text-center">
                        <i class="bi bi-shield-lock"></i> Demo Admin: <strong>admin</strong> / <strong>admin123</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>