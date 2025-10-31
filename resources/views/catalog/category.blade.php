@extends('layouts.app')

@section('content')
<style>
  .catalog-wrap{color:#e8eef6}
  .filter-card{background:#1f2430;border:1px solid #2e3645;border-radius:12px}
  .chip{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:10px;
        background:#2a3040;color:#cfd6e4;text-decoration:none;font-weight:600;margin:6px 8px 0 0}
  .chip:hover{background:#343c50;color:#ffd24b}
  .chip.active{background:#3a4156;color:#111;position:relative}
  .chip.active::after{content:"";position:absolute;inset:0;border-radius:10px;box-shadow:0 0 0 2px #f2cc68 inset}
  .chip.badge{padding:6px 12px;border-radius:999px}
  .grid-movies{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:18px}
  .movie-card{background:#1c212b;border-radius:12px;overflow:hidden}
  .movie-card img{width:100%;height:260px;object-fit:cover}
  .movie-card .meta{padding:10px 12px}
  .movie-title{color:#fff;font-weight:700;margin:0}
  .movie-sub{color:#a6b0c3;font-size:.9rem}
</style>

<div class="container catalog-wrap">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="m-0 fw-bold">
      {{ $currentCategory?->name ? 'Phim ' . $currentCategory->name : 'Danh mục phim' }}
    </h2>
    <button class="btn btn-outline-warning btn-sm" data-bs-toggle="collapse" data-bs-target="#filterBox">
      <i class="bi bi-funnel"></i> Bộ lọc
    </button>
  </div>

  {{-- Bộ lọc --}}
  <div class="collapse show" id="filterBox">
    <div class="filter-card p-3 mb-4">
      @php
        // helper url giữ query
        function qurl($add = []) {
          return request()->fullUrlWithQuery(array_filter($add, fn($v) => $v!==null && $v!==''));
        }
        $is = fn($key,$val) => request($key)==$val;
      @endphp

      {{-- Quốc gia --}}
      <div class="mb-3">
        <div class="fw-bold text-uppercase text-secondary mb-1">Quốc gia:</div>
        <a class="chip {{ request('country') ? '' : 'active' }}" href="{{ qurl(['country'=>null]) }}">Tất cả</a>
        @foreach($countries as $c)
          <a class="chip {{ $is('country',$c->slug)?'active':'' }}" href="{{ qurl(['country'=>$c->slug]) }}">{{ $c->name }}</a>
        @endforeach
      </div>

      {{-- Loại phim --}}
      <div class="mb-3">
        <div class="fw-bold text-uppercase text-secondary mb-1">Loại phim:</div>
        <a class="chip {{ request('type')? '' : 'active' }}" href="{{ qurl(['type'=>null]) }}">Tất cả</a>
        <a class="chip {{ $is('type','movie')?'active':'' }}" href="{{ qurl(['type'=>'movie']) }}">Phim lẻ</a>
        <a class="chip {{ $is('type','series')?'active':'' }}" href="{{ qurl(['type'=>'series']) }}">Phim bộ</a>
      </div>

      {{-- Xếp hạng tuổi --}}
      <div class="mb-3">
        <div class="fw-bold text-uppercase text-secondary mb-1">Xếp hạng:</div>
        @php $ratings=['P'=>'P (Mọi lứa tuổi)','K'=>'K (Dưới 13)','T13'=>'T13','T16'=>'T16','T18'=>'T18']; @endphp
        <a class="chip {{ request('rating')? '' : 'active' }}" href="{{ qurl(['rating'=>null]) }}">Tất cả</a>
        @foreach($ratings as $code=>$label)
          <a class="chip {{ $is('rating',$code)?'active':'' }}" href="{{ qurl(['rating'=>$code]) }}">{{ $label }}</a>
        @endforeach
      </div>

      {{-- Thể loại --}}
      <div class="mb-3">
        <div class="fw-bold text-uppercase text-secondary mb-1">Thể loại:</div>
        <a class="chip {{ request('category')? '' : 'active' }}" href="{{ qurl(['category'=>null]) }}">Tất cả</a>
        @foreach($categories as $cat)
          <a class="chip {{ $is('category',$cat->slug)?'active':'' }}" href="{{ qurl(['category'=>$cat->slug]) }}">{{ $cat->name }}</a>
        @endforeach
      </div>

      {{-- Phiên bản --}}
      <div class="mb-3">
        <div class="fw-bold text-uppercase text-secondary mb-1">Phiên bản:</div>
        <a class="chip {{ request('version')? '' : 'active' }}" href="{{ qurl(['version'=>null]) }}">Tất cả</a>
        <a class="chip {{ $is('version','sub')?'active':'' }}" href="{{ qurl(['version'=>'sub']) }}">Phụ đề</a>
        <a class="chip {{ $is('version','dub')?'active':'' }}" href="{{ qurl(['version'=>'dub']) }}">Lồng tiếng</a>
        <a class="chip {{ $is('version','voice_male')?'active':'' }}" href="{{ qurl(['version'=>'voice_male']) }}">Thuyết minh giọng Nam</a>
        <a class="chip {{ $is('version','voice_female')?'active':'' }}" href="{{ qurl(['version'=>'voice_female']) }}">Thuyết minh giọng Nữ</a>
      </div>

      {{-- Năm --}}
      <div class="mb-3">
        <div class="fw-bold text-uppercase text-secondary mb-1">Năm sản xuất:</div>
        <a class="chip {{ request('year')? '' : 'active' }}" href="{{ qurl(['year'=>null]) }}">Tất cả</a>
        @foreach($years as $y)
          <a class="chip badge {{ $is('year',$y)?'active':'' }}" href="{{ qurl(['year'=>$y]) }}">{{ $y }}</a>
        @endforeach
      </div>

      {{-- Sắp xếp --}}
      <div class="mb-2">
        <div class="fw-bold text-uppercase text-secondary mb-1">Sắp xếp:</div>
        <a class="chip {{ !$sortParam || $sortParam==='latest' ? 'active':'' }}" href="{{ qurl(['sort'=>'latest']) }}">Mới nhất</a>
        <a class="chip {{ $sortParam==='updated' ? 'active':'' }}" href="{{ qurl(['sort'=>'updated']) }}">Mới cập nhật</a>
        <a class="chip {{ $sortParam==='imdb' ? 'active':'' }}" href="{{ qurl(['sort'=>'imdb']) }}">Điểm IMDb</a>
        <a class="chip {{ $sortParam==='views' ? 'active':'' }}" href="{{ qurl(['sort'=>'views']) }}">Lượt xem</a>
      </div>

      <div class="mt-3 d-flex gap-2">
        <a class="btn btn-warning fw-semibold" href="{{ route('catalog.index') }}">
          Lọc kết quả
        </a>
        <a class="btn btn-outline-light" href="{{ route('catalog.index') }}">Đặt lại</a>
      </div>
    </div>
  </div>

  {{-- Lưới phim --}}
  <div class="grid-movies">
    @forelse($movies as $m)
      <a href="{{ route('movies.show', $m->slug) }}" class="movie-card text-decoration-none">
        <img src="{{ $m->poster_url ?? Storage::url($m->poster) }}" alt="{{ $m->title }}">
        <div class="meta">
          <p class="movie-title text-truncate">{{ $m->title }}</p>
          <div class="d-flex justify-content-between">
            <span class="movie-sub">{{ $m->original_title }}</span>
            <span class="badge text-bg-secondary">{{ $m->release_year }}</span>
          </div>
        </div>
      </a>
    @empty
      <div class="text-muted">Không tìm thấy phim phù hợp.</div>
    @endforelse
  </div>

  <div class="mt-4">
    {{ $movies->links() }}
  </div>
</div>
@endsection
