@extends('layouts.app')

@section('content')
<style>
:root{
  --bg-1:#0f1218; --bg-2:#161922;
  --fg-1:#fff;    --fg-2:#cfd3dc;
  --line:#2a2f3a; --accent:#ffd45a;
}
.page{color:var(--fg-1);}
.page h2{font-weight:800;margin:0}

/* Header */
.page-head{display:flex;align-items:center;gap:12px;justify-content:space-between;margin-bottom:18px}
.btn-pill{border-radius:999px;padding:.45rem .9rem;border:1px solid var(--line);color:var(--fg-1);background:transparent}
.btn-pill:hover{background:#1b1f29}

/* Input tối (dùng cho form + modal) */
.form-control-dark{
  background:#10131a !important;
  color:#e8edf6 !important;
  border:1px solid #2a2f3a !important;
  border-radius:10px !important;
}
.form-control-dark::placeholder{color:#9aa3b2;opacity:1}
.form-control-dark:focus{border-color:var(--accent) !important; box-shadow:0 0 0 .15rem rgba(255,212,90,.15)}

/* Create box */
.nc-create{background:var(--bg-2);border:1px solid var(--line);border-radius:16px;padding:14px;margin-top:12px}
.nc-btn-cancel{color:#e8edf6;border-radius:999px;padding:.55rem 1.1rem;border:1px solid var(--line);background:transparent}
.nc-btn-cancel:hover{background:#1b1f29}
.nc-btn-save{background:#e8edf6;color:#111;border:none;border-radius:999px;padding:.55rem 1.1rem;font-weight:700}
.nc-btn-save:hover{background:var(--accent);color:#111}
.nc-invalid{color:#ff8f8f;font-size:.9rem;margin-left:2px}

/* List cards scroller */
.wl-row{display:flex;gap:16px;overflow:auto;padding:6px 2px 12px 2px}
.wl-row::-webkit-scrollbar{display:none}
.wl-card{
  width:300px;background:var(--bg-2);border:2px solid transparent;border-radius:14px;padding:18px;
  flex:0 0 auto;transition:border-color .2s ease, background .2s ease;position:relative;color:var(--fg-1);
}
.wl-card:hover{background:#1b1f29}
.wl-card.active{border-color:var(--accent);box-shadow:0 0 0 4px rgba(255,212,90,.14) inset}
.wl-title{font-weight:800;margin:0 0 10px;font-size:20px}
.wl-meta{display:flex;gap:14px;align-items:center}
.badge-muted{background:#2a2f3a;color:var(--fg-2);border-radius:10px;padding:.25rem .5rem;font-size:.85rem}

/* Dropdown in card */
.wl-actions{margin-left:auto;position:relative;z-index:5}
.wl-actions .btn-icon{background:transparent;border:0;color:#9aa3b2;padding:0 .25rem}
.wl-actions .btn-icon:hover{color:#cfd3dc}
.dropdown-menu{background:#2b2f3a;border:none;border-radius:12px;color:#e8edf6}
.dropdown-menu .dropdown-item{color:#e8edf6}
.dropdown-menu .dropdown-item:hover{background:#383e4d}

/* Movies grid */
.movies{margin-top:26px}
.movie-card{width:260px;background:var(--bg-1);border:1px solid var(--line);border-radius:16px;overflow:hidden;position:relative}
.movie-cover{width:100%;height:360px;object-fit:cover;display:block}
.movie-x{position:absolute;top:10px;right:10px;width:28px;height:28px;border-radius:8px;background:#e9eef7;
  display:flex;align-items:center;justify-content:center;font-weight:700;color:#111;cursor:pointer;border:none}
.movie-badges{position:absolute;left:10px;bottom:12px;display:flex;gap:8px}
.movie-badge{background:#3a3f4b;border-radius:8px;padding:.15rem .45rem;font-size:.8rem;color:#e8edf6}
.movie-body{padding:12px 12px 16px}
.movie-title{margin:0;font-weight:800;font-size:18px}
.movie-sub{margin:4px 0 0;color:#9aa3b2;font-size:.95rem}

/* Toast */
#toast-container{position:fixed;right:12px;bottom:12px;z-index:1100}
.custom-toast{background:#222;color:#fff;border-radius:12px;padding:12px 16px;margin-top:8px;
  box-shadow:0 8px 24px rgba(0,0,0,.35);font-size:14px;opacity:0;transform:translateX(100%);transition:all .35s ease;
  border:1px solid rgba(255,255,255,.08)}
.custom-toast.show{opacity:1;transform:translateX(0)}
</style>

@php $openId = request('open'); @endphp

@push('profileContent')
<div class="page">
  {{-- Header --}}
  <div class="page-head">
    <h2>Danh sách</h2>
    <button class="btn btn-pill" type="button" data-bs-toggle="collapse" data-bs-target="#wlCreateCollapse"
            aria-expanded="{{ $errors->has('name') ? 'true' : 'false' }}" aria-controls="wlCreateCollapse">+ Thêm mới</button>
  </div>

  {{-- Create form --}}
  <div class="collapse {{ $errors->has('name') ? 'show' : '' }}" id="wlCreateCollapse">
    <div class="nc-create">
      <form action="{{ route('watchlists.store') }}" method="POST">
        @csrf
        <div class="row g-2 align-items-center">
          <div class="col-12 col-md">
            <input type="text" name="name" class="form-control form-control-dark @error('name') is-invalid @enderror"
                   placeholder="Nhập tên danh sách (ví dụ: Muốn xem cuối tuần)" value="{{ old('name') }}" required>
          </div>
          <div class="col-auto">
            <button type="button" class="btn nc-btn-cancel" data-bs-toggle="collapse" data-bs-target="#wlCreateCollapse">Hủy</button>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn nc-btn-save">Lưu</button>
          </div>
        </div>
        @error('name') <div class="nc-invalid mt-2">{{ $message }}</div> @enderror
      </form>
    </div>
  </div>

  {{-- Lists --}}
  <div class="wl-row">
    @forelse($watchlists as $wl)
      <div class="wl-card {{ (int)$openId === (int)$wl->id ? 'active' : '' }}">
        <h3 class="wl-title mb-2">{{ $wl->name }}</h3>

        <div class="d-flex align-items-center">
          <span class="badge-muted">
            <i class="bi bi-play-circle me-1"></i>
            {{ $wl->movies_count ?? $wl->items_count ?? 0 }} phim
          </span>

          <div class="wl-actions ms-auto dropdown">
            <button class="btn-icon" type="button"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"
                    aria-label="Mở menu">
              <i class="bi bi-three-dots-vertical fs-6"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-dark">
              <li>
                <a href="#" class="dropdown-item js-edit-wl" data-id="{{ $wl->id }}" data-name="{{ $wl->name }}">
                  <i class="bi bi-pencil me-2"></i> Sửa
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('watchlists.destroy',$wl) }}" class="m-0">
                  @csrf @method('DELETE')
                  <button type="submit" class="dropdown-item text-danger"
                          onclick="return confirm('Xóa danh sách này?')">
                    <i class="bi bi-trash me-2"></i> Xóa
                  </button>
                </form>
              </li>
            </ul>
          </div>
        </div>

        {{-- link điều hướng riêng, không che dropdown --}}
        <a class="stretched-link" href="{{ route('watchlists.index', ['open'=>$wl->id]) }}" aria-label="Mở danh sách {{ $wl->name }}"></a>
      </div>
    @empty
      <div class="wl-card" style="opacity:.75">
        <h3 class="wl-title">Chưa có danh sách</h3>
        <div class="wl-meta"><span class="badge-muted">0 phim</span></div>
      </div>
    @endforelse
  </div>

  {{-- Movies in opened list (nếu controller truyền $movies) --}}
  <div class="movies">
    @isset($movies)
      @if($movies->isEmpty())
        <div class="nc-create"><p class="mb-0" style="color:var(--fg-2)">Danh sách hiện chưa có phim.</p></div>
      @else
        <div class="row g-4">
          @foreach($movies as $m)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <div class="movie-card">
                <button type="button" class="movie-x js-remove" data-watchlist="{{ $openId }}" data-movie="{{ $m->id }}" title="Gỡ khỏi danh sách">×</button>
                <a href="{{ route('movies.show', $m->slug) }}">
                  <img class="movie-cover" src="{{ Storage::url($m->poster) }}" alt="{{ $m->title }}">
                </a>
                <div class="movie-badges">
                  @if(!empty($m->season_label)) <span class="movie-badge">{{ $m->season_label }}</span> @endif
                  @if(!empty($m->dub_label))    <span class="movie-badge">{{ $m->dub_label }}</span>    @endif
                </div>
                <div class="movie-body">
                  <h4 class="movie-title">{{ $m->title }}</h4>
                  <div class="movie-sub">{{ $m->origin_title ?? '' }}</div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        @if(method_exists($movies,'links'))
          <div class="mt-3">{{ $movies->appends(['open'=>$openId])->links() }}</div>
        @endif
      @endif
    @else
      @if($openId)
        <div class="nc-create"><p class="mb-0" style="color:var(--fg-2)">Đang chọn danh sách #{{ $openId }} — hãy nạp phim từ controller để hiển thị ở đây.</p></div>
      @endif
    @endisset
  </div>
</div>

{{-- Modal Sửa danh sách (có Xóa) --}}
<div class="modal fade" id="editWatchlistModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background:#202738;border:none;border-radius:16px;color:#e9eef6">
      <form id="editWatchlistForm" method="POST">
        @csrf @method('PUT')
        <div class="modal-header border-0">
          <h5 class="modal-title">Sửa danh sách</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="name" id="editWlName" class="form-control form-control-dark" placeholder="Tên danh sách" required>
        </div>
        <div class="modal-footer border-0 d-flex justify-content-between">
          <form id="deleteWatchlistForm" method="POST" class="m-0">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Xóa danh sách</button>
          </form>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-warning">Lưu</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endpush

{{-- Toast --}}
<div id="toast-container"></div>

@include('profile.partials.profile_layout', ['active_menu' => 'watchlist'])
@endsection

@push('scripts')
@auth
<script>
// focus input khi mở collapse
document.addEventListener('shown.bs.collapse', (e)=>{
  if(e.target.id==='wlCreateCollapse'){
    e.target.querySelector('input[name="name"]')?.focus();
  }
});

// toast nhỏ
function toast(msg){
  const c=document.getElementById('toast-container');
  const t=document.createElement('div');
  t.className='custom-toast'; t.textContent=msg; c.appendChild(t);
  setTimeout(()=>t.classList.add('show'),30);
  setTimeout(()=>{t.classList.remove('show');setTimeout(()=>t.remove(),350)},4000);
}

// gỡ phim khỏi danh sách
document.addEventListener('click', async (e)=>{
  const btn=e.target.closest('.js-remove'); if(!btn) return;
  e.preventDefault();
  try{
    await fetch(`{{ url('/watchlists') }}/${btn.dataset.watchlist}/items/${btn.dataset.movie}`,{
      method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    });
    const col=btn.closest('.col-12'); col?.remove();
    if(!document.querySelector('.js-remove')){
      document.querySelector('.movies')!.innerHTML =
        `<div class="nc-create"><p class="mb-0" style="color:var(--fg-2)">Danh sách hiện chưa có phim.</p></div>`;
    }
    toast('Đã gỡ phim khỏi danh sách.');
  }catch{ toast('Không thể gỡ phim. Vui lòng thử lại.'); }
});

// mở modal sửa
document.addEventListener('click',(e)=>{
  const link=e.target.closest('.js-edit-wl'); if(!link) return;
  e.preventDefault();

  const id=link.dataset.id, name=link.dataset.name;

  // set action cho form PUT + DELETE
  const editForm=document.getElementById('editWatchlistForm');
  editForm.action=`{{ url('/watchlists') }}/${id}`;
  document.getElementById('editWlName').value=name;

  const delForm=document.getElementById('deleteWatchlistForm');
  delForm.action=`{{ url('/watchlists') }}/${id}`;

  new bootstrap.Modal(document.getElementById('editWatchlistModal')).show();
});

/* ==== Dropdown anti-clipping: port menu ra body khi mở ==== */
document.addEventListener('show.bs.dropdown', function (e) {
  const menu = e.target.querySelector('.dropdown-menu');
  if (!menu) return;
  const rect = e.target.getBoundingClientRect();

  // move to body
  document.body.appendChild(menu);
  menu.style.position = 'fixed';
  menu.style.inset = 'auto auto auto auto';
  menu.style.left = (rect.left) + 'px';
  menu.style.top  = (rect.bottom + 6) + 'px';
  menu.style.transform = 'none';
  menu.classList.add('show');
});
document.addEventListener('hide.bs.dropdown', function (e) {
  const menu = document.querySelector('body > .dropdown-menu.show');
  if (!menu) return;
  // put it back
  const host = e.target.querySelector('.dropdown');
  (host || e.target).appendChild(menu);
  menu.removeAttribute('style');
  menu.classList.remove('show');
});
</script>
@endauth
@endpush
