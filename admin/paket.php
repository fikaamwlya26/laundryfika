<?php
include '../koneksi/koneksi.php';

// Menangani masalah tambah data
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['action'])) {
    // Pastikan semua field ada
    if (isset($_POST['nama_paket'], $_POST['jenis'], $_POST['harga'])) {
        $nama_paket = mysqli_real_escape_string($koneksi, $_POST['nama_paket']);
        $jenis = mysqli_real_escape_string($koneksi, $_POST['jenis']);
        $harga = (int)$_POST['harga']; // Pastikan harga di-casting ke integer

        // Query untuk menambah data
        $query = "INSERT INTO tb_paket (nama_paket, jenis, harga) 
                  VALUES ('$nama_paket', '$jenis', $harga)";

        // Eksekusi query
        if (mysqli_query($koneksi, $query)) {
            echo "Sukses menambahkan data paket!";
            // Redirect setelah berhasil menambah data
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "Semua field harus diisi!";
    }
} 
 
// menangani masalah edit data
 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    // Pastikan id, nama_paket, jenis, harga tidak kosong
    $id = $_POST['id'] ?? null;
    $nama_paket = $_POST['nama_paket'] ?? null;
    $jenis = $_POST['jenis'] ?? null;
    $harga = $_POST['harga'] ?? null;

    if ($id && $nama_paket && $jenis && $harga) {
        // Proses edit data
        $query = "UPDATE tb_paket SET nama_paket = ?, jenis = ?, harga = ? WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ssdi", $nama_paket, $jenis, $harga, $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Data berhasil diperbarui.";
        } else {
            echo "Tidak ada perubahan data.";
        }
    } else {
        echo "Data tidak lengkap.";
    }
}
 
// Menangani pengiriman form untuk "Delete" 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
    // Validasi dan sanitasi input ID produk
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id > 0) {
        // Menyiapkan query untuk menghapus data berdasarkan id
        $query = "DELETE FROM tb_paket WHERE id = ?";  // Pastikan tabel dan kolom sesuai

        // Menyiapkan statement
        if ($stmt = mysqli_prepare($koneksi, $query)) {
            // Mengikat parameter ke statement
            mysqli_stmt_bind_param($stmt, "i", $id);  // "i" untuk integer

            // Menjalankan statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect setelah berhasil menghapus data
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                // Menampilkan pesan kesalahan jika query gagal
                echo "Gagal menghapus data. Error: " . mysqli_stmt_error($stmt);
            }

            // Menutup statement
            mysqli_stmt_close($stmt);
        } else {
            // Menampilkan pesan kesalahan jika query prepare gagal
            echo "Gagal menyiapkan query. Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "ID produk tidak valid.";
    }
}

