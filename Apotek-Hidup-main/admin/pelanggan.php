<?php
session_start();
include "../config/database.php";

// علق على هذي السطور لو ما تبغى Login
// if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
//     header("Location: ../pages/login.php");
//     exit;
// }

// حذف مستخدم
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    header("Location: pelanggan.php");
    exit;
}

// جلب إحصائيات المستخدمين
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$user_biasa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user'"))['total'];
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='admin'"))['total'];

// البحث
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM users WHERE nama LIKE '%$search%' OR email LIKE '%$search%' ORDER BY id DESC";
$data = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggan - Apotek Hidup</title>
    <link rel="stylesheet" href="../assets/css/pelanggan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/image/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>
<body>

<div class="admin-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2>ADMIN</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="data_obat.php">Data Obat</a></li>
            <li><a href="transaksi.php">Transaksi</a></li>
            <li><a href="pelanggan.php" class="active">Pelanggan</a></li>
            <li><a href="laporan.php">Laporan</a></li>
            <li><a href="admin_contact.php">Pesan</a></li>
            <li><a href="../auth/logout.php" class="logout">Logout</a></li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <header class="topbar">
            <div>
                <h1>Pelanggan</h1>
                <p>Daftar pengguna yang terdaftar di sistem Apotek Hidup</p>
            </div>
            <a href="tambah_pelanggan.php" class="btn-primary">+ Tambah Pelanggan</a>
        </header>

        <!-- STATISTIK -->
        <section class="stats">
            <div class="stat-card teal">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <h3>Total Pelanggan</h3>
                    <p><?= $total ?></p>
                </div>
            </div>
            <div class="stat-card yellow">
                <div class="stat-icon">👤</div>
                <div class="stat-info">
                    <h3>Akun Biasa</h3>
                    <p><?= $user_biasa ?></p>
                </div>
            </div>
            <div class="stat-card pink">
                <div class="stat-icon">🔑</div>
                <div class="stat-info">
                    <h3>Admin</h3>
                    <p><?= $admin ?></p>
                </div>
            </div>
            <div class="stat-card">
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Cari pelanggan..." value="<?= $search ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </section>

        <!-- TABLE -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($data)): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= !empty($row['nama']) ? $row['nama'] : '-' ?></td>
                        <td><?= $row['email'] ?></td>
                        <td>
                            <?php if ($row['role'] == 'admin'): ?>
                                <span class="role-badge admin">Admin</span>
                            <?php else: ?>
                                <span class="role-badge user">User</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="detail_pelanggan.php?id=<?= $row['id'] ?>" class="btn-detail">Detail</a>
                            <a href="pelanggan.php?hapus=<?= $row['id'] ?>" 
                               class="btn-hapus" 
                               onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>