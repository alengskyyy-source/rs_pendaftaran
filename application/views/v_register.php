<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pasien - RS Pendaftaran</title>
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
            background: linear-gradient(135deg, #198754, #157347) !important;
        }
        
        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #198754, #157347);
            border: none;
            transition: transform 0.2s;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #157347, #146c43);
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-white py-4 rounded-top-4">
                    <h4 class="mb-0">Registrasi Akun Pasien</h4>
                    <small>Isi data diri dengan benar</small>
                </div>
                <div class="card-body p-4">
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('success'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                    
                    <form method="post" action="<?php echo site_url('auth/register'); ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama_pasien" class="form-control form-control-lg" value="<?php echo set_value('nama_pasien'); ?>" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control form-control-lg" value="<?php echo set_value('tgl_lahir'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea name="alamat" class="form-control form-control-lg" rows="3" placeholder="Masukkan alamat lengkap" required><?php echo set_value('alamat'); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control form-control-lg" value="<?php echo set_value('no_telp'); ?>" placeholder="Contoh: 08123456789" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control form-control-lg" value="<?php echo set_value('username'); ?>" placeholder="Pilih username unik" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Minimal 6 karakter" required>
                            <small class="text-muted">Minimal 6 karakter.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password</label>
                            <input type="password" name="password_confirm" class="form-control form-control-lg" placeholder="Ulangi password" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">Daftar Akun</button>
                    </form>
                    <div class="text-center mt-4">
                        <span>Sudah punya akun?</span>
                        <a href="<?php echo site_url('auth/login'); ?>" class="text-decoration-none fw-bold text-success">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>