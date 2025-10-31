<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netchill</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')
    <style>
      
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            padding-top: 0 !important;
        }
        .card.bg-dark { background-color: #1a1a1a !important; }
        .card-custom-dark { background-color: #1a1a1a !important; }

        .navbar {
            padding: 0.5rem 1rem;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            transition: background-color 0.3s ease-in-out;
        }
        .navbar.transparent { background-color: transparent !important; }
        .navbar.scrolled { background-color: #1a1a1a !important; }

        .search-box .form-control::placeholder { color: #ffffff; opacity: 1; }
        .search-box {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: 8px;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            margin-right: 20px;
            transition: background-color 0.3s ease;
        }
        .search-box:hover { background-color: rgba(255, 255, 255, 0.2); }
        .search-box .form-control:focus {
            outline: none !important;
            box-shadow: none !important;
            background-color: transparent;
            color: #ffffff;
        }
        .search-box .form-control {
            background-color: transparent;
            border: none;
            color: #ffffff;
        }
        .search-box .form-control:hover { color: #ffc107; }
        .search-box .btn-search {
            background: none;
            border: none;
            color: #ffffff;
            padding: 0 8px;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .btn-clear {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            color: #000000;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 19px;
            font-size: 17px;
            line-height: 1;
            cursor: pointer;
            margin-left: 8px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .btn-clear:hover { background-color: #ffc107; color: #000000; }

        .navbar-brand img { height: 40px; margin-right: 10px; }
        .navbar-brand span { font-weight: bold; font-size: 1.5rem; }
        .navbar-brand .text-red { color: #e50914; }
        .navbar-brand .text-yellow { color: #FFC107; }
        .navbar-nav .nav-link { color: #ffffff !important; font-weight: 500; margin: 0 8px; }
        .navbar-nav .nav-link:hover { color: #ffc107 !important; }
        .nav-link.new-badge { position: relative; }
        .nav-link.new-badge::after {
            content: 'NEW';
            background-color: #FFC107;
            color: #222222;
            font-size: 0.65rem;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            position: absolute;
            top: -5px;
            right: -15px;
        }
        .dropdown-menu { background-color: #2c2c2c; border: none; }
        .dropdown-item { color: #cccccc; }
        .dropdown-item:hover { background-color: #3a3a3a; color: #ffffff; }

        main { padding-top: 80px; }
        main.container { position: relative; z-index: 1; margin-top: 0; }

        .banner-full-screen { width: 100%; height: 100vh; object-fit: cover; z-index: 0; position: relative; }
        .banner-full-screen .carousel-item,
        .banner-full-screen img { height: 100%; width: 100%; object-fit: cover; }

        .btn-member {
            background-color: #e50914;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-member:hover { background-color: #f40a17; }
        /* --- Custom Profile Menu Styling --- */
        .custom-profile-menu {
            /* Mimic the dark background */
            background-color: #222222; /* Darker than default dropdown */
            width: 300px; /* Adjust size to fit content */
            border-radius: 8px;
            padding: 0; /* Remove default padding */
            overflow: hidden; /* Contains the internal divs */
        }
        .profile-header-section {
            background-color: #222222;
            padding: 1rem !important;
            border-bottom: 1px solid #333333;
        }
        .profile-header-section small {
            color: #aaaaaa !important;
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }
        .btn-upgrade-roX {
            background-color: #ffc107; /* Yellow background */
            color: #000000; /* Dark text */
            font-weight: bold;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
        }
        .btn-upgrade-roX:hover {
            background-color: #e0ac00;
        }
        
        .balance-section {
            background-color: #2c2c2c; /* Slightly different background for the balance row */
            border-top: 1px solid #333333;
            border-bottom: 1px solid #333333 !important;
        }
        .btn-reload-balance {
            border-color: #ffc107 !important;
            color: #ffc107 !important;
        }
        .btn-reload-balance:hover {
            background-color: #ffc107 !important;
            color: #000000 !important;
        }

        .profile-menu-list {
            padding: 0.5rem 0; /* Padding for the list section */
        }
        .profile-menu-list .dropdown-item {
            color: #ffffff; /* White text for menu items */
            padding: 10px 1rem;
            font-size: 1rem;
            font-weight: 500;
        }
        .profile-menu-list .dropdown-item:hover {
            background-color: #3a3a3a;
            color: #ffc107; /* Yellow on hover */
        }
        /* Icon styling */
        .profile-menu-list .dropdown-item i {
            font-size: 1.25rem;
            width: 20px; /* Align icons */
            text-align: center;
        }
        
        /* Logout Button Styling */
        .custom-profile-menu .text-danger {
            color: #e50914 !important;
            font-weight: 500;
        }
        .custom-profile-menu .text-danger:hover {
            text-decoration: underline;
        }
      /* Nút chuông kiểu “vòng tròn” */
.nc-bell-wrap{
  width:42px;height:42px;border-radius:50%;
  border:2px solid rgba(255,255,255,.35);
  display:flex;align-items:center;justify-content:center;
  position:relative;transition:background .2s ease;
}
.nc-bell-wrap i{ font-size:1.2rem; color:#fff; }
.nc-bell-wrap:hover{ background:rgba(255,255,255,.06); }

/* Badge số lượng */
.nc-badge{
  position:absolute; top:-6px; right:-6px;
  background:#ff4d4f; color:#fff; font-size:.7rem; line-height:1;
  padding:3px 6px; border-radius:999px; border:2px solid #1a1a1a;
}

/* Menu */
.nc-notif-menu{
  width:360px; border:none; border-radius:14px; overflow:hidden;
  background:#2b3140;
}

/* Tabs */
.nc-tabs{ display:flex; gap:12px; padding:.9rem 1.2rem; background:#242a37; }
.nc-tabs button{
  background:none;border:none;color:#cbd5e1;font-weight:600;
  padding:.25rem .5rem; cursor:pointer;
}
.nc-tabs button.active{ color:#f2cc68; }

/* Pane */
.nc-body{ max-height:360px; overflow:auto; }
.nc-pane{ display:none; }
.nc-pane.show{ display:block; }

/* Item */
.nc-item{
  display:block; padding:14px 16px; text-decoration:none;
  border-top:1px solid rgba(255,255,255,.05);
}
.nc-item:hover{ background:#31384a; }
.nc-item-title{ color:#e8eef7; font-weight:600; }
.nc-item-time{ color:#93a1b5; font-size:.85rem; margin-top:2px; }

/* Empty */
.nc-empty{
  padding:40px 16px; text-align:center; color:#9aa4b2; font-weight:600;
}

/* Footer */
.nc-footer{
  display:block; text-align:center; padding:14px 16px;
  color:#fff; font-weight:700; background:#242a37; text-decoration:none;
}
.nc-footer:hover{ background:#2a3040; color:#fff; }
/* --- Auth Modals --- */
.auth-modal{background:#1a1a1a;border:none;border-radius:14px;overflow:hidden;color:#fff}
.auth-side{background:url('/images/login-bg.jpg') center/cover no-repeat}
.auth-logo{height:42px;width:auto}
.auth-title{color:#fff;font-weight:700}
.auth-modal .form-control{background:#262626;border:1px solid #333;color:#fff;border-radius:10px}
.auth-modal .form-control:focus{border-color:#ffc107;box-shadow:none}
.auth-modal .btn-warning{font-weight:700;border-radius:10px;padding:.75rem}
.auth-switch a{text-decoration:none}
.auth-switch a:hover{color:#ffd24b}
.auth-modal .text-muted {
  color: #b0b0b0 !important;
}
/* Input trong modal đăng nhập/đăng ký */
.auth-modal .form-control {
  background-color: #262626;   /* nền tối */
  border: 1px solid #333;
  color: #f1f1f1;             /* màu chữ khi gõ vào (sáng) */
  border-radius: 10px;
}

/* Placeholder (chữ mờ trong ô input) */
.auth-modal .form-control::placeholder {
  color: #b0b0b0;             /* placeholder xám nhạt */
  opacity: 1;                 /* đảm bảo hiển thị rõ */
}

/* Khi focus vào input */
.auth-modal .form-control:focus {
  border-color: #ffc107;      /* viền vàng */
  box-shadow: none;
  color: #ffffff;             /* chữ trắng sáng hơn */
}
/* ---- THU NHỎ RIÊNG MENU QUỐC GIA ---- */
/* Khung: bỏ min/max-width lớn, set chiều rộng gọn */
.mega-dropdown .mega-menu[aria-labelledby="navbarDropdownQuocGia"]{
  min-width: unset !important;
  max-width: 220px !important;   /* bạn có thể chỉnh 320–420px */
  width: 220px !important;
  padding: 10px 12px;
  left: 0;                        /* bám trái theo nút */
  right: auto;
  transform: none;                /* không căn giữa */
}

/* Bố cục: luôn 1 cột */
.mega-dropdown .mega-menu[aria-labelledby="navbarDropdownQuocGia"] .mega-grid{
  display: grid !important;
  grid-template-columns: 1fr !important;
  row-gap: 8px;
}

/* Item: padding nhỏ, chữ gọn */

/* Mega menu khớp viewport, luôn bung xuống dưới navbar */
.mega-dropdown { position: static; } /* để menu canh theo thanh nav */
.mega-dropdown .mega-menu{
  inset: auto !important;         /* bỏ định vị mặc định của Popper */
  left: 0;                        /* mở dưới thanh nav, sát trái container */
  right: auto;
  margin-top: 12px;               /* đừng dính vào navbar -> tránh “dư trên” */
  min-width: 720px;
  max-width: 920px;
  background: #202020;
  border: none;
  border-radius: 14px;
  box-shadow: 0 10px 30px rgba(0,0,0,.35);
  z-index: 1051;                  /* trên nội dung xung quanh */
  
  /* QUAN TRỌNG: không tràn khỏi màn hình dưới */
  --nav-h: 64px;                  /* chỉnh nếu navbar cao khác */
  max-height: calc(100vh - var(--nav-h) - 24px);
  overflow: auto;                 /* cuộn phần trong menu nếu quá dài */
}
.balance-row{
  display:flex; align-items:center; justify-content:space-between;
  gap:12px; padding:10px 14px; border-radius:14px;
  background:#1f242c; /* tùy theme */
}
.btn-topup-round{
  /* Đảm bảo nút tròn */
  width: 40px;                 /* Kích thước vừa phải cho cả 2 dòng */
  height: 40px;                /* Kích thước bằng nhau để tạo thành hình tròn */
  border-radius: 40%;
  
  /* Căn giữa nội dung 2 dòng */
  display: flex; 
  flex-direction: column;      /* Giữ 2 dòng (+ và Nạp) */
  align-items: center; 
  justify-content: center;
  
  /* Thiết lập kiểu chữ và viền */
  border: 2px solid rgba(255,255,255,.85);
  background: transparent; 
  color: #fff; 
  font-weight: 700;
  line-height: 1;              /* Quan trọng: giữ cho khoảng cách dòng gọn */
  padding: 0; 
  gap: 0;                      /* Loại bỏ khoảng cách thừa giữa + và Nạp */
  cursor: pointer;
}
.btn-topup-round .plus{
  font-size: 1.2rem;           /* Tăng kích thước dấu + một chút */
  line-height: 1;
}
.btn-topup-round .label{ 
  font-size: 10px;             /* Thu nhỏ chữ "Nạp" lại */
  margin-top: -3px;            /* Kéo chữ "Nạp" lên sát dấu + */
  line-height: 1;
}

/* Loại bỏ media query lỗi (height:56px là quá cao) */
@media (max-width: 480px){
  .btn-topup-round{ width: 50px; height: 50px; } /* Giữ nguyên kích thước nhỏ */
  /* không cần thay đổi font size */
}
/* Bên trái: đừng để text đè nút */
.balance-row .left{
  display:flex; align-items:center; gap:6px; white-space:nowrap;
}
.balance-row .coin{ font-weight:800; margin-left:2px; }
/* mục */
.mega-item{
  color:#e8edf6; border-radius:10px; padding:.55rem .75rem; white-space:nowrap;
}
.mega-item:hover{ background:#202020; color:#ffd54a; }

@media (min-width: 992px){
  .mega-dropdown:hover > .mega-menu { 
    display: none !important;   /* chặn behavior mở bằng hover */
  }
  .mega-dropdown.show > .mega-menu,
  .mega-dropdown .mega-menu.show {
    display: block !important;  /* hiển thị khi dropdown đã được click */
  }
}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg transparent" id="mainNavbar">
    <div class="container-fluid">
        
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('images/logo-netchill.png') }}" alt="Netchill Logo" style="height: 40px; margin-right: 10px;">
            <span><span class="text-red">Net</span><span class="text-yellow">Chill</span></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
  <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">

  <li class="nav-item search-box position-relative d-flex align-items-center" style="min-width:280px;">
  <form id="globalSearchForm" class="d-flex flex-grow-1" role="search" action="{{ route('catalog.index') }}" method="GET" autocomplete="off">
    <button type="button" id="btnSearchIcon" class="btn-search btn btn-link p-0 pe-2">
      <i class="bi bi-search"></i>
    </button>
    <input id="globalSearchInput" name="q" class="form-control me-2" type="search"
           placeholder="Tìm kiếm phim" aria-label="Search" value="{{ request('q') }}">
  </form>

  {{-- Dropdown gợi ý --}}
  <div id="searchSuggest" class="position-absolute w-100 mt-2 d-none"
       style="top:100%;left:0;z-index:1050;background:#1a1a1a;border:1px solid #2e3645;border-radius:12px;overflow:hidden;">
    <div id="searchSuggestList"></div>
    <a class="d-block text-center py-2 text-decoration-none" id="viewAllLink" href="#"
       style="color:#cfd6e4;border-top:1px solid #2e3645;">Xem tất cả kết quả</a>
  </div>
</li>

    {{-- Thể loại (mega dropdown) --}}
    <x-mega-dropdown
      id="navbarDropdownTheLoai"                 {{-- id phải duy nhất --}}
      label="Thể loại"
      :items="$categories"
      routeName="catalog.byCategory"
    />

<li class="nav-item">
  <a class="nav-link" href="{{ route('catalog.index', ['type' => 'single']) }}">Phim lẻ</a>
</li>
<li class="nav-item">
  <a class="nav-link" href="{{ route('catalog.index', ['type' => 'series']) }}">Phim bộ</a>
</li>
    {{-- Quốc gia (mega dropdown) --}}
<x-mega-dropdown
  id="navbarDropdownQuocGia"
  label="Quốc gia"
  :items="$countries"
  routeName="catalog.byCountry"
  :cols="1"               {{-- 👈 chỉ 1 cột --}}
/>

    {{-- Lịch chiếu --}}
    <li class="nav-item">
  <a class="nav-link new-badge" href="{{ route('schedule.index') }}">Lịch chiếu</a>
</li>
  </ul>

  {{-- Bên phải navbar --}}
  <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
    @guest
      <li class="nav-item">
        <button class="btn btn-member" data-bs-toggle="modal" data-bs-target="#loginModal">
          <i class="bi bi-person"></i> Thành viên
        </button>
      </li>


                    </li>
                    @else
                    {{-- Bell --}}
                    @auth
                    <li class="nav-item dropdown me-2">
  <a class="nav-link p-0" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
    <div class="nc-bell-wrap">
      <i class="bi bi-bell"></i>
      @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
      @if($unreadCount > 0)
        <span class="nc-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
      @endif
    </div>
  </a>

  <div class="dropdown-menu dropdown-menu-end p-0 nc-notif-menu" aria-labelledby="notifDropdown">
    {{-- Tabs --}}
    <div class="nc-tabs">
      <button type="button" class="active" data-target="#tab-movie">Phim</button>
      <button type="button" data-target="#tab-community">Cộng đồng</button>
      <button type="button" data-target="#tab-read">Đã đọc</button>
    </div>

    {{-- Nội dung --}}
    <div class="nc-body">
      {{-- Pane: Phim (ưu tiên chưa đọc) --}}
      <div class="nc-pane show" id="tab-movie">
      @php
$allNotifs = Auth::user()->notifications()->latest()->take(20)->get();
$movieNotifs = $allNotifs->filter(fn($n) =>
    ($n->data['category'] ?? null) === 'movie' || str_contains($n->type, 'Movie')
);
$communityNotifs = $allNotifs->filter(fn($n) =>
    ($n->data['category'] ?? null) === 'community' ||
    str_contains($n->type, 'Comment') ||
    str_contains($n->type, 'Reply') ||
    str_contains($n->type, 'Mention')
);
@endphp

        @forelse($movieNotifs as $n)
          <a class="nc-item {{ $n->read_at ? '' : 'unread' }}" data-id="{{ $n->id }}" href="{{ $n->data['url'] ?? '#' }}">
            <div class="nc-item-title">{{ $n->data['title'] ?? 'Thông báo' }}</div>
            <div class="nc-item-time">{{ $n->created_at->diffForHumans() }}</div>
          </a>
        @empty
          <div class="nc-empty">Không có thông báo nào</div>
        @endforelse
      </div>

      {{-- Pane: Cộng đồng (ưu tiên chưa đọc) --}}
      <div class="nc-pane" id="tab-community">
      @php
  $communityNotifs = Auth::user()
      ->notifications()
      ->where(function($q){
          // 1) Nếu bạn có gắn category = community
          $q->whereJsonContains('data->category', 'community')
            ->orWhere('data->category', 'community')
          // 2) Theo class/type của Laravel Notification
            ->orWhere('type', 'like', '%Comment%')
            ->orWhere('type', 'like', '%Reply%')
            ->orWhere('type', 'like', '%Mention%')
          // 3) Theo field tùy biến trong JSON
            ->orWhere('data->type', 'comment_reply')
            ->orWhere('data->type', 'comment')
            ->orWhere('data->event', 'comment_reply')
            ->orWhere('data->event', 'mention');
      })
      ->orderByRaw('read_at is null desc')
      ->latest()
      ->take(10)
      ->get();
@endphp

@forelse($communityNotifs as $n)
  <a class="nc-item {{ $n->read_at ? '' : 'unread' }}" data-id="{{ $n->id }}" href="{{ $n->data['url'] ?? '#' }}">
    <div class="nc-item-title">{{ $n->data['title'] ?? 'Thông báo' }}</div>
    <div class="nc-item-time">{{ $n->created_at->diffForHumans() }}</div>
  </a>
@empty
  <div class="nc-empty">Không có thông báo nào</div>
@endforelse

      </div>
    </div>

    <a class="nc-footer" href="{{ route('notifications.index') }}">Xem toàn bộ</a>

  </div>
</li>

@endauth

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0;">
             {{-- Bắt đầu phần thay đổi AVATAR - Sử dụng i.pravatar.cc nếu không có ảnh --}}
             @php
                 // Sử dụng email làm seed để tạo avatar ngẫu nhiên duy nhất
                 $avatarSeed = Auth::user()->email ?? Auth::user()->id;
                 $defaultAvatarUrl = 'https://i.pravatar.cc/150?u=' . urlencode($avatarSeed);
                 $userAvatar = Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : $defaultAvatarUrl;
             @endphp
             <img src="{{ $userAvatar }}" 
                  alt="Profile" 
                  style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid #ffc107; object-fit: cover;">
             {{-- Kết thúc phần thay đổi AVATAR --}}
        </a>
        
        <div class="dropdown-menu dropdown-menu-end custom-profile-menu" aria-labelledby="userDropdown">
            
            <div class="p-3 profile-header-section">
            @php
  $u = Auth::user();

  // seed cho avatar mặc định
  $seed = $u->email ?: $u->id;
  $defaultAvatarUrl = 'https://i.pravatar.cc/150?u=' . urlencode($seed);

  // Ưu tiên: profile_photo_url -> file đã upload (Storage) -> pravatar
  $avatar = $u->profile_photo_url
            ?? ($u->avatar ? \Illuminate\Support\Facades\Storage::url($u->avatar) : null)
            ?? $defaultAvatarUrl;
  // Số dư
  $balance = (int) ($u->balance ?? 0);
@endphp


<style>
  /* Thu gọn chiều cao khu vực header */
  .profile-header-section{ background:#272b37; border-radius:16px; padding:12px !important }
  .profile-mini{ display:flex; gap:10px; align-items:center }
  .profile-mini__avatar{ width:42px; height:42px; border-radius:50%; overflow:hidden;
                          box-shadow:0 0 0 2px #ffc107; flex:0 0 42px }
  .profile-mini__avatar img{ width:100%; height:100%; object-fit:cover }
  .profile-mini__meta .name{ color:#fff; font-weight:600; line-height:1.1 }
  .profile-mini__meta .sub{ color:#aeb5c6; font-size:.85rem }
  .btn-upgrade-mini{ background:#f2cc68; color:#111; border:none; width:100%;
                     font-weight:700; border-radius:10px; padding:.5rem .75rem }
  .btn-upgrade-mini:hover{ filter:brightness(.95) }
  .balance-row{ display:flex; align-items:center; justify-content:space-between;
                background:#202430; border-radius:12px; padding:.4rem .6rem; margin-top:.5rem }
  .balance-row .left{ display:flex; align-items:center; gap:.5rem; color:#e8eaef }
  .coin{ font-weight:800 }
  .btn-topup{ --bs-btn-color:#fff; --bs-btn-border-color:#fff; --bs-btn-hover-bg:#fff; --bs-btn-hover-color:#111;
              padding:.15rem .45rem; border-radius:999px }
</style>

<div class="p-3 profile-header-section">
  {{-- dòng info gọn --}}
  <div class="profile-mini">
    <div class="profile-mini__avatar">
      <img src="{{ $avatar }}" alt="avatar">
    </div>
    <div class="profile-mini__meta flex-grow-1">
      <div class="name">
        {{ $u->name }}
        @if($u->is_premium) <i class="bi bi-shield-check text-warning ms-1" title="RoX Premium"></i> @endif
      </div>
      <div class="sub">
        @if(!$u->is_premium)
          Nâng cấp tài khoản <b>RoX</b> để có trải nghiệm đẳng cấp hơn.
        @else
          Thành viên <b>RoX</b>.
        @endif
      </div>
    </div>
  </div>

  {{-- nút nâng cấp --}}
  @auth
    @if(!$u->is_premium)
      <a href="{{ route('premium.pricing') }}" class="btn btn-upgrade-mini mt-2">
        Nâng cấp ngay <i class="bi bi-caret-up-fill"></i>
      </a>
    @endif
  @else
    <a href="{{ route('login') }}" class="btn btn-upgrade-mini mt-2">Đăng nhập để nâng cấp</a>
  @endauth

  {{-- số dư + nạp --}}
  <div class="balance-row">
    <div class="left">
      <i class="bi bi-wallet2 me-1"></i> <span>Số dư</span>
      <span class="ms-2 fw-bold">{{ number_format($balance) }}</span>
      <span class="coin">Ⓡ</span>
    </div>
    <button type="button"
        class="btn-topup-round"
        data-bs-toggle="modal"
        data-bs-target="#topupModal">
  <span class="plus">+</span>
  <span class="label">Nạp</span>
</button>
  </div>
</div>

<ul class="list-unstyled mb-0 profile-menu-list mt-3">
  @auth
    @if($u->can('admin') || ($u->is_admin ?? false))
      <li><a class="dropdown-item fw-bold text-info" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-shield-lock me-2"></i> Dashboard Admin</a></li>
      <li><a class="dropdown-item" href="{{ route('movies.create') }}">
        <i class="bi bi-film me-2"></i> Tạo phim mới</a></li>
      <li><hr class="dropdown-divider bg-secondary"></li>
    @endif
  @endauth

  <li><a class="dropdown-item" href="{{ route('favorites.index') }}"><i class="bi bi-heart me-2"></i> Yêu thích</a></li>
  <li><a class="dropdown-item" href="{{ route('watchlists.index') }}"><i class="bi bi-plus me-2"></i> Danh sách</a></li>
  <li><a class="dropdown-item" href="{{ route('profile.history') }}"><i class="bi bi-clock-history me-2"></i> Xem tiếp</a></li>
  <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person me-2"></i> Tài khoản</a></li>
</ul>

<div class="p-3 pt-2">
  <a class="dropdown-item text-danger d-flex align-items-center p-0" href="{{ route('logout') }}"
     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="bi bi-box-arrow-right me-2"></i> Thoát
  </a>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</div>

            
        </div>
    </li>
@endguest
            </ul>
        </div>
    </div>
</nav>
<main>
    <div class="">
       
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</main>
<!-- Modal: Đăng nhập -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content auth-modal">
      <div class="row g-0">
        <div class="col-md-6 d-none d-md-block auth-side"></div>
        <div class="col-md-6 p-4 d-flex flex-column justify-content-center">
          <div class="text-center mb-3">
          </div>
          <h4 class="text-center auth-title mb-3">Đăng nhập</h4>

          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
              <input type="email" class="form-control" name="email" placeholder="Email" required autofocus>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="password" placeholder="Mật khẩu" required>
            </div>

            {{-- (Tuỳ chọn) Cloudflare Turnstile / reCAPTCHA đặt tại đây --}}
            {{-- @turnstile() --}}

            <button type="submit" class="btn btn-warning w-100">Đăng nhập</button>
          </form>

          <div class="text-center auth-switch mt-3">
            <span class="text-muted">Nếu bạn chưa có tài khoản,</span>
            <a href="#" class="text-warning" data-bs-target="#registerModal" data-bs-toggle="modal" data-bs-dismiss="modal">đăng ký ngay</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Đăng ký -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content auth-modal">
      <div class="row g-0">
        <div class="col-md-6 d-none d-md-block auth-side"></div>
        <div class="col-md-6 p-4 d-flex flex-column justify-content-center">
          <div class="text-center mb-3">
          </div>
          <h4 class="text-center auth-title mb-3">Tạo tài khoản mới</h4>

          <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
              <input type="text" class="form-control" name="name" placeholder="Tên hiển thị" required>
            </div>
            <div class="mb-3">
              <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="password" placeholder="Mật khẩu" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
            </div>

            {{-- (Tuỳ chọn) Cloudflare Turnstile / reCAPTCHA đặt tại đây --}}
            {{-- @turnstile() --}}

            <button type="submit" class="btn btn-warning w-100">Đăng ký</button>
          </form>

          <div class="text-center auth-switch mt-3">
            <span class="text-muted">Đã có tài khoản?</span>
            <a href="#" class="text-warning" data-bs-target="#loginModal" data-bs-toggle="modal" data-bs-dismiss="modal">Đăng nhập</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal nạp tiền -->
<div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="topupModalLabel">Nạp tiền vào tài khoản</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('payment.topup') }}" method="POST" novalidate>
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="amount" class="form-label">Nhập số tiền muốn nạp</label>
            <div class="input-group">
              <input
                type="number"
                name="amount"
                id="amount"
                class="form-control @error('amount') is-invalid @enderror"
                placeholder="VD: 50000"
                min="1000"
                step="1000"
                required
                value="{{ old('amount') }}"
              >
              <span class="input-group-text">Ⓡ</span>
              @error('amount')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-text text-muted">(Nạp thủ công - không cần tài khoản ngân hàng)</div>
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-warning fw-bold">Xác nhận nạp</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  (function(){
  const input   = document.getElementById('globalSearchInput');
  const form    = document.getElementById('globalSearchForm');
  const box     = document.getElementById('searchSuggest');
  const list    = document.getElementById('searchSuggestList');
  const viewAll = document.getElementById('viewAllLink');
  const iconBtn = document.getElementById('btnSearchIcon');

  const gotoCatalog = (q) => {
    const base = "{{ route('catalog.index') }}";
    const url  = q && q.trim() ? (base + '?q=' + encodeURIComponent(q.trim())) : base;
    window.location.href = url;
  };

  iconBtn.addEventListener('click', () => {
    input.focus();
    if (input.value.trim() !== '') gotoCatalog(input.value);
  });

  let timer=null, activeIndex=-1;
  const debounce = (fn, ms=220) => (...args)=>{ clearTimeout(timer); timer=setTimeout(()=>fn(...args),ms); };

  const hideBox = () => { box.classList.add('d-none'); activeIndex = -1; };
  const showBox = () => { box.classList.remove('d-none'); };

  const renderItems = (items, keyword) => {
    list.innerHTML = items.length ? items.map((it, idx) => `
      <a href="${it.url}" class="d-flex gap-2 p-2 text-decoration-none item"
         data-index="${idx}" style="color:#e8eef6;">
        <img src="${it.poster}" alt="${it.title}" width="44" height="66"
             style="object-fit:cover;border-radius:8px;">
        <div class="flex-grow-1">
          <div class="fw-bold">${it.title}</div>
          <div class="small text-secondary">${it.sub ?? ''}${it.year ? ' • '+it.year : ''}${it.age ? ' • '+it.age : ''}</div>
        </div>
      </a>
    `).join('') : `<div class="p-3 text-muted">Không tìm thấy “${keyword}”.</div>`;

    activeIndex = items.length ? 0 : -1;
  };

  const fetchSuggest = debounce(async () => {
    const q = input.value.trim();
    if (!q) { hideBox(); return; }
    try {
      const url = "{{ route('search.suggest') }}" + "?q=" + encodeURIComponent(q);
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });

      if (!res.ok) { hideBox(); return; }          // 4xx/5xx
      const json = await res.json();
      const items = Array.isArray(json.data) ? json.data : [];
      renderItems(items, q);

      viewAll.href = "{{ route('catalog.index') }}" + "?q=" + encodeURIComponent(q);
      showBox();
    } catch (e) {
      hideBox();
      // console.error(e); // mở nếu muốn debug
    }
  });

  input.addEventListener('input', fetchSuggest);
  input.addEventListener('focus', fetchSuggest);

  form.addEventListener('submit', (e) => { e.preventDefault(); gotoCatalog(input.value); });

  input.addEventListener('keydown', (e) => {
    const items = list.querySelectorAll('.item');
    if (!items.length) return;
    if (e.key === 'ArrowDown') { e.preventDefault(); activeIndex = (activeIndex + 1) % items.length; items[activeIndex].focus?.(); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); activeIndex = (activeIndex - 1 + items.length) % items.length; items[activeIndex].focus?.(); }
    else if (e.key === 'Enter' && activeIndex >= 0) { e.preventDefault(); items[activeIndex].click(); }
    else if (e.key === 'Escape') { hideBox(); input.blur(); }
  });

  document.addEventListener('click', (e) => {
    if (!box.contains(e.target) && !form.contains(e.target)) hideBox();
  });
})();
  document.addEventListener('DOMContentLoaded', function () {
  const nav = document.getElementById('mainNavbar');
  if (!nav) return;

  const setState = () => {
    const y = window.pageYOffset || document.documentElement.scrollTop || 0;
    const solid = y > 2; // >2px là coi như đã cuộn
    nav.classList.toggle('scrolled', solid);
    nav.classList.toggle('transparent', !solid);
  };

  // Lần đầu load + khi cuộn
  setState();
  window.addEventListener('scroll', setState, { passive: true });

  // Khi mở menu mobile -> luôn đặc; đóng -> trả theo vị trí cuộn
  const collapse = document.getElementById('navbarNav');
  collapse?.addEventListener('show.bs.collapse', () => nav.classList.add('scrolled'));
  collapse?.addEventListener('hide.bs.collapse', setState);
});
  // Đổi tab
  document.addEventListener('click', function(e) {
    const tabBtn = e.target.closest('.nc-tabs button');
    if (!tabBtn) return;
    const targetSel = tabBtn.getAttribute('data-target');
    if (!targetSel) return;

    // active tab
    document.querySelectorAll('.nc-tabs button').forEach(b => b.classList.remove('active'));
    tabBtn.classList.add('active');

    // show pane
    document.querySelectorAll('.nc-pane').forEach(p => p.classList.remove('show'));
    const pane = document.querySelector(targetSel);
    if (pane) pane.classList.add('show');
  });

  // Đánh dấu đã đọc rồi mới điều hướng
  document.addEventListener('click', function(e) {
    const item = e.target.closest('.nc-item');
    if (!item) return;

    e.preventDefault(); // chặn điều hướng ngay
    const id = item.dataset.id;
    const href = item.getAttribute('href') || '#';

    if (!id) { window.location.href = href; return; }

    fetch("{{ url('/notifications') }}/" + id + "/read", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    }).then(() => {
      item.classList.remove('unread');

      // cập nhật badge
      const badge = document.querySelector('.nc-badge');
      if (badge) {
        let cur = badge.textContent.trim() === '99+' ? 99 : parseInt(badge.textContent.trim(), 10) || 0;
        cur = Math.max(0, cur - 1);
        if (cur === 0) {
          badge.remove();
        } else {
          badge.textContent = cur > 99 ? '99+' : String(cur);
        }
      }
    }).catch(() => {
      // bỏ qua lỗi
    }).finally(() => {
      // điều hướng sau khi xử lý
      if (href && href !== '#') window.location.href = href;
    });
  });

  // Scroll highlight comment (giữ nguyên logic của bạn)
  document.addEventListener('DOMContentLoaded', () => {
    const hash = location.hash;
    if (hash && hash.startsWith('#cmt-')) {
      const target = document.querySelector(hash);
      if (target) {
        target.scrollIntoView({behavior:'smooth', block:'center'});
        target.style.transition = 'background 0.6s';
        target.style.background = '#2a2a2a';
        setTimeout(()=> target.style.background='transparent', 1200);
      }
    }
  });
</script>

@stack('scripts')

</body>
</html>
