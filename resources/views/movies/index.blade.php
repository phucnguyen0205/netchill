@extends('layouts.app')

@section('content')
<style>
  /* Banner chính */
.banner-custom {
    position: relative;
    width: 100%;
    height: 100vh; /* Full màn hình */
    overflow: hidden;
    margin-top: -80px; 
    z-index: 1;
}

.banner-custom img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Hiệu ứng mờ viền ngoài banner */
.banner-custom::after {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: radial-gradient(circle, rgba(0,0,0,0) 80%, rgba(0,0,0,0.7) 100%);
}
/* Thông tin trong banner */
.banner-info {
    position: absolute;
    bottom: 40%;
    left: 5%;
    z-index: 2;
    color: white;
    text-shadow: 0 2px 8px rgba(0,0,0,0.6);
}

/* Thumbnail chuyển sang bên phải */
.banner-thumbnails {
    position: absolute;
    right: 20px;
    bottom: 20px;
    display: flex;
    flex-direction: row; /* hàng ngang */
    gap: 10px;
    z-index: 3;
}

.banner-thumbnails img {
    width: 80px;
    height: 50px;
    object-fit: cover;
    border: 2px solid transparent;
    border-radius: 6px;
    cursor: pointer;
    transition: transform 0.3s, border-color 0.3s;
}

.banner-thumbnails img.active,
.banner-thumbnails img:hover {
    border-color: #ffc107;
    transform: scale(1.1);
}

.hide-scrollbar {
    -ms-overflow-style: none;  /* IE và Edge */
    scrollbar-width: none;     /* Firefox */
}
.hide-scrollbar::-webkit-scrollbar {
    display: none;             /* Chrome, Safari, Opera */
}
.title-wrapper h6 {
    height: 2.4em; /* 2 dòng x 1.2em mỗi dòng */
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* số dòng */
    -webkit-box-orient: vertical;
}
/* Ẩn scrollbar nhưng vẫn cho phép cuộn ngang */
.hide-scrollbar {
        -ms-overflow-style: none;  /* IE và Edge */
        scrollbar-width: none;     /* Firefox */
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;             /* Chrome, Safari, Opera */
    }
  /* Tạo stacking context riêng để z-index ổn định */
.hero-wrap{ position:relative; margin-top:-80px; height:100vh; overflow:hidden; isolation:isolate; }

/* Tầng ảnh nền */
.hero-img{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; transition:opacity .5s; z-index:0; }
.hero-img.fadeout{ opacity:0; }


