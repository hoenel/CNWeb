@extends('layouts.app')

@section('title', 'Sửa sự cố')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h3 class="mb-4">Sửa sự cố #{{ $issue->id }}</h3>

        <form action="{{ route('issues.update', $issue) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Chọn máy tính -->
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

            <!-- Người báo cáo -->
            <div class="mb-3">
                <label class="form-label">Người báo cáo</label>
                <input type="text" name="reported_by" class="form-control" 
                       value="{{ $issue->reported_by }}">
            </div>

            <!-- Thời gian báo cáo -->
            <div class="mb-3">
                <label class="form-label">Thời gian báo cáo</label>
                <input type="datetime-local" name="reported_date" class="form-control" 
                       value="{{ date('Y-m-d\TH:i', strtotime($issue->reported_date)) }}">
            </div>

            <!-- Mô tả sự cố -->
            <div class="mb-3">
                <label class="form-label">Mô tả sự cố <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="4" required>{{ $issue->description }}</textarea>
            </div>

            <!-- Mức độ khẩn cấp -->
            <div class="mb-3">
                <label class="form-label">Mức độ <span class="text-danger">*</span></label>
                <select name="urgency" class="form-select" required>
                    <option value="Low" {{ $issue->urgency == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ $issue->urgency == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ $issue->urgency == 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <!-- Trạng thái -->
            <div class="mb-3">
                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="Open" {{ $issue->status == 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="In Progress" {{ $issue->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Resolved" {{ $issue->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>

            <!-- Nút submit -->
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