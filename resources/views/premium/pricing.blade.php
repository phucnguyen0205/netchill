@extends('layouts.app')

@section('title', 'Tài khoản RoX')

@section('content')
@php
  use Illuminate\Support\Facades\Storage;
  $user = Auth::user();
  $seed = $user->email ?: $user->id;
  $defaultAvatarUrl = 'https://i.pravatar.cc/150?u=' . urlencode($seed);
  $avatar = $user->profile_photo_url
            ?? ($user->avatar ? Storage::url($user->avatar) : null)
            ?? $defaultAvatarUrl;
@endphp
<style>
    
  :root{
    --rox-yellow-1:#FFE082; /* nhạt */
    --rox-yellow-2:#FFC107; /* warning */
    --rox-yellow-3:#FFB300; /* đậm */
  }
  .hero-title{ font-weight:800; letter-spacing:.5px }
  .plan-card{
  background: radial-gradient(140% 140% at 10% -10%, #FFE082 0%, #FFC107 45%, #FFB300 100%);
  border: none; border-radius: 1.25rem; box-shadow: 0 12px 34px rgba(0,0,0,.28);
  color: #111;
}
  .plan-card .title{ font-size:1.5rem; font-weight:800 }
  .plan-card .price{ font-size:2.1rem; font-weight:900; letter-spacing:.5px }
  .plan-card .strike{ text-decoration: line-through; opacity:.65; font-size:.95rem }
  .plan-card .coin{ font-weight:800; }
  .feature-list{ padding-left:0; list-style:none }
  .feature-list li{ margin:.45rem 0; display:flex; gap:.5rem; align-items:flex-start }
  .feature-list li .tick{ font-weight:900 }
  .badge-save{ background:#fff; color:#111; border-radius:.6rem; padding:.2rem .6rem; font-weight:800; box-shadow:0 2px 8px rgba(0,0,0,.08) }
  .choose-btn{ background:#fff; color:#111; border:none; border-radius:.8rem; padding:.8rem 1rem; font-weight:800; }
  .choose-btn:hover{ filter: brightness(.95); }
  .plan-card .foot{ margin-top:auto }
  .user-chip{ background: rgba(255,255,255,.06); }
</style>

<div class="container py-4 py-md-5">
  {{-- Header user --}}
  <div class="text-center mb-4">
    <h1 class="hero-title text-white mb-2">Tài khoản RoX</h1>
    <p class="text-muted mb-4">Sở hữu tài khoản RoX để nhận nhiều quyền lợi và tăng trải nghiệm xem phim.</p>

    <div class="d-inline-flex align-items-center gap-3 p-3 rounded-4 border border-secondary-subtle user-chip">
      <img src="{{ $avatar }}" class="rounded-circle object-fit-cover" width="64" height="64" alt="avatar">
      <div class="text-start">
        <div class="text-white fw-semibold">
          {{ $user->name }}
          @if($user->is_premium) <i class="bi bi-shield-check text-warning ms-1" title="RoX Premium"></i> @endif
        </div>
        <div class="text-white small">
          @if($user->is_premium)
            Bạn đang là thành viên RoX.
          @else
            Bạn đang là thành viên miễn phí.
          @endif
        </div>
      </div>
      {{-- nếu có số dư / nạp thì gắn thêm nút ở đây --}}
    </div>
  </div>

  <h3 class="text-center text-white fw-bold mb-4">Nâng cấp tài khoản RoX ngay bây giờ</h3>

  {{-- Plans --}}
  <div class="row g-4 justify-content-center">
    {{-- 1 tháng --}}
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card plan-card h-100 p-3">
        <div class="card-body d-flex flex-column">
          <div class="title mb-2">1 tháng</div>
          <div class="price mb-1">39K <span class="coin">Ⓡ</span></div>
          <ul class="feature-list mt-3 mb-4">
            <li><span class="tick">✔</span> Tắt toàn bộ quảng cáo</li>
            <li><span class="tick">✔</span> Xem phim chất lượng 4K</li>
            <li><span class="tick">✔</span> Ưu tiên tải &amp; phát nhanh</li>
            <li><span class="tick">✔</span> Sử dụng stickers &amp; GIFs trong chat</li>
            <li><span class="tick">✔</span> Tải lên ảnh đại diện tuỳ chọn</li>
            <li><span class="tick">✔</span> Gắn nhãn <b>ROX</b> cạnh tên</li>
          </ul>
          <div class="foot">
            @if(!$user->is_premium)
              <form method="POST" action="{{ route('premium.upgrade') }}">
                @csrf
                <input type="hidden" name="plan" value="month1">
                <button class="choose-btn w-100">Chọn <i class="bi bi-chevron-up"></i></button>
              </form>
            @else
              <div class="text-center"><span class="badge bg-light text-dark">Đang sử dụng</span></div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- 6 tháng --}}
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card plan-card h-100 p-3">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center justify-content-between">
            <div class="title">6 tháng</div>
            <span class="badge-save">Giảm 19%</span>
          </div>
          <div class="strike mt-1">234K</div>
          <div class="price mb-1">189K <span class="coin">Ⓡ</span></div>
          <ul class="feature-list mt-3 mb-4">
            <li><span class="tick">✔</span> Toàn bộ quyền lợi gói 1 tháng</li>
            <li><span class="tick">✔</span> Ưu tiên hỗ trợ kỹ thuật</li>
            <li><span class="tick">✔</span> Gợi ý nội dung cá nhân hoá</li>
          </ul>
          <div class="foot">
            @if(!$user->is_premium)
              <form method="POST" action="{{ route('premium.upgrade') }}">
                @csrf
                <input type="hidden" name="plan" value="month6">
                <button class="choose-btn w-100">Chọn <i class="bi bi-chevron-up"></i></button>
              </form>
            @else
              <div class="text-center"><span class="badge bg-light text-dark">Đang sử dụng</span></div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- 12 tháng --}}
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card plan-card h-100 p-3">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center justify-content-between">
            <div class="title">12 tháng</div>
            <span class="badge-save">Giảm 28%</span>
          </div>
          <div class="strike mt-1">468K</div>
          <div class="price mb-1">339K <span class="coin">Ⓡ</span></div>
          <ul class="feature-list mt-3 mb-4">
            <li><span class="tick">✔</span> Toàn bộ quyền lợi gói 6 tháng</li>
            <li><span class="tick">✔</span> Ưu đãi dài hạn &amp; ổn định giá</li>
            <li><span class="tick">✔</span> Huy hiệu <b>ROX+</b> đặc biệt</li>
          </ul>
          <div class="foot">
            @if(!$user->is_premium)
              <form method="POST" action="{{ route('premium.upgrade') }}">
                @csrf
                <input type="hidden" name="plan" value="month12">
                <button class="choose-btn w-100">Chọn <i class="bi bi-chevron-up"></i></button>
              </form>
            @else
              <div class="text-center"><span class="badge bg-light text-dark">Đang sử dụng</span></div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  @if (session('status'))
    <div class="alert alert-info mt-4 text-center">{{ session('status') }}</div>
  @endif
</div>
@endsection
