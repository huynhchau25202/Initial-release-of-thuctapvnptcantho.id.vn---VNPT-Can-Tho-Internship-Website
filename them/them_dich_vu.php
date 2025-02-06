<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
include('../connect.php');

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenDichVu = $_POST['TenDichVu'];

    // Chuẩn bị câu lệnh SQL để chèn dữ liệu
    $stmt = $conn->prepare("INSERT INTO dichvu (TenDichVu) VALUES (?)");
    $stmt->bind_param("s", $tenDichVu);

    if ($stmt->execute()) {
        // Chuyển hướng đến trang danh sách dịch vụ sau khi thêm thành công
        echo "<script>alert('Thêm thành công.');</script>";
        header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_dich_vu.php");
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Dịch Vụ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body> -->
<?php include '../menu.php'; ?>
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-plus-circle"></i> Thêm Dịch Vụ Mới</h2>
        </div>
        <div class="card-body p-5">
            <form action="../them/them_dich_vu.php" method="post">
                <div class="form-group">
                    <label for="TenDichVu" class="form-label">Tên Dịch Vụ</label>
                    <input type="text" class="form-control" id="TenDichVu" name="TenDichVu" required>
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="bi bi-floppy"></i> Lưu
                </button>
                <a href="../danhsach/danh_sach_thong_tin_dich_vu.php" class="btn btn-secondary">
                    <i class="bi bi-backspace"></i> Quay Lại
                </a>
            </form>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>

<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->