.hero-info{ position:absolute; left:5vw; top:18vh; max-width:min(720px,60vw); color:#fff; z-index:15; }

/* Thumbnails: luôn nằm trên cùng và chắc chắn nhận click */
.hero-thumbs{ position:absolute; right:18px; bottom:18px; display:flex; gap:10px; z-index:20; pointer-events:auto; }
.hero-thumbs .thumb{
  width:110px; height:68px; object-fit:cover; border-radius:10px; cursor:pointer;
  border:2px solid transparent; box-shadow:0 4px 10px rgba(0,0,0,.35); transition:.2s;
}
.hero-thumbs .thumb.active{ border-color:#ffc107; box-shadow:0 0 0 3px rgba(255,193,7,.2), 0 10px 22px rgba(0,0,0,.5); }
.hero-info{position:absolute;left:5vw;top:18vh;max-width:min(720px,60vw);z-index:6;color:#fff}
.hero-title{font-weight:700;font-size:clamp(29px,7vw,84px);line-height:0.95;margin:0 0 8px}
.hero-subtitle{color:#e5e7eb;font-size:clamp(10px,2vw,20px);margin-bottom:12px}

.hero-badges{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px}
.badge{display:inline-flex;align-items:center;border-radius:14px;padding:.25rem .6rem;font-weight:700;font-size:.9rem}
.badge.imdb{background:#f5c518;color:#111}
.badge.solid{background:#fff;color:#111}
.badge.ghost{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);backdrop-filter:blur(3px)}

.hero-chips{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px}
.chip{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.24);
  padding:.25rem .55rem;border-radius:999px;font-size:.85rem}


.hero-actions{display:flex;align-items:center;gap:10px}
.btn.hero-play{width:64px;height:64px;display:inline-grid;place-items:center;border-radius:999px;
  background:linear-gradient(135deg,#fddb00,#f2c700);box-shadow:0 10px 28px rgba(253,219,0,.35)}
.btn.hero-play i{font-size:28px;color:#111}
.btn.hero-round{width:54px;height:54px;display:inline-grid;place-items:center;border-radius:999px;
  background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);backdrop-filter:blur(4px);color:#fff}

  .hero-thumbs{ 
    position:absolute; 
    right:18px; 
    top: 550px;
    bottom: 40px; 
    display:flex; 
    gap:10px; 
    z-index:20; 
    pointer-events:auto; 
}

.hero-thumbs .thumb{
    /* Thu nhỏ kích thước: ví dụ 90x55px */
    width: 90px; 
    height: 55px; 
    object-fit:cover; 
    border-radius:10px; 
    cursor:pointer;
    border:2px solid transparent; 
    box-shadow:0 4px 10px rgba(0,0,0,.35); 
    transition:.2s;
}
  border:2px solid transparent;box-shadow:0 4px 10px rgba(0,0,0,.35);transition:.2s}
.hero-thumbs .thumb:hover{transform:translateY(-2px)}
.hero-thumbs .thumb.active{border-color:#ffc107;box-shadow:0 0 0 3px rgba(255,193,7,.2), 0 10px 22px rgba(0,0,0,.5)}
.hero-bg{position:absolute; inset:0; overflow:hidden; z-index:0; isolation:isolate;}
.hero-img{
  position:absolute; inset:0; width:100%; height:100%; object-fit:cover;
  opacity:0; transition:opacity .8s ease;
  will-change: opacity, transform;
}
.hero-img.show{ opacity:1; }

.banner-dots{
  --gap: 5px;         /* khoảng cách giữa các chấm (giảm = dày hơn) */
  --dot: 0.8px;        /* kích thước chấm */
  --alpha: .90;        /* độ trong suốt (cao hơn = đậm hơn) */
  --color: 120,120,120; /* MÀU XÁM ĐẬM */

  position: absolute;
  inset: 0;
  z-index: 2;
  pointer-events: none;

  background-image:
    radial-gradient(rgba(var(--color),var(--alpha)) var(--dot), transparent var(--dot)),
    radial-gradient(rgba(var(--color),var(--alpha)) var(--dot), transparent var(--dot));
  background-size: var(--gap) var(--gap);
  background-position: 0 0, calc(var(--gap)/2) calc(var(--gap)/2);
  opacity: .35;            /* tổng độ đậm của lớp */
  mix-blend-mode: multiply; /* hòa với nền theo kiểu tối nhẹ */
}

.banner-vignette{
  position:absolute; inset:0; z-index:3; pointer-events:none;
  background:
    /* 1. Gradient Xuyên tâm (giữ tâm sáng) */
    radial-gradient(circle at center, rgba(0,0,0,0.1) 60%, rgba(0,0,0,0.4) 100%),
    linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.05) 20%),
    /* 2. ĐỔ BÓNG NỐI LỚP (TỪ DƯỚI LÊN): Sử dụng màu #1a1a1a */
    linear-gradient(to top, 
      /* Màu #1a1a1a hoàn toàn (alpha 1) tại đáy 0% */
      rgba(26, 26, 26, 1) 0%, 
      /* Màu #1a1a1a đậm kéo dài đến 15% chiều cao */
      rgba(26, 26, 26, 0.7) 15%, 
      /* Mờ dần về trong suốt tại 40% */
      rgba(26, 26, 26, 0) 40%); 
}
.movie-card-vertical {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
}
.movie-card-vertical img {
    width: 60px;
    height: 90px;
    object-fit: cover;
    border-radius: 8px;
}
.movie-card-vertical .rank {
    font-size: 2.2rem;
    font-weight: 800;
    color: #ffc107;
    margin-right: 8px;
    min-width: 30px;
    text-align: center;
}
.movie-card-vertical .title {
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.movie-card-vertical .subtitle {
    font-size: 0.85rem;
    color: #9aa3b2;
}
.top10-carousel {
    display: flex;
    overflow-x: auto; /* Cho phép cuộn ngang */
    gap: 24px; /* Khoảng cách giữa các phim */
    padding-bottom: 20px; /* Thêm padding nếu có scrollbar */
    -ms-overflow-style: none;  /* IE và Edge */
    scrollbar-width: none;     /* Firefox */
}
.top10-carousel::-webkit-scrollbar {
    display: none;             /* Chrome, Safari, Opera */
}

/* Mỗi item trong carousel */
.top10-carousel-item {
    flex-shrink: 0; /* Không co lại khi cuộn */
    width: 240px; /* Chiều rộng cố định cho mỗi item (poster + info) */
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
}

/* Poster chính */
.top10-poster-wrap {
    position: relative;
    width: 100%;

    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 15px;
    height: 360px;
}
.top10-poster-wrap img {
    width: 100%;
    height: 100%;
    /* THAY ĐỔI TẠI ĐÂY */
    object-fit: contain; /* Đảm bảo toàn bộ ảnh được nhìn thấy */
    background-color: #1a1a1a; /* Thêm màu nền tối cho vùng trống nếu có */
}
/* Badges PD/TM trên poster */
.top10-badges-overlay {
    position: absolute;
    bottom: 15px;
    left: 15px;
    display: flex;
    gap: 8px;
}
.top10-badge {
    background: rgba(0,0,0,0.6);
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
}
.top10-badge.pd { background-color: #007bff; } /* Màu xanh */
.top10-badge.tm { background-color: #28a745; } /* Màu xanh lá */

/* Rank, Tên phim, Chi tiết nằm dưới poster */
.top10-info-block {
    display: flex;
    align-items: flex-start; /* Căn chỉnh số rank và text */
    gap: 12px; /* Khoảng cách giữa số rank và text */
}
.top10-rank-number-lg {
    font-size: 3.5rem; /* Số rank rất lớn */
    font-weight: 900;
    color: #ffc107; /* Màu vàng */
    line-height: 1;
    min-width: 40px; /* Đảm bảo căn chỉnh */
    text-align: right;
}
.top10-text-content-new {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.top10-title-main {
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 3px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Giới hạn 2 dòng cho tiêu đề chính */
    -webkit-box-orient: vertical;
}
.top10-title-eng {
    color: #9aa3b2;
    font-size: 0.9rem;
    margin-bottom: 6px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.top10-details-footer-new {
    display: flex;
    gap: 8px;
    align-items: center;
    color: #9aa3b2; /* Màu xám cho chi tiết tập */
    font-size: 0.85rem;
    font-weight: 600;
}
.top10-details-footer-new .divider {
    color: #4a5468; /* Màu xám đậm hơn cho dấu chấm */
    font-weight: 900;
}
.top10-details-footer-new .label-text {
    color: #9aa3b2;
    font-weight: 400;
}
.hide-scrollbar {
    -ms-overflow-style: none;  /* IE và Edge */
    scrollbar-width: none;     /* Firefox */
}
.hide-scrollbar::-webkit-scrollbar {
    display: none;             /* Chrome, Safari, Opera */
}
/* Style cho các khối phim mới (Hàn, Trung) */
.movie-grid-4 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
}

/* Styles cho phần Top Bình luận */
.top-comment-item {
    width: 260px; /* Cố định width */
    flex-shrink: 0;
    padding: 15px;
    background: #1f2533;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.top-comment-user {
    display: flex;
    align-items: center;
    gap: 10px;
}
.top-comment-user img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
.top-comment-user-info {
    font-weight: 600;
    color: #fff;
}
.top-comment-content {
    color: #c0c6d2;
    font-size: 0.9rem;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3; /* Giới hạn 3 dòng */
    -webkit-box-orient: vertical;
}
.top-comment-footer {
    display: flex;
    gap: 15px;
    font-size: 0.85rem;
    color: #9aa3b2;
    margin-top: auto;
}
.side-list-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,.05);
}
.side-list-item:last-child { border-bottom: none; }
.side-list-item .rank-number {
    font-size: 1.4rem;
    font-weight: 700;
    color: #ffc107;
    min-width: 25px;
    text-align: center;
}
.side-list-item img {
    width: 48px;
    height: 72px;
    object-fit: cover;
    border-radius: 4px;
}
.country-carousel {
    display: flex;
    overflow-x: auto; 
    gap: 110px; /* Khoảng cách giữa các phim */
    padding-bottom: 10px; 
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.country-carousel::-webkit-scrollbar {
    display: none;
}

/* Mỗi item trong carousel (phim Hàn/Trung) */
.country-carousel-item {
    flex-shrink: 0;
    width: 280px; /* Chiều rộng item lớn hơn để chứa poster tỉ lệ rộng */
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
}

/* Poster tỉ lệ rộng */
.country-poster-wrap{
  position: relative;
  width: 130%;                /* ⬅️ bỏ 130% gây tràn/đè */
  height: 210px;
  border-radius: 10px;
  overflow: hidden;           /* vẫn bo góc, không lộ viền ảnh */
  background: #11151c;        /* nền tối lấp vùng trống khi contain */
  display: flex;              /* để dễ canh giữa nếu dùng contain */
  align-items: center;
  justify-content: center;
  box-shadow: 0 6px 18px rgba(0,0,0,.25);
}
.country-poster-wrap img{
  width: 100%;
  height: 100%;
  object-fit: contain;        /* ⬅️ hiển thị toàn bộ ảnh, không bị cắt */
  object-position: center;    /* ⬅️ luôn giữa khung */
  display: block;
}
.country-title-main {
    font-size: 1.05rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 2px;
    text-align: center; /* Đảm bảo căn giữa */
    max-width: 100%;
}
.country-title-eng {
    color: #9aa3b2;
    font-size: 0.9rem;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    text-align: center; /* Đảm bảo căn giữa */
    max-width: 100%;
}
.comment-panel{background:#161a22;border:1px solid #2a3040;border-radius:16px;padding:16px}
  .comment-list{display:flex;flex-direction:column;gap:14px}
  .comment-item{
    display:flex;align-items:flex-start;gap:7px;
    background:#0f141c;border:1px solid #263044;border-radius:18px;
    padding:14px 6px; box-shadow:0 5px 14px rgba(0,0,0,.25);
  }
  .comment-body{flex:1; min-width:0}
  .comment-avatar{width:30px;height:30px;border-radius:999px;object-fit:cover;flex:0 0 38px}
  .comment-head{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
  .comment-name{color:#e9eef8;font-weight:400}
  .comment-time{color:#a2acbd;font-size:.9rem}
  .comment-movie i{margin-right:6px}
  .comment-movie{color:#9aa3b2;font-size:.13rem;text-decoration:none}
  .comment-text{color:#cfd6e4; white-space:nowrap;          /* chỉ 1 dòng */
  overflow:hidden;
  text-overflow:ellipsis; margin:2px 0 0}
  .clamp-1{
  display:block;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}
.panel .side-list-item { padding:6px 0; }
.panel .side-list-item img { width:36px; height:54px; border-radius:4px; object-fit:cover; }
.panel .rank-number { min-width:22px; }
.comment-panel .comment-list{
    height: 420px;             /* ← chỉnh số này theo thiết kế */
    overflow-y: hidden;        /* ẩn phần vượt */
    position: relative;
  }
  .comment-item{
    display:flex; gap:12px; padding:12px;
    background:#11151d; border:1px solid #222a38; border-radius:12px;
    transition: transform .35s ease, opacity .35s ease;
  }
  .comment-item + .comment-item{ margin-top:10px; }
  .comment-avatar{ width:40px; height:40px; border-radius:50%; object-fit:cover }
  .comment-body .clamp-1{
    overflow:hidden; text-overflow:ellipsis; white-space:nowrap;  /* 1 dòng */
  }
  /* hiệu ứng xuất hiện từ trên xuống */
  .slide-in{ transform: translateY(-12px); opacity:0; }
  .slide-in.show{ transform: translateY(0); opacity:1; }
/* Ken-Burns nhẹ */
@keyframes kbZoom{
  0%   { transform: scale(1)   translate3d(0,0,0); }
  100% { transform: scale(1.08) translate3d(0,0,0); }
}
@media (max-width: 992px){
  .hero-info{top:14vh;max-width:80vw}
  .hero-thumbs .thumb{width:88px;height:54px}
}
@media (max-width: 576px){
  .hero-info{left:16px;right:16px;top:auto;bottom:18vh}
  .hero-title{font-size: clamp(28px,9vw,40px)}
  .hero-thumbs{left:16px;right:16px;justify-content:center;flex-wrap:wrap}
}

</style>
@php
  // GIỮ NGUYÊN PHẦN LOGIC HERO BANNER VÀ $featured
  $featured = \App\Models\Movie::with(['banners' => fn($q)=>$q->latest(), 'categories:id,name'])
              ->latest()->take(6)->get();

  $bannersOf = function($m){
      $arr = [];
      foreach(($m->banners ?? []) as $b){
          if(!empty($b->image_path)) $arr[] = \Storage::url($b->image_path);
      }
      if (empty($arr) && !empty($m->poster)) $arr[] = \Storage::url($m->poster);
      if (empty($arr)) $arr[] = asset('images/banner-fallback.jpg');
      return $arr;
  };

  $first = $featured->first();
  $firstBanners = $first ? $bannersOf($first) : [asset('images/banner-fallback.jpg')];

  // LOGIC MOCK DATA CHO CÁC PHẦN MỚI (CẦN THAY THẾ BẰNG DỮ LIỆU THẬT)
  $top10 = \App\Models\Movie::where('is_series', 1)
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get()
              ->map(function($m, $i) {
                  $m->rank = $i + 1;
                  return $m;
              });

              $newKorean = \App\Models\Movie::with(['banners:id,movie_id,image_path,created_at'])
  ->whereHas('countries', fn($q) => $q->where('name', 'Hàn Quốc'))
  ->latest()->take(4)->get();

// Tương tự cho Phim Trung Quốc
$newChinese = \App\Models\Movie::with(['banners:id,movie_id,image_path,created_at'])
  ->whereHas('countries', fn($q) => $q->where('name', 'Trung Quốc'))
  ->latest()->take(4)->get();

  $topComments = \App\Models\Comment::with(['user:id,name','movie:id,slug,title'])
    ->when(\Schema::hasColumn('comments','likes_count'),
        fn($q) => $q->orderByDesc('likes_count')->orderByDesc('created_at'),
        fn($q) => $q->orderByDesc('created_at')
    )
    ->take(5)->get();
  $hotMovies = \App\Models\Movie::orderBy('updated_at', 'desc')->take(5)->get(); 

// Dữ liệu cho phần YÊU THÍCH NHẤT (FAVORITE)
// Giữ nguyên, giả định cột favorites_count tồn tại
$favMovies = \App\Models\Movie::withCount('favorites') 
                              ->orderBy('favorites_count', 'desc')
                              ->take(5)
                              ->get();
  $recentComments = \App\Models\Comment::with('user', 'movie')->latest()->take(5)->get();

@endphp
@if($featured->count())
<section class="hero-wrap">
<div class="hero-bg">
  <img id="heroImgA" class="hero-img show" src="{{ $firstBanners[0] }}" alt="hero a">
  <img id="heroImgB" class="hero-img"        src="{{ $firstBanners[0] }}" alt="hero b">
</div>
<div class="banner-overlay"></div>
  <div class="banner-dots"></div> 
  <div class="banner-vignette"></div> 

  {{-- INFO BOX trên banner --}}
  <div class="hero-info">
    <h1 id="heroTitle" class="hero-title">{{ $first->title }}</h1>
    @if(!empty($first->english_title))
      <div id="heroSubtitle" class="hero-subtitle">{{ $first->english_title }}</div>
    @else
      <div id="heroSubtitle" class="hero-subtitle d-none"></div>
    @endif

    <div class="hero-badges">
      <span class="badge imdb">IMDb {{ number_format(($first->imdb_rating ?? (($first->ratings_avg_stars ?? 0)*2)),1) }}</span>
      @if(!empty($first->age_rating)) <span class="badge solid">{{ $first->age_rating }}</span>@endif
      @if(!empty($first->release_year)) <span class="badge ghost">{{ $first->release_year }}</span>@endif
      @if(!empty($first->duration)) <span class="badge ghost">{{ $first->duration }}</span>@endif
    </div>

    <div id="heroChips" class="hero-chips">
      @foreach(($first->categories?->pluck('name')->take(6) ?? collect()) as $name)
        <span class="chip">{{ $name }}</span>
      @endforeach
    </div>
    <p id="heroDesc" class="hero-desc text-white">
  {{ \Illuminate\Support\Str::limit($first->description, 220) }}
</p>

    <div class="hero-actions">
      @php
        // Play URL giống logic bạn dùng
        $playUrl = route('movies.detai', $first->slug);
      @endphp
      <a id="heroLink" href="{{ $playUrl }}" class="btn hero-play">
        <i class="bi bi-play-fill"></i>
      </a>
      <button class="btn hero-round"><i class="bi bi-heart"></i></button>
      <button class="btn hero-round"><i class="bi bi-info-lg"></i></button>
    </div>
  </div>

  {{-- 6 thumbnails (đem đủ metadata để cập nhật info khi click) --}}
  <div class="hero-thumbs">
    @foreach($featured as $i => $m)
    @php
  $arr = array_values($bannersOf($m));
@endphp
<img
  class="thumb {{ $i===0 ? 'active':'' }}"
  src="{{ $arr[0] }}"
  alt="{{ $m->title }}"
  data-index="{{ $i }}"
  data-title="{{ e($m->title) }}"
  data-subtitle="{{ e($m->english_title ?? '') }}"
  data-link="{{ route('movies.detai', $m->slug) }}"
  data-banners='@json($arr)'
  data-imdb="{{ number_format(($m->imdb_rating ?? (($m->ratings_avg_stars ?? 0)*2)),1) }}"
  data-age="{{ $m->age_rating ?? '' }}"
  data-year="{{ $m->release_year ?? '' }}"
  data-duration="{{ $m->duration ?? '' }}"
  data-chips='@json(($m->categories?->pluck("name")->take(6) ?? collect())->values())'
  data-desc="{{ e(\Illuminate\Support\Str::limit($m->description, 220)) }}"
>

    @endforeach
  </div>
</section>
@endif
<div class="container-fluid py-4">
<h1 class="text-center mb-4">Danh sách Phim</h1>
    <div class="d-flex overflow-auto hide-scrollbar" style="gap: 32px;">
        @foreach($movies->chunk(2) as $twoMovies)
            <div class="d-flex flex-column flex-shrink-0" style="gap: 40px;">
                @foreach($twoMovies as $movie)
                    <div class="card bg-dark text-light border-0 text-start" style="width: 200px;">
                        <a href="{{ route('movies.detai', $movie->slug) }}">
                            
                            <img src="{{ Storage::url($movie->poster) }}" 
                                 alt="{{ $movie->title }}" 
                                 
                                 class="img-fluid rounded" 
                                 style="object-fit: contain; height: 300px; width: 100%; background-color: #1a1a1a;">
                                 
                        </a>
                        
                        <div class="title-wrapper mt-2 px-1">
                            {{-- Tên Tiếng Việt --}}
                            <h6 class="card-title text-light mb-0 text-center">{{ Str::title($movie->title) }}</h6>
                            
                            {{-- THÊM TÊN TIẾNG ANH --}}
                            @if(!empty($movie->english_title))
                                <p class="top10-title-eng small mb-0 text-center">{{ $movie->english_title }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    
    {{-- Pagination links --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $movies->links('pagination::bootstrap-5') }}
    </div>
</div>
    <div class="d-flex overflow-auto hide-scrollbar" style="gap: 32px;">
        </div>

    {{-- Pagination links --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $movies->links('pagination::bootstrap-5') }}
    </div>
</div>

<div class="container-fluid">
    <hr class="my-5 bg-secondary">
    
    {{-- ======================================================= --}}
    {{-- 🔥 PHẦN 1: TOP 10 PHIM HÔM NAY (Theo Screenshot 1) --}}
    {{-- ======================================================= --}}
    <div class="mb-5">
        <h2 class="text-light fs-3 mb-4">Top 10 phim bộ hôm nay</h2>
        
        <div class="top10-carousel hide-scrollbar">
            @forelse($top10 as $movie)
            <a href="{{ route('movies.detai', $movie->slug) }}" class="top10-carousel-item">
                
                {{-- KHỐI POSTER --}}
                <div class="top10-poster-wrap">
                    <img src="{{ Storage::url($movie->poster) }}" alt="{{ $movie->title }}">
                    
                    {{-- Badges PD/TM (Ví dụ: PD. 36 TM. 28) --}}
                    <div class="top10-badges-overlay">
                        @php
                            // Giả định $movie->episodes_total và $movie->episodes_new_count
                            $episodesTotal = $movie->episodes ? $movie->episodes->count() : 0;
                            // Để hiển thị số tập đang phát (PD) và tập mới (TM), bạn cần có dữ liệu này
                            // Ví dụ: $movie->progress_episode_count và $movie->new_episode_count
                            $pdCount = $movie->progress_episode_count ?? ($episodesTotal > 0 ? $episodesTotal : 'N/A');
                            $tmCount = $movie->new_episode_count ?? 'N/A'; // Số tập mới (hoặc tập gần nhất)
                        @endphp
                        <span class="top10-badge pd">PD. {{ $pdCount }}</span>
                        <span class="top10-badge tm">TM. {{ $tmCount }}</span>
                    </div>
                </div>
                {{-- KHỐI INFO DƯỚI POSTER --}}
                <div class="top10-info-block">
                    {{-- SỐ RANK LỚN --}}
                    <span class="top10-rank-number-lg">{{ $movie->rank }}</span>

                    <div class="top10-text-content-new">
                        {{-- Tên Tiếng Việt --}}
                        <div class="top10-title-main">{{ $movie->title }}</div>
                        
                        {{-- Tên Tiếng Anh --}}
                        <div class="top10-title-eng">{{ $movie->english_title ?? 'N/A' }}</div>
                        
                        {{-- CHI TIẾT TẬP/PHẦN (TNN • Phần 1 • Tập XX) --}}
                        <div class="top10-details-footer-new">
                            @php
                                // Giả định $movie->latest_episode (số tập mới nhất)
                                // $movie->season_number (phần)
                                // $movie->episodes->count() (tổng số tập)
                                $latestEpLabel = $movie->latest_episode ? 'T' . $movie->latest_episode : 'TN/A'; 
                                $seasonNumber = $movie->season_number ?? 1; // Giả sử Phần 1
                                $totalEpisodeCount = $movie->episodes ? $movie->episodes->count() : 'N/A'; // Tổng số tập
                            @endphp

                            <span>{{ $latestEpLabel }}</span>
                            <span class="divider">•</span>
                            <span><span class="label-text">Phần</span> {{ $seasonNumber }}</span>
                            <span class="divider">•</span>
                            <span><span class="label-text">Tập</span> {{ $totalEpisodeCount }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
                <p class="text-muted">Danh sách top 10 phim bộ đang được cập nhật.</p>
            @endforelse
        </div>
    </div>
    <hr class="my-5 bg-secondary">
    {{-- Phần Phim Mới Nhất ban đầu của bạn (Giữ nguyên) --}}
    <h2 class="text-light">Phim Mới Nhất</h2>
<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-4">
    @foreach($latestMovies as $movie)
        <div class="col">
            <div class="card bg-dark text-light border-0 text-start h-100">
                <div class="poster-wrapper mb-2">
                    <a href="{{ route('movies.detai', $movie->slug) }}">
                        <img src="{{ Storage::url($movie->poster) }}" 
                             alt="{{ $movie->title }}" 
                             class="img-fluid rounded" 
                             style="object-fit: contain; height: 300px; width: 100%; background-color: #1a1a1a;">
                    </a>
                </div>
                <div class="title-wrapper px-1">
                    {{-- Tên Tiếng Việt --}}
                    <h6 class="text-light mb-0 text-center">{{ Str::title($movie->title) }}</h6>
                    {{-- Tên Tiếng Anh (ĐÃ THÊM) --}}
                    @if(!empty($movie->english_title))
                        <p class="top10-title-eng mb-0 text-center">{{ $movie->english_title }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
    <hr class="my-5 bg-secondary">
    {{-- ======================================================= --}}
    {{-- 🔥 PHẦN 2: PHIM HÀN QUỐC MỚI & PHIM TRUNG QUỐC MỚI (MẪU CAROUSEL) --}}
    {{-- ======================================================= --}}
    <div class="row mb-5">
        {{-- Phim Hàn Quốc mới --}}
        <div class="col-12 mb-5">
            <h2 class="text-light fs-3 mb-3">Phim Hàn Quốc mới</h2>
            <div class="country-carousel hide-scrollbar">
                @forelse($newKorean as $movie)
                <a href="{{ route('movies.detai', $movie->slug) }}" class="country-carousel-item">
                    {{-- KHỐI POSTER --}}
                    @php
  $bannerPath = optional($movie->banners->sortByDesc('created_at')->first())->image_path;
  $imgUrl = $bannerPath
              ? Storage::url($bannerPath)
              : ($movie->poster
                   ? Storage::url($movie->poster)
                   : asset('images/banner-fallback.jpg'));
@endphp
<div class="country-poster-wrap">
  <img src="{{ $imgUrl }}" alt="{{ $movie->title }}">
</div>

                    {{-- INFO DƯỚI POSTER --}}
                    <div class="country-text-content">
                        <div class="country-title-main text-center">{{ $movie->title }}</div>
                        <div class="country-title-eng text-center">{{ $movie->english_title ?? 'N/A' }}</div>
                    </div>
                </a>
                @empty
                    <p class="text-muted">Đang cập nhật phim Hàn mới.</p>
                @endforelse
            </div>
        </div>

        {{-- Phim Trung Quốc mới --}}
        <div class="col-12 mt-4">
            <h2 class="text-light fs-3 mb-3">Phim Trung Quốc mới</h2>
            <div class="country-carousel hide-scrollbar">
                @forelse($newChinese as $movie)
                <a href="{{ route('movies.detai', $movie->slug) }}" class="country-carousel-item">
                    {{-- KHỐI POSTER --}}
                    @php
  $bannerPath = optional($movie->banners->sortByDesc('created_at')->first())->image_path;
  $imgUrl = $bannerPath
              ? Storage::url($bannerPath)
              : ($movie->poster
                   ? Storage::url($movie->poster)
                   : asset('images/banner-fallback.jpg'));
@endphp
<div class="country-poster-wrap">
  <img src="{{ $imgUrl }}" alt="{{ $movie->title }}">
</div>

                    {{-- INFO DƯỚI POSTER --}}
                    <div class="country-text-content">
                        <div class="country-title-main text-center">{{ $movie->title }}</div>
                        <div class="country-title-eng text-center">{{ $movie->english_title ?? 'N/A' }}</div>
                    </div>
                </a>
                @empty
                    <p class="text-muted">Đang cập nhật phim Trung mới.</p>
                @endforelse
            </div>
        </div>
    </div>
    <hr class="my-5 bg-secondary">

    {{-- ======================================================= --}}
    {{-- 🔥 PHẦN 3: TOP BÌNH LUẬN & DANH SÁCH (Theo Screenshot 4) --}}
    {{-- ======================================================= --}}
    <div class="mb-5">
    <h2 class="text-light fs-3 mb-4">Top Bình luận</h2>
        <div class="row">
        {{-- ====== 3 cột: Sôi nổi / Yêu thích / Bình luận mới ====== --}}
<div class="row g-4 align-items-stretch">

  {{-- SÔI NỔI NHẤT (TRÁI) --}}
  <div class="col-12 col-lg-3 order-lg-1">   {{-- ↓ từ 4 xuống 3 --}}
    <div class="panel h-100 p-3" style="background:#161a22;border:1px solid #2a3040;border-radius:16px;">
      <h4 class="text-light fs-5 mb-3">
        <i class="bi bi-fire text-warning me-2"></i>SÔI NỔI NHẤT
      </h4>
      <div class="d-flex flex-column gap-2">
        @forelse($hotMovies as $i => $movie)
          <a href="{{ route('movies.detai', $movie->slug) }}" class="side-list-item text-decoration-none">
            <span class="rank-number">{{ $i + 1 }}.</span>
            <img src="{{ Storage::url($movie->poster) }}" alt="{{ $movie->title }}">
            <span class="text-light">{{ $movie->title }}</span>
          </a>
        @empty
          <p class="text-muted">Đang cập nhật...</p>
        @endforelse
      </div>
    </div>
  </div>

  {{-- YÊU THÍCH NHẤT (GIỮA) --}}
  <div class="col-12 col-lg-3 order-lg-2">   {{-- ↓ từ 4 xuống 3 --}}
    <div class="panel h-100 p-3" style="background:#161a22;border:1px solid #2a3040;border-radius:16px;">
      <h4 class="text-light fs-5 mb-3">
        <i class="bi bi-heart-fill text-warning me-2"></i>  {{-- 🔁 đổi sang vàng --}}
        YÊU THÍCH NHẤT
      </h4>
      <div class="d-flex flex-column gap-2">
        @forelse($favMovies as $i => $movie)
          <a href="{{ route('movies.detai', $movie->slug) }}" class="side-list-item text-decoration-none">
            <span class="rank-number">{{ $i + 1 }}.</span>
            <img src="{{ Storage::url($movie->poster) }}" alt="{{ $movie->title }}">
            <span class="text-light">{{ $movie->title }}</span>
          </a>
        @empty
          <p class="text-muted">Đang cập nhật...</p>
        @endforelse
      </div>
    </div>
  </div>

  {{-- BÌNH LUẬN MỚI (PHẢI) --}}
  <div class="col-12 col-lg-6 order-lg-3">   {{-- ↑ từ 4 lên 6 --}}
    <div class="comment-panel h-100">
      <h4 class="text-light fs-5 mb-3">
        <i class="bi bi-lightning-charge text-warning me-2"></i>BÌNH LUẬN MỚI
      </h4>
      <div id="recentCommentsList" class="comment-list">
        @foreach($recentComments as $c)
          @php
            $raw = $c->user->avatar ?? $c->user->profile_photo_path ?? null;
            $avatar = $raw
              ? (Str::startsWith($raw, ['http://','https://']) ? $raw : Storage::url($raw))
              : 'https://i.pravatar.cc/150?u='.urlencode($c->user->email ?? $c->user->id ?? $c->id);
            $oneLine = trim(preg_replace('/\s+/', ' ', strip_tags($c->content ?? '')));
          @endphp
          <div class="comment-item">
            <img class="comment-avatar" src="{{ $avatar }}" alt="avatar">
            <div class="comment-body">
              <div class="comment-head">
                <strong class="text-white">{{ $c->user->name }}</strong>
                <span class="text-secondary small">{{ $c->created_at->diffForHumans() }}</span>
              </div>
              <div class="d-flex align-items-center gap-2 text-secondary small">
                <i class="bi bi-play-fill text-warning"></i>
                <a href="{{ route('movies.detai', $c->movie->slug) }}" class="text-decoration-none text-secondary clamp-1">
                  {{ $c->movie->title }}
                </a>
              </div>
              <p class="mb-0 text-light clamp-1" title="{{ $oneLine }}">{{ \Illuminate\Support\Str::limit($oneLine, 180) }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

</div>
    <hr class="my-5 bg-secondary">
</div>
@endsection
@push('scripts')
<script>
   (function(){
  const list   = document.getElementById('recentCommentsList');
  if(!list) return;

  let sinceMs  = Date.now();          // mốc thời gian lần nhận gần nhất
  const POLL   = 6000;                // mỗi 6s
  const MAX_ITEMS = 20;               // giữ tối đa để không phình DOM
  const GAP_PX = 10;                  // đúng bằng margin giữa item (ở CSS)

  function buildItem(c){
    // avatar thật (ưu tiên path trong DB; nếu là relative -> Storage::url server sẽ render sẵn):
    const avatar = c.avatar;
    const user   = (c.user_name || 'Người dùng');
    const time   = (c.time_ago || '');
    const movieTitle = (c.movie_title || '');
    const movieUrl   = c.movie_slug ? ("{{ url('/movies') }}/"+c.movie_slug) : "#";
    const oneLine = (c.content || '').replace(/\s+/g,' ').trim();
    const url = c.movie_slug ? `{{ url('/') }}/movies/${c.movie_slug}` : '#';
    const div = document.createElement('div');
    div.className = 'comment-item slide-in';
    div.innerHTML = `
       <div class="comment-item" data-id="${c.id}">
        <img class="comment-avatar" src="${c.avatar}" alt="avatar">
        <div class="flex-grow-1">
          <div class="comment-head">
            <span class="comment-name">${escapeHtml(c.user_name || 'Người dùng')}</span>
            <span class="text-warning">∞</span>
            <span class="comment-time">${escapeHtml(c.time_ago || '')}</span>
          </div>
          <a class="comment-movie d-inline-flex align-items-center mt-1" href="${url}">
            <i class="bi bi-play-fill"></i>${escapeHtml(c.movie_title || 'Không rõ')}
          </a>
          <p class="comment-text mb-0">${escapeHtml(c.content || '')}</p>
        </div>
      </div>`;
    return div;
  }

  function prependAndScroll(items){
    if(!items || !items.length) return;
    // Chèn theo thứ tự thời gian (mới nhất nằm trên cùng)
    items.slice().reverse().forEach(c=>{
      const node = buildItem(c);
      list.prepend(node);
      // kích hoạt hiệu ứng
      requestAnimationFrame(()=> node.classList.add('show'));
      // giữ số lượng tối đa
      while(list.children.length > MAX_ITEMS){
        list.lastElementChild?.remove();
      }
      // cuộn xuống đúng bằng chiều cao item vừa thêm để tạo cảm giác “đẩy xuống”
      const h = node.getBoundingClientRect().height;
      list.scrollBy({ top: h + GAP_PX, behavior: 'smooth' });
    });
  }

  async function poll(){
    try{
      const url = new URL("{{ route('comments.feed') }}", window.location.origin);
      url.searchParams.set('since', sinceMs.toString());
      const res = await fetch(url.toString(), { headers: { 'X-Requested-With':'XMLHttpRequest' }});
      if(!res.ok) throw new Error('HTTP '+res.status);
      const data = await res.json();   // {mode, since, comments:[...]}

      if(Array.isArray(data.comments) && data.comments.length){
        // cập nhật mốc thời gian do server trả
        if(typeof data.since === 'number') sinceMs = data.since;
        prependAndScroll(data.comments);
      }
    }catch(e){
      // im lặng, thử lại chu kỳ sau
      // console.debug('poll error', e);
    }finally{
      setTimeout(poll, POLL);
    }
  }

  // Khởi động: đảm bảo khung không nhảy chiều cao
  list.style.scrollBehavior = 'auto';
  setTimeout(()=>{ list.style.scrollBehavior = 'smooth'; }, 0);

  poll();
})();
(function(){
  const heroA   = document.getElementById('heroImgA');
  const heroB   = document.getElementById('heroImgB');
  const heroWrap= document.querySelector('.hero-wrap');
  const thumbs  = Array.from(document.querySelectorAll('.hero-thumbs .thumb'));
  const thumbsBox = document.querySelector('.hero-thumbs');

  // Info nodes
  const heroTitle = document.getElementById('heroTitle');
  const heroSub   = document.getElementById('heroSubtitle');
  const heroDesc  = document.getElementById('heroDesc');
  const heroLink  = document.getElementById('heroLink');
  const chipsWrap = document.getElementById('heroChips');
  const badges    = document.querySelector('.hero-badges');

  // ====== Cấu hình auto next ======
  const INTERVAL = 10000;              // 10 giây
  let currentMovie = 0;
  let frontIsA = true;
  let loop = null;

  function crossfadeTo(src){
    const front = frontIsA ? heroA : heroB;
    const back  = frontIsA ? heroB : heroA;
    if (!src) return;

    back.classList.remove('show');
    back.src = src;
    // force reflow để chuyển động mượt
    void back.offsetWidth;
    back.classList.add('show');
    front.classList.remove('show');
    frontIsA = !frontIsA;
  }

  function updateInfo(thumb){
    if (!thumb) return;
    // title + subtitle
    if (heroTitle) heroTitle.textContent = thumb.dataset.title || '';
    const sub = (thumb.dataset.subtitle || '').trim();
    if (heroSub){
      heroSub.textContent = sub;
      heroSub.classList.toggle('d-none', !sub);
    }
    // badges
    if (badges){
      const imdb = thumb.dataset.imdb || '';
      const age  = thumb.dataset.age  || '';
      const year = thumb.dataset.year || '';
      const dura = thumb.dataset.duration || '';
      badges.innerHTML = `
        ${imdb ? `<span class="badge imdb">IMDb ${imdb}</span>` : ``}
        ${age  ? `<span class="badge solid">${age}</span>`      : ``}
        ${year ? `<span class="badge ghost">${year}</span>`     : ``}
        ${dura ? `<span class="badge ghost">${dura}</span>`     : ``}
      `;
    }
    // chips
    if (chipsWrap){
      chipsWrap.innerHTML = '';
      try{
        (JSON.parse(thumb.dataset.chips || '[]')||[]).forEach(n=>{
          const el = document.createElement('span');
          el.className='chip'; el.textContent=n;
          chipsWrap.appendChild(el);
        });
      }catch{}
    }
    // desc + link
    if (heroDesc) heroDesc.textContent = thumb.dataset.desc || '';
    if (heroLink) heroLink.href = thumb.dataset.link || '#';
  }

  function setMovie(i){
    currentMovie = ((i%thumbs.length)+thumbs.length)%thumbs.length;
    thumbs.forEach(t=>t.classList.remove('active'));
    const t = thumbs[currentMovie];
    if (t) t.classList.add('active');

    // banner đầu của movie hiện tại
    let src = t ? t.src : '';
    try{
      const banners = JSON.parse(t.dataset.banners || '[]');
      if (banners.length) src = banners[0];
    }catch{}

    crossfadeTo(src);
    updateInfo(t);
  }

  function nextMovie(){
    setMovie(currentMovie + 1);
  }

  function startLoop(){
    stopLoop();
    loop = setInterval(nextMovie, INTERVAL);
  }
  function stopLoop(){
    if (loop){ clearInterval(loop); loop = null; }
  }

  // Click thumbnail -> chuyển ngay, reset timer
  thumbsBox?.addEventListener('click', e=>{
    const t = e.target.closest('.thumb');
    if (!t) return;
    const idx = Number(t.dataset.index || 0);
    setMovie(idx);
    startLoop();
  });

  // Pause khi hover hero
  heroWrap?.addEventListener('mouseenter', stopLoop);
  heroWrap?.addEventListener('mouseleave', startLoop);

  // Khởi tạo
  if (heroA && heroB && thumbs.length){
    heroA.classList.add('show');
    setMovie(0);
    startLoop(); // tự động: cứ 10s nhảy sang thumbnail kế tiếp (đồng bộ banner)
  }
})();
</script>
@endpush