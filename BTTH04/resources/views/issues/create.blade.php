@extends('layouts.app')

@section('title', 'Thêm sự cố mới')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h3 class="mb-4">Thêm sự cố mới</h3>

        <form action="{{ route('issues.store') }}" method="POST">
            @csrf

            <!-- Chọn máy tính -->
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

            <!-- Người báo cáo -->
            <div class="mb-3">
                <label class="form-label">Người báo cáo</label>
                <input type="text" name="reported_by" class="form-control" 
                       placeholder="Nhập tên người báo cáo">
            </div>

            <!-- Mô tả sự cố -->
            <div class="mb-3">
                <label class="form-label">Mô tả sự cố <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="4" 
                          placeholder="Mô tả chi tiết sự cố..." required></textarea>
            </div>

            <!-- Mức độ khẩn cấp -->
            <div class="mb-3">
                <label class="form-label">Mức độ <span class="text-danger">*</span></label>
                <select name="urgency" class="form-select" required>
                    <option value="">-- Chọn mức độ --</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <!-- Nút submit -->
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