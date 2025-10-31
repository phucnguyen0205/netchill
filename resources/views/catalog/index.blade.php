@extends('layouts.app')

@section('content')
<style>
/* ===== Catalog look ===== */
.catalog-wrap{color:#e8eef6}
.filter-card{background:#202020;border:1px solid #2e3645;border-radius:12px}
.filter-card .section{display:flex;align-items:center;gap:14px;flex-wrap:wrap;padding:14px 18px;border-bottom:1px solid #2a3040}
.filter-card .section:last-child{border-bottom:0}
.filter-label{min-width:110px;color:#9aa6bd;font-weight:700}

.chips{display:flex;flex-wrap:wrap}
.chip{
  display:inline-flex;align-items:center;gap:6px;
  padding:8px 14px;border-radius:10px;background:#202020;
  color:#cfd6e4;text-decoration:none;font-weight:700;margin:6px 8px 0 0
}
.chip:hover{background:#202020;color:#ffd24b}
.chip.active{background:#202020;color:#ffd24b;position:relative}
.chip.active::after{content:"";position:absolute;inset:0;border-radius:10px;box-shadow:0 0 0 2px #f2cc68 inset}

/* Grid phim */
.grid-movies{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:18px}
.movie-card{background:#1a1a1a;border-radius:12px;overflow:hidden}
.movie-card .poster{position:relative}
.movie-card .poster img{width:100%;height:320px;object-fit:cover;display:block;/* bo 4 góc */border-radius:12px}
.movie-card .meta{padding:10px 12px}
.movie-title{color:#fff;text-align: center; font-weight:800;margin:0}
.movie-sub{color:#a6b0c3;font-size:.9rem;margin:4px 0 0; text-align:center;}

/* badge “P.Đề” hay các tag nhỏ */
.badge-corner{position:absolute;left:10px;bottom:10px;background:#2b3140;color:#e8eef6;
  border-radius:8px;padding:4px 8px;font-weight:700;font-size:.85rem}
/* Kill collapse slide animation */
.collapsing{
  transition: none !important;
  height: auto !important;      /* mở/đóng tức thì */
}
.chips{display:flex;flex-wrap:wrap}
.chip{
  display:inline-flex;align-items:center;gap:6px;
  padding:8px 14px;border-radius:10px;
  background:#202020; color:#cfd6e4; text-decoration:none;
  font-weight:700; margin:6px 8px 0 0;
  border:1px solid #2e3645;           /* viền nhẹ để phân tách */
}
:root { --header-h: 50px; }          /* md-: ~72px */
@media (min-width: 992px){
  :root { --header-h: 50px; }        /* lg+: ~80px */
}
.header-spacer {
  padding-top: calc(var(--header-h) + env(safe-area-inset-top, 0px) + 66px);
}

/* Khi cuộn/nhảy tới anchor trong catalog không bị che */
.catalog-wrap {
  scroll-margin-top: calc(var(--header-h) + 62px);
}
</style>
<div class="catalog-wrap container my-4 header-spacer">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="m-0 fw-bold">
      {{ $currentCategory?->name ? 'Phim ' . $currentCategory->name : 'Danh mục phim' }}
    </h2>
    <button class="btn btn-outline-warning btn-sm" data-bs-toggle="collapse" data-bs-target="#filterBox">
      <i class="bi bi-funnel"></i> Bộ lọc
    </button>
  </div>
  {{-- === FORM BỘ LỌC (GET) === --}}
  <form id="filterForm" method="GET" action="{{ route('catalog.index') }}">
    {{-- GIỮ LẠI QUERY KHÁC (nếu cần) --}}
    @foreach(request()->except(['page']) as $k => $v)
      @if(!in_array($k, ['country','type','rating','category','year','sort','q']))
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
      @endif
    @endforeach

    {{-- CÁC INPUT ẨN CHO CHIP --}}
    <input type="hidden" name="country"  value="{{ request('country') }}">
    <input type="hidden" name="type"     value="{{ request('type') }}">
    <input type="hidden" name="rating"   value="{{ request('rating') }}">
    <input type="hidden" name="category" value="{{ request('category') }}">
    <input type="hidden" name="year"     value="{{ request('year') }}">
    <input type="hidden" name="sort"     value="{{ request('sort','latest') }}">

    <div id="filterBox" class="filter-card mb-4 collapse show">

      {{-- Tên phim (lọc khi bấm Lọc kết quả) --}}


      {{-- Quốc gia --}}
      <div class="section">
        <div class="filter-label">Quốc gia:</div>
        <div class="chips">
          <button type="button" class="chip {{ request('country') ? '' : 'active' }}"
                  data-name="country" data-value="">Tất cả</button>
          @foreach($countries as $c)
            <button type="button"
                    class="chip {{ request('country')===$c->slug ? 'active' : '' }}"
                    data-name="country" data-value="{{ $c->slug }}">
              {{ $c->name }}
            </button>
          @endforeach
        </div>
      </div>

      {{-- Loại phim --}}
      <div class="section">
        <div class="filter-label">Loại phim:</div>
        <div class="chips">
          @php $type = request('type'); @endphp
          <button type="button" class="chip {{ !$type ? 'active':'' }}" data-name="type" data-value="">Tất cả</button>
          <button type="button" class="chip {{ $type==='movie' ? 'active':'' }}" data-name="type" data-value="movie">Phim lẻ</button>
          <button type="button" class="chip {{ $type==='series' ? 'active':'' }}" data-name="type" data-value="series">Phim bộ</button>
        </div>
      </div>

      {{-- Xếp hạng tuổi --}}
      <div class="section">
        <div class="filter-label">Xếp hạng:</div>
        <div class="chips">
          @php $rating = request('rating'); @endphp
          <button type="button" class="chip {{ !$rating ? 'active':'' }}" data-name="rating" data-value="">Tất cả</button>
          @foreach(['P','K','T13','T16','T18'] as $r)
            <button type="button" class="chip {{ $rating===$r ? 'active':'' }}"
                    data-name="rating" data-value="{{ $r }}">{{ $r }}</button>
          @endforeach
        </div>
      </div>

      {{-- Thể loại --}}
      <div class="section">
        <div class="filter-label">Thể loại:</div>
        <div class="chips">
          <button type="button" class="chip {{ request('category') ? '' : 'active' }}"
                  data-name="category" data-value="">Tất cả</button>
          @foreach($categories as $cat)
            <button type="button"
                    class="chip {{ request('category')===$cat->slug ? 'active':'' }}"
                    data-name="category" data-value="{{ $cat->slug }}">
              {{ $cat->name }}
            </button>
          @endforeach
        </div>
      </div>

      {{-- Năm sản xuất --}}
      <div class="section">
        <div class="filter-label">Năm sản xuất:</div>
        <div class="chips">
          <button type="button" class="chip {{ request('year') ? '' : 'active' }}"
                  data-name="year" data-value="">Tất cả</button>
          @foreach($years as $y)
            <button type="button"
                    class="chip {{ (int)request('year')===$y ? 'active':'' }}"
                    data-name="year" data-value="{{ $y }}">{{ $y }}</button>
          @endforeach
        </div>
      </div>

      {{-- Sắp xếp --}}
      <div class="section">
        <div class="filter-label">Sắp xếp:</div>
        <div class="chips">
          @php $sort = request('sort','latest'); @endphp
          <button type="button" class="chip {{ $sort==='latest' ? 'active':'' }}" data-name="sort" data-value="latest">Mới nhất</button>
          <button type="button" class="chip {{ $sort==='updated' ? 'active':'' }}" data-name="sort" data-value="updated">Mới cập nhật</button>
          <button type="button" class="chip {{ $sort==='imdb' ? 'active':'' }}"    data-name="sort" data-value="imdb">Điểm IMDb</button>
          <button type="button" class="chip {{ $sort==='views' ? 'active':'' }}"   data-name="sort" data-value="views">Lượt xem</button>
        </div>
      </div>

      <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-warning fw-semibold">
          Lọc kết quả
        </button>
        <a class="btn btn-outline-light" href="{{ route('catalog.index') }}">Đặt lại</a>
      </div>
    </div>
  </form>

  {{-- GRID PHIM --}}
  <div class="grid-movies">
    @forelse($movies as $m)
      <a class="movie-card text-decoration-none" href="{{ route('movies.show', $m->slug) }}">
        <div class="poster">
          <img src="{{ $m->poster ? Storage::url($m->poster) : asset('images/placeholder-poster.jpg') }}"
               alt="{{ $m->title }}">
          @if($m->version === 'sub')
            <span class="badge-corner">P.Đề</span>
          @endif
        </div>
        <div class="meta">
          <h6 class="movie-title text-truncate">{{ $m->title }}</h6>
          <div class="movie-sub text-truncate">
            {{ $m->english_title ?? $m->original_title ?? '' }}
          </div>
        </div>
      </a>
    @empty
      <p class="text-muted">Không có phim phù hợp.</p>
    @endforelse
  </div>

  <div class="mt-4">{{ $movies->links() }}</div>
</div>

{{-- JS đồng bộ chip -> input ẩn, chỉ submit khi bấm "Lọc kết quả" --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('filterForm');

  // click chip -> toggle active + set value vào input hidden
  document.querySelectorAll('.chip[data-name]').forEach(chip => {
    chip.addEventListener('click', () => {
      const name  = chip.getAttribute('data-name');
      const value = chip.getAttribute('data-value') ?? '';

      // bỏ active cùng nhóm
      document.querySelectorAll(`.chip[data-name="${name}"]`).forEach(c => c.classList.remove('active'));
      chip.classList.add('active');

      // set hidden
      const target = form.querySelector(`input[name="${name}"]`);
      if (target) target.value = value;
    });
  });

  // khi submit, xóa input rỗng để URL gọn
  form.addEventListener('submit', () => {
    form.querySelectorAll('input[type="hidden"]').forEach(i => {
      if (i.value === '') i.disabled = true;
    });
  });
});
</script>
@endpush
