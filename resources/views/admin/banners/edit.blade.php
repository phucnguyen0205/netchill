{{-- resources/views/admin/banners/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card bg-dark text-light">
        <div class="card-header">Chỉnh sửa Banner</div>
        <div class="card-body">
            <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="current_image" class="form-label">Ảnh Banner hiện tại</label>
                    <div class="mb-2">
                        <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}" style="width: 200px;">
                    </div>
                    <label for="image" class="form-label">Chọn ảnh Banner mới (để thay thế)</label>
                    <input class="form-control" type="file" id="image" name="image">
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $banner->title }}">
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Liên kết</label>
                    <input type="url" class="form-control" id="link" name="link" value="{{ $banner->link }}">
                </div>
                
                <button type="submit" class="btn btn-primary">Cập nhật Banner</button>
            </form>
        </div>
    </div>
</div>
@endsection