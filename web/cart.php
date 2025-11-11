<?php
session_start();
include_once(__DIR__ . '/../config/database.php');
include_once(__DIR__ . '/includes/header.php');

// Nếu chưa có giỏ hàng trong session, tạo mới
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Xử lý thêm sản phẩm vào giỏ hàng
if (isset($_GET['add'])) {
  $id = intval($_GET['add']);
  $qty = $_GET['qty'] ? intval($_GET['qty']) : 1;
  
  // Kiểm tra sản phẩm có tồn tại không
  $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = :id");
  $stmt->execute([':id' => $id]);
  $product = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if ($product) {
    if (isset($_SESSION['cart'][$id])) {
      $_SESSION['cart'][$id]['qty'] += $qty;
    } else {
      $_SESSION['cart'][$id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'qty' => $qty
      ];
    }
  }
  header("Location: cart.php");
  exit;
}

// Xử lý xóa sản phẩm
if (isset($_GET['remove'])) {
  $id = intval($_GET['remove']);
  unset($_SESSION['cart'][$id]);
  header("Location: cart.php");
  exit;
}

// Xử lý cập nhật số lượng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
  foreach ($_POST['qty'] as $id => $qty) {
    $_SESSION['cart'][$id]['qty'] = max(1, intval($qty));
  }
  header("Location: cart.php");
  exit;
}

// Tính tổng tiền
$total = 0;
foreach ($_SESSION['cart'] as $item) {
  $total += $item['price'] * $item['qty'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🛒 Giỏ hàng | PetShop</title>
  <link rel="icon" type="image/png" href="../assets/logo.jpg">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-50 text-gray-800">

<!-- Tiêu đề -->
<section class="text-center py-10 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
  <h1 class="text-3xl font-bold">🛍️ Giỏ hàng của bạn</h1>
  <p>Kiểm tra lại sản phẩm trước khi thanh toán</p>
</section>

<main class="container mx-auto p-6">
  <?php if (empty($_SESSION['cart'])): ?>
    <div class="text-center text-gray-500 py-20">
      <p>🛒 Giỏ hàng của bạn đang trống.</p>
      <a href="index.php" class="mt-4 inline-block bg-blue-500 text-white px-5 py-3 rounded-lg hover:bg-blue-600">Tiếp tục mua hàng</a>
    </div>
  <?php else: ?>
  <form method="post">
    <div class="overflow-x-auto bg-white shadow rounded-lg">
      <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-gray-600">
          <tr>
            <th class="p-3 text-left">Sản phẩm</th>
            <th class="p-3 text-center">Giá</th>
            <th class="p-3 text-center">Số lượng</th>
            <th class="p-3 text-center">Thành tiền</th>
            <th class="p-3 text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($_SESSION['cart'] as $id => $item): ?>
          <tr class="border-t hover:bg-gray-50">
            <td class="flex items-center gap-3 p-3">
              <img src="../uploads/<?= htmlspecialchars($item['image'] ?? 'noimage.jpg') ?>" class="w-16 h-16 object-cover rounded-lg">
              <span class="font-medium"><?= htmlspecialchars($item['name']) ?></span>
            </td>
            <td class="text-center text-red-500 font-semibold"><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
            <td class="text-center">
              <input type="number" name="qty[<?= $id ?>]" value="<?= $item['qty'] ?>" min="1" class="w-16 border rounded text-center">
            </td>
            <td class="text-center font-bold"><?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?>₫</td>
            <td class="text-center">
              <a href="?remove=<?= $id ?>" class="text-red-500 hover:underline">🗑️ Xóa</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Tổng cộng -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-6 shadow mt-6 rounded-lg">
      <div class="text-xl font-semibold text-gray-700">
        Tổng tiền: <span class="text-red-500"><?= number_format($total, 0, ',', '.') ?>₫</span>
      </div>
      <div class="flex gap-3 mt-4 md:mt-0">
        <button type="submit" name="update_cart" class="bg-gray-200 px-5 py-2 rounded-lg hover:bg-gray-300">
          🔄 Cập nhật giỏ hàng
        </button>
        <a href="index.php" class="bg-blue-500 text-white px-5 py-2 rounded-lg hover:bg-blue-600">
          🛍️ Tiếp tục mua hàng
        </a>
        <a href="checkout.php" class="bg-green-500 text-white px-5 py-2 rounded-lg hover:bg-green-600">
          💳 Thanh toán
        </a>
      </div>
    </div>
  </form>
  <?php endif; ?>
</main>

<?php include_once(__DIR__ . '/includes/footer.php'); ?>
<script src="assets/js/main.js"></script>
</body>
</html>
