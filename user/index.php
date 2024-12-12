<?php
// Buat pemanggilan database disini
// Koneksi ke database
// include '../koneksi/koneksi.php';
include '../koneksi/koneksi.php';

// Query untuk mengambil data layanan dari tb_paket
$sql_paket = "SELECT * FROM tb_paket";
$result_paket = $koneksi->query($sql_paket);

// Query untuk mengambil data komentar dari tb_komentar
$sql_komentar = "SELECT * FROM tb_komentar ORDER BY tanggal DESC";
$result_komentar = $koneksi->query($sql_komentar);

// Query untuk mengambil data produk dari tb_produk
$sql_produk = "SELECT * FROM tb_produk";
$result_produk = $koneksi->query($sql_produk);

// Menangani form komentar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    $komentar = $_POST['komentar'];
    $rating = $_POST['rating'];

    // Menggunakan prepared statements untuk menghindari SQL injection
    $stmt = $koneksi->prepare("INSERT INTO tb_komentar (komentar, rating) VALUES (?, ?)");
    $stmt->bind_param("si", $komentar, $rating); // 's' untuk string, 'i' untuk integer
    if ($stmt->execute()) {
        echo "<script>alert('Komentar berhasil ditambahkan!');</script>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Laundry Fika</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Script JS untuk Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
 
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="js/script.js">
    <style>
        .product-card {
    width: 200px;
    height: 350px;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    }

    .product-card:hover {
        transform: scale(1.05);
    }

    .product-card img {
        width: 100px;
        height: 100px;
        border-radius: 8px;
    }

    .product-card h3 {
        font-size: 1.2em;
        margin: 10px 0;
    }

    .product-card p {
        font-size: 0.9em;
        color: #555;
    }

    .product-card a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
        display: block;
        margin-top: 10px;
    }

    .product-card a:hover {
        text-decoration: underline;
    }

    .whatsapp-link {
        text-decoration: none;
        color: #25d366; /* Warna hijau WhatsApp */
        font-weight: bold;
        display: inline-block;
        margin-top: 10px;
        padding: 8px;
        border-radius: 5px;
        background-color: #ffffff;
        border: 1px solid #25d366;
        transition: background-color 0.3s ease;
    }

    .whatsapp-link:hover {
        background-color: #25d366;
        color: #ffffff;
    }

    .whatsapp-link i {
        margin-right: 8px;
        font-size: 18px;
    }

/* Styling Footer */
    </style>
    
</head>
<body>

    <!-- Header Section -->
    <header>
        <img src="../img/logo.png" alt="Benings Laundry logo">
        <nav>
            <a href="#Hero">Beranda</a>
            <a href="#services">Layanan</a>
            <a href="#products">Produk</a>
            <a href="#order-section">Pemesanan</a>
            <a href="#comment-section">Testimoni</a>
        </nav>
        <a href="#" class="login-btn">Masuk</a>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="Hero">
        <div class="hero-text">
            <h1>Laundry Fika</h1>
            <p>Anda sibuk? Cucian numpuk? Percayakan kepada kami, Cucian anda pasti bersih, rapi dan nyaman saat beraktifitas.</p>
            <button class="btn btn-primary">Pesan Sekarang!</button>
            <button class="btn btn-secondary">Testimoni</button>
        </div>
        <img src="../img/signboard1.png" alt="Benings Laundry signboard">
    </section>

    <!-- Section Layanan -->
    <section class="services" id="services">
        <h2>Layanan Laundry Fika</h2>
        <p>Kami Memberikan Pelayanan Terbaik</p>
        <div class="service-cards">
            <?php
            // Menampilkan layanan dari tb_paket
            if ($result_paket->num_rows > 0) {
                while($row = $result_paket->fetch_assoc()) {
                    echo '<div class="service-card">';
                    echo '<img src="../img/paket/" alt="' . $row['nama_paket'] . ' icon">';
                    echo '<h3>' . strtoupper($row['nama_paket']) . '</h3>';
                    echo '<p>' . $row['jenis'] . '</p>'; // Menampilkan jenis layanan
                    echo '<a href="#">Selengkapnya</a>';
                    echo '</div>';
                }
            } else {
                echo 'Tidak ada layanan yang tersedia.';
            }
            ?>
        </div>
    </section>

    <!-- Order Section -->
    <section class="order-section" id="order-section">
        <img src="../img/ayopesan.png" alt="Laundry basket with clean clothes">
        <div class="order-text">
            <h2>Solusi Tepat Untuk Pakaian Bersih Anda</h2>
            <p>Kami menghadirkan Laundry Express kami untuk memberikan Anda pengalaman laundry yang cepat dan tepat. Dalam hitungan jam, pakaian Anda akan bersih, rapi, dan siap dipakai.</p>
            <button class="btn btn-primary">Pesan Sekarang!</button>
        </div>
    </section>
  
    <!-- Section Produk -->
    <!-- tambahkan kode untuk memanggil produk di sini pastikan sesuai dengan database anda -->
