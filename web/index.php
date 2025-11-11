<?php
include_once(__DIR__ . '/../config/database.php');

// 🐾 Lấy danh sách sản phẩm nổi bật (mới nhất) kèm thông tin khuyến mãi
$sql_products = "
  SELECT p.*, c.name AS category_name, pr.discount AS promo_discount
  FROM products p
  LEFT JOIN categories c ON p.category_id = c.id
  LEFT JOIN promotions pr ON pr.product_id = p.id
  ORDER BY p.created_at DESC
  LIMIT 8
";
$stmt = $conn->query($sql_products);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🎁 Lấy danh sách khuyến mãi mới nhất
$sql_promotions = "
  SELECT * FROM promotions
  ORDER BY id DESC
  LIMIT 3
";
try {
  $promoStmt = $conn->query($sql_promotions);
  $promotions = $promoStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $promotions = []; // Nếu bảng không tồn tại hoặc lỗi
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🐾 PetShop | Cửa hàng thú cưng</title>
  <link rel="icon" type="image/png" href="../assets/logo.jpg">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .fade { opacity: 0; transition: opacity 0.8s ease-in-out; }
    .fade.active { opacity: 1; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

  <?php include_once(__DIR__ . '/includes/header.php'); ?>

  <!-- 🌈 Banner -->
  <section class="relative overflow-hidden">
    <div id="banner-slides" class="relative w-full h-[400px]">
      <div class="slide absolute inset-0 fade active">
        <img src="../assets/banner1.jpg" alt="Thức ăn cho thú cưng" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white text-center">
          <h1 class="text-4xl font-bold mb-2">Thức ăn cho thú cưng 🐶</h1>
          <p>Chất lượng, dinh dưỡng và an toàn cho boss yêu của bạn!</p>
        </div>
      </div>
      <div class="slide absolute inset-0 fade">
        <img src="../assets/banner2.jpg" alt="Phụ kiện thú cưng" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white text-center">
          <h1 class="text-4xl font-bold mb-2">Phụ kiện siêu dễ thương 😺</h1>
          <p>Vòng cổ, áo, đồ chơi và hơn thế nữa!</p>
        </div>
      </div>
      <div class="slide absolute inset-0 fade">
        <img src="../assets/banner3.jpg" alt="Dịch vụ chăm sóc" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white text-center">
          <h1 class="text-4xl font-bold mb-2">Dịch vụ chăm sóc toàn diện 🧴</h1>
          <p>Tắm, cắt tỉa, spa cho thú cưng của bạn.</p>
        </div>
      </div>
    </div>

    <button id="prev" class="absolute top-1/2 left-4 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-800 rounded-full p-3">❮</button>
    <button id="next" class="absolute top-1/2 right-4 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-800 rounded-full p-3">❯</button>
  </section>

  <!-- 🧸 Sản phẩm nổi bật -->
  <section class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4 text-blue-700">🌟 Sản phẩm nổi bật</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $p): ?>
          <?php 
            $hasPromo = !empty($p['promo_discount']);
            $discountPrice = $hasPromo ? $p['price'] * (1 - $p['promo_discount'] / 100) : $p['price'];
          ?>
          <div class="relative product-card bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
            <?php if ($hasPromo): ?>
              <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded animate-pulse">
                🎁 -<?= htmlspecialchars($p['promo_discount']) ?>%
              </div>
            <?php endif; ?>

            <img src="../uploads/<?= htmlspecialchars($p['image'] ?? 'noimage.jpg') ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-40 object-cover">

            <div class="p-4">
              <h3 class="name mb-1 truncate font-semibold"><?= htmlspecialchars($p['name']) ?></h3>
              <p class="text-gray-500 text-sm mb-2"><?= htmlspecialchars($p['category_name'] ?? 'Khác') ?></p>

              <?php if ($hasPromo): ?>
                <p class="text-gray-400 line-through text-sm"><?= number_format($p['price'], 0, ',', '.') ?>₫</p>
                <p class="text-red-600 font-bold"><?= number_format($discountPrice, 0, ',', '.') ?>₫</p>
              <?php else: ?>
                <p class="text-blue-600 font-bold"><?= number_format($p['price'], 0, ',', '.') ?>₫</p>
              <?php endif; ?>

              <a href="product_detail.php?id=<?= $p['id'] ?>" class="block text-center bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 mt-3">
                Xem chi tiết
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="col-span-full text-center text-gray-500 italic">Không tìm thấy sản phẩm nào.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- 🎁 Khuyến mãi -->
  <section class="bg-yellow-50 py-10">
    <div class="container mx-auto px-6">
      <h2 class="text-2xl font-bold text-yellow-700 mb-6 text-center">🎉 Ưu đãi & Khuyến mãi</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($promotions)): ?>
          <?php foreach ($promotions as $promo): ?>
            <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg">
              <h3 class="text-lg font-bold text-blue-600"><?= htmlspecialchars($promo['title'] ?? 'Khuyến mãi đặc biệt 🎁') ?></h3>
              <p class="text-gray-600 mt-2"><?= htmlspecialchars($promo['description'] ?? 'Ưu đãi hấp dẫn cho khách hàng PetShop!') ?></p>
              <p class="text-red-500 font-semibold mt-3">Giảm <?= htmlspecialchars($promo['discount'] ?? 10) ?>%</p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="col-span-full text-center text-gray-500 italic">Hiện chưa có khuyến mãi nào.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <?php include_once(__DIR__ . '/includes/footer.php'); ?>

  <script>
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;
    function showSlide(index) {
      slides.forEach((slide, i) => slide.classList.toggle('active', i === index));
    }
    document.getElementById('next').addEventListener('click', () => {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    });
    document.getElementById('prev').addEventListener('click', () => {
      currentSlide = (currentSlide - 1 + slides.length) % slides.length;
      showSlide(currentSlide);
    });
    setInterval(() => {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    }, 5000);
  </script>
</body>
</html>
