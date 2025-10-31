@extends('layouts.app')

@section('title','Lịch chiếu')

@section('content')
<style>
  :root{
    --admin-sb-w: 260px;         /* rộng sidebar (điều chỉnh theo partial của bạn) */
    --admin-header-h: 0px;       /* nếu header cố định, set ví dụ 56px */
  }
  .admin-page{ position: relative; }
  /* Sidebar cố định bên trái trên màn hình >= lg */
  @media (min-width: 992px){
    .admin-sidebar{
      position: fixed;
      left: 0;
      top: var(--admin-header-h);
      width: var(--admin-sb-w);
      height: calc(100vh - var(--admin-header-h));
      overflow-y: auto;
      z-index: 100;              /* dưới content */
    }
    .admin-content{
      margin-left: var(--admin-sb-w);
      padding-left: 16px;        /* tạo khoảng cách nhỏ */
      padding-right: 16px;
    }
  }
  /* Mobile: sidebar hiển thị như bình thường (không fixed), nội dung không cần đẩy */
  @media (max-width: 991.98px){
    .admin-sidebar{ position: static; width: 100%; height: auto; }
    .admin-content{ margin-left: 0; }
  }
</style>

<div class="admin-page container-fluid">
  <!-- Sidebar -->
  <aside class="admin-sidebar">
    @include('admin.partials.sidebar')
  </aside>

  <!-- Nội dung chính: đã được đẩy sang phải khi >= lg -->
  <main class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Lịch chiếu</h4>
      <a href="{{ route('admin.showtimes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Thêm suất
      </a>
    </div>

    {{-- Filters --}}
    <form class="row g-2 mb-3">
      <div class="col-auto">
        <label class="form-label mb-1">Từ ngày</label>
        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
      </div>
      <div class="col-auto">
        <label class="form-label mb-1">Đến ngày</label>
        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
      </div>
      <div class="col-auto">
        <label class="form-label mb-1">Phim</label>
        <select name="movie_id" class="form-select">
          <option value="">— Tất cả —</option>
          @foreach($movies as $id => $title)
            <option value="{{ $id }}" @selected(request('movie_id')==$id)>{{ $title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-auto">
        <label class="form-label mb-1">Phòng</label>
        <input type="text" name="room" value="{{ request('room') }}" class="form-control" placeholder="VD: A1">
      </div>
      <div class="col-auto align-self-end">
        <button class="btn btn-outline-secondary">Lọc</button>
        <a class="btn btn-link" href="{{ route('admin.showtimes.index') }}">Reset</a>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle">
        <thead>
          <tr>
            <th style="width:130px">Ngày</th>
            <th style="width:120px">Giờ</th>
            <th>Phim</th>
            <th style="width:120px">Phòng</th>
            <th style="width:120px">Khởi chiếu</th>
            <th style="width:160px"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($showtimes as $st)
            <tr>
              <td>{{ $st->show_date->format('d/m/Y') }}</td>
              <td>
                {{ $st->start_time->format('H:i') }}
                @if($st->end_time) – {{ $st->end_time->format('H:i') }} @endif
              </td>
              <td>{{ $st->movie?->title ?? '—' }}</td>
              <td>{{ $st->room ?? '—' }}</td>
              <td>
                @if($st->is_premiere)
                  <span class="badge bg-danger-subtle text-danger">Khởi chiếu</span>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td class="text-end">
                <a href="{{ route('admin.showtimes.edit',$st) }}" class="btn btn-sm btn-outline-light">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('admin.showtimes.destroy',$st) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Xóa suất này?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted">Không có dữ liệu.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $showtimes->links() }}
  </main>
</div>
@endsection
