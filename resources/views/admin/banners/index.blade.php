@extends('layouts.app')

@section('content')
<style>
  :root { --sidebar-w: 280px; }
  /* Sidebar cố định (bạn đã có CSS riêng, giữ nguyên) */
  .sidebar{
    position: fixed; left:0; top:0; bottom:0;
    width: var(--sidebar-w);
    min-height: 100vh;
    z-index: 1000;
    padding-top: 4rem !important;
  }
  /* Vùng nội dung đẩy sang phải để không bị đè */
  .admin-main{
    margin-left: var(--sidebar-w);
    padding: 1.5rem;
  }
  /* Bảng tối gọn trong nền dark */
  .card.bg-dark .table { margin-bottom: 0; }

  /* Responsive: dưới lg thì cho sidebar lên trên và nội dung full width */
  @media (max-width: 991.98px){
    .sidebar{
      position: static; width: 100%; min-height: auto; padding-top: 0 !important;
    }
    .admin-main{ margin-left: 0; }
  }
</style>

<div class="container-fluid px-0">
  {{-- Sidebar (cố định trái) --}}
  @include('admin.partials.sidebar')

  {{-- Main content (đẩy sang phải bằng margin-left) --}}
  <div class="admin-main">
    <div class="card bg-dark text-light">
    <h1>Quản lý Banner</h1>
      <div class="card-body">
        {{-- Form thêm Banner --}}
        <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
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

        <hr class="border-secondary">

        {{-- Bảng hiển thị Banner --}}
        <h5 class="mt-3 mb-3">Danh sách Banner</h5>
        <div class="table-responsive">
          <table class="table table-striped table-dark align-middle">
            <thead>
              <tr>
                <th style="width:180px;">Hình ảnh</th>
                <th>Tiêu đề</th>
                <th style="width:160px;">Hành động</th>
              </tr>
            </thead>
            <tbody>
              @forelse($banners as $banner)
                <tr>
                  <td>
                    <img src="{{ Storage::url($banner->image_path) }}"
                         alt="{{ $banner->title }}" style="width: 150px; height:auto;">
                  </td>
                  <td>{{ $banner->title }}</td>
                  <td>
                    <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('banners.destroy', $banner->id) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này không?');">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted">Chưa có banner nào.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div> {{-- /.card-body --}}
    </div> {{-- /.card --}}
  </div> {{-- /.admin-main --}}
</div> {{-- /.container-fluid --}}
@endsection
