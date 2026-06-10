<?php include 'sidebar.php'; ?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Pendaftar</h6>
                    <h2 class="mb-0"><?php echo (int) $statistik->total_pendaftar; ?></h2>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-users text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Pending</h6>
                    <h2 class="mb-0 text-warning"><?php echo (int) $statistik->pending; ?></h2>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <i class="fas fa-clock text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Disetujui</h6>
                    <h2 class="mb-0 text-success"><?php echo (int) $statistik->diterima; ?></h2>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #27ae60, #229954);">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Ditolak</h6>
                    <h2 class="mb-0 text-danger"><?php echo (int) $statistik->ditolak; ?></h2>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <i class="fas fa-times-circle text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Statistik Sederhana -->
<div class="row mb-4">
    <div class="col-12">
        <div class="stat-card">
            <h5 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Ringkasan Statistik</h5>
            <div class="row text-center">
                <div class="col-4">
                    <div class="border rounded p-3 bg-warning bg-opacity-10">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                        <h4 class="mt-2"><?php echo (int) $statistik->pending; ?></h4>
                        <small>Menunggu</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="border rounded p-3 bg-success bg-opacity-10">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                        <h4 class="mt-2"><?php echo (int) $statistik->diterima; ?></h4>
                        <small>Disetujui</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="border rounded p-3 bg-danger bg-opacity-10">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                        <h4 class="mt-2"><?php echo (int) $statistik->ditolak; ?></h4>
                        <small>Ditolak</small>
                    </div>
                </div>
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

<!-- Data Table -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            <i class="fas fa-table me-2"></i> Data Pendaftaran Pasien
        </h5>
        <div>
            <a href="<?php echo site_url('admin/export_csv'); ?>" class="btn btn-success btn-sm me-2">
                <i class="fas fa-download me-1"></i> CSV
            </a>
        </div>
    </div>
    
    <?php if (empty($pendaftaran)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Belum ada data pendaftaran.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Pasien</th>
                        <th>Kontak</th>
                        <th>Dokter & Spesialis</th>
                        <th>Keluhan</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; foreach ($pendaftaran as $row): ?>
                    <tr class="align-middle">
                        <td><?php echo $no++; ?></td>
                        <td>
                            <strong><?php echo html_escape($row->nama_pasien); ?></strong><br>
                            <small class="text-muted">
                                <i class="fas fa-birthday-cake"></i> <?php echo html_escape($row->tgl_lahir); ?>
                            </small>
                        </td>
                        <td>
                            <i class="fas fa-phone"></i> <?php echo html_escape($row->no_telp); ?><br>
                            <small><?php echo html_escape(substr($row->alamat, 0, 30)); ?>...</small>
                        </td>
                        <td>
                            <strong><?php echo html_escape($row->nama_dokter); ?></strong><br>
                            <span class="badge bg-info"><?php echo html_escape($row->spesialis); ?></span>
                        </td>
                        <td><?php echo html_escape(substr($row->keluhan, 0, 50)); ?>...</td>
                        <td>
                            <i class="fas fa-calendar"></i> <?php echo html_escape($row->tgl_kunjungan); ?><br>
                            <i class="fas fa-clock"></i> <?php echo html_escape(substr($row->jam_kunjungan, 0, 5)); ?>
                        </td>
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
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo site_url('admin/update_status/' . $row->id_daftar . '/disetujui'); ?>" 
                                   class="btn btn-success" title="Setujui" onclick="return confirm('Setujui pendaftaran ini?')">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="<?php echo site_url('admin/update_status/' . $row->id_daftar . '/ditolak'); ?>" 
                                   class="btn btn-danger" title="Tolak" onclick="return confirm('Tolak pendaftaran ini?')">
                                    <i class="fas fa-times"></i>
                                </a>
                                <a href="<?php echo site_url('admin/update_status/' . $row->id_daftar . '/pending'); ?>" 
                                   class="btn btn-warning" title="Pending" onclick="return confirm('Kembalikan status menjadi pending?')">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Total: <?php echo count($pendaftaran); ?> pendaftaran</small>
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