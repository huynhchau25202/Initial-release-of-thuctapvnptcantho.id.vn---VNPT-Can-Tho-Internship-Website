<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
include('../connect.php');

// Lấy ID Nhân Viên Bán Hàng từ URL
$ID_TTNVBH = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Truy vấn thông tin nhân viên đăng nhập
$ID_NhanVien = $_SESSION['ID_NhanVien'];
$sql_user = "SELECT TenNhanVien FROM nhanvien WHERE ID_NhanVien='$ID_NhanVien'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $row_user = $result_user->fetch_assoc();
    $nguoisua = $row_user['TenNhanVien'];
} else {
    // Xử lý khi không tìm thấy thông tin nhân viên
    $nguoisua = "Không tìm thấy thông tin nhân viên";
}

// Kiểm tra nếu form đã submit để cập nhật thông tin nhân viên bán hàng
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $TenNhanVien = $conn->real_escape_string($_POST['TenNhanVien']);
    $SoDienThoai = $conn->real_escape_string($_POST['SoDienThoai']);
    $DiaChi = $conn->real_escape_string($_POST['DiaChi']);

    // Đặt múi giờ Việt Nam
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    // Lấy ngày hiện tại
    $ngaysua = date("Y-m-d H:i:s");

    // Kiểm tra nếu số điện thoại đã tồn tại cho nhân viên khác
    $sql_check = "SELECT * FROM ttnhanvienbanhang WHERE SoDienThoai='$SoDienThoai' AND ID_TTNVBH != $ID_TTNVBH";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Số điện thoại đã tồn tại.');</script>";
    } else {
        // Cập nhật thông tin nhân viên bán hàng
        $sql_update = "UPDATE ttnhanvienbanhang SET TenNhanVien='$TenNhanVien', SoDienThoai='$SoDienThoai', DiaChi='$DiaChi', nguoisua='$nguoisua', ngaysua='$ngaysua' WHERE ID_TTNVBH=$ID_TTNVBH";

        if ($conn->query($sql_update) === TRUE) {
            echo "<script>alert('Cập nhật thành công.');</script>";
            header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php");
            exit();
        } else {
            echo "Lỗi: " . $sql_update . "<br>" . $conn->error;
        }
    }
}

// Truy vấn thông tin chi tiết của nhân viên bán hàng
$sql_nhanvien = "SELECT * FROM ttnhanvienbanhang WHERE ID_TTNVBH = $ID_TTNVBH";
$result_nhanvien = $conn->query($sql_nhanvien);
$nhanvien = $result_nhanvien->fetch_assoc();

$conn->close();
?>


<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Nhân Viên Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body> -->
<?php include '../menu.php'; ?>
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-user-edit"></i> Sửa Thông Tin Nhân Viên Bán Hàng</h2>
        </div>
        <div class="card-body p-5">
            <?php if ($nhanvien) : ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="TenNhanVien">Tên Nhân Viên</label>
                        <input type="text" class="form-control" id="TenNhanVien" name="TenNhanVien" value="<?php echo htmlspecialchars($nhanvien['TenNhanVien']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="SoDienThoai">Số Điện Thoại</label>
                        <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai" value="<?php echo htmlspecialchars($nhanvien['SoDienThoai']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="DiaChi">Địa Chỉ</label>
                        <input type="text" class="form-control" id="DiaChi" name="DiaChi" value="<?php echo htmlspecialchars($nhanvien['DiaChi']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
                    <a href="../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Quay Lại</a>
                </form>
            <?php else : ?>
                <p class="text-center">Không tìm thấy thông tin nhân viên bán hàng.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>

<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->