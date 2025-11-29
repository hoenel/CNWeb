<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị Hoa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .flower-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .user-link {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1 class="text-center">Quản Trị Danh Sách Hoa</h1>
        </div>
    </div>

    <div class="container">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Danh Sách Các Loài Hoa</h2>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-circle"></i> Thêm Hoa Mới
                </button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;">STT</th>
                            <th style="width: 20%;">Tên Hoa</th>
                            <th style="width: 15%;">Hình Ảnh</th>
                            <th style="width: 45%;">Mô Tả</th>
                            <th style="width: 15%;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Include file chứa dữ liệu hoa
                        include 'flowers.php';
                        
                        // Hiển thị danh sách các loài hoa dưới dạng bảng
                        foreach ($flowers as $index => $flower) {
                            echo '<tr>';
                            echo '<td>' . ($index + 1) . '</td>';
                            echo '<td>' . htmlspecialchars($flower['name']) . '</td>';
                            echo '<td><img src="images/' . htmlspecialchars($flower['image']) . '" alt="' . htmlspecialchars($flower['name']) . '" class="flower-image"></td>';
                            echo '<td>' . htmlspecialchars(substr($flower['description'], 0, 150)) . '...</td>';
                            echo '<td>';
                            echo '<div class="action-buttons">';
                            echo '<button class="btn btn-sm btn-primary" onclick="viewFlower(' . $index . ')" data-bs-toggle="modal" data-bs-target="#viewModal"><i class="bi bi-eye"></i></button>';
                            echo '<button class="btn btn-sm btn-warning" onclick="editFlower(' . $index . ')" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>';
                            echo '<button class="btn btn-sm btn-danger" onclick="deleteFlower(' . $index . ')"><i class="bi bi-trash"></i></button>';
                            echo '</div>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Xem Chi Tiết -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi Tiết Hoa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewModalBody">
                    <!-- Nội dung chi tiết sẽ được load bằng JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Mới -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Hoa Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        <div class="mb-3">
                            <label class="form-label">Tên Hoa</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hình Ảnh</label>
                            <input type="text" class="form-control" name="image" placeholder="Tên file ảnh (vd: hoahong.jpg)" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô Tả</label>
                            <textarea class="form-control" name="description" rows="5" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-success" onclick="addFlower()">Thêm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chỉnh Sửa -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh Sửa Hoa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editIndex" name="index">
                        <div class="mb-3">
                            <label class="form-label">Tên Hoa</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hình Ảnh</label>
                            <input type="text" class="form-control" id="editImage" name="image" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô Tả</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="5" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="updateFlower()">Cập Nhật</button>
                </div>
            </div>
        </div>
    </div>

    <div class="user-link">
        <a href="index.php" class="btn btn-info btn-lg">
            <i class="bi bi-house"></i> Trang Người Dùng
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dữ liệu hoa từ PHP
        const flowers = <?php echo json_encode($flowers); ?>;

        function viewFlower(index) {
            const flower = flowers[index];
            const content = `
                <div class="text-center">
                    <h3>${flower.name}</h3>
                    <img src="images/${flower.image}" alt="${flower.name}" class="img-fluid mb-3" style="max-width: 400px; border-radius: 10px;">
                    <p class="text-start">${flower.description}</p>
                </div>
            `;
            document.getElementById('viewModalBody').innerHTML = content;
        }

        function editFlower(index) {
            const flower = flowers[index];
            document.getElementById('editIndex').value = index;
            document.getElementById('editName').value = flower.name;
            document.getElementById('editImage').value = flower.image;
            document.getElementById('editDescription').value = flower.description;
        }

        function addFlower() {
            alert('Chức năng thêm mới (cần kết nối với backend để lưu vào database hoặc file)');
            // Trong thực tế, sẽ gửi dữ liệu qua AJAX hoặc form submit
        }

        function updateFlower() {
            alert('Chức năng cập nhật (cần kết nối với backend để lưu vào database hoặc file)');
            // Trong thực tế, sẽ gửi dữ liệu qua AJAX hoặc form submit
        }

        function deleteFlower(index) {
            const flower = flowers[index];
            if (confirm(`Bạn có chắc chắn muốn xóa hoa "${flower.name}" không?`)) {
                alert('Chức năng xóa (cần kết nối với backend để xóa khỏi database hoặc file)');
                // Trong thực tế, sẽ gửi request xóa qua AJAX
            }
        }
    </script>
</body>
</html>