// Mengambil data untuk "Read"
$result = mysqli_query($koneksi, "SELECT * FROM tb_paket");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bening - Tables</title>
    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        .center{
        text-align: center;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Bening <sup>Laundry</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

             <!-- Nav Item - Dashboard -->
             <li class="nav-item ">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="produk.php">
                    <i class="fas fa fa-folder"></i>
                    <span>Produk</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="paket.php"> 
                    <i class="fas fa-fw fa-laptop"></i>
                    <span>Paket</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="transaksi.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Transaksi</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="komentar.php"> 
                    <i class="fas fa-fw fa-comments"></i>
                    <span>Komentar</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="user.php"> 
                    <i class="fas fa-fw fa-user"></i>
                    <span>User</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="setting.php"> 
                    <i class="fas fa-cogs fa-sm fa-fw mr-2"></i>
                    <span>Setting</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle"
                                    src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading --> 
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h4 class="m-0 font-weight-bold text-primary">Data Laundry</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered center" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="center">
                                            <th>No</th>  
                                            <th>Nama Paket</th>
                                            <th>Jenis</th>
                                            <th>Harga (kg)</th> 
                                            <th>Aksi</th> 
                                        </tr>
                                    </thead>
                                    <!-- Pengambilan data dari database-->
                                    <tbody> 
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td> 
                                        <td><?= $row['nama_paket']; ?></td> 
                                        <td><?= $row['jenis']; ?></td> 
                                        <td><?= $row['harga']; ?></td> 
                                        <td style="width:20%" >
                                            <!-- Detail Button -->
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </button> 

                                            <!-- Edit Button -->
                                            <button class="btn btn-warning btn-sm" onclick="openEditModal(
                                                <?php echo $row['id']; ?>,
                                                '<?php echo addslashes($row['nama_paket']); ?>',  
                                                '<?php echo addslashes($row['jenis']); ?>',
                                                <?php echo $row['harga']; ?>
                                            )">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </button> 

                                            <!-- Delete Button --> 
                                            <button class="btn btn-danger btn-sm " data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>  
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Modal Tambah Data Produk -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Tambah Data Produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Tambah Data Produk dengan method POST dan enctype untuk upload file -->
                            <form id="formTambah" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="namaPaket" class="form-label">Nama Paket</label>
                                    <input type="text" class="form-control" name="nama_paket" id="namaPaket" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis" class="form-label">Jenis</label>
                                    <input class="form-control" name="jenis" id="jenis" required></input>
                                </div>
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" name="harga" id="harga" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Tambah Data end -->
 
            <!-- Modal Edit --> 
            <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Paket</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm" method="POST" action="">
                                <!-- Input hidden untuk ID dan Action -->
                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="action" value="edit">

                                <div class="mb-3">
                                    <label for="edit_nama" class="form-label">Nama Paket</label>
                                    <input type="text" class="form-control" id="edit_nama" name="nama_paket" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_jenis" class="form-label">Jenis</label>
                                    <input type="text" class="form-control" id="edit_jenis" name="jenis" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="edit_harga" name="harga" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" form="editForm" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Edit End --> 
 
            <!-- Modal Konfirmasi Hapus -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus produk ini?
                        </div>
                        <div class="modal-footer">
                            <form method="POST" action="">
                                <input type="hidden" id="deleteId" name="id">
                                <input type="hidden" name="action" value="delete">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 
            <!-- Modal Konfirmasi Hapus End -->


            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Bening Laundry</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                    </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>
    
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

 
    <!-- Script tambah data dengan fetch -->
    <script>
        // Fungsi untuk menambah data produk menggunakan fetch API
        document.getElementById("formTambah").addEventListener("submit", function(event) {
            event.preventDefault(); // Mencegah form untuk reload halaman saat disubmit

            const form = document.getElementById('formTambah');
            const formData = new FormData(form);

            // Kirim data menggunakan fetch API untuk menambah produk
            fetch('', { // Form ini mengarah ke file yang sama untuk menangani form
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Tampilkan respon dari server
                if (data.includes("Sukses")) { // Menyaring pesan sukses
                    // Jika sukses, tutup modal dan reload halaman untuk menampilkan data terbaru
                    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
                    addModal.hide(); // Tutup modal
                    location.reload(); // Refresh halaman
                } else {
                    alert("Terjadi kesalahan, silakan coba lagi.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Terjadi kesalahan saat mengirim data.");
            });
        });
    </script>
    <!-- script tambah data end-->

    <!-- script edit -->
    <script>
        // script edit
        function openEditModal(id, namaPaket, jenis, harga) {
            // Isi nilai input dalam modal edit
            document.getElementById('edit_id').value = id;  // Pastikan ID diisi
            document.getElementById('edit_nama').value = namaPaket;
            document.getElementById('edit_jenis').value = jenis;
            document.getElementById('edit_harga').value = harga;

            // Menampilkan modal edit produk
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        } 
    </script> 
    <!-- script edit end-->

    <!-- script untuk hapus data -->
    <script>
        function setDeleteId(id) {
            document.getElementById('deleteId').value = id;
        }
    </script>
    <!-- script untuk hapus data end-->


</body>

</html>