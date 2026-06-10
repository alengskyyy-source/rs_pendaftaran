<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pasien - Admin RS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid px-4">
        <span class="navbar-brand fw-bold">Admin RS Online</span>
        <div class="ms-auto">
            <a href="<?php echo site_url('admin/pasien'); ?>" class="btn btn-outline-light btn-sm">Kembali ke Data Pasien</a>
            <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>
<div class="container py-4">
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <?php if ($mode == 'add'): ?>
                <h4 class="mb-0">Tambah Data Pasien Baru</h4>
            <?php else: ?>
                <h4 class="mb-0">Edit Data Pasien</h4>
            <?php endif; ?>
        </div>
        <div class="card-body p-4">
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <?php
                if ($mode == 'add') {
                    $action = site_url('admin/pasien_add');
                    $nama_pasien = '';
                    $tgl_lahir = '';
                    $alamat = '';
                    $no_telp = '';
                    $username = '';
                } else {
                    $action = site_url('admin/pasien_edit/' . $pasien->id_pasien);
                    $nama_pasien = $pasien->nama_pasien;
                    $tgl_lahir = $pasien->tgl_lahir;
                    $alamat = $pasien->alamat;
                    $no_telp = $pasien->no_telp;
                    $username = $pasien->username;
                }
            ?>
            <form method="post" action="<?php echo $action; ?>">
                <div class="mb-3">
                    <label class="form-label">Nama Pasien <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pasien" class="form-control" value="<?php echo set_value('nama_pasien', $nama_pasien); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_lahir" class="form-control" value="<?php echo set_value('tgl_lahir', $tgl_lahir); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea name="alamat" class="form-control" rows="3" required><?php echo set_value('alamat', $alamat); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="no_telp" class="form-control" value="<?php echo set_value('no_telp', $no_telp); ?>" required>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label">Username Login <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" value="<?php echo set_value('username', $username); ?>" required>
                    <small class="text-muted">Username harus unik</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password <?php if ($mode == 'edit'): ?><small class="text-muted">(Kosongkan jika tidak ingin mengubah password)</small><?php endif; ?> <span class="text-danger"><?php echo ($mode == 'add') ? '*' : ''; ?></span></label>
                    <input type="password" name="password" class="form-control" <?php echo ($mode == 'add') ? 'required' : ''; ?>>
                    <?php if ($mode == 'add'): ?>
                        <small class="text-muted">Minimal 6 karakter</small>
                    <?php endif; ?>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="<?php echo site_url('admin/pasien'); ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Data Pasien</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>