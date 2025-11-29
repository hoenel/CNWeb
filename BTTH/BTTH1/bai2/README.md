# Bài 02 — Đọc tệp tin văn bản (Quiz)

Hướng dẫn nhanh để chạy trang quiz bằng XAMPP / PHP:

- Đặt cả thư mục `bai2` (chứa `index.php`, `style.css`, `Quiz.txt`) vào `C:\xampp\htdocs\` (hoặc tương ứng với `htdocs` của XAMPP trên máy bạn).
- Khởi động Apache trong XAMPP Control Panel.
- Mở trình duyệt và truy cập: `http://localhost/bai2/` hoặc `http://localhost/bai2/index.php`.

Ghi chú về hoạt động của trang:
- `index.php` đọc `Quiz.txt`, phân tích các câu hỏi, lựa chọn và dòng `ANSWER:` để biết đáp án.
- Trang hiển thị dạng trắc nghiệm; câu có nhiều đáp án đúng sẽ hiển thị checkbox, câu chỉ có 1 đáp án đúng sẽ hiển thị radio.
- Sau khi nộp, trang sẽ chấm và hiển thị kết quả, đáp án đúng và đáp án của bạn.

Nếu muốn chỉnh sửa file dữ liệu, sửa `Quiz.txt` theo định dạng hiện tại:

    [Câu hỏi]
    A. [lựa chọn A]
    B. [lựa chọn B]
    C. [lựa chọn C]
    D. [lựa chọn D]
    ANSWER: [Chữ cái hoặc các chữ cái, phân tách bằng dấu phẩy]
