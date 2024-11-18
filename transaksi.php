<?php 
include 'koneksi/koneksi.php';// panggil dile koneksi.php disini
// Menangani pengiriman form untuk "Create"
// buat perintah untuk mengirimkan inputan disini!
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['status'], $_POST['kode_invoice'], $_POST['pelanggan'], 
              $_POST['tanggal'], $_POST['batas_waktu'], $_POST['tanggal_dibayar'], 
              $_POST['dibayar'], $_POST['total'])) {
        // Ambil data dari post
        $status = $_POST['status'];
        $kode_invoice = $_POST['kode_invoice'];
        $pelanggan = $_POST['pelanggan'];
        $tanggal = $_POST['tanggal'];
        $batas_waktu = $_POST['batas_waktu'];
        $tanggal_dibayar = $_POST['tanggal_dibayar'];
        $dibayar = $_POST['dibayar'];
        $total = $_POST['total'];
        // Query untuk menambah data
        $query = "INSERT INTO tb_transaksi (status, kode_invoice, pelanggan, tanggal, batas_waktu, tanggal_dibayar, dibayar, total)
                  VALUES ('$status', '$kode_invoice', '$pelanggan', '$tanggal', '$batas_waktu', '$tanggal_dibayar', '$dibayar', '$total')";
        if (mysqli_query($koneksi, $query)) {
            // Redirect setelah berhasil menambah data
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "Semua field harus diisi!";
    }
}

