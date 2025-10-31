@extends('layouts.app')

@section('content')
<style>
  body {
background-color: #0d1217;
color: #d1d5db;
}


.movie-content {
position: relative;
z-index: 10;
}
.movie-poster {
border-radius: 12px;
box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
}

/* === BUTTONS (ACTION & PLAY) === */
.btn-action-group .btn {
background-color: #2c333a;
border-color: #2c333a;
color: #fff;
padding: 1rem 2rem;
font-weight: bold;
}


.btn-play,
.btn-action-group .btn-play {
background-image: linear-gradient(to right, #fddb00, #f2c700);
color: #1a1a1a !important;
border: none !important;
padding: 1rem 2.5rem !important; / Chọn padding lớn nhất /
border-radius: 50px !important;
font-weight: 700 !important; / Chọn font-weight 700/bold /
font-size: 1.2rem !important;
box-shadow: 0 5px 15px rgba(253, 219, 0, 0.4);
transition: all 0.3s ease;
}
.btn-play:hover,
.btn-action-group .btn-play:hover {
transform: translateY(-2px);
box-shadow: 0 8px 20px rgba(253, 219, 0, 0.6);
}
.btn-play .bi-play-fill,
.btn-action-group .btn-play .bi-play-fill {
font-size: 1.5rem; 
margin-right: 0.5rem;

}

.btn-action-icon {
background: none;
border: none;
color: #d1d5db;
font-size: 14px;
cursor: pointer;
text-align: center;
}
.btn-action-icon .bi {
font-size: 1.5rem;
display: block;

}

/* === NAVIGATION & UTILITY === */
.nav-tabs {
border-bottom: 2px solid #2c333a;
}
.nav-tabs .nav-link {
color: #d1d5db;
border: none;
background: none;
}
.nav-tabs .nav-link.active {
background-color: #2c333a !important;
border-color: #2c333a;
color: #fff;
border-radius: 8px 8px 0 0;
}
.dropdown-item {
color: #d1d5db;
}
.dropdown-item:hover {
background-color: #2c333a;
color: #fff;
}
.dropdown-toggle::after{ display:none; }
.badge-rounded-pill-dark {
background-color: #1e252e;
border: 1px solid #2c333a;
color: #d1d5db;
}
.form-switch .form-check-input:checked {
background-color: #ffc107;
border-color: #ffc107;
}
.fav-active {
color: #ffd45a !important;
}
.btn.btn-link.text-secondary { color: #9aa4b2 !important; }
.btn.btn-link.text-secondary:hover { color: #cbd5e1 !important; }
.more-toggle i{ font-size:1rem; }

.episode-button:hover {
background-color: #2c333a;
}
.episode-button i {
margin-right: .45rem;
font-size: 1rem;
}

/* SEASON BAR / META */
.season-bar{display:flex;align-items:center;gap:12px;margin-bottom:14px}
.season-title{display:flex;align-items:center;gap:10px;color:#ffe27a;font-weight:800;font-size:1.6rem}
.season-title i{font-size:1.5rem}

/* META PILLS (Badges nhỏ) */
.meta-row{display:flex;gap:.2rem;flex-wrap:wrap}
.meta-pill{
color:#fff;
border:1px solid rgba(255,255,255,.8);
background:transparent;
padding:.15rem .45rem;
border-radius:10px;
font-size:.8rem;
font-weight:600
}
.meta-pill.primary{background:#fff;color:#111;border-color:#fff;padding:.18rem .5rem}

/* AIRED PILL (Hộp tổng tập đã chiếu) */
.aired-pill{
display:inline-flex;
align-items:center;
gap:.35rem;
background:linear-gradient(180deg,#2a231c,#221c15);
border:1px solid rgba(255,153,0,.25);
color:#ffb15a;
border-radius:18px;
padding:.35rem .6rem;
font-size:.9rem;
font-weight:600
}
.aired-pill i{font-size:1rem}

/* CHIP OUTLINE (Viền trắng) */
.chip-outline{
border:1px solid rgba(255,255,255,.7);
color:#fff;
background:transparent;
border-radius:12px;
padding:.35rem .65rem;
display:inline-flex;
align-items:center;
gap:.4rem
}
.chip-outline strong{font-weight:700}
.chip-outline + .chip-outline{margin-left:.5rem}

/* === COMMENT SECTION === */
.comment-section {
margin: 8px 0 !important;
}
.comment-section h4 {
color: #fff;
font-weight: 600;
display: flex;
align-items: center;
gap: 10px;
}
.comment-section .comment-tabs {
display: inline-flex;
border: 1px solid #3d4a57;
border-radius: 8px;
overflow: hidden;
}
.comment-section .comment-tabs button {
background-color: #1e252e;
border: none;
color: #d1d5db;
padding: 8px 15px;
font-size: 1rem;
transition: background-color 0.3s;
}
.comment-section .comment-tabs button.active {
background-color: #3d4a57;
color: #fff;
}


.comment-input-area { 
background-color: #1e252e;
border-radius: 8px;
padding: 1rem;
border: 1px solid #3d4a57;
margin-top: 10px !important;
}
.comment-input-area .inner-box {
background-color: #1a1a1a;
border-radius: 8px;
padding: 1rem;
}
.comment-input-area textarea {
background-color: transparent;
border: none;
color: #d1d5db;
width: 100%;
resize: vertical;
padding: 0;
outline: none;
}
.comment-input-area .comment-actions { 
display: flex;
justify-content: flex-end;
align-items: center;
margin-top: 1rem;
}
.comment-input-area .comment-actions .btn-send {
background: none;
border: none;
color: #fddb00;
font-size: 1.2rem;
font-weight: bold;
display: flex;
align-items: center;
gap: 0.5rem;
cursor: pointer;
transition: color 0.3s ease;
}
.comment-input-area .comment-actions .btn-send:hover {
color: #f2c700;
}


.comment-list .comment-item { 
gap: 15px;
padding: 1rem 0;
border-bottom: 1px solid #2c333a;
position: relative;
overflow: visible;
}
.comment-list .comment-item:last-child {
border-bottom: none;
}
.comment-list .comment-avatar { 
width: 45px;
height: 45px;
border-radius: 50%;
object-fit: cover;
}
.comment-list .comment-content {
flex-grow: 1;
position: relative;
overflow: visible;
}
.comment-list .comment-header {
display: flex;
align-items: center;
gap: 10px;
margin-bottom: 5px;
}
.comment-list .comment-header .comment-author {
font-weight: bold;
color: #fff;
}
.comment-list .comment-header .comment-meta {
font-size: 0.8rem;
color: #7f8994;
}
.comment-list .comment-body p {
margin-bottom: 5px;
}
.comment-badge {
background-color: #2c333a;
color: #d1d5db;
padding: 3px 8px;
border-radius: 12px;
font-size: 0.8rem;
}


.comment-actions { 
display: flex;
align-items: center;
gap: 16px;
font-size: 0.9rem;
}
.comment-actions .btn-like,
.comment-actions .btn-dislike {
color:#7f8994;
transition: color .15s ease, font-weight .15s ease;
font-weight:500;
cursor:pointer;
}
.comment-actions .btn-like.active,
.comment-actions .btn-dislike.active {
color: var(--bs-warning, #fddb00);
font-weight:700;
}
.comment-actions .btn-like i,
.comment-actions .btn-dislike i {
color: inherit;
}
.comment-actions button {
background: none;
border: none;
color: #7f8994;
cursor: pointer;
transition: color 0.3s;
}
.comment-actions button:hover {
color: #d1d5db;
}


.comment-reply {
margin-left: 0 !important; 
}
.replies-toggle { 
font-size: 0.9rem;
color: #fddb00;
cursor: pointer;
margin-top: 5px;
text-decoration: none;
display: inline-flex;
align-items: center;
gap: .25rem;
}
.replies-toggle i {
display: inline-block;
transition: transform 0.3s;
}
.replies-toggle.collapsed i {
transform: rotate(0deg);
}
.replies-toggle:not(.collapsed) i {
transform: rotate(180deg);
}

/* === GALLERY === */
.gallery-grid{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
gap:14px;
}
.g-tile{position:relative;overflow:hidden;border-radius:12px}
.g-tile.poster{aspect-ratio:2/3;}
.g-tile.banner{ aspect-ratio: 21 / 9; }
.g-tile img{
position:absolute; inset:0;
width:100%;
height:100%;
object-fit:cover;
}

/* === RECOMMEND === */
.rec-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:18px}
.rec-card{position:relative;background:#141a22;border:1px solid #252c36;border-radius:14px;overflow:hidden}
.rec-card img{width:100%;height:230px;object-fit:cover;display:block}
.rec-title{font-weight:600;color:#e6ebf3;font-size:.95rem;padding:.6rem .6rem 0}
.rec-sub{color:#94a3b8;font-size:.8rem;padding:0 .6rem .7rem}
.chip{position:absolute;left:8px;bottom:8px;background:rgba(0,0,0,.55);backdrop-filter:blur(6px);
color:#fff;border:1px solid rgba(255,255,255,.15);border-radius:999px;padding:.15rem .5rem;font-size:.75rem}
.chip + .chip{left:auto;right:8px}


.mention{ 
background:#2b3240; color:#cbd5e1; padding:2px 6px; border-radius:6px;
font-size:.85rem; font-weight:500;
}
.custom-toast{
background:#222;
color:#fff;
border-radius:12px;
padding:12px 16px;
box-shadow:0 8px 24px rgba(0,0,0,.35);
font-size:14px;
margin-top:8px;
display:flex;
align-items:center;
gap:8px;
opacity:0;
transform:translateX(100%);
transition:all .35s ease;
border:1px solid rgba(255,255,255,.08);
}
.custom-toast.show{
opacity:1;
transform:translateX(0);
}
.custom-toast .toast-icon{
font-size:1.1rem;
}
.custom-toast.success{ border-color:rgba(40,167,69,.35); }
.custom-toast.info{ border-color:rgba(255,193,7,.45); }
/* === FIX Gallery: đặt CUỐI FILE === */

/* Grid chuẩn, responsive */
.gallery-grid{
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 14px;
}

/* Mỗi ô chứa ảnh, bo góc + ẩn tràn */
.g-tile{
  position: relative;
  overflow: hidden;
  border-radius: 12px;
  background: #12171d;
  border: 1px solid rgba(255,255,255,.08);
}

/* Tỷ lệ khung: poster dọc, banner ngang (đều R-E-S-P-O-N-S-I-V-E) */
.g-tile.poster{ aspect-ratio: 2 / 3; }   /* 600x900 kiểu dọc */
.g-tile.banner{ aspect-ratio: 21 / 9; }  /* 2100x900 kiểu ngang */

/* Ảnh luôn FULL khung ô (không cố định px) */
.gallery-grid .g-tile > img{
  position: absolute; inset: 0;
  width: 100% !important;
  height: 100% !important;
  object-fit: cover;
  display: block;
  border-radius: 0;         /* bo góc đã ở .g-tile */
}

/* Hủy mọi rule cũ gây khóa kích thước (nếu vẫn còn ở trên) */
.gallery-grid img{ width: auto; height: auto; }


</style>

@php
  // ====== HERO BANNER (không quảng cáo) ======
  if (!isset($bannerPath)) {
      if ($movie->relationLoaded('banners')) {
          $hero = $movie->banners->firstWhere('variant','hero') ?? $movie->banners->first();
      } else {
          $hero = \App\Models\Banner::where('movie_id',$movie->id)
                    ->where('variant','hero')->latest()->first()
               ?? \App\Models\Banner::where('movie_id',$movie->id)->latest()->first();
      }
      $bannerPath = $hero?->image_path ?? ($movie->banner ?: null);
  }

  // ====== SEASONS / EPISODES ======
  if ($movie->relationLoaded('seasons')) {
      $seasonNumbers   = $movie->seasons->pluck('season_number')->filter()->sort()->values();
      $plannedEpisodes = $movie->seasons->sum('total_episodes');
  } else {
      $seasonNumbers   = \App\Models\Season::where('movie_id',$movie->id)
                          ->orderBy('season_number')->pluck('season_number');
      $plannedEpisodes = \App\Models\Season::where('movie_id',$movie->id)->where('movie_id',$movie->id)->sum('total_episodes');
  }
  $seasonBadge = $seasonNumbers->isEmpty() ? 'Phim bộ' : ('Phần '.$seasonNumbers->implode(', '));

  if ($movie->relationLoaded('episodes')) {
      $episodesCount = $movie->episodes->count();
  } else {
      $episodesCount = $movie->episodes()->count();
  }

  // ====== GALLERY SOURCES (poster + mọi banner) ======
  $galleryItems = collect();

// Poster luôn là ảnh dọc
if ($movie->poster) {
    $galleryItems->push([
        'url'  => Storage::url($movie->poster),
        'type' => 'poster'
    ]);
}

// Banner hero (ảnh ngang)
if (!empty($bannerPath) && (!$movie->poster || Storage::url($movie->poster) !== Storage::url($bannerPath))) {
    $galleryItems->push([
        'url'  => Storage::url($bannerPath),
        'type' => 'banner'
    ]);
}

// Banner phụ (ảnh ngang khác hero)
$banners = $movie->relationLoaded('banners')
    ? $movie->banners
    : \App\Models\Banner::where('movie_id', $movie->id)->get();

foreach ($banners as $b) {
    if ($b->image_path && Storage::url($b->image_path) !== Storage::url($bannerPath)) {
        $galleryItems->push([
            'url'  => Storage::url($b->image_path),
            'type' => 'banner'
        ]);
    }
}

// Loại trùng ảnh
$galleryItems = $galleryItems->unique('url')->values();

  // ====== RECOMMENDATIONS (có thể truyền từ Controller là $recommendations) ======
  /** @var \Illuminate\Support\Collection|\App\Models\Movie[] $recommendMovies */
  $recommendMovies = isset($recommendations) && $recommendations instanceof \Illuminate\Support\Collection
      ? $recommendations
      : \App\Models\Movie::query()
          ->when($movie->categories && $movie->categories->count(), function($q) use ($movie){
              $catIds = $movie->categories->pluck('id');
              $q->whereHas('categories', fn($qq)=>$qq->whereIn('categories.id',$catIds));
          })
          ->where('id','!=',$movie->id)
          ->latest()->take(12)->get();

  // Version label map
  $verLabel = ['sub'=>'Phụ đề','dub'=>'Lồng tiếng','raw'=>'RAW'];
@endphp
@php
  // Lấy tất cả ảnh banner (ngang) của phim
  $bannerUrls = collect($galleryItems ?? collect())
      ->where('type','banner')->pluck('url')->values()->all();

  // Fallback (nếu không có banner nào)
  if (empty($bannerUrls)) {
      if (!empty($movie->poster)) {
          $bannerUrls = [ Storage::url($movie->poster) ];
      } elseif (!empty($bannerPath)) {
          $bannerUrls = [ Storage::url($bannerPath) ];
      } else {
          $bannerUrls = [ asset('images/banner-fallback.jpg') ];
      }
  }
@endphp

<style>
  
  .movie-banner{position:relative;height:620px;}
  .banner-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(13,18,23,1),rgba(13,18,23,.55))}
  .movie-content{position:relative}
  .movie-poster{width: 85%;height: 85%;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.4)}
  .banner-overlay{ pointer-events:none; }

/* đảm bảo phần nội dung nổi lên trên banner */
.movie-content{ position:relative; z-index:2; }
.movie-banner{ position:relative; z-index:1; }
/* đề phòng: buộc nút & ô tập nhận sự kiện chuột */
.btn-play,
.episode-button{ position:relative; z-index:3; pointer-events:auto; }
  .btn-play{background-image:linear-gradient(to right,#fddb00,#f2c700);color:#1a1a1a!important;border:none!important;
            padding:.9rem 2.4rem!important;border-radius:50px!important;font-weight:700!important;font-size:1.1rem!important}
  .btn-play:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(253,219,0,.55)}
  .badge-rounded-pill-dark{background:#1e252e;border:1px solid #2c333a;color:#d1d5db}
  .episode-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(125px,1fr));gap:1rem}
  .episode-button{display:block;background:#1e252e;color:#fff;border:1px solid #2c333a;border-radius:10px;padding:.70rem;text-decoration:none}
  .episode-button:hover{background:#2c333a}
  .nav-tabs{border-bottom:2px solid #2c333a}
  .nav-tabs .nav-link{color:#d1d5db;border:none;background:none}
  .nav-tabs .nav-link.active{background:#2c333a!important;color:#fff;border-radius:8px 8px 0 0}
  .rec-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:18px}
  .rec-card{position:relative;background:#141a22;border:1px solid #252c36;border-radius:14px;overflow:hidden}
  .rec-card img{width:100%;height:230px;object-fit:cover;display:block}
  .rec-title{font-weight:600;color:#e6ebf3;font-size:.95rem;padding:.6rem .6rem 0}
  .rec-sub{color:#94a3b8;font-size:.8rem;padding:0 .6rem .7rem}
  .chip{position:absolute;left:8px;bottom:8px;background:rgba(0,0,0,.55);backdrop-filter:blur(6px);
        color:#fff;border:1px solid rgba(255,255,255,.15);border-radius:999px;padding:.15rem .5rem;font-size:.75rem}
  .chip + .chip{left:auto;right:8px}
  .fav-active{color:#ffd45a!important}
  /* Comments (giữ nguyên tinh gọn) */
  .comment-section h4{color:#fff;font-weight:600;display:flex;align-items:center;gap:10px}
  .comment-tabs{display:inline-flex;border:1px solid #3d4a57;border-radius:8px;overflow:hidden}
  .comment-tabs button{background:#1e252e;border:0;color:#d1d5db;padding:8px 14px}
  .comment-tabs button.active{background:#3d4a57;color:#fff}
  .comment-input-area{background:#1e252e;border:1px solid #3d4a57;border-radius:10px;padding:12px}
  .comment-item{display:flex;gap:14px;padding:14px 0;border-bottom:1px solid #2c333a}
  .comment-avatar{width:45px;height:45px;border-radius:50%;object-fit:cover}
  .comment-actions{display:flex;align-items:center;gap:16px}
  .replies-toggle{display:inline-flex;align-items:center;gap:.25rem;color:#fddb00;text-decoration:none}
/* Tăng kích cỡ banner chi tiết (responsive) */
.hero-detail{
  position: relative;
  height: 72vh;          /* cao hơn, đúng tỉ lệ màn hình */
  min-height: 560px;     /* không quá thấp ở màn nhỏ */
  max-height: 82vh;      /* tránh quá cao trên monitor lớn */
  margin-top: -80px;
  overflow: hidden;
  isolation: isolate;
}

/* Tầng ảnh nền (z:0) — giữ nguyên */
.hero-bg{ position:absolute; inset:0; overflow:hidden; z-index:0; }
.hero-img{
  position:absolute; inset:0; width:100%; height:100%; object-fit:cover;
  opacity:0; transition:opacity .8s ease; will-change:opacity, transform;
}
.hero-img.show{ opacity:1; }

/* Gradient nền mềm (z:1) — nằm DƯỚI lưới để lưới hiện rõ */
.banner-overlay{
  position:absolute; inset:0; z-index:1; pointer-events:none;
  background: linear-gradient(180deg, rgba(13,18,23,0) 35%, rgba(13,18,23,.85) 100%);
}
.banner-dots{
  --gap: 5px;         /* khoảng cách giữa các chấm (giảm = dày hơn) */
  --dot: 0.8px;        /* kích thước chấm */
  --alpha: .70;        /* độ trong suốt (cao hơn = đậm hơn) */
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
/* Viền tối xung quanh (z:3) — ôm lấy toàn bộ banner + lưới */
.banner-vignette{
  position:absolute; inset:0; z-index:3; pointer-events:none;
  background:
    radial-gradient(circle at center, rgba(0,0,0,0.1) 65%, rgba(0,0,0,0.35) 100%),
    linear-gradient(to bottom, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.15) 90%),
    linear-gradient(to top, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.1) 60%);
}
.rating-star {
    font-size: 1.1rem; /* Thay thế CSS inline cũ */
    transition: opacity 0.2s ease;
    opacity: 0.7; /* Mặc định hơi mờ */
    margin: 0 1px;
}

/* Hiệu ứng khi hover lên nút chứa các ngôi sao */
#openRatingModal:hover .rating-star {
    opacity: 1; /* Sáng rõ khi hover */
    transform: scale(1.05);
}

/* (Tuỳ chọn) Đổi màu nền của nút khi hover để làm nổi bật hơn */
#openRatingModal:hover {
    background-color: #2c333a; /* Màu nền tối hơn 1 chút */
    border-color: #2c333a;
}
#modalStars .rating-active {
    color: #f2cc68 !important; /* Dùng màu vàng từ nút Gửi/Đóng modal của bạn */
    /* Hoặc dùng màu vàng của Bootstrap: color: var(--bs-warning, #ffc107) !important; */
}
/* Đảm bảo sao rỗng có màu xám */
#modalStars .bi-star {
    color: #7f8994 !important; /* Màu xám/secondary */
}
/* Tinh chỉnh mobile */
@media (max-width: 768px){
  .hero-detail{
    height: 64vh;
    min-height: 440px;
  }
  .banner-grid{ background-size: 32px 32px; opacity: .24; }
}

</style>
<div class="movie-detail">
  {{-- Banner (no ads) --}}
  <div class="movie-banner hero-detail position-relative">
  <div class="hero-bg">
    <img id="dHeroA" class="hero-img show" src="{{ $bannerUrls[0] }}" alt="banner a">
    <img id="dHeroB" class="hero-img"        src="{{ $bannerUrls[0] }}" alt="banner b">
  </div>

  <div class="banner-overlay"></div>
  <div class="banner-dots"></div>  {{-- lớp chấm --}}
  <div class="banner-vignette"></div>
</div>


</div>
  {{-- Content --}}
  <section class="container movie-content py-4">
    <div class="row g-4">
      {{-- LEFT --}}
      <div class="col-md-3">
        <div class="text-center">
          <img src="{{ $movie->poster ? Storage::url($movie->poster) : asset('images/placeholder-poster.jpg') }}"
               alt="{{ $movie->title }}" class="img-fluid rounded shadow movie-poster">
          <h3 class="mt-3 text-white">{{ $movie->title }}</h3>
          <p class="text-muted mb-2">{{ $movie->english_title ?? '—' }}</p>

          {{-- Badges --}}
          <div class="meta-row mb-3 justify-content-center">
  <span class="meta-pill primary">{{ $movie->age_rating ?? 'P' }}</span>
  <span class="meta-pill">{{ $movie->release_year ?? 'N/A' }}</span>

  @if($movie->is_series)
    <span class="meta-pill">{{ $seasonBadge }}</span>
    <span class="meta-pill">Tập: {{ $episodesCount }}{{ $plannedEpisodes ? ' / '.$plannedEpisodes : '' }}</span>
  @endif

  <span class="meta-pill">
    {{ $verLabel[$movie->version] ?? strtoupper($movie->version) }}
  </span>
</div>

{{-- Categories --}}
          <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
            @php $categories = $movie->categories ?? collect(); @endphp
            @if($categories->isNotEmpty())
              @foreach($categories as $cat)
                <span class="badge badge-rounded-pill-dark rounded-pill">{{ $cat->name }}</span>
              @endforeach
            @else
              <span class="badge badge-rounded-pill-dark rounded-pill">Đang cập nhật</span>
            @endif
          </div>
            @php
              $shown = (int) ($episodesCount ?? 0);
              $total = (int) ($plannedEpisodes ?? 0);
            @endphp
            @if($movie->is_series && $total > 0)
              <div class="aired-pill mb-3">
                <i class="bi bi-arrow-clockwise"></i>
                Đã chiếu: {{ $shown }} / {{ $total }} tập
              </div>
            @endif
        </div>
   {{-- Intro --}}
        <div class="mt-4 text-start">
          <h5 class="text-white">Giới thiệu</h5>
          {{-- Dùng operator ?: để hiển thị mặc định nếu $movie->description rỗng --}}
          <p class="text-white">{{ $movie->description ?: 'Đang cập nhật nội dung.' }}</p>
          
          {{-- Thời lượng --}}
          @if(!empty($movie->duration))
            <p class="text-light mb-1">
              <strong>Thời lượng:</strong> {{ $movie->duration }}
            </p>
          @endif
          
          {{-- Quốc gia (Ưu tiên hiển thị danh sách nếu có nhiều, nếu không thì dùng $movie->country) --}}
          @if($movie->countries && $movie->countries->count())
            <p class="text-light mb-1">
              <strong>Quốc gia:</strong> {{ $movie->countries->pluck('name')->join(', ') }}
            </p>
          @elseif(optional($movie->country)->name)
             <p class="text-light mb-1">
              <strong>Quốc gia:</strong> {{ $movie->country->name }}
            </p>
          @else
             <p class="text-light mb-1">
              <strong>Quốc gia:</strong> Đang cập nhật
            </p>
          @endif
          
          {{-- Sản xuất --}}
          @if(!empty($movie->production))
            <p class="text-light mb-0">
              <strong>Sản xuất:</strong> {{ $movie->production }}
            </p>
          @endif
        </div>
      </div>
      {{-- RIGHT --}}
      <div class="col-md-9">
        {{-- Actions + Rating summary --}}
        <div class="d-flex align-items-center gap-3 flex-wrap mb-4">
         {{-- CTA xem ngay --}}
          <a href="{{ route('movies.show', $movie->slug) }}" class="btn btn-lg btn-action-group btn-play">
            <i class="bi bi-play-fill"></i> Xem Ngay
          </a>
          @php $isFav = auth()->check() && auth()->user()->hasFavoritedModel($movie); @endphp
          <button id="btnFav" class="btn-action-icon"
                  data-type="movie" data-id="{{ $movie->id }}"
                  data-toggle-url="{{ route('favorites.toggle') }}"
                  @guest data-login-modal="#memberModal" @endguest>
            <i id="favIcon" class="bi {{ $isFav ? 'bi-heart-fill fav-active' : 'bi-heart' }}"></i>
            <span id="favText">{{ $isFav ? 'Đã thích' : 'Yêu thích' }}</span>
          </button>

          {{-- Add to list dropdown --}}
          <div class="dropdown d-inline-block ms-auto" id="addToListDropdownWrap">
  <button
    class="btn-action-icon dropdown-toggle"
    data-bs-toggle="dropdown"
    aria-expanded="false"
    id="btnAddToList"
    data-movie-id="{{ $movie->id }}"
    @guest data-login-url="{{ route('login') }}" @endguest
  >
    <i class="bi bi-plus-circle"></i>
    <span>Thêm vào</span>
  </button>
  <div class="dropdown-menu dropdown-menu-end p-3"
       style="width: 340px; background:#2b3140; border:none; border-radius:14px;">
    {{-- ===== Styles cục bộ cho dropdown này ===== --}}
    <style>
      #addToListDropdownWrap .wl-label     { color:#cfd3dc; font-weight:700; }
      #addToListDropdownWrap .wl-hint      { color:#9aa3b2; font-size:.85rem; }
      #addToListDropdownWrap .wl-input     { background:#10131a; border:1px solid #2a2f3a; border-radius:10px; color:#cfd3dc; }
      #addToListDropdownWrap .wl-input::placeholder { color:#9aa3b2; opacity:1; }
      #addToListDropdownWrap .wl-item      { border-radius:10px; color:#e8edf6; transition:background .15s ease; }
      #addToListDropdownWrap .wl-item:hover{ background:#32394a; text-decoration:none; }
      #addToListDropdownWrap .wl-badge     { background:#3a4050; color:#cfd3dc; border-radius:999px; padding:.15rem .5rem; font-size:.8rem; }
      #addToListDropdownWrap .wl-empty     { color:#cfd3dc; background:#253042; border:1px dashed #3b465c; border-radius:12px; padding:14px; }
      #addToListDropdownWrap .wl-sep       { border-color:rgba(255,255,255,.08) !important; }
    </style>

    {{-- ===== (1) Header + Search ===== --}}
    <div class="d-flex align-items-center justify-content-between mb-2">
      @php
        $userLists = \App\Models\Watchlist::where('user_id', auth()->id())->latest()->get();
        $listCount = $userLists->count();
      @endphp
      <div>
        <div class="wl-label">Danh sách của bạn</div>
        <div class="wl-hint">{{ $listCount }} danh sách • chọn để thêm phim này</div>
      </div>
      <i class="bi bi-collection text-warning fs-5"></i>
    </div>

    <div class="mb-2 position-relative">
      <i class="bi bi-search position-absolute" style="left:10px; top:50%; transform:translateY(-50%); color:#9aa3b2;"></i>
      <input
        type="text"
        class="form-control form-control-sm ps-4 wl-input"
        id="wlFilterInput"
        placeholder="Tìm danh sách của bạn..."
        autocomplete="off"
      >
    </div>

    {{-- ===== (2) Danh sách của bạn ===== --}}
    <div id="wlList" style="max-height: 260px; overflow:auto;">
      @forelse($userLists as $wl)
        <a href="#"
           class="d-flex align-items-center justify-content-between px-2 py-2 text-decoration-none wl-item"
           data-id="{{ $wl->id }}"
           title="Thêm vào &ldquo;{{ $wl->name }}&rdquo;">
          <div class="d-flex align-items-center gap-2 overflow-hidden">
            <i class="bi bi-list-check text-warning"></i>
            <span class="text-truncate" style="max-width: 210px">{{ $wl->name }}</span>
          </div>
          <span class="wl-badge">{{ $wl->movies_count ?? $wl->items_count ?? 0 }} phim</span>
        </a>
      @empty
        <div class="wl-empty text-center">
          <div class="mb-1"><i class="bi bi-inboxes"></i></div>
          Chưa có danh sách nào. Hãy tạo mới bên dưới!
        </div>
      @endforelse
    </div>

    <hr class="my-2 wl-sep">

    {{-- ===== (3) Tạo nhanh danh sách mới ===== --}}
    <div class="mb-1 wl-label">Tạo danh sách mới</div>
    <form id="quickCreateForm" class="d-flex gap-2">
      @csrf
      <input
        type="text"
        name="name"
        id="quickName"
        class="form-control form-control-sm wl-input"
        placeholder="Ví dụ: Muốn xem cuối tuần"
        required
      >
      <button class="btn btn-sm btn-warning fw-semibold" type="submit">Tạo</button>
    </form>

    <div class="wl-hint mt-2"><i class="bi bi-lightning-charge"></i> Tạo xong sẽ tự thêm phim vào danh sách mới.</div>
  </div>
</div>
          <div class="ms-auto text-end">
            @php
              $avg10 = round(($movie->ratings_avg_stars ?? 0) * 2, 1);
              $rateCount = $movie->ratings_count ?? 0;
              $userStars = auth()->check() ? optional($movie->ratings->firstWhere('user_id', auth()->id()))->stars : 0;
            @endphp
            <button id="openRatingModal" type="button" class="btn btn-sm btn-outline-light"
                    data-bs-toggle="modal" data-bs-target="#ratingModal">
              @for ($i = 1; $i <= 5; $i++)
                <i class="bi {{ $userStars >= $i ? 'bi-star-fill' : 'bi-star' }} text-warning" style="font-size:1.1rem;"></i>
              @endfor
            </button>
            <div class="small text-secondary mt-1">
              <span id="ratingText">{{ $avg10 }} / 10</span> •
              <span id="ratingCount">{{ $rateCount }}</span> lượt đánh giá
            </div>
          </div>
        </div>

        {{-- Tabs header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
          <ul class="nav nav-tabs border-bottom-0" id="movieTabs" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" id="episodes-tab" data-bs-toggle="tab" data-bs-target="#episodes" type="button">Tập phim</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button">Gallery</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="recommend-tab" data-bs-toggle="tab" data-bs-target="#recommend" type="button">Đề xuất</button>
            </li>
          </ul>
         
        </div>

        {{-- Tabs content --}}
        <div class="tab-content py-4">
        @php
  // Lấy danh sách season đã load (đã sort trong Controller càng tốt)
  $seasons = $movie->seasons ?? collect();
  $seasonNumbers = $seasons->pluck('season_number')->filter()->sort()->values();
  if ($seasonNumbers->isEmpty()) { $seasonNumbers = collect([1]); }
  $requestedSeason = (int) request()->get('season', $seasonNumbers->first());
  $currentSeason   = $seasonNumbers->contains($requestedSeason) ? $requestedSeason : $seasonNumbers->first();
  $currentSeasonModel = $seasons->firstWhere('season_number', $currentSeason);
  $currentList        = $currentSeasonModel?->episodes ?? collect(); // ✅ Danh sách tập hiện tại
  $plannedOfCurrent = max(0, (int) ($currentSeasonModel?->total_episodes ?? 0));
  $verLabel = $verLabel ?? ['sub'=>'Phụ đề','dub'=>'Thuyết minh','raw'=>'Chưa dịch'];

  // 🔥 BỔ SUNG: Tính tổng số tập để hiển thị trên tab (theo yêu cầu trước)
  $totalEpisodes = $seasons->sum(fn($s) => $s->episodes->count());
@endphp

    <div class="tab-pane fade show active" id="episodes" role="tabpanel" aria-labelledby="episodes-tab">
      <div class="season-bar">
        <div class="season-title"><i class="bi bi-list"></i> Phần {{ $currentSeason }} 
   
        </div>

        @if($seasonNumbers->count() > 1)
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
              Danh sách phần
            </button>
            <div class="dropdown-menu dropdown-menu-dark">
              @foreach($seasonNumbers as $sn)
                <a class="dropdown-item {{ $sn == $currentSeason ? 'active' : '' }}" 
                   href="{{ route('movies.show', [$movie->slug, 'season' => $sn]) }}">
                  Phần {{ $sn }}
                </a>
              @endforeach
            </div>
          </div>
        @endif

        @if($movie->version)
          <span class="chip-outline ms-auto">
            <i class="bi bi-collection-play"></i>{{ $verLabel[$movie->version] ?? strtoupper($movie->version) }}
          </span>
        @endif
      </div>
      
      {{-- LƯU Ý: Nếu muốn hiển thị danh sách tập của TẤT CẢ các phần, 
         thì không cần thanh chọn season và phải bỏ logic `$currentSeason`.
         Vì bạn có thanh chọn season, nên ta chỉ hiển thị `$currentList`.
      --}}
      <div class="episode-grid">
        @php
          // episode chỉ có ở trang episodes.show, không có ở trang movie.show
          $currentEpisodeId = isset($episode) ? $episode->id : null; 
        @endphp

        @if($currentList->count())
          {{-- ✅ CHỈ LẶP QUA DANH SÁCH TẬP CỦA PHẦN HIỆN TẠI ($currentList) --}}
          @foreach($currentList->sortBy('episode_number') as $ep)
            <a class="episode-button {{ $currentEpisodeId === $ep->id ? 'active' : '' }}"
               href="{{ route('episodes.show', $ep) }}">
              <i class="bi bi-play-fill me-1"></i> Tập {{ $ep->episode_number }}
            </a>
          @endforeach

          {{-- Hiển thị các tập chưa có dữ liệu (dựa trên plannedOfCurrent) --}}
          @if($plannedOfCurrent && $currentList->max('episode_number') < $plannedOfCurrent)
            @for($i = ($currentList->max('episode_number') ?? 0) + 1; $i <= $plannedOfCurrent; $i++)
              <span class="episode-button disabled"><i class="bi bi-clock"></i> Tập {{ $i }}</span>
            @endfor
          @endif
        
        @elseif($plannedOfCurrent > 0)
          {{-- Nếu chưa có tập nào nhưng có số tập dự kiến --}}
          @for($i = 1; $i <= $plannedOfCurrent; $i++)
            <span class="episode-button disabled"><i class="bi bi-clock"></i> Tập {{ $i }}</span>
          @endfor
        @else
          <span class="text-white-50 p-3 block"></span>
        @endif
      </div>
    </div>
<div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
@if($galleryItems->isNotEmpty())
  <h5 class="gallery-title">Ảnh</h5>
  <div class="gallery-grid">
    @foreach($galleryItems as $img)
      <div class="g-tile {{ $img['type'] }}">
        <img src="{{ $img['url'] }}" alt="{{ ucfirst($img['type']) }} - {{ $movie->title }}">
      </div>
    @endforeach
  </div>
@else
  <p class="text-muted">Chưa có hình ảnh.</p>
@endif
</div>
        {{-- COMMENTS --}}
        <section class="comment-section mt-4">
          <div class="d-flex align-items-center gap-3">
            <h4><i class="bi bi-chat-dots"></i> Bình luận ({{ $movie->comments ? $movie->comments->count() : '0' }})</h4>
            <div class="comment-tabs">
              <button class="active" type="button" data-tab="comments">Bình luận</button>
              <button type="button" data-tab="ratings">Đánh giá</button>
            </div>
          </div>

          {{-- Input --}}
          <div class="comment-input-area mt-3" id="commentBox">
            @auth
              <form method="POST" action="{{ route('comments.store', $movie->slug) }}">
                @csrf
                <div class="inner-box bg-dark rounded-3 p-2">
                  <textarea name="content" rows="3" placeholder="Viết bình luận" maxlength="1000" class="w-100 bg-transparent border-0 text-white"></textarea>
                </div>
                <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                <div class="comment-actions mt-2">
                  <div class="form-check form-switch me-auto">
                    <input class="form-check-input" type="checkbox" id="secretComment">
                    <label class="form-check-label" for="secretComment">Tiết lộ</label>
                  </div>
                  <button class="btn btn-send" type="submit" style="color:#fddb00">Gửi <i class="bi bi-send-fill"></i></button>
                </div>
              </form>
            @else
              <div class="inner-box bg-dark rounded-3 p-2">
                <textarea rows="3" placeholder="Viết bình luận" maxlength="1000" class="w-100 bg-transparent border-0 text-white"></textarea>
              </div>
              <div class="comment-actions mt-2">
                <div class="form-check form-switch me-auto">
                  <input class="form-check-input" type="checkbox" id="secretComment">
                  <label class="form-check-label" for="secretComment">Tiết lộ</label>
                </div>
                <button class="btn btn-send" type="button"
                        onclick="handleCommentSubmit(event, '{{ route('login') }}')"
                        style="color:#fddb00">Gửi <i class="bi bi-send-fill"></i></button>
              </div>
            @endauth
          </div>

          {{-- Ratings list (ẩn mặc định) --}}
          <div class="mt-4 comment-list" id="ratingsList" style="display:none;">
            @php $ratings = collect($movie->ratings); @endphp
            @if($ratings->count())
              @foreach($ratings as $r)
                @include('profile.partials._one_rating', ['r'=>$r])
              @endforeach
            @else
              <p class="text-white">Chưa có đánh giá nào.</p>
            @endif
          </div>

          {{-- Comments list --}}
          @php
            $comments = $movie->comments()
              ->whereNull('parent_id')
              ->with([
                'user:id,name,email,avatar,gender',
                'replies' => function($q){
                  $q->with(['user:id,name,email,avatar,gender'])
                    ->withCount([
                      'votes as likes_count'    => fn($qq) => $qq->where('value', 1),
                      'votes as dislikes_count' => fn($qq) => $qq->where('value',-1),
                    ])->latest();
                },
              ])
              ->withCount([
                'votes as likes_count'    => fn($q) => $q->where('value', 1),
                'votes as dislikes_count' => fn($q) => $q->where('value',-1),
              ])->latest()->get();
          @endphp
          @php
// Khởi tạo helper avatar/icon nếu chưa có
if (!isset($uiOf) || !is_callable($uiOf)) {
    $uiOf = function ($user) {
        // chống null
        $uid   = $user->id ?? 'guest';
        $seed  = $user->email ?? $uid;
        $avatar = !empty($user?->avatar)
            ? Storage::url($user->avatar)
            : 'https://i.pravatar.cc/150?u=' . urlencode($seed);

        $g = strtolower(trim($user->gender ?? 'unknown'));
        $map = [
            'male'    => ['icon' => 'bi-gender-male',      'color' => 'text-primary',   'label' => 'Nam'],
            'female'  => ['icon' => 'bi-gender-female',    'color' => 'text-danger',    'label' => 'Nữ'],
            'unknown' => ['icon' => 'bi-gender-ambiguous', 'color' => 'text-secondary', 'label' => 'Không xác định'],
        ];
        $m = $map[$g] ?? $map['unknown'];

        return [
            'avatar' => $avatar,
            'icon'   => $m['icon'],
            'color'  => $m['color'],
            'label'  => $m['label'],
        ];
    };
}
@endphp
@php
// Khởi tạo helper format @mention + link (an toàn XSS)
if (!isset($formatMentions) || !is_callable($formatMentions)) {
    $formatMentions = function ($text) {
        // 1) Escape toàn bộ trước (chống XSS)
        $text = e($text ?? '');

        // 2) Tự động link hoá URL
        //   https://... -> <a ...>...</a>
        $text = preg_replace(
            '~(https?://[^\s<]+)~i',
            '<a href="$1" target="_blank" rel="nofollow noopener">$1</a>',
            $text
        );

        // 3) Tô màu @username (2-30 ký tự: chữ, số, _, .)
        $text = preg_replace(
            '/@([A-Za-z0-9_.]{2,30})/u',
            '<span class="mention">@$1</span>',
            $text
        );

        // 4) Giữ xuống dòng
        return nl2br($text);
    };
}
@endphp

          <div class="mt-4 comment-list" id="commentsList">
            @if($comments->count())
              @foreach($comments as $comment)
                @php $ui = $uiOf($comment->user); @endphp
                <div id="cmt-{{ $comment->id }}" class="comment-item">
                  <img src="{{ $ui['avatar'] }}" alt="Avatar" class="comment-avatar">
                  <div class="comment-content flex-grow-1">
                    <div class="comment-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <span class="comment-author text-white fw-semibold">{{ $comment->user->name }}</span>
                        <i class="bi {{ $ui['icon'] }} {{ $ui['color'] }} ms-2" title="{{ $ui['label'] }}"></i>
                        <span class="comment-meta ms-2 text-secondary">{{ $comment->created_at->diffForHumans() }}</span>
                        @if($comment->episode_number)
                          <span class="comment-badge ms-2 badge bg-secondary">P.{{ $comment->season_number ?? 1 }} • Tập {{ $comment->episode_number }}</span>
                        @endif
                      </div>

                      @auth
                        @if(Auth::id() === $comment->user_id)
                          <div class="dropdown ms-2">
                            <button type="button" class="btn btn-sm btn-link text-secondary p-0 dropdown-toggle"
                                    id="cmtMenu-{{ $comment->id }}" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                              <i class="bi bi-three-dots-vertical fs-5"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow">
                              <li><a class="dropdown-item" href="#" data-edit data-id="{{ $comment->id }}"
                                     data-content='@json($comment->content)' data-target="#edit-form-{{ $comment->id }}">Chỉnh sửa</a></li>
                              <li><hr class="dropdown-divider"></li>
                              <li>
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="m-0" data-delete>
                                  @csrf @method('DELETE')
                                  <button type="submit" class="dropdown-item text-danger">Xóa</button>
                                </form>
                              </li>
                            </ul>
                          </div>
                        @endif
                      @endauth
                    </div>

                    <div class="comment-body">
                      <p id="comment-body-{{ $comment->id }}">{!! $formatMentions($comment->content) !!}</p>

                      <form id="edit-form-{{ $comment->id }}" class="edit-form d-none mt-2"
                            action="{{ route('comments.update', $comment) }}" method="POST">
                        @csrf @method('PUT')
                        <textarea name="content" class="form-control mb-2" rows="3">{{ $comment->content }}</textarea>
                        <div class="d-flex gap-2">
                          <button class="btn btn-sm btn-primary">Lưu</button>
                          <button type="button" class="btn btn-sm btn-secondary" data-cancel>Hủy</button>
                        </div>
                      </form>
                    </div>

                    <div class="comment-actions">
                      <button class="btn-like" data-id="{{ $comment->id }}" data-type="like">
                        <i class="bi bi-hand-thumbs-up"></i> <span class="like-count">{{ $comment->likes_count }}</span>
                      </button>
                      <button class="btn-dislike" data-id="{{ $comment->id }}" data-type="dislike">
                        <i class="bi bi-hand-thumbs-down"></i> <span class="dislike-count">{{ $comment->dislikes_count }}</span>
                      </button>
                      <button onclick="replyUnder({{ $comment->id }}, '{{ $comment->user->name }}')">Trả lời</button>
                    </div>

                    @if($comment->replies->count())
                      <a class="replies-toggle collapsed" data-bs-toggle="collapse" href="#replies-{{ $comment->id }}">
                        <i class="bi bi-chevron-down"></i> {{ $comment->replies->count() }} bình luận
                      </a>
                    @endif

                    <div class="collapse mt-2" id="replies-{{ $comment->id }}">
                      @foreach($comment->replies as $reply)
                        @php $rui = $uiOf($reply->user); @endphp
                        <div id="cmt-{{ $reply->id }}" class="comment-item comment-reply">
                          <img src="{{ $rui['avatar'] }}" class="comment-avatar" alt="Avatar">
                          <div class="comment-content flex-grow-1">
                            <div class="comment-header d-flex justify-content-between align-items-center">
                              <div class="d-flex align-items-center">
                                <span class="comment-author text-white fw-semibold">{{ $reply->user->name }}</span>
                                <i class="bi {{ $rui['icon'] }} {{ $rui['color'] }} ms-2" title="{{ $rui['label'] }}"></i>
                                <span class="comment-meta ms-2 text-secondary">{{ $reply->created_at->diffForHumans() }}</span>
                              </div>
                              @auth
                                @if(Auth::id() === $reply->user_id)
                                  <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="dropdown">
                                      <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                      <li><a class="dropdown-item" href="#" data-edit data-id="{{ $reply->id }}"
                                             data-content='@json($reply->content)'>Chỉnh sửa</a></li>
                                      <li><hr class="dropdown-divider"></li>
                                      <li>
                                        <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="m-0" data-delete>
                                          @csrf @method('DELETE')
                                          <button type="submit" class="dropdown-item text-danger">Xóa</button>
                                        </form>
                                      </li>
                                    </ul>
                                  </div>
                                @endif
                              @endauth
                            </div>

                            <div class="comment-body">
                              <p id="comment-body-{{ $reply->id }}">{!! $formatMentions($reply->content) !!}</p>

                              <form id="edit-form-{{ $reply->id }}" class="d-none mt-2"
                                    action="{{ route('comments.update', $reply) }}" method="POST">
                                @csrf @method('PUT')
                                <textarea name="content" class="form-control mb-2" rows="3">{{ $reply->content }}</textarea>
                                <div class="d-flex gap-2">
                                  <button class="btn btn-sm btn-primary">Lưu</button>
                                  <button type="button" class="btn btn-sm btn-secondary" data-cancel>Hủy</button>
                                </div>
                              </form>
                            </div>

                            <div class="comment-actions">
                              <button class="btn-like" data-id="{{ $reply->id }}" data-type="like">
                                <i class="bi bi-hand-thumbs-up"></i> <span class="like-count">{{ $reply->likes_count }}</span>
                              </button>
                              <button class="btn-dislike" data-id="{{ $reply->id }}" data-type="dislike">
                                <i class="bi bi-hand-thumbs-down"></i> <span class="dislike-count">{{ $reply->dislikes_count }}</span>
                              </button>
                              <button onclick="replyUnder({{ $comment->id }}, '{{ $reply->user->name }}')">Trả lời</button>
                            </div>
                          </div>
                        </div>
                      @endforeach
                      <div id="inline-reply-{{ $comment->id }}"></div>
                    </div>
                  </div>
                </div>
              @endforeach
            @else
              <p class="text-white text-center mt-5">Chưa có bình luận nào.</p>
            @endif
          </div>
        </section>
      </div> {{-- /col-md-9 --}}
    </div> {{-- /row --}}
  </section>
</div>

{{-- Rating Modal (GIỮ) --}}
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#202738;border:none;border-radius:16px;">
      <div class="modal-header border-0">
        <h5 class="modal-title text-white" id="ratingModalLabel">{{ $movie->title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @php
          $userStars = auth()->check() ? optional($movie->ratings->firstWhere('user_id', auth()->id()))->stars : 0;
        @endphp
        <div id="modalStars"
     data-post-url="{{ route('movies.ratings.store', $movie->slug) }}"
     data-initial="{{ (int) $userStars }}">
    @for($i=1;$i<=5;$i++)
      {{-- XÓA class "text-warning" ở đây --}}
      <i class="bi {{ $userStars >= $i ? 'bi-star-fill rating-active' : 'bi-star' }} star-lg"
         style="font-size:2.4rem; cursor:pointer;"
         data-value="{{ $i }}"></i>
    @endfor
</div>
        <div class="form-group mt-3">
          <textarea id="ratingComment" class="form-control" rows="4"
                    style="background:#1b2231;border-color:#2e3a4d;color:#e9eaee;border-radius:10px;"
                    placeholder="Viết nhận xét (tuỳ chọn)"></textarea>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button id="btnSubmitRating" type="button" class="btn" style="background:#f2cc68;font-weight:600;border-radius:10px;">
          Gửi đánh giá
        </button>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">Đóng</button>
      </div>
    </div>
  </div>
</div>

{{-- Toast container --}}
<div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index:1100;"></div>

@push('scripts')
<script>
(function(){
  // ====== helpers
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const toast = (msg, ok=true) => {
    let box = document.getElementById('toast-container');
    if(!box){ box = document.createElement('div'); box.id='toast-container'; box.className='position-fixed bottom-0 end-0 p-3'; box.style.zIndex=1100; document.body.appendChild(box); }
    const el = document.createElement('div');
    el.className = `toast align-items-center text-bg-${ok?'success':'danger'} border-0 show mb-2`;
    el.role = 'alert';
    el.innerHTML = `<div class="d-flex"><div class="toast-body">${msg}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    box.appendChild(el);
    setTimeout(()=> el.remove(), 3500);
  };

  // ====== rating stars
  const starsWrap = document.getElementById('modalStars');
  if(!starsWrap) return;

  const postUrl = starsWrap.dataset.postUrl;
  const initial = parseInt(starsWrap.dataset.initial || '0', 10);
  const stars = Array.from(starsWrap.querySelectorAll('.star-lg'));
  const btnSubmit = document.getElementById('btnSubmitRating');
  const commentEl = document.getElementById('ratingComment');

  let selected = initial > 0 ? initial : 0;

  const paint = (n) => {
    stars.forEach((i, idx)=>{
      i.classList.toggle('bi-star-fill', idx < n);
      i.classList.toggle('bi-star',      idx >= n);
      // 🔥 BỔ SUNG DÒNG NÀY: Dùng class để đổi màu
      i.classList.toggle('rating-active', idx < n);
    });
  };
  paint(selected);

  // hover effect
  stars.forEach(st => {
    st.addEventListener('mouseenter', () => paint(parseInt(st.dataset.value,10)));
    st.addEventListener('mouseleave', () => paint(selected || initial));
    st.addEventListener('click', () => {
      selected = parseInt(st.dataset.value,10);
      paint(selected);
    });
  });
  btnSubmit?.addEventListener('click', async () => {
  if (!postUrl) return;

  if (!selected || selected < 1 || selected > 5) {
    alert('Vui lòng chọn số sao trước khi gửi!');
    return;
  }

  // LẤY COMMENT Ở ĐÂY → KHÔNG CÒN LỖI
  const comment = (commentEl?.value || '').trim();

  try {
    const res = await fetch(postUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrf
      },
      body: JSON.stringify({ stars: selected, comment }), // ← truyền biến comment đã khai báo
      credentials: 'same-origin'
    });

    if (!res.ok) {
      if (res.status === 401) { window.location.href = '/login'; return; }
      const text = await res.text();
      throw new Error(text.slice(0, 200));
    }

    const data = await res.json();
    // Cập nhật UI
    if (typeof data.avg10 !== 'undefined') {
      document.getElementById('ratingText')?.replaceChildren(document.createTextNode(`${data.avg10} / 10`));
    }
    if (typeof data.count !== 'undefined') {
      document.getElementById('ratingCount')?.replaceChildren(document.createTextNode(data.count));
    }

    // đóng modal
    const modalEl = document.getElementById('ratingModal');
    (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)).hide();

  } catch (err) {
    console.error(err);
    alert('Gửi đánh giá thất bại. Vui lòng thử lại!');
  }
});
})();

// ===== Like/Dislike (chống null)
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.btn-like, .btn-dislike');
  if (!btn) return;
  e.preventDefault();

  const box = btn.closest('.comment-actions');
  if (!box) { console.warn('Missing .comment-actions wrapper for', btn); return; }

  const likeBtn    = box.querySelector('.btn-like');
  const dislikeBtn = box.querySelector('.btn-dislike');
  if (!likeBtn || !dislikeBtn) return; // thiếu nút thì bỏ

  const likeIcon   = likeBtn.querySelector('i');
  const dslIcon    = dislikeBtn.querySelector('i');
  const likeCount  = likeBtn.querySelector('.like-count');
  const dslCount   = dislikeBtn.querySelector('.dislike-count');

  const toInt = (el) => parseInt(el?.textContent || '0', 10) || 0;
  const setInt= (el, v) => { if (el) el.textContent = Math.max(0, v); };

  const likeWasActive    = likeBtn.classList.contains('active');
  const dislikeWasActive = dislikeBtn.classList.contains('active');

  if (btn.classList.contains('btn-like')) {
    if (!likeWasActive) {
      likeBtn.classList.add('active');
      dislikeBtn.classList.remove('active');
      likeIcon?.classList.add('bi-hand-thumbs-up-fill');
      likeIcon?.classList.remove('bi-hand-thumbs-up');
      dslIcon?.classList.remove('bi-hand-thumbs-down-fill');
      dslIcon?.classList.add('bi-hand-thumbs-down');
      setInt(likeCount, toInt(likeCount) + 1);
      if (dislikeWasActive) setInt(dslCount, toInt(dslCount) - 1);
    } else {
      likeBtn.classList.remove('active');
      likeIcon?.classList.remove('bi-hand-thumbs-up-fill');
      likeIcon?.classList.add('bi-hand-thumbs-up');
      setInt(likeCount, toInt(likeCount) - 1);
    }
  } else {
    if (!dislikeWasActive) {
      dislikeBtn.classList.add('active');
      likeBtn.classList.remove('active');
      dslIcon?.classList.add('bi-hand-thumbs-down-fill');
      dslIcon?.classList.remove('bi-hand-thumbs-down');
      likeIcon?.classList.remove('bi-hand-thumbs-up-fill');
      likeIcon?.classList.add('bi-hand-thumbs-up');
      setInt(dslCount, toInt(dslCount) + 1);
      if (likeWasActive) setInt(likeCount, toInt(likeCount) - 1);
    } else {
      dislikeBtn.classList.remove('active');
      dslIcon?.classList.remove('bi-hand-thumbs-down-fill');
      dslIcon?.classList.add('bi-hand-thumbs-down');
      setInt(dslCount, toInt(dslCount) - 1);
    }
  }
});
// Đồng bộ icon fill theo trạng thái .active khi trang mới load (nếu server render sẵn .active)
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.comment-actions').forEach(box => {
    const likeBtn  = box.querySelector('.btn-like');
    const dslBtn   = box.querySelector('.btn-dislike');
    const likeIcon = likeBtn?.querySelector('i');
    const dslIcon  = dslBtn?.querySelector('i');

    if (likeBtn?.classList.contains('active')) {
      likeIcon?.classList.add('bi-hand-thumbs-up-fill');
      likeIcon?.classList.remove('bi-hand-thumbs-up');
    } else {
      likeIcon?.classList.remove('bi-hand-thumbs-up-fill');
      likeIcon?.classList.add('bi-hand-thumbs-up');
    }
    if (dslBtn?.classList.contains('active')) {
      dslIcon?.classList.add('bi-hand-thumbs-down-fill');
      dslIcon?.classList.remove('bi-hand-thumbs-down');
    } else {
      dslIcon?.classList.remove('bi-hand-thumbs-down-fill');
      dslIcon?.classList.add('bi-hand-thumbs-down');
    }
  });
});


// ============ COMMENT HELPERS ============
function handleCommentSubmit(event, loginUrl) {
  event.preventDefault();
  const ta = document.getElementById('comment-text');
  if (!ta || ta.value.trim() === '') return alert('Vui lòng nhập bình luận.');
  if (confirm('Bạn cần đăng nhập để gửi bình luận. Đăng nhập ngay?')) {
    window.location.href = loginUrl;
  }
}

function replyToComment(username) {
  const ta = document.getElementById('comment-text-auth') || document.getElementById('comment-text');
  if (!ta) return;
  ta.value = `@${username} `;
  ta.focus();
}

function confirmDelete(event) {
  event.preventDefault();
  if (confirm('Bạn có chắc chắn muốn xóa bình luận này không?')) {
    event.target.closest('form').submit();
  }
}

// Đổi @mention + link trong JS (dùng khi update nội dung sau khi sửa)
function formatMentionsForJS(text){
  const escape = s => s.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
  text = escape(text);
  text = text.replace(/(https?:\/\/[^\s<]+)/g, '<a href="$1" target="_blank" rel="nofollow noopener">$1</a>');
  text = text.replace(/@([A-Za-z0-9_.]{2,30})/g, '<span class="mention">@$1</span>');
  return text.replace(/\n/g,'<br>');
}

/* --- Sửa bình luận qua AJAX (duy nhất một phiên bản) --- */
async function editComment(id, oldContent){
  const next = prompt('Chỉnh sửa bình luận:', oldContent ?? '');
  if(!next || next.trim()==='' || next===oldContent) return;

  try{
    const res = await fetch(`{{ url('/comments') }}/${id}`, {
      method:'PUT',
      headers:{
        'X-CSRF-TOKEN':'{{ csrf_token() }}',
        'Accept':'application/json',
        'Content-Type':'application/json'
      },
      body: JSON.stringify({ content: next })
    });
    if(!res.ok) throw new Error('update failed');

    document.getElementById('comment-body-'+id).innerHTML = formatMentionsForJS(next);
  }catch(e){
    console.error(e);
    alert('Không thể cập nhật. Thử lại sau!');
  }
}

// Chèn form trả lời ngay DƯỚI thread gốc (không thụt trái thêm)
function replyUnder(rootId, username){
  const holder = document.getElementById('inline-reply-'+rootId);
  if(!holder) return;
  holder.innerHTML = `
    <form method="POST" action="{{ route('comments.store', $movie->slug) }}" class="comment-input-area mt-3">
      @csrf
      <input type="hidden" name="parent_id" value="${rootId}">
      <div class="inner-box">
        <textarea name="content" rows="3" maxlength="1000" placeholder="Trả lời @${username}">@${username} </textarea>
      </div>
      <div class="comment-actions">
        <button type="submit" class="btn btn-send">Gửi <i class="bi bi-send-fill"></i></button>
      </div>
    </form>
  `;
  const collapse = document.getElementById('replies-'+rootId);
  if (collapse && !collapse.classList.contains('show')) {
    new bootstrap.Collapse(collapse, {toggle:true});
  }
}


// ============ TABS Bình luận / Đánh giá ============
document.addEventListener('DOMContentLoaded', () => {
  const tabs        = document.querySelectorAll('.comment-tabs button');
  const commentsBox = document.getElementById('commentsList');
  const ratingsBox  = document.getElementById('ratingsList');
  const commentForm = document.getElementById('commentBox');

  if (tabs.length >= 2 && commentsBox && ratingsBox) {
    // Mặc định: Bình luận
    tabs[0].classList.add('active');
    tabs[1].classList.remove('active');
    commentsBox.style.display = '';
    ratingsBox.style.display  = 'none';
    commentForm && (commentForm.style.display = '');

    tabs[0].addEventListener('click', () => {
      tabs[0].classList.add('active'); tabs[1].classList.remove('active');
      commentsBox.style.display = '';
      ratingsBox.style.display  = 'none';
      commentForm && (commentForm.style.display = '');
    });

    tabs[1].addEventListener('click', () => {
      tabs[1].classList.add('active'); tabs[0].classList.remove('active');
      commentsBox.style.display = 'none';
      ratingsBox.style.display  = '';
      commentForm && (commentForm.style.display = 'none');
    });
  }
});
document.addEventListener('click', (e) => {
  const link = e.target.closest('[data-edit]');
  if (!link) return;
  e.preventDefault();

  const form = document.querySelector(link.getAttribute('data-target'));
  if (!form) return;

  const bodyP = form.closest('.comment-body').querySelector('p[id^="comment-body-"]');
  if (bodyP) bodyP.style.display = 'none';
  form.classList.remove('d-none');
  form.style.display = 'block';
  form.querySelector('textarea[name="content"]').focus();

  // đóng dropdown
  const toggle = link.closest('.dropdown')?.querySelector('[data-bs-toggle="dropdown"]');
  if (toggle) bootstrap.Dropdown.getOrCreateInstance(toggle)?.hide();
});
// Hủy
document.addEventListener('click', (e) => {
  const btn = e.target.closest('[data-cancel]');
  if (!btn) return;
  const form = btn.closest('form.edit-form');
  const bodyP = form.closest('.comment-body').querySelector('p[id^="comment-body-"]');
  if (bodyP) bodyP.style.display = 'block';
  form.classList.add('d-none');
  form.style.display = 'none';
});
// Submit sửa (AJAX PUT) rồi thay nội dung
document.addEventListener('submit', async (e) => {
  const form = e.target.closest('form.edit-form');
  if (!form) return;
  e.preventDefault();

  const fd = new FormData(form);
  const content = (fd.get('content')||'').toString().trim();
  if (!content) return alert('Nội dung không được để trống.');

  try{
    const res = await fetch(form.action, {
      method:'POST', // HTML form -> POST, Laravel dùng _method=PUT
      headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' },
      body: new URLSearchParams(fd)
    });
    if(!res.ok) throw 0;

    const p = form.closest('.comment-body').querySelector('p[id^="comment-body-"]');
    if (p) {
      // nếu bạn đã có formatMentionsForJS, dùng lại để giữ @mention/link
      const fmt = (typeof formatMentionsForJS === 'function')
        ? formatMentionsForJS(content)
        : content.replace(/\n/g,'<br>');
      p.innerHTML = fmt;
      p.style.display = 'block';
    }
    form.classList.add('d-none');
    form.style.display = 'none';
  }catch{
    alert('Không thể cập nhật bình luận. Thử lại sau.');
  }
});

// Xác nhận xóa
document.addEventListener('submit', (e) => {
  const del = e.target.closest('form[data-delete]');
  if (!del) return;
  if (!confirm('Xóa bình luận này?')) e.preventDefault();
});

function showToast(message, type = 'info'){
  const container = document.getElementById('toast-container');
  if (!container) return;

  const el = document.createElement('div');
  el.className = `custom-toast ${type}`;
  el.innerHTML = `<i class="bi bi-bell toast-icon"></i><span>${message}</span>`;
  container.appendChild(el);

  // hiện
  setTimeout(() => el.classList.add('show'), 30);

  // tự ẩn sau 5s
  setTimeout(() => {
    el.classList.remove('show');
    setTimeout(() => el.remove(), 350);
  }, 5000);
}

// ===== Nút Yêu thích =====
document.getElementById('btnFav')?.addEventListener('click', function() {
  const btn  = this;
  const type = btn.dataset.type; // 'movie' | 'person'
  const id   = btn.dataset.id;
  const url  = btn.dataset.toggleUrl;
  const loginUrl = btn.dataset.loginUrl;

  @if(Auth::guest())
    window.location.href = loginUrl;
  @else
    fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ type, id })
    })
    .then(r => r.json())
    .then(({state}) => {
  const icon = document.getElementById('favIcon');
  const text = document.getElementById('favText');

  if (state === 'added') {
    icon.className = 'bi bi-heart-fill fav-active';  // dùng fav-active thay vì text-danger
    text.textContent = 'Đã thích';
    showToast('Đã thêm vào danh sách yêu thích.', 'success');
  } else {
    icon.className = 'bi bi-heart';                  // trở lại trái tim rỗng, không màu
    text.textContent = 'Yêu thích';
    showToast('Đã gỡ khỏi danh sách yêu thích.');
  }
})
    .catch(() => showToast('Có lỗi xảy ra. Thử lại sau!'));
  @endif
});
function ncToast(message){
  const c = document.getElementById('toast-container');
  const t = document.createElement('div');
  t.className = 'custom-toast';
  t.style.background = '#222';
  t.style.color = '#fff';
  t.style.borderRadius = '12px';
  t.style.padding = '12px 16px';
  t.style.marginTop = '8px';
  t.style.boxShadow = '0 8px 24px rgba(0,0,0,.35)';
  t.style.opacity = '0';
  t.style.transform = 'translateX(100%)';
  t.style.transition = 'all .35s ease';
  t.textContent = message;
  c.appendChild(t);
  setTimeout(()=> t.style.cssText += ';opacity:1;transform:translateX(0);', 30);
  setTimeout(()=> { t.style.opacity='0'; t.style.transform='translateX(100%)';
    setTimeout(()=> t.remove(), 350);
  }, 4000);
}
// lọc danh sách theo từ khóa
document.getElementById('wlFilterInput')?.addEventListener('input', function(){
  const q = this.value.toLowerCase();
  document.querySelectorAll('#wlList .wl-item').forEach(a=>{
    const show = a.textContent.toLowerCase().includes(q);
    a.style.display = show ? 'flex' : 'none';
  });
});

// thêm vào danh sách (event delegation)
document.getElementById('wlList')?.addEventListener('click', function(e){
  const a = e.target.closest('.wl-item');
  if(!a) return; // click ngoài item
  e.preventDefault();

  const wlId    = a.dataset.id;
  const movieId = document.getElementById('btnAddToList').dataset.movieId;

  fetch(`{{ url('/watchlists') }}/${wlId}/items`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ movie_id: movieId })
  })
  .then(r => r.json())
  .then(res => {
    ncToast(res.message || 'Đã thêm vào danh sách.');

    // đóng dropdown
    const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('btnAddToList'));
    dropdown?.hide();
  })
  .catch(()=> ncToast('Không thể thêm. Vui lòng thử lại.'));
});
// tạo nhanh + gắn luôn
document.getElementById('quickCreateForm')?.addEventListener('submit', function(e){
  e.preventDefault();
  const name = document.getElementById('quickName').value.trim();
  if(!name) return;
  const movieId = document.getElementById('btnAddToList').dataset.movieId;

  fetch(`{{ route('watchlists.quick_create') }}`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ name, movie_id: movieId })
  })
  .then(r => r.json())
  .then(res => {
    ncToast(res.message || 'Đã tạo danh sách và thêm phim.');

    // thêm item mới vào danh sách trong dropdown để lần sau có sẵn
    if(res.watchlist?.id){
      const a = document.createElement('a');
      a.href = '#';
      a.className = 'd-flex align-items-center justify-content-between px-2 py-2 text-decoration-none wl-item';
      a.dataset.id = res.watchlist.id;
      a.style.cssText = 'border-radius:8px; color:#e8edf6;';
      a.innerHTML = `<span class="text-truncate">${res.watchlist.name}</span><i class="bi bi-plus-lg"></i>`;
      document.getElementById('wlList').prepend(a);
      a.addEventListener('click', (ev)=> {
        ev.preventDefault();
        fetch(`{{ url('/watchlists') }}/${res.watchlist.id}/items`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ movie_id: movieId })
        }).then(()=> ncToast('Đã thêm vào danh sách.'));
      });
      document.getElementById('quickName').value = '';
    }

    // đóng dropdown
    const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('btnAddToList'));
    dropdown?.hide();
  })
  .catch(()=> ncToast('Không thể tạo danh sách. Vui lòng thử lại.'));
});

// Khách bấm "Thêm vào" => chuyển login
const btnATL = document.getElementById('btnAddToList');
if (btnATL && btnATL.dataset.loginUrl) {
  btnATL.addEventListener('click', function(e){
    e.preventDefault();
    window.location.href = btnATL.dataset.loginUrl;
  });
}
(function(){
  const imgA = document.getElementById('dHeroA');
  const imgB = document.getElementById('dHeroB');
  const wrap = document.querySelector('.hero-detail');

  // danh sách nguồn ảnh từ PHP
  const sources = @json($bannerUrls ?? []);
  let cur = 0, frontIsA = true, timer = null;

  function crossfadeTo(src){
    const front = frontIsA ? imgA : imgB;
    const back  = frontIsA ? imgB : imgA;

    back.classList.remove('show','zoom');
    // preload an toàn trước khi hiển thị
    const tmp = new Image();
    tmp.onload = () => {
      back.src = src;
      void back.offsetWidth;        // reset animation
      back.classList.add('zoom','show');
      front.classList.remove('show','zoom');
      frontIsA = !frontIsA;
    };
    tmp.src = src;
  }

  function next(){
    if (!sources.length) return;
    cur = (cur + 1) % sources.length;
    crossfadeTo(sources[cur]);
  }

  function startAuto(){
    stopAuto();
    if (sources.length <= 1) return;
    timer = setInterval(next, 5000);
  }
  function stopAuto(){
    if (timer){ clearInterval(timer); timer = null; }
  }

  // pause khi hover
  wrap?.addEventListener('mouseenter', stopAuto);
  wrap?.addEventListener('mouseleave', startAuto);

  // init
  imgA?.classList.add('show','zoom');
  startAuto();
})();
</script>
@endpush
@endsection