@extends('layouts.app')

@section('content')
<style>
  .text-tertiary { color: #D3D3D3 !important; }
</style>

@push('profileContent')
@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $histories */
    $histories = $histories ?? collect(); // fallback nếu chưa truyền
@endphp

<div class="container-fluid px-0">
  {{-- Header --}}
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="mb-0 text-white">Xem tiếp</h2>

    @if($histories instanceof \Illuminate\Contracts\Pagination\Paginator && $histories->total() > 0)
      <div class="text-tertiary">
        Tổng: {{ number_format($histories->total()) }} phim
      </div>
    @endif
  </div>

  @if($histories->isEmpty())
    {{-- Empty state --}}
    <div class="rounded-4 p-5 w-100" style="background:#161922; border-radius:20px;">
      <div class="d-flex align-items-center justify-content-center" style="min-height:180px;">
        <p class="text-tertiary mb-0">Bạn chưa có lịch sử xem nào</p>
      </div>
    </div>
  @else
    {{-- Grid card giống danh sách ở trang thông báo --}}
    <div class="row g-4">
      @foreach($histories as $h)
        @php
          $m = $h->movie ?? null;
          if(!$m) continue;
          $resumeUrl = route('movies.show', $m->slug) . '?t=' . (int) $h->last_position;
          $labelPos  = sprintf('%02d:%02d', floor($h->last_position/60), $h->last_position%60);
          $labelDur  = $h->duration ? sprintf('%02d:%02d', floor($h->duration/60), $h->duration%60) : '—';
          $pct       = max(0, min(100, (int) $h->progress));
        @endphp

        <div class="col-12 col-md-6 col-lg-4">
          <a href="{{ $resumeUrl }}"
             class="d-block h-100 p-4 rounded-4 text-decoration-none bg-dark"
             style="background:#1b1f2a; color:#e8eef7;">
            <div class="d-flex align-items-start gap-3">
              {{-- Poster (nếu có) --}}
              @if(!empty($m->poster))
                <img src="{{ \Illuminate\Support\Facades\Storage::url($m->poster) }}"
                     alt="{{ $m->title }}"
                     style="width:76px;height:108px;object-fit:cover;border-radius:10px;border:1px solid rgba(255,255,255,.08)">
              @else
                <div style="width:76px;height:108px;border-radius:10px;background:#232839;border:1px solid rgba(255,255,255,.08)"></div>
              @endif

              <div class="flex-grow-1">
                <h5 class="text-white fw-bold mb-2" style="font-size:1rem;">{{ $m->title }}</h5>

                <div class="text-tertiary small mb-1">
                  {{ $labelPos }} / {{ $labelDur }} • {{ $pct }}%
                </div>

                <div class="progress" style="height:6px;">
                  <div class="progress-bar bg-warning" role="progressbar"
                       style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                  </div>
                </div>

                @if($h->last_watched_at)
                  <div class="text-tertiary small mt-2">
                    Lần xem gần nhất: {{ $h->last_watched_at->diffForHumans() }}
                  </div>
                @endif
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>

    <div class="mt-4">
      {{ $histories->withQueryString()->links() }}
    </div>
  @endif
</div>
@endpush

{{-- Giống file mẫu: dùng layout profile + active menu tùy bạn đặt key --}}
@include('profile.partials.profile_layout', ['active_menu' => 'history'])
@endsection
