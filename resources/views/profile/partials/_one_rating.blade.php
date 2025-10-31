@php
  if (!isset($r) && isset($rating)) { $r = $rating; }
  $user  = $r->user ?? null;
  $seed  = $user?->email ?? $user?->id ?? $r->id;
  $avatar = $user?->avatar ? Storage::url($user->avatar) : 'https://i.pravatar.cc/100?u=' . urlencode($seed);
  $currentStars = (int) ($r->stars ?? 0);
@endphp

<style>
  /* Chỉ áp dụng cục bộ cho block sao */
  .rating-stars { display:flex; align-items:center; gap:.25rem; }
  .rating-stars input[type="radio"]{ display:none; }xq
  .rating-stars label{
    cursor:pointer; font-size:1.1rem; line-height:1;
  }
  .rating-stars .bi-star-fill{ color:#fddb00; } /* vàng */
</style>

<div class="comment-item">
  <img src="{{ $avatar }}" class="comment-avatar" alt="avatar">
  <div class="comment-content">
    <div class="comment-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <span class="comment-author text-white">{{ $user?->name ?? 'Người dùng' }}</span>
        <span class="comment-meta">{{ optional($r->created_at)->diffForHumans() }}</span>
      </div>
      {{-- Hiển thị sao hiện tại (tĩnh) trên màn lớn nếu bạn muốn --}}
      <div class="d-none d-md-inline-block">
        @for($i=1;$i<=5;$i++)
          <i class="bi {{ $i <= $currentStars ? 'bi-star-fill text-warning' : 'bi-star' }}"></i>
        @endfor
      </div>
    </div>

    @if(!empty($r->comment))
      <div class="comment-body">
        <p>{!! nl2br(e($r->comment)) !!}</p>
      </div>
    @endif

    {{-- Form sửa số sao (PUT -> ratings.update) --}}
    @auth
    <form
      action="{{ route('movies.ratings.store', $movie->slug) }}"
      method="POST"
      class="rating-form d-flex align-items-center gap-3 mt-2"
      data-initial="{{ $currentStars }}"
    >
      @csrf
      @method('PUT')

      <div class="rating-stars" aria-label="Chọn số sao">
        {{-- radios --}}
        @for($i=5; $i>=1; $i--)
          <input type="radio" name="stars" id="star-{{ $r->id }}-{{ $i }}" value="{{ $i }}" @checked($currentStars === $i)>
        @endfor

        {{-- labels (click vào sẽ chọn radio tương ứng) --}}
        @for($i=5; $i>=1; $i--)
          <label for="star-{{ $r->id }}-{{ $i }}" class="bi" data-value="{{ $i }}" title="{{ $i }} sao" aria-label="{{ $i }} sao"></label>
        @endfor
      </div>
    </form>
    @endauth
  </div>
</div>

{{-- JS cho block sao --}}
<script>
  (function(){
    const form = document.currentScript.closest('.comment-item').querySelector('.rating-form');
    if(!form) return;

    const labels = Array.from(form.querySelectorAll('.rating-stars label'));
    const radios  = Array.from(form.querySelectorAll('input[name="stars"]'));

    function paint(n){
      // n: số sao được chọn
      labels.forEach(lb => {
        const v = parseInt(lb.dataset.value,10);
        lb.classList.toggle('bi-star-fill', v <= n);
        lb.classList.toggle('bi-star',      v >  n);
      });
    }
    // init theo giá trị hiện tại
    paint(parseInt(form.dataset.initial || '0', 10));

    // click label => checked radio + tô lại
    labels.forEach(lb => {
      lb.addEventListener('click', () => {
        const v = parseInt(lb.dataset.value, 10);
        const radio = form.querySelector(`#${lb.getAttribute('for')}`);
        if (radio) radio.checked = true;
        paint(v);
      });
    });

    // nếu đổi radio bằng bàn phím
    radios.forEach(r => r.addEventListener('change', () => paint(parseInt(r.value,10))));
  })();
</script>
