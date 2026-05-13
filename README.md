# API Music Weather - Backend

Hệ thống API Backend được xây dựng bằng Laravel 10, hỗ trợ quản lý nhạc và tích hợp lưu trữ đám mây qua Google Drive. Dự án được thiết kế để cung cấp khả năng stream nhạc trực tuyến, quản lý album và tối ưu hóa không gian lưu trữ bằng cách tránh trùng lặp file.

## 🚀 Tính Năng Chính

- **Xác Thực Người Dùng**: Sử dụng Laravel Sanctum (Đăng ký, Đăng nhập, Đăng xuất, Đổi mật khẩu).
- **Tích Hợp Google Drive**: Sử dụng Google Drive làm bộ nhớ lưu trữ file nhạc (.mp3) thay vì lưu trữ cục bộ, giúp tiết kiệm dung lượng server.
- **Tối Ưu Hóa Lưu Trữ**: Sử dụng mã hash SHA-256 để kiểm tra file. Nếu nhiều người dùng cùng tải lên một file nhạc giống nhau, hệ thống chỉ lưu 1 bản duy nhất trên Drive nhưng vẫn hiển thị trong danh sách của từng người.
- **Streaming & Download**:
    - Hỗ trợ Stream nhạc trực tiếp từ Google Drive với header `Range` (phát nhạc mượt mà hơn).
    - Hỗ trợ tải xuống từng bài hát hoặc nén toàn bộ album thành file ZIP để tải về.
- **Quản Lý Nhạc & Album**: 
    - Thêm/Sửa/Xóa bài hát và album.
    - Đặt tên tùy chỉnh (custom name/artist) cho bài hát trong thư viện cá nhân.
- **Bảo Mật & Hiệu Năng**:
    - Rate Limiting (giới hạn tần suất gọi API).
    - Rollback thủ công khi upload Drive thất bại để đảm bảo tính toàn vẹn dữ liệu.
    - Dockerized (Sẵn sàng triển khai với Docker).

## 🛠 Tech Stack

- **Framework**: Laravel 10
- **Language**: PHP 8.1+
- **Storage**: Google Drive API v3
- **Database**: MySQL / MariaDB
- **Authentication**: Laravel Sanctum
- **Containerization**: Docker

## 📦 Cài Đặt

### 1. Yêu cầu hệ thống
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Tài khoản Google Cloud (để cấu hình Drive API)

### 2. Các bước cài đặt
1. Clone dự án:
   ```bash
   git clone <repository-url>
   cd api-music-weather
   ```
2. Cài đặt các phụ thuộc:
   ```bash
   composer install
   ```
3. Sao chép file cấu hình:
   ```bash
   cp .env.example .env
   ```
4. Tạo Key cho ứng dụng:
   ```bash
   php artisan key:generate
   ```
5. Cấu hình Database và Google Drive trong file `.env`:
   ```env
   # Database
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=music_weather
   DB_USERNAME=root
   DB_PASSWORD=

   # Google Drive API
   GOOGLE_DRIVE_CLIENT_ID=your_client_id
   GOOGLE_DRIVE_CLIENT_SECRET=your_client_secret
   GOOGLE_DRIVE_REFRESH_TOKEN=your_refresh_token
   GOOGLE_DRIVE_FOLDER_ID=your_folder_id
   ```
6. Chạy Migration:
   ```bash
   php artisan migrate
   ```
7. Chạy server phát triển:
   ```bash
   php artisan serve
   ```

## 🔌 API Endpoints (Tóm tắt)

### Authentication
- `POST /api/register`: Đăng ký tài khoản.
- `POST /api/login`: Đăng nhập & lấy token.
- `POST /api/logout`: Đăng xuất (Yêu cầu Token).

### Songs
- `GET /api/songs`: Danh sách bài hát của người dùng.
- `POST /api/songs`: Upload bài hát mới (mp3).
- `GET /api/songs/{id}/stream`: Stream nhạc trực tuyến.
- `GET /api/songs/{id}/download`: Tải bài hát về máy.
- `DELETE /api/songs/{id}`: Xóa bài hát khỏi thư viện.

### Albums
- `GET /api/albums`: Danh sách album.
- `POST /api/albums`: Tạo album mới.
- `GET /api/albums/{id}`: Chi tiết album và các bài hát bên trong.
- `GET /api/albums/{id}/download-all`: Tải toàn bộ album (ZIP).

## 🐳 Triển Khai (Docker)

Dự án đã có sẵn `Dockerfile` và `start.sh`. Bạn có thể build image và chạy container dễ dàng:

```bash
docker build -t music-weather-api .
docker run -p 10000:10000 --env-file .env music-weather-api
```

## 📝 Giới Hạn Tải Lên
- Dung lượng file tối đa: **50MB**
- Định dạng hỗ trợ: **mp3, mpeg**

---
© 2024 Music Weather Project. All rights reserved.
