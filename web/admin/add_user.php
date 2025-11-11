<?php
include_once(__DIR__ . '/../../config/database.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];

  $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
  $stmt->execute([$username, $password, $email]);

  header("Location: users.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>➕ Thêm người dùng | PetShop Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">➕ Thêm người dùng</h2>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block mb-1 font-medium">Tên đăng nhập</label>
        <input type="text" name="username" required class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block mb-1 font-medium">Mật khẩu</label>
        <input type="text" name="password" required class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block mb-1 font-medium">Email</label>
        <input type="email" name="email" required class="w-full border p-2 rounded">
      </div>

      <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">💾 Lưu</button>
      <a href="users.php" class="ml-3 text-gray-600 hover:underline">← Quay lại</a>
    </form>
  </div>
</body>
</html>