<section class="products" id="products">
    <h2>Produk Laundry Fika</h2>
    <p>Kami Menyediakan Berbagai Produk Berkualitas</p>
    <div class="product-cards">
        <?php
        // Query untuk mengambil data produk dari tb_produk
        $sql_produk = "SELECT * FROM tb_produk";
        $result_produk = $koneksi->query($sql_produk);

        // Menampilkan produk dari tb_produk
        if ($result_produk->num_rows > 0) {
            while ($row = $result_produk->fetch_assoc()) {
                echo "<div class='product-card'>";
                echo "<img src='../img/produk/" . $row['gambar'] . "' alt='" . $row['nama_barang'] . "'>";
                echo "<h3>" . strtoupper($row['nama_barang']) . "</h3>";
                echo "<p>" . $row['deskripsi'] . "</p>"; // Menampilkan deskripsi produk
                echo "<p>Harga: Rp " . number_format($row['harga'], 0, ',', '.') . "</p>";
                
                // Menambahkan link Pesan Sekarang dengan icon WhatsApp
                echo "<a href='https://wa.me/yourwhatsappnumber?text=Saya%20tertarik%20dengan%20produk%20" 
                     . urlencode($row['nama_barang']) . "' target='_blank' class='whatsapp-link'>";
                echo "<i class='fab fa-whatsapp'></i> Pesan Sekarang";
                echo "</a>";

                echo "</div>";
            }
        } else {
            echo "Tidak ada produk yang tersedia.";
        }
        ?>
    </div>
</section>


    <!-- Section Komentar --> 
    <!-- buat section untuk menambahkan komentar disini -->
<section class="comment-section" id="comment-section">
    <!-- Formulir Komentar -->
    <div class="form-container">
        <form action="" method="POST">
            <label for="komentar">Komentar:</label>
            <textarea id="komentar" name="komentar" rows="4" required></textarea>

            <label for="rating">Rating:</label>
            <select id="rating" name="rating" required>
                <option value="1">1 - Buruk</option>
                <option value="2">2 - Cukup</option>
                <option value="3">3 - Baik</option>
                <option value="4">4 - Sangat Baik</option>
                <option value="5">5 - Luar Biasa</option>
            </select>

            <button type="submit" name="submit_comment" class="btn btn-primary">Kirim Komentar</button>
        </form>
    </div>
</section>

    <!-- Daftar Komentar -->
    <!-- buat pemanggilan komentar disini -->
<div class="comment-list-container">
    <div class="comment-list">
        <?php
        // Periksa apakah ada komentar
        if ($result_komentar->num_rows > 0) {
            while ($row = $result_komentar->fetch_assoc()) {
                echo "<div class='comment-item'>
                    <div class='customer-info' style='display:flex;'>
                        <img src='../img/user.png' alt='Customer photo' style='width:10%;'>
                        <h3 style='margin-left:1em;'>Unknown</h3>
                    </div>
                    <p><strong>Rating:</strong> " . $row['rating'] . " / 5</p>
                    <p><strong>Komentar:</strong> " . htmlspecialchars($row['komentar']) . "</p>
                    <p><small>Ditambahkan pada: " . $row['tanggal'] . "</small></p>
                </div>";
            }
        } else {
            echo "<p>Belum ada komentar.</p>";
        }
        ?>
    </div>
</div>


    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-column">
                <h3>Laundry Fika</h3>
                <p>Memberikan layanan laundry terbaik dengan proses cepat, kualitas terbaik, dan harga yang terjangkau.</p>
            </div>
            <div class="footer-column">
                <h3>Menu</h3>
                <ul>
                    <li><a href="#">Beranda</a></li>
                    <li><a href="#">Layanan</a></li>
                    <li><a href="#">Pemesanan</a></li>
                    <li><a href="#">Kontak Kami</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Kontak Kami</h3>
                <p>Jl. Raya Laundry No. 1, Putatsari Grobogan</p>
                <p>Telepon: (0821) 0071-69189</p>
                <p>Email: laundryfika26@gmail.com</p>
            </div>
            <div class="footer-column">
                <h3>Ikuti Kami di media sosial</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2024  Laundry Fika- Fresh & Clean Laundry</p>
        </div>
    </footer>

 
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.testimonial-slider', {
            slidesPerView: 'auto',  // Menyesuaikan jumlah slide berdasarkan ukuran layar
            spaceBetween: 20,       // Memberikan jarak antar slide
            loop: true,             // Mengaktifkan loop slide
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            }
        });
    </script>

</body>
</html>