// Menangani pengiriman form untuk "Edit"
// buat perintah untuk mengedit disini!
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $tanggal_dibayar = $_POST['tanggal_dibayar'];
    $dibayar = $_POST['dibayar'];

    $query = "UPDATE tb_transaksi SET status='$status', tanggal_dibayar='$tanggal_dibayar', dibayar='$dibayar' WHERE id='$id'";

    if (mysqli_query($koneksi, $query)) {
        // Redirect setelah berhasil mengedit data
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
// Menangani pengiriman form untuk "Delete"
// buat perintah untuk mengirimkan menghapus data disini!
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM tb_transaksi WHERE id='$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }else {
            echo "Error: " . mysqli_error($koneksi);     
    }
}
// Mengambil data untuk "Read"
// buat perintah untuk mengambil data disini 
$result = mysqli_query($koneksi, "SELECT * FROM tb_transaksi");
?>
 
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fika - Transaksi</title>
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Transaksi -->
            <li class="nav-item active">
                <a class="nav-link" href="transaksi.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Transaksi</span></a>
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
                                    src="img/undraw_profile.svg">
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
                                            <th >Nama Pelanggan</th>
                                            <th>Status</th>
                                            <th>Kode Invoice</th>
                                            <th>Tanggal</th>
                                            <th>Batas Waktu</th>
                                            <th>Tanggal Dibayar</th>
                                            <th>Dibayar</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead> 
                                    <tbody> 
                                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>                      <!--pemanggilan data nomor/id dari database-->
                                            <td><?= $row['pelanggan']; ?></td>          <!--pemanggilan data nama pelanggan dari database-->
                                            <td><?= $row['status']; ?></td>             <!--pemanggilan data status dari database-->
                                            <td><?= $row['tanggal']; ?></td>            <!--pemanggilan data kode_invoice dari database-->
                                            <td><?= $row['kode_invoice']; ?></td>       <!--pemanggilan data tanggal dari database-->
                                            <td><?= $row['batas_waktu']; ?></td>        <!--pemanggilan data batas_waktu dari database-->
                                            <td><?= $row['tanggal_dibayar']; ?></td>    <!--pemanggilan data tanggal dibayar dari database-->
                                            <td><?= $row['dibayar']; ?></td>            <!--pemanggilan data status pembayaran dari database-->
                                            <td><?= $row['total']; ?></td>              <!--pemanggilan data total dari database-->
                                        

                                    <!-- buat pemanggilan data disini untuk mengisi tabel  -->
                                         
                                            <!--penambahan tombol pada aksi-->
                                            <td class="d-blox">
                                                 <!--Tombol Detail-->
                                                <button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#detailModal'>
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </button> 
                                                <!--Tombol Edit-->
                                                <button class="btn btn-warning btn-sm" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['status']; ?>', '<?php echo $row['tanggal_dibayar']; ?>', '<?php echo $row['dibayar']; ?>')">
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
                          <label for="pelanggan" class="form-label">Pelanggan</label> 
                          <input type="text" class="form-control" name="pelanggan" id="pelanggan" required>  <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                          <label for="status" class="form-label">Status</label>
                          <select class="form-control" name="status" id="status" required><!-- tambahkan name disini -->                       
                            <option>Baru</option>
                            <option>Proses</option>
                            <option>Selesai</option>
                            <option>Diambil</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label for="kodeInvoice" class="form-label">Kode Invoice</label>
                          <input type="datetime-local" class="form-control" name="kode_invoice" id="kodeInvoice" required><!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                          <label for="tanggal" class="form-label">Tanggal</label>
                          <input type="datetime-local" class="form-control" name="tanggal" id="tanggal" required> <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                          <label for="batasWaktu" class="form-label">Batas Waktu</label>
                          <input type="datetime-local" class="form-control" name="batas waktu" id="batasWaktu" required> <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                            <label for="dibayar" class="form-label">Dibayar</label>
                            <select id="dibayar" name="dibayar" class="form-control" > <!-- tambahkan name disini -->
                                <option value="Sudah">Sudah</option>
                                <option value="Belum">Belum</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="divTanggalDibayar" style="display: none;">
                            <label for="tanggalDibayar" class="form-label">Tanggal Dibayar</label>
                            <input type="datetime-local" class="form-control"  name="tanggal dibayar" id="tanggalDibayar" > <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                          <label for="total" class="form-label">Total</label>
                          <input type="number" class="form-control"  name="total" id="total" required> <!-- tambahkan name disini -->
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
                            <h5 class="modal-title" id="editModalLabel">Edit Data Transaksi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEdit" method="POST" action="">
                                <input type="hidden" id="edit_id" name="id">
                                <input type="hidden" name="action" value="edit">
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select class="form-control" id="edit_status" name="status" required>
                                        <option value="Baru">Baru</option>
                                        <option value="Proses">Proses</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Diambil">Diambil</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_tanggal_dibayar" class="form-label">Tanggal Dibayar</label>
                                    <input type="datetime-local" class="form-control" id="edit_tanggal_dibayar" name="tanggal_dibayar">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_dibayar" class="form-label">Dibayar</label>
                                    <select class="form-control" id="edit_dibayar" name="dibayar" required>
                                        <option value="Sudah">Sudah</option>
                                        <option value="Belum">Belum</option>
                                    </select>
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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    
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
            const status = document.getElementById('status').value;
            const kodeInvoice = document.getElementById('kodeInvoice').value;
            const pelanggan = document.getElementById('pelanggan').value;
            const tanggal = document.getElementById('tanggal').value;
            const batasWaktu = document.getElementById('batasWaktu').value;
            const dibayar = document.getElementById('dibayar').value;
            let tanggalDibayar = document.getElementById('tanggalDibayar').value || '-'; // Default '-' jika kosong
            const total = document.getElementById('total').value;

            // Jika dibayar "Belum", kosongkan tanggal dibayar
            if (dibayar === 'Belum') {
                tanggalDibayar = '-';
            }

            const tableBody = document.getElementById('tableBody');
            const newRow = `
            <tr>
                <td>${nomor}</td>
                <td>${pelanggan}</td>
                <td>${status}</td>
                <td>${kodeInvoice}</td>
                <td>${tanggal}</td>
                <td>${batasWaktu}</td>
                <td>${tanggalDibayar}</td>
                <td>${dibayar}</td>
                <td>Rp ${total}</td>
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

    <!-- script edit -->
    <!-- tambahkan script untuk mengedit disini -->

    <!-- script untuk hapus data -->
    <!-- tambahkab script untuk menghapus data disini -->


</body>

</html>