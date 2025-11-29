<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Điểm Danh - 65HTTT</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        
        .stat-item {
            text-align: center;
            padding: 10px 20px;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9em;
            margin-top: 5px;
        }
        
        .table-container {
            padding: 20px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        tbody tr {
            transition: all 0.3s ease;
        }
        
        tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        tbody tr:nth-child(even):hover {
            background: #e9ecef;
        }
        
        .row-number {
            font-weight: bold;
            color: #667eea;
        }
        
        .username {
            font-weight: 600;
            color: #495057;
        }
        
        .email {
            color: #6c757d;
            font-size: 0.9em;
        }
        
        .course {
            background: #667eea;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            display: inline-block;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9em;
            border-top: 2px solid #e9ecef;
        }
        
        .alert {
            padding: 15px;
            margin: 20px;
            border-radius: 5px;
            text-align: center;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Danh Sách Điểm Danh</h1>
            <p>Lớp 65HTTT - Khóa học CSE485.202401</p>
        </div>
        
        <?php
        // Đường dẫn đến file CSV
        $csvFile = '65HTTT_Danh_sach_diem_danh.csv';
        
        // Kiểm tra file có tồn tại không
        if (!file_exists($csvFile)) {
            echo '<div class="alert alert-error">';
            echo '❌ Không tìm thấy file CSV: ' . htmlspecialchars($csvFile);
            echo '</div>';
            exit;
        }
        
        // Mở file CSV
        $file = fopen($csvFile, 'r');
        
        if ($file === false) {
            echo '<div class="alert alert-error">';
            echo '❌ Không thể mở file CSV';
            echo '</div>';
            exit;
        }
        
        // Đọc dòng đầu tiên (header)
        $headers = fgetcsv($file);
        
        // Đọc tất cả các dòng còn lại
        $data = [];
        while (($row = fgetcsv($file)) !== false) {
            $data[] = $row;
        }
        
        fclose($file);
        
        // Hiển thị thống kê
        $totalRecords = count($data);
        $totalCourses = count(array_unique(array_column($data, 6)));
        $totalClasses = count(array_unique(array_column($data, 4)));
        ?>
        
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number"><?php echo $totalRecords; ?></div>
                <div class="stat-label">Tổng Sinh Viên</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $totalClasses; ?></div>
                <div class="stat-label">Số Lớp</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $totalCourses; ?></div>
                <div class="stat-label">Khóa Học</div>
            </div>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Username</th>
                        <th>Họ và Đệm</th>
                        <th>Tên</th>
                        <th>Lớp</th>
                        <th>Email</th>
                        <th>Khóa học</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($data)) {
                        echo '<tr><td colspan="7" style="text-align: center; padding: 30px; color: #6c757d;">
                                Không có dữ liệu để hiển thị
                              </td></tr>';
                    } else {
                        $stt = 1;
                        foreach ($data as $row) {
                            // Kiểm tra đủ số cột
                            if (count($row) >= 7) {
                                echo '<tr>';
                                echo '<td class="row-number">' . $stt++ . '</td>';
                                echo '<td class="username">' . htmlspecialchars($row[0]) . '</td>';
                                echo '<td>' . htmlspecialchars($row[2]) . '</td>';
                                echo '<td>' . htmlspecialchars($row[3]) . '</td>';
                                echo '<td>' . htmlspecialchars($row[4]) . '</td>';
                                echo '<td class="email">' . htmlspecialchars($row[5]) . '</td>';
                                echo '<td><span class="course">' . htmlspecialchars($row[6]) . '</span></td>';
                                echo '</tr>';
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="footer">
            <p>📅 Ngày tạo: <?php echo date('d/m/Y H:i:s'); ?> | 
               💾 File: <?php echo htmlspecialchars($csvFile); ?> | 
               📊 Tổng số bản ghi: <?php echo $totalRecords; ?></p>
        </div>
    </div>
</body>
</html>
