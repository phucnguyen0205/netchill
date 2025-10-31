@extends('layouts.app')

@section('content')
<style>
.btn-remove-fav {
  width: 20px;
  height: 20px;
  border-radius: 50% !important; /* làm tròn */
  background: rgba(255, 255, 255, 0.85) !important; /* nền trắng mờ */
  color: #000 !important; /* màu chữ đen */
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  padding: 0;
  line-height: 1;
  transition: background 0.2s ease;
}

.btn-remove-fav:hover {
  background: #FFD700 !important; /* đỏ khi hover */
  color: #fff !important;
}
.text-tertiary {
  color: #D3D3D3 !important; /* xám nhạt */
}/* ====== TOAST YÊU THÍCH ====== */
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
/* biến thể màu (nếu muốn xài sau này) */
.custom-toast.success{ border-color:rgba(40,167,69,.35); }
.custom-toast.info{ border-color:rgba(255,193,7,.45); }

</style>

  @push('profileContent')
    <div class="container-fluid px-0">
      {{-- Header --}}
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="mb-0 text-white">Yêu thích</h2>

        {{-- Tabs --}}
        @php $tab = $tab ?? 'movies'; @endphp
        <div class="d-flex gap-2">
          <a href="{{ route('favorites.index', ['tab' => 'movies']) }}"
             class="btn {{ $tab === 'movies' ? 'btn-light text-dark' : 'btn-outline-light' }} rounded-pill px-4 py-2">
            Phim
          </a>
          <a href="{{ route('favorites.index', ['tab' => 'people']) }}"
             class="btn {{ $tab === 'people' ? 'btn-light text-dark' : 'btn-outline-light' }} rounded-pill px-4 py-2">
            Diễn viên
          </a>
        </div>
      </div>
      {{-- Nội dung --}}
      @if ($tab === 'movies')
        @if($movies->isEmpty())
          <div class="rounded-4 p-5 w-100" style="background:#161922; border-radius:20px;">
            <div class="d-flex align-items-center justify-content-center" style="min-height:180px;">
              <p class="text-tertiary mb-0">Chưa có bộ phim nào trong danh sách yêu thích của bạn.</p>
            </div>
          </div>
        @else
          <div class="d-flex overflow-auto hide-scrollbar" style="gap: 32px;">
            @foreach($movies->chunk(2) as $twoMovies)
              <div class="d-flex flex-column flex-shrink-0" style="gap: 40px;">
                @foreach($twoMovies as $movie)
                  <div class="card bg-dark text-light border-0 text-center" style="width: 200px;">
                    {{-- Nút gỡ --}}
                    <button type="button"
                            class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow-sm btn-remove-fav"
                            title="Gỡ khỏi yêu thích"
                            data-type="movie"
                            data-id="{{ $movie->id }}">
                      <span class="fw-bold">×</span>
                    </button>

                    <a href="{{ route('movies.show', $movie->slug) }}">
                      <img src="{{ Storage::url($movie->poster) }}"
                           alt="{{ $movie->title }}"
                           class="img-fluid rounded"
                           style="object-fit: cover; height: 300px; width: 100%;">
                    </a>
                    <div class="title-wrapper mt-2">
                      <h6 class="card-title mb-0">{{ Str::title($movie->title) }}</h6>
                    </div>
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>
        @endif
      @else
        @if($people->isEmpty())
          <div class="rounded-4 p-5 w-100" style="background:#161922; border-radius:20px;">
            <div class="d-flex align-items-center justify-content-center" style="min-height:180px;">
              <p class="text-tertiary mb-0">Chưa có diễn viên nào trong danh sách yêu thích của bạn.</p>
            </div>
          </div>
        @else
          <div class="row g-4">
            @foreach($people as $person)
              <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card bg-dark text-light border-0 text-center" style="width: 200px;">
                  <button type="button"
                          class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow-sm btn-remove-fav"
                          title="Gỡ khỏi yêu thích"
                          data-type="person"
                          data-id="{{ $person->id }}">
                    <span class="fw-bold">×</span>
                  </button>

                  <a href="{{ route('people.show', $person->slug ?? $person->id) }}">
                    <img src="{{ $person->photo ?? asset('images/placeholder-1x1.jpg') }}"
                         alt="{{ $person->name }}"
                         class="img-fluid rounded"
                         style="object-fit: cover; height: 200px; width: 100%;">
                  </a>
                  <div class="title-wrapper mt-2">
                    <h6 class="card-title mb-0">{{ $person->name }}</h6>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      @endif
    </div>
  @endpush
  <div id="toast-container"
     class="position-fixed bottom-0 end-0 p-3"
     style="z-index: 1100;"></div>

  @include('profile.partials.profile_layout', ['active_menu' => 'favourites'])
  
@endsection
@push('scripts')
@auth
<script>
function showToast(message) {
  const container = document.getElementById('toast-container');

  const toast = document.createElement('div');
  toast.className = 'custom-toast';
  toast.textContent = message;

  container.appendChild(toast);

  // trigger animation
  setTimeout(() => toast.classList.add('show'), 100);

  // tự ẩn sau 5s
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 400); // đợi animation xong rồi xoá
  }, 5000);
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-remove-fav').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const id   = this.dataset.id;
      const type = this.dataset.type;

      fetch("{{ route('favorites.destroy') }}", {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, type })
      })
      .then(r => r.json())
      .then(() => {
        const card = this.closest('.card');
        if (card) card.remove();

        if (!document.querySelector('.btn-remove-fav')) {
          const wrapper = document.createElement('div');
          wrapper.className = 'rounded-4 p-5 w-100 mt-3';
          wrapper.style.background = '#161922';
          wrapper.style.borderRadius = '20px';
          const inner = document.createElement('div');
          inner.className = 'd-flex align-items-center justify-content-center';
          inner.style.minHeight = '180px';
          const p = document.createElement('p');
          p.className = 'text-tertiary mb-0';
          p.textContent = (type === 'movie')
            ? 'Chưa có bộ phim nào trong danh sách yêu thích của bạn.'
            : 'Chưa có diễn viên nào trong danh sách yêu thích của bạn.';
          inner.appendChild(p);
          wrapper.appendChild(inner);
          document.querySelector('.container-fluid.px-0')?.appendChild(wrapper);
        }

        // 👉 Thông báo sau khi xóa
        showToast((type === 'movie')
          ? 'Đã gỡ phim khỏi danh sách yêu thích.'
          : 'Đã gỡ diễn viên khỏi danh sách yêu thích.');
      })
      .catch(console.error);
    });
  });
});
</script>
@endauth
@endpush
