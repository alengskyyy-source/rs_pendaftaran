<?php include 'sidebar.php'; ?>

<!-- Welcome Card -->
<div class="stat-card mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4>Selamat Datang, <?php echo html_escape($pasien->nama_pasien); ?>!</h4>
            <p class="text-muted mb-0">Sistem Pendaftaran Pasien Online Rumah Sakit</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="<?php echo site_url('pasien/daftar'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Pendaftaran Baru
            </a>
        </div>
    </div>
</div>

<!-- Profile Card -->
<div class="stat-card mb-4">
    <h5 class="mb-3">
        <i class="fas fa-user-circle me-2"></i> Profil Pasien
    </h5>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="150"><strong>Nama Lengkap</strong></td>
                    <td>: <?php echo html_escape($pasien->nama_pasien); ?></td>
                </tr>
                <tr>
                    <td><strong>Tanggal Lahir</strong></td>
                    <td>: <?php echo html_escape($pasien->tgl_lahir); ?></td>
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td>: <?php echo html_escape($pasien->alamat); ?></td>
                </tr>
                <tr>
                    <td><strong>No. Telepon</strong></td>
                    <td>: <?php echo html_escape($pasien->no_telp); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6 text-center">
            <div class="border rounded p-3 bg-light">
                <i class="fas fa-calendar-check" style="font-size: 2rem; color: #3498db;"></i>
                <h5 class="mt-2">Total Pendaftaran</h5>
                <h2 class="text-primary"><?php echo count($pendaftaran); ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- History Table -->
<div class="table-container">
    <h5 class="mb-3">
        <i class="fas fa-history me-2"></i> Riwayat Pendaftaran
    </h5>
    
    <?php if (empty($pendaftaran)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Belum ada riwayat pendaftaran. Silakan buat pendaftaran baru.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Dokter</th>
                        <th>Spesialis</th>
                        <th>Keluhan</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Jam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; foreach ($pendaftaran as $row): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo html_escape($row->nama_dokter); ?></strong></td>
                        <td><span class="badge bg-info"><?php echo html_escape($row->spesialis); ?></span></td>
                        <td><?php echo html_escape($row->keluhan); ?></td>
                        <td><i class="fas fa-calendar"></i> <?php echo html_escape($row->tgl_kunjungan); ?></td>
                        <td><i class="fas fa-clock"></i> <?php echo html_escape(substr($row->jam_kunjungan, 0, 5)); ?></td>
                        <td>
                            <?php if ($row->status == 'pending'): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-spinner"></i> Pending
                                </span>
                            <?php elseif ($row->status == 'disetujui'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Disetujui
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times"></i> Ditolak
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('menuToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });
    
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('menuToggle');
        if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
            if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
</script>

</body>
</html>