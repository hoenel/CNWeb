# 📚 HƯỚNG DẪN XÂY DỰNG PROJECT QUẢN LÝ SỰ CỐ MÁY TÍNH (LARAVEL MVC)

> **Project:** Hệ thống quản lý sự cố máy tính trong phòng thực hành  
> **Tech Stack:** Laravel 9+, MySQL, Bootstrap 5  
> **Pattern:** MVC (Model-View-Controller)

---

## 📖 MỤC LỤC

1. [Bước 1: Tạo Migrations](#bước-1-tạo-migrations)
2. [Bước 2: Tạo Models](#bước-2-tạo-models)
3. [Bước 3: Tạo Seeders](#bước-3-tạo-seeders)
4. [Bước 4: Chạy Migration và Seed](#bước-4-chạy-migration-và-seed)
5. [Bước 5: Tạo Controller](#bước-5-tạo-controller)
6. [Bước 6: Tạo Routes](#bước-6-tạo-routes)
7. [Bước 7: Setup Bootstrap](#bước-7-setup-bootstrap)
8. [Bước 8: Tạo Layout Master](#bước-8-tạo-layout-master)
9. [Bước 9: Tạo Views](#bước-9-tạo-views)
10. [Bước 10: Cấu hình Pagination](#bước-10-cấu-hình-pagination)
11. [Bước 11: Test và Debug](#bước-11-test-và-debug)
12. [Lỗi thường gặp và cách fix](#lỗi-thường-gặp-và-cách-fix)

---

## 🎯 CẤU TRÚC DATABASE

### Bảng `computers`
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | BIGINT | Primary Key |
| computer_name | VARCHAR(50) | Tên máy tính |
| model | VARCHAR(50) | Model máy |
| operating_system | VARCHAR(50) | Hệ điều hành |
| processor | VARCHAR(50) | CPU |
| memory | INT | RAM (GB) |
| available | BOOLEAN | Còn khả dụng? |
| timestamps | TIMESTAMP | created_at, updated_at |

### Bảng `issues`
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | BIGINT | Primary Key |
| computer_id | BIGINT | Foreign Key → computers.id |
| reported_by | VARCHAR(50) | Người báo cáo |
| reported_date | DATETIME | Thời gian báo cáo |
| description | TEXT | Mô tả sự cố |
| urgency | ENUM | Low, Medium, High |
| status | ENUM | Open, In Progress, Resolved |
| timestamps | TIMESTAMP | created_at, updated_at |

---

## BƯỚC 1: TẠO MIGRATIONS

### 1.1. Tạo Migration cho bảng `computers`

```bash
php artisan make:migration create_computers_table
```

**File:** `database/migrations/YYYY_MM_DD_HHMMSS_create_computers_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->string('computer_name', 50);
            $table->string('model', 50);
            $table->string('operating_system', 50);
            $table->string('processor', 50);
            $table->integer('memory');  // ⚠️ KHÔNG dùng memory(GB) - sai cú pháp!
            $table->boolean('available')->default(true);  // ⚠️ KHÔNG thừa dấu ngoặc
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('computers');
    }
};
```

### ⚠️ LƯU Ý QUAN TRỌNG:
- ❌ **SAI:** `$table->integer('memory(GB)');` - Tên cột không được có dấu ngoặc
- ✅ **ĐÚNG:** `$table->integer('memory');`
- ❌ **SAI:** `$table->boolean(('available'))` - Thừa dấu ngoặc
- ✅ **ĐÚNG:** `$table->boolean('available')`

---

### 1.2. Tạo Migration cho bảng `issues`

```bash
php artisan make:migration create_issues_table
```

**File:** `database/migrations/YYYY_MM_DD_HHMMSS_create_issues_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('computer_id')
                  ->constrained('computers')
                  ->onDelete('cascade');
            $table->string('reported_by', 50)->nullable();
            $table->dateTime('reported_date');
            $table->text('description');
            $table->enum('urgency', ['Low', 'Medium', 'High']);
            $table->enum('status', ['Open', 'In Progress', 'Resolved'])
                  ->default('Open');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('issues');
    }
};
```

### 💡 TIP:
- `foreignId()` tự động tạo UNSIGNED BIGINT
- `constrained()` tự động tạo foreign key constraint
- `onDelete('cascade')` sẽ tự xóa issues khi xóa computer

---

## BƯỚC 2: TẠO MODELS

### 2.1. Tạo Model `Computer`

```bash
php artisan make:model Computer
```

**File:** `app/Models/Computer.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Computer extends Model
{
    use HasFactory;

    protected $fillable = [
        'computer_name', 
        'model',
        'operating_system',
        'processor',
        'memory',      // ⚠️ Phải khớp với tên cột trong migration
        'available'
    ];

    // Relationship: 1 Computer có nhiều Issues
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
```

---

### 2.2. Tạo Model `Issue`

```bash
php artisan make:model Issue
```

**File:** `app/Models/Issue.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'computer_id',
        'reported_by',
        'reported_date',   // ⚠️ Phải là reported_date, KHÔNG phải dateTime
        'description',
        'urgency',
        'status'
    ];

    // Relationship: 1 Issue thuộc về 1 Computer
    public function computer()
    {
        return $this->belongsTo(Computer::class);
    }
}
```

### ⚠️ LƯU Ý:
- `$fillable` phải khớp CHÍNH XÁC với tên cột trong migration
- Nếu migration dùng `reported_date` thì Model cũng phải dùng `reported_date`
- ❌ Không dùng `dateTime` trong fillable nếu migration dùng `reported_date`

---

## BƯỚC 3: TẠO SEEDERS

### 3.1. Tạo Seeder cho `computers`

```bash
php artisan make:seeder ComputerSeeder
```

**File:** `database/seeders/ComputerSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Computer;
use Faker\Factory as Faker;

class ComputerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 51; $i++) {
            Computer::create([
                'computer_name' => $faker->randomElement(['Lenovo', 'Dell', 'HP', 'Asus', 'Acer']) 
                                   . ' ' . $faker->bothify('??###'),
                'model' => $faker->bothify('Model-??###'),
                'operating_system' => $faker->randomElement([
                    'Windows 10', 
                    'Windows 11', 
                    'Ubuntu 20.04', 
                    'macOS Monterey', 
                    'Fedora 35'
                ]),
                'processor' => $faker->randomElement([
                    'Intel i5', 
                    'Intel i7', 
                    'AMD Ryzen 5', 
                    'AMD Ryzen 7', 
                    'Apple M1'
                ]),
                'memory' => $faker->randomElement([2, 4, 8, 16, 32, 64]),  // ⚠️ Đúng tên cột
                'available' => $faker->boolean(80)  // 80% khả dụng
            ]);
        }
    }
}
```

### ⚠️ LƯU Ý:
- Tên cột phải khớp: `'memory'` chứ không phải `'memory(GB)'`

---

### 3.2. Tạo Seeder cho `issues`

```bash
php artisan make:seeder IssueSeeder
```

**File:** `database/seeders/IssueSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Issue;
use App\Models\Computer;
use Faker\Factory as Faker;

class IssueSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        // Lấy tất cả computer IDs
        $computerIds = Computer::pluck('id')->toArray();

        for ($i = 1; $i <= 100; $i++) {
            Issue::create([
                'computer_id' => $faker->randomElement($computerIds),
                'reported_by' => $faker->name(),
                'reported_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'description' => $faker->paragraph(),
                'urgency' => $faker->randomElement(['Low', 'Medium', 'High']),
                'status' => $faker->randomElement(['Open', 'In Progress', 'Resolved'])
            ]);
        }
    }
}
```

---

### 3.3. Cập nhật `DatabaseSeeder`

**File:** `database/seeders/DatabaseSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ⚠️ Thứ tự quan trọng: Computer trước, Issue sau
        $this->call([
            ComputerSeeder::class,
            IssueSeeder::class,
        ]);
    }
}
```

---

## BƯỚC 4: CHẠY MIGRATION VÀ SEED

### 4.1. Chạy migration

```bash
php artisan migrate
```

### 4.2. Chạy seeder

```bash
php artisan db:seed
```

### 4.3. Hoặc làm cả 2 cùng lúc (reset database)

```bash
php artisan migrate:fresh --seed
```

⚠️ **Cảnh báo:** `migrate:fresh` sẽ XÓA HẾT dữ liệu cũ!

---

## BƯỚC 5: TẠO CONTROLLER

```bash
php artisan make:controller IssueController --resource
```

**File:** `app/Http/Controllers/IssueController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Models\Computer;

class IssueController extends Controller
{
    // Hiển thị danh sách issues
    public function index()
    {
        $issues = Issue::with('computer')->paginate(10);
        return view('issues.index', compact('issues'));
    }

    // Hiển thị form thêm mới
    public function create()
    {
        $computers = Computer::all();
        return view('issues.create', compact('computers'));
    }

    // Xử lý lưu issue mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'computer_id' => 'required|exists:computers,id',
            'reported_by' => 'nullable|string|max:50',
            'description' => 'required|string',
            'urgency' => 'required|in:Low,Medium,High',
        ]);

        Issue::create([
            'computer_id' => $validated['computer_id'],
            'reported_by' => $validated['reported_by'] ?? null,
            'reported_date' => now(),
            'description' => $validated['description'],
            'urgency' => $validated['urgency'],
            'status' => 'Open',
        ]);

        return redirect()->route('issues.index')
                        ->with('success', 'Thêm sự cố mới thành công!');
    }

    // Hiển thị form sửa
    public function edit(Issue $issue)
    {
        $computers = Computer::all();
        return view('issues.edit', compact('issue', 'computers'));
    }

    // Xử lý cập nhật issue
    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'computer_id' => 'required|exists:computers,id',
            'reported_by' => 'nullable|string|max:50',
            'reported_date' => 'required|date',
            'description' => 'required|string',
            'urgency' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Open,In Progress,Resolved',
        ]);

        $issue->update($validated);
        
        return redirect()->route('issues.index')
                        ->with('success', 'Cập nhật sự cố thành công!');
    }

    // Xử lý xóa issue
    public function destroy(Issue $issue)
    {
        $issue->delete();
        
        return redirect()->route('issues.index')
                        ->with('success', 'Xóa sự cố thành công!');
    }
}
```

### 💡 GIẢI THÍCH CÁC METHOD:

| Method | Route | Mục đích |
|--------|-------|----------|
| `index()` | GET /issues | Hiển thị danh sách |
| `create()` | GET /issues/create | Hiển thị form thêm |
| `store()` | POST /issues | Xử lý lưu data |
| `edit()` | GET /issues/{id}/edit | Hiển thị form sửa |
| `update()` | PUT /issues/{id} | Xử lý cập nhật |
| `destroy()` | DELETE /issues/{id} | Xử lý xóa |

---

## BƯỚC 6: TẠO ROUTES

**File:** `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IssueController;

Route::resource('issues', IssueController::class)->except(['show']);
```

### 💡 TIP:
- `resource()` tự động tạo 7 routes RESTful
- `except(['show'])` bỏ qua route show (không cần xem chi tiết)

### Kiểm tra routes:
```bash
php artisan route:list --path=issues
```

---

## BƯỚC 7: SETUP BOOTSTRAP

### 7.1. Download Bootstrap

Tải Bootstrap 5.3.8 từ: https://getbootstrap.com/docs/5.3/getting-started/download/

### 7.2. Copy vào thư mục public

Giải nén và copy folder `bootstrap-5.3.8-dist` vào thư mục gốc project, sau đó chạy:

```bash
Copy-Item -Path "bootstrap-5.3.8-dist\bootstrap-5.3.8-dist" -Destination "public\bootstrap" -Recurse -Force
```

Hoặc thủ công: Copy folder `css` và `js` vào `public/bootstrap/`

### 7.3. Cấu trúc sau khi copy:
```
public/
  bootstrap/
    css/
      bootstrap.min.css
    js/
      bootstrap.bundle.min.js
```

---

## BƯỚC 8: TẠO LAYOUT MASTER

### 8.1. Tạo thư mục layouts

```bash
mkdir resources\views\layouts
```

### 8.2. Tạo file `app.blade.php`

**File:** `resources/views/layouts/app.blade.php`

```php
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản lý sự cố')</title>

    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
</head>
<body>
    <div class="container mt-4">
        {{-- Hiển thị thông báo thành công --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Hiển thị lỗi validation --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Nội dung chính --}}
        @yield('content')
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
```

---

## BƯỚC 9: TẠO VIEWS

### 9.1. Tạo thư mục issues

```bash
mkdir resources\views\issues
```

---

### 9.2. View Index (Danh sách)

**File:** `resources/views/issues/index.blade.php`

```php
@extends('layouts.app')

@section('title', 'Danh sách sự cố')

@section('content')
<h3 class="mb-3">Danh sách sự cố</h3>

<a href="{{ route('issues.create') }}" class="btn btn-primary mb-3">
    + Thêm sự cố
</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Tên máy</th>
            <th>Model</th>
            <th>Người báo</th>
            <th>Thời gian</th>
            <th>Mức độ</th>
            <th>Trạng thái</th>
            <th width="120">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($issues as $issue)
        <tr>
            <td>{{ $issue->id }}</td>
            <td>{{ $issue->computer->computer_name }}</td>
            <td>{{ $issue->computer->model }}</td>
            <td>{{ $issue->reported_by }}</td>
            <td>{{ $issue->reported_date }}</td>
            <td>
                <span class="badge bg-warning text-dark">
                    {{ $issue->urgency }}
                </span>
            </td>
            <td>
                <span class="badge bg-info">
                    {{ $issue->status }}
                </span>
            </td>
            <td>
                <a href="{{ route('issues.edit', $issue) }}" class="btn btn-sm btn-warning">
                    Sửa
                </a>

                <button type="button" class="btn btn-sm btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal"
                        data-issue-id="{{ $issue->id }}"
                        data-issue-name="{{ $issue->computer->computer_name }}">
                    Xóa
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $issues->links() }}

<!-- Modal Xác nhận Xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">⚠️ Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Bạn có chắc chắn muốn xóa sự cố này?</p>
                <p class="mb-0"><strong>Máy tính: <span id="modalComputerName"></span></strong></p>
                <p class="text-muted small">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Hủy
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        🗑️ Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const issueId = button.getAttribute('data-issue-id');
        const computerName = button.getAttribute('data-issue-name');
        
        document.getElementById('modalComputerName').textContent = computerName;
        document.getElementById('deleteForm').action = '/issues/' + issueId;
    });
</script>
@endsection
```

---

### 9.3. View Create (Form thêm)

**File:** `resources/views/issues/create.blade.php`

```php
@extends('layouts.app')

@section('title', 'Thêm sự cố mới')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h3 class="mb-4">Thêm sự cố mới</h3>

        <form action="{{ route('issues.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Máy tính <span class="text-danger">*</span></label>
                <select name="computer_id" class="form-select" required>
                    <option value="">-- Chọn máy tính --</option>
                    @foreach($computers as $computer)
                        <option value="{{ $computer->id }}">
                            {{ $computer->computer_name }} - {{ $computer->model }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Người báo cáo</label>
                <input type="text" name="reported_by" class="form-control" 
                       placeholder="Nhập tên người báo cáo">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả sự cố <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="4" 
                          placeholder="Mô tả chi tiết sự cố..." required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Mức độ <span class="text-danger">*</span></label>
                <select name="urgency" class="form-select" required>
                    <option value="">-- Chọn mức độ --</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-success">
                    ✓ Lưu sự cố
                </button>
                <a href="{{ route('issues.index') }}" class="btn btn-secondary">
                    ← Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
```

---

### 9.4. View Edit (Form sửa)

**File:** `resources/views/issues/edit.blade.php`

```php
@extends('layouts.app')

@section('title', 'Sửa sự cố')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h3 class="mb-4">Sửa sự cố #{{ $issue->id }}</h3>

        <form action="{{ route('issues.update', $issue) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Máy tính <span class="text-danger">*</span></label>
                <select name="computer_id" class="form-select" required>
                    @foreach($computers as $computer)
                        <option value="{{ $computer->id }}" 
                            {{ $issue->computer_id == $computer->id ? 'selected' : '' }}>
                            {{ $computer->computer_name }} - {{ $computer->model }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Người báo cáo</label>
                <input type="text" name="reported_by" class="form-control" 
                       value="{{ $issue->reported_by }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Thời gian báo cáo</label>
                <input type="datetime-local" name="reported_date" class="form-control" 
                       value="{{ date('Y-m-d\TH:i', strtotime($issue->reported_date)) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả sự cố <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="4" required>{{ $issue->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Mức độ <span class="text-danger">*</span></label>
                <select name="urgency" class="form-select" required>
                    <option value="Low" {{ $issue->urgency == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ $issue->urgency == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ $issue->urgency == 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="Open" {{ $issue->status == 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="In Progress" {{ $issue->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Resolved" {{ $issue->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    ✓ Cập nhật
                </button>
                <a href="{{ route('issues.index') }}" class="btn btn-secondary">
                    ← Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
```

---

## BƯỚC 10: CẤU HÌNH PAGINATION

**File:** `app/Providers/AppServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Paginator::useBootstrapFive();
    }
}
```

### 💡 Giải thích:
- Laravel mặc định dùng Tailwind CSS cho pagination
- Phải config để dùng Bootstrap 5

---

## BƯỚC 11: TEST VÀ DEBUG

### 11.1. Khởi động server

```bash
php artisan serve
```

### 11.2. Truy cập các URL:

| URL | Chức năng |
|-----|-----------|
| `http://127.0.0.1:8000/issues` | Danh sách sự cố |
| `http://127.0.0.1:8000/issues/create` | Thêm sự cố |
| `http://127.0.0.1:8000/issues/1/edit` | Sửa sự cố #1 |

### 11.3. Test các chức năng:

- ✅ Xem danh sách có phân trang
- ✅ Thêm sự cố mới
- ✅ Sửa sự cố
- ✅ Xóa sự cố (modal xác nhận)
- ✅ Validation form
- ✅ Thông báo success/error

---

## 🔧 LỖI THƯỜNG GẶP VÀ CÁCH FIX

### ❌ Lỗi 1: Trang hiển thị JSON thay vì HTML

**Triệu chứng:**
```json
{"current_page":1,"data":[{"id":1,"computer_id":25,...}]}
```

**Nguyên nhân:**
- Trình duyệt gửi header `Accept: application/json`
- Extension (Postman Interceptor) can thiệp

**Cách fix:**
1. Mở chế độ ẩn danh/incognito
2. Tắt tất cả extension trình duyệt
3. Hard refresh: `Ctrl + F5`
4. Clear cache:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

### ❌ Lỗi 2: Bootstrap CSS không load

**Triệu chứng:**
- Trang không có style, text đen trắng
- Console lỗi 404 khi load CSS

**Nguyên nhân:**
- Bootstrap chưa copy vào `public/`
- Đường dẫn `asset()` sai

**Cách fix:**
1. Kiểm tra file tồn tại:
```
public/bootstrap/css/bootstrap.min.css
public/bootstrap/js/bootstrap.bundle.min.js
```

2. Nếu chưa có, copy lại:
```bash
Copy-Item -Path "bootstrap-5.3.8-dist\bootstrap-5.3.8-dist" -Destination "public\bootstrap" -Recurse -Force
```

3. Clear cache và restart server

---

### ❌ Lỗi 3: Pagination hiển thị sai

**Triệu chứng:**
- Pagination chiếm full màn hình
- Style không đẹp

**Nguyên nhân:**
- Chưa config `Paginator::useBootstrapFive()`

**Cách fix:**
Thêm vào `AppServiceProvider.php`:
```php
use Illuminate\Pagination\Paginator;

public function boot()
{
    Paginator::useBootstrapFive();
}
```

---

### ❌ Lỗi 4: SQLSTATE[42S22] Column not found

**Triệu chứng:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'memory(GB)'
```

**Nguyên nhân:**
- Tên cột trong Model không khớp với Migration
- Seeder dùng tên cột sai

**Cách fix:**
1. Kiểm tra migration: `$table->integer('memory');`
2. Kiểm tra Model fillable: `'memory'`
3. Kiểm tra Seeder: `'memory' => ...`
4. Chạy lại:
```bash
php artisan migrate:fresh --seed
```

---

### ❌ Lỗi 5: Class "Issue" not found

**Nguyên nhân:**
- Chưa import class trong Controller
- Namespace sai

**Cách fix:**
Thêm vào đầu Controller:
```php
use App\Models\Issue;
use App\Models\Computer;
```

---

### ❌ Lỗi 6: Target class [IssueController] does not exist

**Nguyên nhân:**
- Route không tìm thấy Controller
- Namespace sai

**Cách fix:**
Trong `routes/web.php`:
```php
use App\Http\Controllers\IssueController;

Route::resource('issues', IssueController::class);
```

---

## 🎯 CHECKLIST HOÀN THÀNH

### Database
- [ ] Migration `computers` đúng cú pháp
- [ ] Migration `issues` có foreign key
- [ ] Model `Computer` có relationship `hasMany`
- [ ] Model `Issue` có relationship `belongsTo`
- [ ] Seeder `ComputerSeeder` tạo 51 records
- [ ] Seeder `IssueSeeder` tạo 100 records
- [ ] Chạy `migrate:fresh --seed` thành công

### Backend
- [ ] Controller có đầy đủ 6 methods RESTful
- [ ] Validation đầy đủ và chính xác
- [ ] Route resource đã config
- [ ] Pagination Bootstrap đã setup

### Frontend
- [ ] Layout `app.blade.php` có Bootstrap
- [ ] View `index.blade.php` có bảng + modal xóa
- [ ] View `create.blade.php` có form đầy đủ
- [ ] View `edit.blade.php` có form với data cũ
- [ ] Alert thông báo success/error hoạt động
- [ ] Modal xóa đẹp, không dùng `alert()`

### Test
- [ ] Xem danh sách: `/issues`
- [ ] Thêm mới thành công
- [ ] Sửa thành công
- [ ] Xóa thành công (có modal xác nhận)
- [ ] Validation hiển thị lỗi đúng
- [ ] Pagination hoạt động

---

## 💡 CÁC TIP NÂNG CAO

### 1. Reset Auto Increment sau khi xóa

**Chỉ dùng trong môi trường DEV:**
```bash
php artisan tinker
```
```php
DB::statement('ALTER TABLE issues AUTO_INCREMENT = 1');
```

**Hoặc reset toàn bộ:**
```bash
php artisan migrate:fresh --seed
```

---

### 2. Thêm cột Description vào bảng index

Trong `index.blade.php`, thêm cột:
```php
<th>Mô tả</th>

<td>{{ Str::limit($issue->description, 50) }}</td>
```

---

### 3. Format ngày giờ đẹp hơn

Cài thêm Carbon (đã có sẵn trong Laravel):
```php
<td>{{ \Carbon\Carbon::parse($issue->reported_date)->format('d/m/Y H:i') }}</td>
```

---

### 4. Thêm filter theo urgency/status

Trong Controller:
```php
public function index(Request $request)
{
    $query = Issue::with('computer');
    
    if ($request->urgency) {
        $query->where('urgency', $request->urgency);
    }
    
    $issues = $query->paginate(10);
    return view('issues.index', compact('issues'));
}
```

---

## 🎓 KẾT LUẬN

Bạn đã hoàn thành một project Laravel CRUD chuẩn MVC với:
- ✅ Database design chuẩn với relationships
- ✅ RESTful Controller
- ✅ Blade Templates với Bootstrap 5
- ✅ Form validation
- ✅ Pagination
- ✅ Modal confirm delete
- ✅ Alert messages

**Chúc bạn học tốt! 🚀**

---

## 📚 TÀI LIỆU THAM KHẢO

- Laravel Documentation: https://laravel.com/docs/9.x
- Bootstrap 5: https://getbootstrap.com/docs/5.3
- Laravel Validation: https://laravel.com/docs/9.x/validation
- Eloquent ORM: https://laravel.com/docs/9.x/eloquent
- Blade Templates: https://laravel.com/docs/9.x/blade
