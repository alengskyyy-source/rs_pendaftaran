<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?? 'Sistem Pendaftaran RS'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --dark-color: #1a252f;
            --light-color: #ecf0f1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, #1a252f 0%, #2c3e50 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .sidebar-header p {
            margin: 5px 0 0;
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu .menu-item {
            padding: 12px 25px;
            margin: 5px 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sidebar-menu .menu-item:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .sidebar-menu .menu-item.active {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            box-shadow: 0 5px 15px rgba(52,152,219,0.3);
        }

        .sidebar-menu .menu-item a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-menu .menu-item i {
            width: 25px;
            font-size: 1.2rem;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .top-navbar {
            background: white;
            border-radius: 15px;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .table-custom thead th {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            border: none;
            padding: 15px;
        }

        .table-custom tbody tr:hover {
            background: #f8f9fa;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .menu-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1100;
                background: #3498db;
                color: white;
                border: none;
                border-radius: 10px;
                padding: 10px 15px;
                cursor: pointer;
            }
        }

        @media (min-width: 769px) {
            .menu-toggle {
                display: none;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease;
        }

        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .btn-group-sm .btn {
            margin: 0 2px;
        }
    </style>
</head>
<body>

<button class="menu-toggle" id="menuToggle">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-hospital-user" style="font-size: 3rem; margin-bottom: 10px;"></i>
        <h4>Diskiee Hospital</h4>
        <p>Sistem Pendaftaran Pasien</p>
    </div>
    
    <div class="sidebar-menu">
        <?php 
        $current_role = $this->session->userdata('role');
        if ($current_role == 'admin'): 
        ?>
        <div class="menu-item <?php echo ($active_menu == 'dashboard') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('admin'); ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>
        <div class="menu-item <?php echo ($active_menu == 'pasien') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('admin/pasien'); ?>">
                <i class="fas fa-users"></i>
                <span>Data Pasien</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?php echo site_url('admin/export_csv'); ?>">
                <i class="fas fa-file-export"></i>
                <span>Export CSV</span>
            </a>
        </div>
        <?php else: ?>
        <div class="menu-item <?php echo ($active_menu == 'dashboard') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('pasien'); ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>
        <div class="menu-item <?php echo ($active_menu == 'daftar') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('pasien/daftar'); ?>">
                <i class="fas fa-edit"></i>
                <span>Form Pendaftaran</span>
            </a>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="sidebar-footer">
        <div class="menu-item">
            <a href="<?php echo site_url('auth/logout'); ?>">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>

<div class="main-content" id="mainContent">
    <div class="top-navbar">
        <h5 class="page-title">
            <i class="fas fa-<?php echo $icon ?? 'home'; ?>"></i> 
            <?php echo $page_title ?? 'Dashboard'; ?>
        </h5>
        <div class="user-info">
            <span>
                <i class="fas fa-user-circle"></i> 
                <?php echo $this->session->userdata('username'); ?>
                <br><small class="text-muted">
                    <?php echo ucfirst($this->session->userdata('role')); ?>
                </small>
            </span>
            <div class="user-avatar">
                <?php echo strtoupper(substr($this->session->userdata('username'), 0, 1)); ?>
            </div>
        </div>
    </div>
    
    <div class="fade-in">