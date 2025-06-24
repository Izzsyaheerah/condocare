<?php
session_start();
// include 'database.php';

// Semak jika pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ReWear: Utama</title>

   <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #fff;
    margin: 0; padding: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  
  /* Hero Section */
  .hero-section {
    background-image: url('dashboard1.jpg');
    background-size: cover;
    background-position: bottom center; /* Fokus bahagian atas tengah */
    background-repeat: no-repeat;
    position: relative;
    color: #fff;
    padding: 100px 20px;
    text-align: center;
  }

  .hero-section h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 15px;
  }

  .hero-section p {
    font-size: 1.25rem;
    margin-bottom: 30px;
    font-weight: 400;
  }

  .btn-hero {
    background-color: #111;
    color: #fff;
    border: none;
    padding: 15px 50px;
    border-radius: 25px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-weight: 600;
    text-transform: uppercase;
  }

  .btn-hero:hover {
    background-color: #333;
  }

  /* Subheading Section */
  .subheading-section {
    max-width: 1100px;
    margin: 30px auto 0 auto;
    padding: 0 20px;
  }
  .subheading-section h2 {
    color: #3c7962;
    font-weight: 700;
    font-size: 1.8rem;
  }

  /* Footer */
  footer {
    background-color: #3c7962;
    color: #fff;
    text-align: center;
    padding: 15px 0;
    font-weight: 500;
  }

  @media (max-width: 768px) {
    .hero-section h1 {
      font-size: 2.8rem;
    }
    .hero-section p {
      font-size: 1rem;
    }
    
  }
  
  /* Icon containers */
  .icon-col {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  /* Icon images */
  .icon-col img {
    width: 60px;
    height: auto;
    margin-bottom: 12px; /* jarak antara ikon dan teks */
  }

  /* Icon labels */
  .icon-col p {
    margin: 0;
    color: #3c7962;
    font-weight: 600;
    font-size: 1rem;
    text-align: center;
  }
/* Button hover effect */
.btn-hero {
  transition: transform 0.3s ease, background-color 0.3s ease;
}

.btn-hero:hover {
  transform: scale(1.05);
  background-color: #222;
}
.icon-col {
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer; /* optional, nampak clickable */
  /* warna dan background ikut asal */
}

.icon-col:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
.icon-info-section .icon-box {
  background-color: #fff;
  border: 1px solid #e6e6e6;
  border-radius: 12px;
  transition: all 0.3s ease;
  height: 100%;
  padding: 25px 20px;
  text-align: center;
  color: #333;
}


.icon-info-section .icon-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.icon-info-section .icon-img {
  width: 60px;
  height: 60px;
  object-fit: contain;
  border-radius: 50%;
  background-color: #fff;
  padding: 10px;
  border: 2px solid #ccc;
  transition: all 0.3s ease;
}

.hero-slide {
  height: 65vh;
  background-size: cover; /* atau contain jika nak elak potong */
  background-position: bottom center;
  background-repeat: no-repeat;
  position: relative;
}


.carousel-caption {
  bottom: 30%;
}

.carousel-caption h1 {
  font-size: 3.2rem;
  font-weight: 700;
  text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
}

.carousel-caption p {
  font-size: 1.2rem;
  font-weight: 400;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.carousel .btn-hero {
  margin-top: 20px;
}
.icon-info-section .icon-box:hover {
  background-color: #3c7962; /* hijau ReWear */
  color: #fff;
  border-color: #3c7962;
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.icon-info-section .icon-box:hover h5,
.icon-info-section .icon-box:hover p {
  color: #fff;
}

.icon-info-section .icon-box:hover .icon-img {
  border-color: #fff;
  background-color: #fff;
  
}
.carousel-control-prev,
.carousel-control-next {
  width: 8%; /* kawal lebar klik kawasan */
  top: 50%;
  transform: translateY(-50%);
  height: 100%;
  z-index: 10;
}

.carousel-inner {
  position: relative;
  overflow: hidden; /* penting: limit kandungan ke dalam gambar */
  border-radius: 8px; /* optional: rounded edge */
}

.carousel .carousel-control-prev-icon,
.carousel .carousel-control-next-icon {
  background-color: rgba(0, 0, 0, 0.5); 
  border-radius: 50%;
  width: 40px;
  height: 40px;
  background-size: 60% 60%;
  background-position: center;
  background-repeat: no-repeat;
}


</style>
</head>
<body>

<!-- Navbar -->
 <?php include 'sidebar.php'; ?>

<!-- Hero Carousel Section -->
<div id="rewearCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000" data-bs-touch="false" data-bs-pause="hover">

  <div class="carousel-inner">
<div class="carousel-item active">
  <div class="hero-slide" style="background-image: url('dashboard1.jpg');">
    <div class="carousel-caption d-none d-md-block">
      <h1>Selamat Datang ke ReWear</h1>
      <p>ReWear ialah platform komuniti warga UKM untuk memberikan atau menukar pakaian terpakai secara mudah, mesra alam, dan percuma.</p>
      <button class="btn-hero" onclick="window.location.href='katalog.php'">Lihat Katalog</button>
    </div>
  </div>
</div>

    <div class="carousel-item">
      <div class="hero-slide" style="background-image: url('image1.jpg');">
        <div class="carousel-caption d-none d-md-block">
          <h1>DERMA DENGAN MUDAH</h1>
          <p>Setiap pakaian anda boleh memberi makna baru buat orang lain.</p>
          <button class="btn-hero" onclick="window.location.href='katalog.php'">Lihat Katalog</button>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="hero-slide" style="background-image: url('image2.jpg');">
        <div class="carousel-caption d-none d-md-block">
          <h1>PERTUKARAN BIJAK</h1>
          <p>Tukar pakaian terpakai dan jimatkan belanja dengan ReWear.</p>
          <button class="btn-hero" onclick="window.location.href='katalog.php'">Lihat Katalog</button>
        </div>
      </div>
    </div>
    <!-- Butang Kiri -->
<button class="carousel-control-prev" type="button" data-bs-target="#rewearCarousel" data-bs-slide="prev">
  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  <span class="visually-hidden">Sebelum</span>
</button>

<!-- Butang Kanan -->
<button class="carousel-control-next" type="button" data-bs-target="#rewearCarousel" data-bs-slide="next">
  <span class="carousel-control-next-icon" aria-hidden="true"></span>
  <span class="visually-hidden">Seterusnya</span>
</button>
  </div>
</div>


<section class="icon-info-section py-5">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-3 mb-4">
        <div class="icon-box px-3 py-4 h-100">
          <img src="icon1.png" alt="Pakaian Terpakai" class="icon-img mb-3">
          <h5 class="fw-bold">Pakaian Terpakai</h5>
          <p>Derma atau tukar pakaian yang masih elok dan bersih kepada rakan-rakan UKM.</p>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="icon-box px-3 py-4 h-100">
          <img src="icon2.png" alt="UKM, Bangi" class="icon-img mb-3">
          <h5 class="fw-bold">UKM, Bangi</h5>
          <p>Platform ini khas untuk komuniti UKM bagi memudahkan pertukaran secara fizikal.</p>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="icon-box px-3 py-4 h-100">
          <img src="icon3.png" alt="Pertukaran Mudah" class="icon-img mb-3">
          <h5 class="fw-bold">Pertukaran Mudah</h5>
          <p>Hanya beberapa klik untuk tukar pakaian secara selamat dan pantas.</p>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="icon-box px-3 py-4 h-100">
          <img src="icon4.png" alt="Pemberian Pakaian" class="icon-img mb-3">
          <h5 class="fw-bold">Pemberian Pakaian</h5>
          <p>Beri pakaian secara percuma kepada yang memerlukan dalam komuniti anda.</p>
        </div>
      </div>
    </div>
  </div>
</section>



<!-- Footer -->
<footer>
  &copy; 2025 ReWear. Semua Hak Cipta Terpelihara.
</footer>

</body>
</html>
