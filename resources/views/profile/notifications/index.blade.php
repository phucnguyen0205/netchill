@extends('layouts.app')

@section('content')
<style>
  .text-tertiary { color: #D3D3D3 !important; }
</style>

@push('profileContent')
@php
    // fallback nếu controller chưa truyền vào
    $notifications = $notifications ?? Auth::user()->notifications()->latest()->paginate(20);
    $unreadCount   = $unreadCount   ?? Auth::user()->unreadNotifications()->count();
@endphp

<div class="container-fluid px-0">
  {{-- Header --}}
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="mb-0 text-white">Thông báo</h2>

    @if($unreadCount > 0)
      <form action="{{ route('notifications.read_all') }}" method="POST" class="m-0">
        @csrf
        <button type="submit"
                class="btn btn-outline-light rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
          ✓ Đánh dấu tất cả đã đọc ({{ $unreadCount > 99 ? '99+' : $unreadCount }})
        </button>
      </form>
    @endif
  </div>

  @if($notifications->isEmpty())
    {{-- Empty state --}}
    <div class="rounded-4 p-5 w-100" style="background:#161922; border-radius:20px;">
      <div class="d-flex align-items-center justify-content-center" style="min-height:180px;">
        <p class="text-tertiary mb-0">Bạn chưa có thông báo nào</p>
      </div>
    </div>
  @else
    {{-- Grid card giống danh sách --}}
    <div class="row g-4">
      @foreach($notifications as $n)
        <div class="col-12 col-md-6 col-lg-4">
          <a href="{{ $n->data['url'] ?? '#' }}"
             class="notif-item d-block h-100 p-4 rounded-4 text-decoration-none
                    {{ $n->read_at ? 'bg-dark' : 'bg-dark border border-2 border-warning' }}"
             data-id="{{ $n->id }}"
             style="background:#1b1f2a; color:#e8eef7;">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="text-white fw-bold mb-0" style="font-size:1rem;">
                {{ $n->data['title'] ?? 'Thông báo' }}
              </h5>
              @if(!$n->read_at)
                <span class="badge bg-warning text-dark">Mới</span>
              @endif
            </div>
            <div class="text-tertiary small">
              {{ $n->created_at->diffForHumans() }}
            </div>
          </a>
        </div>
      @endforeach
    </div>

    <div class="mt-4">
      {{ $notifications->withQueryString()->links() }}
    </div>
  @endif
</div>
@endpush

@include('profile.partials.profile_layout', ['active_menu' => 'notifications'])
@endsection

@section('scripts')
<script>
document.addEventListener('click', function(e){
  const item = e.target.closest('.notif-item');
  if(!item) return;
  e.preventDefault();

  const id   = item.dataset.id;
  const href = item.getAttribute('href') || '#';
  if(!id){ if(href && href !== '#') window.location.href = href; return; }

  const readUrlTpl = @json(route('notifications.read', ['notification' => '__ID__']));
  fetch(readUrlTpl.replace('__ID__', id), {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
  }).finally(() => {
    if(href && href !== '#') window.location.href = href;
    else location.reload();
  });
});
</script>
@endsection
