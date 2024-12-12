<?php 
include '../koneksi/koneksi.php'; // Panggil file koneksi.php disini

// Menangani pengiriman form untuk "Create"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah semua field sudah diisi
    if (isset($_POST['nama_layanan'], $_POST['deskripsi'], $_POST['kategori'], $_POST['berat'], 
              $_POST['status'], $_POST['harga']) && isset($_FILES['gambar'])) {
        
        // Ambil data dari form POST
        $nama_layanan = $_POST['nama_layanan'];
        $deskripsi = $_POST['deskripsi'];
        $kategori = $_POST['kategori'];
        $berat = $_POST['berat'];
        $status = $_POST['status'];
        $harga = $_POST['harga'];
        
        // Mengelola upload gambar
        $target_dir = "../img/produk/";
        $gambar = $_FILES['gambar']['name'];
        $target_file = $target_dir . basename($gambar);

        // Periksa apakah gambar berhasil di-upload
        if ($_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                // Query untuk menambah data
                $query = "INSERT INTO tb_produk (nama_barang, deskripsi, harga, stok, gambar) 
                          VALUES ('$nama_barang', '$deskripsi', $harga, $stok, '$gambar')";

                if (mysqli_query($koneksi, $query)) {
                    echo "Sukses menambahkan data produk!";
                    // Redirect setelah berhasil menambah data
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "Error: " . mysqli_error($koneksi);
                }
            } else {
                echo "Gagal mengunggah gambar.";
            }
        } else {
            echo "Terjadi kesalahan saat mengupload gambar.";
        }
    } else {
        echo "Semua field harus diisi!";
    }
}

