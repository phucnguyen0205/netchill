@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card bg-dark text-light">
        <div class="card-header">Quản lý Banner</div>
        <div class="card-body">
            {{-- Form thêm Banner --}}
            <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="image" class="form-label">Chọn hình ảnh Banner</label>
                    <input class="form-control" type="file" id="image" name="image" required>
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề (tuỳ chọn)</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Liên kết (tuỳ chọn)</label>
                    <input type="url" class="form-control" id="link" name="link">
                </div>
                <button type="submit" class="btn btn-primary">Thêm Banner</button>
            </form>

            <hr>

            {{-- Bảng hiển thị Banner --}}
            <h5 class="mt-4">Danh sách Banner</h5>
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                    <tr>
                        <td><img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}" style="width: 150px;"></td>
                        <td>{{ $banner->title }}</td>
                        <td>
                            <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa banner này không?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection