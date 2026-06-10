<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pasien - Admin RS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid px-4">
        <span class="navbar-brand fw-bold">Admin RS Online</span>
        <div class="ms-auto">
            <a href="<?php echo site_url('admin'); ?>" class="btn btn-outline-light btn-sm">Dashboard</a>
            <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>
<div class="container-fluid px-4 py-4">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Data Pasien</h3>
            <p class="text-muted mb-0">Admin dapat menambah, mengedit, dan menghapus data pasien.</p>
        </div>
        <a href="<?php echo site_url('admin/pasien_add'); ?>" class="btn btn-primary">Tambah Pasien</a>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-white rounded-top-4"><strong>Daftar Pasien</strong></div>
        <div class="card-body">
            <?php if (empty($pasien)): ?>
                <div class="alert alert-info mb-0">Belum ada data pasien.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                        <tr>
                            <th>No</th><th>Nama Pasien</th><th>Username</th><th>Tanggal Lahir</th><th>Alamat</th><th>No. Telp</th><th width="170">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($pasien as $row): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo html_escape($row->nama_pasien); ?></td>
                                <td><?php echo html_escape($row->username); ?></td>
                                <td><?php echo html_escape($row->tgl_lahir); ?></td>
                                <td><?php echo html_escape($row->alamat); ?></td>
                                <td><?php echo html_escape($row->no_telp); ?></td>
                                <td>
                                    <a href="<?php echo site_url('admin/pasien_edit/' . $row->id_pasien); ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="<?php echo site_url('admin/pasien_delete/' . $row->id_pasien); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data pasien ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
