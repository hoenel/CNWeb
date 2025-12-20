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
            <th>Mô tả</th>
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
            <td>{{ Str::limit($issue->description, 50) }}</td>
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
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle"></i> Xác nhận xóa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Khi modal mở, set action URL và tên máy tính
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const issueId = button.getAttribute('data-issue-id');
        const computerName = button.getAttribute('data-issue-name');
        
        // Set tên máy tính vào modal
        document.getElementById('modalComputerName').textContent = computerName;
        
        // Set action URL cho form
        const form = document.getElementById('deleteForm');
        form.action = '/issues/' + issueId;
    });
</script>
@endsection
