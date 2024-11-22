<?php
// Dummy Data
$brands = ['Acer', 'Alienware', 'Asus', 'Dell', 'HP', 'Lenovo'];
$products = [
    [
        'name' => 'Acer Predator Helios 300 PH315-52',
        'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROSh9x2SASk6bF-TcrJWb_c4lhCJTutgiYYw&s',
        'price' => 99999,
        'brand' => 'Acer'
    ],
    [
        'name' => 'Acer Aspire 3 A315-24P',
        'image' => 'https://www.tmt.my/data/editor/sc-product/18946/100000015000NBB-ACRA31524PR4RQ.png',
        'price' => 31349,
        'brand' => 'Acer'
    ],
    [
        'name' => 'Alienware M15 R6',
        'image' => 'https://via.placeholder.com/300x200?text=Alienware+M15',
        'price' => 145999,
        'brand' => 'Alienware'
    ],
    [
        'name' => 'Asus ROG Zephyrus G14',
        'image' => 'https://via.placeholder.com/300x200?text=Asus+ROG',
        'price' => 129999,
        'brand' => 'Asus'
    ],
];

// Filter products by brand
$selectedBrand = isset($_GET['brand']) ? $_GET['brand'] : 'All';
$filteredProducts = $selectedBrand === 'All' ? $products : array_filter($products, function ($product) use ($selectedBrand) {
    return $product['brand'] === $selectedBrand;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Showcase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Pastikan gambar dalam kartu memiliki ukuran seragam */
        .card-img-top {
            height: 80px; /* Tetapkan tinggi gambar */
            object-fit: cover; /* Sesuaikan ukuran gambar tanpa distorsi */
        }

        /* Pastikan semua kartu memiliki tinggi yang sama */
        .card {
            min-height: 300px; /* Sesuaikan dengan konten maksimum kartu */
        }

        /* Agar bagian kartu tampil konsisten */
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-img-top {
            height: 190px; /* Tetapkan tinggi gambar */
            width: 100%; /* Gambar mengisi lebar kartu */
            object-fit: cover; /* Memotong gambar agar proporsional */
        }

        /* Pastikan semua kartu memiliki ukuran yang seragam */
        .card {
            min-height: 400px; /* Tinggi minimal kartu */
        }

        /* Atur tata letak dalam kartu */
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .navbar {
        border-radius: 20px; /* Sudut melengkung */
        width: 90%; /* Kurangi lebar navbar */
        margin: 20px auto 0; /* Menambahkan margin atas untuk menurunkan navbar */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Bayangan navbar */
        display: flex;
        justify-content: space-between; /* Memisahkan kiri dan kanan */
        align-items: center; /* Menjaga item navbar sejajar secara vertikal */
    }

    .navbar .search-container {
        position: absolute; /* Posisi absolute untuk menjaga search tetap di tengah */
        left: 50%;
        transform: translateX(-50%); /* Menggeser ke kiri untuk menyelaraskan */
        width: 50%; /* Atur lebar sesuai keinginan */
        display: flex;
        justify-content: center;
    }

    /* Menggeser tulisan Laptop Store Medan lebih ke kanan */
    .navbar-brand {
        margin-left: 20px; /* Geser tulisan ke kanan */
    }

    .navbar-nav.ms-auto {
        margin-right: 10; /* Menghapus margin kanan untuk navbar-nav */
    }

    .navbar-nav .nav-item {
        margin-right: 20px; /* Memberikan jarak antar item navbar */
    }

    .navbar-nav .nav-link {
        padding-right: 15px; /* Memberikan sedikit padding ke kanan */
    }
    
    
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#">Laptop Store Medan</a>
            
            <!-- Kolom pencarian tetap di tengah -->
            <div class="search-container">
                <form class="form-inline my-2 my-lg-0 d-flex justify-content-center w-100">
                    <input class="form-control rounded-pill" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success rounded-pill ms-2" type="submit">Search</button>
                </form>
            </div>
            
            <!-- Menu navigasi di kanan -->
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                <li class="dropdown show">
                    <a class="btn bg-transparent border-0 text-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Kategori
                      </a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="#">Laptop</a>
                        <a class="dropdown-item" href="#">Aksesoris</a>
                        
                      </div>
                </li>
                
                <!-- Profile dropdown -->
                <div class="dropdown show">
                    <a class="btn bg-transparent border-0 text-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Profile
                    </a>
                  
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                      <a class="dropdown-item" href="/auth">Masuk</a>
                      <a class="dropdown-item" href="/auth/register">Daftar</a>
                      
                    </div>
                  </div>
            </ul>
        </div>
    </nav>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="margin-top: 50px">
        <ol class="carousel-indicators">
          <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="d-block" src="https://i.pinimg.com/736x/c4/60/39/c4603933f5d17df6fee7f74ee14c523a.jpg" alt="First slide" style="width: 85%; height: 700px; margin: 0 auto; border-radius: 20px">

          </div>
          <div class="carousel-item">
            <img class="d-block" src="https://i.pinimg.com/736x/c4/60/39/c4603933f5d17df6fee7f74ee14c523a.jpg" alt="Second slide" style="width: 85%; height: 700px; margin: 0 auto; border-radius: 20px">
          </div>
          <div class="carousel-item">
            <img class="d-block" src="https://i.pinimg.com/736x/c4/60/39/c4603933f5d17df6fee7f74ee14c523a.jpg" alt="Third slide" style="width: 85%; height: 700px; margin: 0 auto; border-radius: 20px">
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    {{-- <ul class="nav justify-content-center">
        <li class="nav-item">
          <a class="nav-link active" href="#">Laptop</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Dekstop</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Printer</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Akesesoris</a>
        </li>
      </ul> --}}
      <div class="container mt-4">
        <div class="row">
            <!-- Main Content -->
            <div class="container mt-4">
                <div class="row">
                    <!-- Main Content -->
                    <div class="col-md-12">
                        <h4>Products</h4>
                        <div class="row">
                            <?php if (empty($filteredProducts)): ?>
                                <p class="text-center">No products found for this brand.</p>
                            <?php else: ?>
                                <?php foreach ($filteredProducts as $product): ?>
                                    <div class="col-md-2 mb-2"> <!-- Ukuran kolom untuk 4 kartu per baris -->
                                        <div class="card">
                                            <img src="<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $product['name'] ?></h5>
                                                <p class="card-text">
                                                    <strong>Price:</strong> $<?= number_format($product['price'], 2) ?><br>
                                                    <strong>Brand:</strong> <?= $product['brand'] ?>
                                                </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
