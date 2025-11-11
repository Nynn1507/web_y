<?php
// Bắt đầu session (nếu chưa có)
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Tính tổng số sản phẩm trong giỏ
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    $cartCount += $item['qty'];
  }
}
?>

<header class="bg-white shadow sticky top-0 z-50">
  <div class="container mx-auto flex items-center justify-between p-4">

    <!-- 🐾 Logo bên trái -->
    <div class="flex items-center space-x-2">
      <a href="index.php" class="flex items-center space-x-2 group">
        <img src="../assets/logo.jpg" alt="PetShop Logo"
             class="w-12 h-12 rounded-full border border-gray-200 group-hover:scale-105 transition">
        <span class="text-2xl font-bold text-blue-600 group-hover:text-blue-700 transition">
          PetShop
        </span>
      </a>
    </div>

    <!-- 🧭 Menu giữa -->
    <nav class="flex items-center space-x-6 text-base font-medium">
      <a href="index.php" class="hover:text-blue-600">🏠 Trang chủ</a>
      <a href="products.php" class="hover:text-blue-600">🐶 Sản phẩm</a>
      
      <a href="about.php" class="hover:text-blue-600">📞 Liên hệ</a>
    </nav>

    <!-- 🛒 Giỏ hàng + 👤 Đăng nhập bên phải -->
    <div class="flex items-center space-x-4">
      <!-- Giỏ hàng -->
      <a href="cart.php" class="relative text-2xl group">
        🛒
        <?php if ($cartCount > 0): ?>
          <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs px-1.5 rounded-full group-hover:scale-110 transition">
            <?= $cartCount ?>
          </span>
        <?php endif; ?>
      </a>

      <!-- Đăng nhập -->
      <a href="login.php" class="text-gray-700 hover:text-blue-600 text-base">
        👤 Đăng nhập
      </a>
    </div>

  </div>
</header>