// Menangani pengiriman form untuk "Edit"
// buat perintah untuk mengedit disini!
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $kategori = $_POST['kategori'];
    $status = $_POST['status'];
    $harga = $_POST['harga'];

    $query = "UPDATE tb_layanan SET kategori='$kategori', status='$status', harga='$harga' WHERE id='$id'";


    if (mysqli_query($koneksi, $query)) {
        // Redirect setelah berhasil mengedit data
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
// Menangani pengiriman form untuk "Delete" 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
    // Validasi dan sanitasi input ID produk
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id > 0) {
        // Menyiapkan query untuk menghapus data berdasarkan id_barang
        $query = "DELETE FROM tb_layanan WHERE id = ?";

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
                echo "Gagal menghapus produk. Error: " . mysqli_stmt_error($stmt);
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
$result = mysqli_query($koneksi, "SELECT * FROM tb_layanan");
?>
 
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fika - Tables</title>
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
                <div class="sidebar-brand-text mx-3">Laundry <sup>Fika</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
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
            <li class="nav-item">
                <a class="nav-link" href="layanan.php"> 
                    <i class="fas fa-fw fa-laptop"></i>
                    <span>Layanan</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="transaksi.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Transaksi</span></a>
            </li>
            <li class="nav-item active">
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Fika Amaliya</span>
                                <img class="img-profile rounded-circle"
                                    src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Pengaturan
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Aktivitas
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
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="center">
                                            <th>No</th>
                                            <th>Layanan</th>
                                            <th >Nama Layanan</th>
                                            <th>Deskripsi</th>
                                            <th> Kategori </th>
                                            <th>Berat</th>
                                            <th> Status</th>
                                            <th>Harga</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead> 
                                    <tbody> 
                                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>                      <!--pemanggilan data nomor/id dari database-->
                                            <td><img src="../img/layanan/<?= $row['gambar']; ?>" alt="<?= $row['nama_layanan']; ?>" width="100"></td>
                                            <td><?= $row['nama_layanan']; ?></td>          <!--pemanggilan data nama barang dari database-->
                                            <td><?= $row['deskripsi']; ?></td>             <!--pemanggilan data dekripsi dari database-->
                                            <td><?= $row['kategori']; ?></td>            <!--pemanggilan data kategori dari database-->
                                            <td><?= $row['berat']; ?> Kg</td>       <!--pemanggilan data berat dari database-->
                                            <td><?= $row['status']; ?></td>        <!--pemanggilan data status dari database-->
                                            <td><?= $row['harga']; ?></td>            <!--pemanggilan data harga dari database-->
                                            

                                    <!-- buat pemanggilan data disini untuk mengisi tabel  -->
                                         
                                            <!--penambahan tombol pada aksi-->
                                            <td class="d-blox">
                                                 <!--Tombol Detail-->
                                                <button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#detailModal'>
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </button> 
                                                <!--Tombol Edit-->
                                                <button class="btn btn-warning btn-sm" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['kategori']; ?>', '<?php echo $row['status']; ?>', '<?php echo $row['harga']; ?>')">
                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                </button>
                                                <!--Tombol Hapus-->
                                                <a href='#' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick="setDeleteId(<?php echo $row['id']; ?>)">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </a>   
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

            <!-- Modal Tambah Data -->
             
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="addModalLabel">Tambah Data Laundry</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- pada from gunakan metod post -->
                         <!-- Setiap inputan tambahkan name yang sesuai dengan database
                          contoh:
                          name="nama" //sesuaikan dengan nama kolom pada tabel database -->
                    <form id="formTambah" method="POST" action="">
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar Layanan</label>
                        <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                          <label for="nama_layanan" class="form-label">Nama Layanan</label> 
                          <input type="text" class="form-control" name="nama_layanan" id="nama_layanan" required>  <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                          <label for="deskripsi" class="form-label">Deskripsi</label> 
                          <input type="text" class="form-control" name="deskripsi" id="deskripsi" required>  <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select id="kategori" name="kategori" class="form-control" > <!-- tambahkan name disini -->
                                <option value="Sudah">Cuci</option>
                                <option value="Belum">Setrika</option>
                                <option value="Sudah">Cuci Setrika</option>

                            </select>
                        <div class="mb-3">
                          <label for="berat" class="form-label">Berat</label> 
                          <input type="text" class="form-control" name="berat" id="berat" required>  <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                          <label for="status" class="form-label">Status</label>
                          <select class="form-control" name="status" id="status" required><!-- tambahkan name disini -->                       
                            <option>Sedia</option>
                            <option>Tidak Sedia</option>
                          </select>
                        <div class="mb-3">
                          <label for="harga" class="form-label">Harga</label>
                          <input type="number" class="form-control" name="harga" id="harga" required> <!-- tambahkan name disini -->
                        </div>
                    </form>
                    </div> 
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button> 
                        <!-- pastikan type dari button tambah adalah submit-->
                      <button type="submit" class="btn btn-primary" form="formTambah">Tambah</button>
                    </div>
                  </div>
                </div>
              </div> 
            <!-- Modal Tambah Data end -->

            <!-- Modal Edit --> 
                <!-- buat modal edit disini -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Data Layanan Laundry</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEdit" method="POST" action="">
                                <input type="hidden" id="edit_id" name="id">
                                <input type="hidden" name="action" value="edit">
                                <div class="mb-3">
                                    <label for="edit_kategori" class="form-label">Kategori</label>
                                    <select class="form-control" id="edit_kategori" name="kategori" required>
                                        <option value="Sudah">Cuci</option>
                                        <option value="Belum">Setrika</option>
                                        <option value="Belum">Cuci Setrika</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select class="form-control" id="edit_status" name="status" required>
                                        <option value="Baru">Sedia</option>
                                        <option value="Proses">Tidak Sedia</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" name="harga" id="edit_harga" required> <!-- tambahkan name disini -->
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Edit End -->
            <!-- Modal Konfirmasi Hapus -->
                <!-- buat modal hapus data disini -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus data ini?
                        </div>
                        <div class="modal-footer">
                            <!-- Modal Delete -->
                            <form method="POST">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" id="deleteId">
                                <!-- ID dari fungsi setDeleteId -->
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
                        <span>Copyright &copy; Laundry Fika</span>
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

    <script>
        // Event listener untuk menampilkan atau menyembunyikan input "Tanggal Dibayar"
        document.getElementById('dibayar').addEventListener('change', function() {
            const dibayar = this.value;
            const divTanggalDibayar = document.getElementById('divTanggalDibayar');

            // Jika dibayar "Sudah", tampilkan input Tanggal Dibayar
            if (dibayar === 'Sudah') {
                divTanggalDibayar.style.display = 'block';
            } else {
                // Jika dibayar "Belum", sembunyikan input Tanggal Dibayar dan kosongkan nilai
                divTanggalDibayar.style.display = 'none';
                document.getElementById('tanggalDibayar').value = '';
            }
        });

        let nomor = 1;

        function tambahData() {
            const NamaBarang = document.getElementById('gambar').value;
            const NamaBarang = document.getElementById('namaLayanan').value;
            const Deskripsi = document.getElementById('deksripsi').value;
            const Harga = document.getElementById('kategori').value;
            const berat = document.getElementById('berat').value;
            // const batasWaktu = document.getElementById('batasWaktu').value;
            // const dibayar = document.getElementById('dibayar').value;
            // let tanggalDibayar = document.getElementById('tanggalDibayar').value || '-'; // Default '-' jika kosong
            const stok = document.getElementById('status').value;
            const NamaBarang = document.getElementById('harga').value;


            // Jika dibayar "Belum", kosongkan tanggal dibayar
            if (dibayar === 'Belum') {
                tanggalDibayar = '-';
            }

            const tableBody = document.getElementById('tableBody');
            const newRow = `
            <tr>
                <td>${nomor}</td>
                <td>${gambar}</td>
                <td>${namaLayanan}</td>
                <td>${deskripsi}</td>
                <td>${kategori}</td>
                <td>${berat}</td>
                <td>${status}</td>
                <td>Rp ${harga}</td>
                <td>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal">Detail</button>
                <button class="btn btn-warning btn-sm">Edit</button>
                <button class="btn btn-danger btn-sm">Hapus</button>
                </td>
            </tr>
            `;

            tableBody.insertAdjacentHTML('beforeend', newRow);
            nomor++;

            // Reset form
            document.getElementById('formTambah').reset();
            // Sembunyikan input tanggal dibayar setelah reset form
            document.getElementById('divTanggalDibayar').style.display = 'none';

            // Tutup modal
            const addModal = new bootstrap.Modal(document.getElementById('addModal'));
            addModal.hide();
        }
    </script>

    <!-- script untuk menambah data -->
    <!-- tambahkan script untuk menambah data disini -->
        <script> 
        document.getElementById("dibayar").addEventListener("change",
         function () { 
            const tanggalDibayarDiv = 
        document.getElementById("divTanggalDibayar"); 
        tanggalDibayarDiv.style.display = this.value === "Sudah" ? 
        "block" : "none"; 
                    }); 
        </script> 

    <!-- script edit -->
    <!-- tambahkan script untuk mengedit disini -->
    <script> 
    function openEditModal(id, kategori, status, harga) { 
        // Mengisi nilai input dalam modal edit 
        document.getElementById('edit_id').value = id; 
        document.getElementById('edit_kategori').value = kategori; 
        document.getElementById('edit_status').value = status; 
        document.getElementById('edit_harga').value = harga; 
        // Menampilkan modal 
        const editModal = new 
    bootstrap.Modal(document.getElementById('editModal')); 
        editModal.show();
    } 
    </script>
    <script>
        // Fungsi untuk mengupdate data 
        function updateData() { 
            const form = document.getElementById('formEdit'); 
            const formData = new FormData(form); 
            // Kirim data menggunakan fetch API 
            fetch('', { // Menggunakan URL yang sama (file ini) untuk mengirim data 
            method: 'POST', 
            body: formData, 
            }) 
            .then(response => response.text()) 
            .then(data => { 
                console.log(data); // Tampilkan respon server 
                location.reload(); // Refresh halaman setelah data diperbarui }) 
            })
                .catch(error => { 
                    console.error('Error:', error); 
            }); 
    } 
    </script> 

    <!-- script untuk hapus data -->
    <!-- tambahkab script untuk menghapus data disini -->
    <script> 
    function setDeleteId(id) { 
    document.getElementById('deleteId').value = id; 
    } 
    </script> 

</body>

</html>