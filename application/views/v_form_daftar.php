<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pendaftaran - RS Pendaftaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand fw-bold">RS Online</span>
        <div class="ms-auto">
            <a href="<?php echo site_url('pasien'); ?>" class="btn btn-outline-light btn-sm">Dashboard</a>
            <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
<div class="container py-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-success text-white py-3 rounded-top-4">
            <h4 class="mb-0">Formulir Pendaftaran Berobat</h4>
            <small>Lengkapi data kunjungan pasien</small>
        </div>
        <div class="card-body p-4">
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <form method="post" action="<?php echo site_url('pasien/daftar'); ?>">
                <h5 class="mb-3">Data Pasien</h5>
                <div class="mb-3">
                    <label class="form-label">Nama Pasien</label>
                    <input type="text" name="nama_pasien" class="form-control" value="<?php echo set_value('nama_pasien', $pasien->nama_pasien); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control" value="<?php echo set_value('tgl_lahir', $pasien->tgl_lahir); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" required><?php echo set_value('alamat', $pasien->alamat); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="no_telp" class="form-control" value="<?php echo set_value('no_telp', $pasien->no_telp); ?>" required>
                </div>

                <hr>
                <h5 class="mb-3">Data Pendaftaran</h5>
                <div class="mb-3">
                    <label class="form-label">Keluhan Penyakit</label>
                    <textarea name="keluhan" class="form-control" rows="4" required><?php echo set_value('keluhan'); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Kunjungan</label>
                    <input type="date" name="tgl_kunjungan" class="form-control" value="<?php echo set_value('tgl_kunjungan'); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jam Kunjungan</label>
                    <input type="time" name="jam_kunjungan" class="form-control" value="<?php echo set_value('jam_kunjungan'); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pilih Dokter Spesialis</label>
                    <select name="id_dokter" class="form-select" required>
                        <option value="">-- Pilih Dokter --</option>
                        <?php foreach ($dokter as $d): ?>
                            <option value="<?php echo $d->id_dokter; ?>" <?php echo set_select('id_dokter', $d->id_dokter); ?>>
                                <?php echo html_escape($d->nama_dokter); ?> - <?php echo html_escape($d->spesialis); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="<?php echo site_url('pasien'); ?>" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Kirim Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
