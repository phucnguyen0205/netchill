@extends('layouts.app')

@section('content')
<style>
  /* ====== Layout cố định với sidebar 280px ====== */
  .admin-layout {
    padding-left: 280px;     /* trừ chiều rộng sidebar cố định */
    min-height: 100vh;
    background: #0b0f16;
  }
  @media (max-width: 991.98px) {
    .admin-layout { padding-left: 0; } /* mobile: sidebar có thể ẩn/collapsible */
  }

  /* Trang thống kê */
  .stats-page { color: #f1f1f1; }
  .card.bg-dark { border: 1px solid rgba(255,255,255,.08); border-radius: 14px; }
  .table-dark.table-striped tbody tr:nth-of-type(odd) { --bs-table-accent-bg: rgba(255,255,255,.03); }
  .search-wrap { position: sticky; top: 0; z-index: 5; background: #0b0f16; padding-top: .25rem; padding-bottom: .5rem; }

  /* Input đẹp hơn chút */
  .form-control.bg-dim {
    background: #121722; border: 1px solid #fcf4a3; color: #e8eef8;
  }
  .form-control.bg-dim::placeholder { color: #9aa6b2; }
</style>

<div class="admin-layout">
  {{-- Sidebar cố định (đã fixed 280px ở partial) --}}
  @include('admin.partials.sidebar')

  <div class="container-fluid stats-page py-3">
    <div class="row">
      <div class="col-12">

        {{-- Thanh tìm kiếm: client-side tức thì + submit GET để server-side --}}
        <div class="search-wrap mb-3">
          <form method="GET" action="{{ route('admin.stats.index') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
              <input
                id="searchAll"
                name="q"
                value="{{ request('q') }}"
                type="text"
                class="form-control bg-dim"
                placeholder="Tìm phim trong bảng thống kê… (gõ để lọc tức thì hoặc Enter để tìm server-side)"
                autocomplete="off">
            </div>
            <div class="col-auto">
              <button class="btn btn-warning fw-semibold">Tìm</button>
            </div>
            @if(request('q'))
              <div class="col-auto">
                <a class="btn btn-outline-secondary" href="{{ route('admin.stats.index') }}">Xoá lọc</a>
              </div>
            @endif
          </form>
        </div>

        <h1 class="mb-4">Thống kê</h1>

        {{-- KPI --}}
        <div class="row g-3">
          <div class="col-md-3">
            <div class="card bg-dark text-light h-100">
              <div class="card-body">
                <div class="h6 text-white-50">Tổng người dùng</div>
                <div class="display-6">{{ $totalUsers }}</div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-dark text-light h-100">
              <div class="card-body">
                <div class="h6 text-white-50">Đăng ký Premium</div>
                <div class="display-6">{{ $preUsers }}</div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-dark text-light h-100">
              <div class="card-body">
                <div class="h6 text-white-50">Chưa Premium</div>
                <div class="display-6">{{ $nonPreUsers }}</div>
              </div>
            </div>
          </div>
        </div>

        <hr class="border-secondary my-4">

        {{-- Top Views --}}
        <h5 class="text-light">Top 10 phim theo lượt xem</h5>
        <div class="table-responsive">
          <table class="table table-dark table-striped align-middle movie-table" data-table="views">
            <thead>
              <tr><th style="width:60%">Phim</th><th class="text-end">Lượt xem</th></tr>
            </thead>
            <tbody>
            @foreach($topViews as $m)
              <tr>
                <td class="movie-title">{{ $m->title }}</td>
                <td class="text-end">{{ $m->watch_histories_count }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>

        {{-- Top Ratings --}}
        <h5 class="text-light mt-4">Top 10 phim theo lượt đánh giá</h5>
        <div class="table-responsive">
          <table class="table table-dark table-striped align-middle movie-table" data-table="ratings">
            <thead>
              <tr><th style="width:60%">Phim</th><th class="text-end">Lượt đánh giá</th></tr>
            </thead>
            <tbody>
            @foreach($topRatings as $m)
              <tr>
                <td class="movie-title">{{ $m->title }}</td>
                <td class="text-end">{{ $m->ratings_count }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>

        {{-- Top Interactions --}}
        <h5 class="text-light mt-4">Top 10 phim theo tương tác</h5>
        <div class="table-responsive">
          <table class="table table-dark table-striped align-middle movie-table" data-table="interactions">
            <thead>
              <tr>
                <th style="width:52%">Phim</th>
                <th class="text-end">Comments</th>
                <th class="text-end">Ratings</th>
                <th class="text-end">Tổng tương tác</th>
              </tr>
            </thead>
            <tbody>
            @foreach($topInteractions as $m)
              @php $sum = (int)($m->comments_count ?? 0) + (int)($m->ratings_count ?? 0); @endphp
              <tr>
                <td class="movie-title">{{ $m->title }}</td>
                <td class="text-end">{{ $m->comments_count }}</td>
                <td class="text-end">{{ $m->ratings_count }}</td>
                <td class="text-end">{{ $sum }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

{{-- Lọc tức thì client-side --}}
<script>
  (function(){
    const input = document.getElementById('searchAll');
    if(!input) return;

    const tables = Array.from(document.querySelectorAll('.movie-table'));

    const filter = () => {
      const q = (input.value || '').trim().toLowerCase();
      tables.forEach(tbl => {
        const rows = tbl.querySelectorAll('tbody tr');
        rows.forEach(tr => {
          const title = tr.querySelector('.movie-title')?.textContent?.toLowerCase() || '';
          tr.style.display = q ? (title.includes(q) ? '' : 'none') : '';
        });
      });
    };

    input.addEventListener('input', filter);
    // chạy 1 lần khi load (để hiển thị kết quả nếu có ?q từ server)
    filter();
  })();
</script>
@endsection
