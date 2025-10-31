@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="mb-3">Chỉnh sửa phim</h1>

  {{-- Alerts --}}
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

  {{-- Tabs --}}
  <ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-basic" type="button">Cơ bản</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-categories" type="button">Thể loại</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-countries" type="button">Quốc gia</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-media" type="button">Media</button></li>
    <li class="nav-item">
      <button class="nav-link" id="tab-episodes-tab" data-bs-toggle="tab" data-bs-target="#tab-episodes" type="button" {{ $movie->is_series ? '' : 'disabled' }}>
        Phần & Tập
      </button>
    </li>
  </ul>

  <form id="movieForm" method="POST" action="{{ route('movies.update', $movie) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="tab-content">
      {{-- ===== CƠ BẢN ===== --}}
      <div class="tab-pane fade show active" id="tab-basic">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $movie->title) }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tên tiếng Anh</label>
            <input type="text" name="english_title" class="form-control" value="{{ old('english_title', $movie->english_title) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Năm phát hành</label>
            <input type="number" name="release_year" min="1900" max="{{ date('Y') }}" class="form-control"
                   value="{{ old('release_year', $movie->release_year) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Loại phim</label>
            <select name="is_series" id="is_series" class="form-select">
              <option value="0" {{ old('is_series', (int)$movie->is_series) === 0 ? 'selected' : '' }}>Phim lẻ</option>
              <option value="1" {{ old('is_series', (int)$movie->is_series) === 1 ? 'selected' : '' }}>Phim bộ</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Phiên bản</label>
            @php $versions = ['sub'=>'Phụ đề','dub'=>'Lồng tiếng','raw'=>'RAW']; @endphp
            <select name="version" class="form-select">
              @foreach($versions as $val=>$label)
                <option value="{{ $val }}" {{ old('version', $movie->version) === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Độ tuổi</label>
            @php $ages = ['P','K','T13','T16','T18']; @endphp
            <select name="age_rating" class="form-select">
              @foreach($ages as $r)
                <option value="{{ $r }}" {{ old('age_rating', $movie->age_rating) === $r ? 'selected' : '' }}>{{ $r }}</option>
              @endforeach
            </select>
          </div>

          {{-- Optional: thời lượng, NSX --}}
          <div class="col-md-3">
            <label class="form-label">Thời lượng</label>
            <input type="text" name="duration" class="form-control" value="{{ old('duration', $movie->duration) }}" placeholder="VD: 120 phút / 12x45’">
          </div>
          <div class="col-md-9">
            <label class="form-label">Nhà sản xuất</label>
            <input type="text" name="production" class="form-control" value="{{ old('production', $movie->production) }}">
          </div>

          {{-- Tổng số phần (chỉ với phim bộ) --}}
          <div class="col-md-3" id="block-total-seasons" style="{{ $movie->is_series ? '' : 'display:none' }}">
            <label class="form-label">Tổng số phần</label>
            @php
              $existingSeasonCount = ($movie->relationLoaded('seasons') ? $movie->seasons : \App\Models\Season::where('movie_id',$movie->id)->get())->count();
            @endphp
            <input type="number" id="total_seasons" name="total_seasons" class="form-control" min="1"
                   value="{{ old('total_seasons', $existingSeasonCount ?: null) }}">
            <small class="text-muted">Đổi số này sẽ tự sinh/xoá phần ở tab “Phần & Tập”.</small>
          </div>

          <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="description" rows="4" class="form-control">{{ old('description', $movie->description) }}</textarea>
          </div>
        </div>
      </div>

      {{-- ===== THỂ LOẠI ===== --}}
      <div class="tab-pane fade" id="tab-categories">
        @php
          $pickedCategories = collect(old('category_ids', $movie->categories?->pluck('id')->all() ?? []));
        @endphp
        <label class="form-label">Thể loại</label>
        <select name="category_ids[]" id="category_ids" class="form-select" multiple>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ $pickedCategories->contains($cat->id) ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
        <small class="text-muted d-block mt-1">Giữ Ctrl/Cmd để chọn nhiều.</small>
      </div>

      {{-- ===== QUỐC GIA ===== --}}
      <div class="tab-pane fade" id="tab-countries">
        @php
          $pickedCountries = collect(old('country_ids', $movie->countries?->pluck('id')->all() ?? []));
        @endphp
        <label class="form-label">Quốc gia</label>
        <select name="country_ids[]" id="country_ids" class="form-select" multiple>
          @foreach($countries as $c)
            <option value="{{ $c->id }}" {{ $pickedCountries->contains($c->id) ? 'selected' : '' }}>
              {{ $c->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- ===== MEDIA (Poster/Banner/Video) ===== --}}
      <div class="tab-pane fade" id="tab-media">
        <div class="row g-3">
          {{-- Poster --}}
          <div class="col-md-6">
            <label class="form-label">Poster hiện tại</label>
            <div class="mb-2">
              @if($movie->poster)
                <img class="rounded shadow" src="{{ Storage::url($movie->poster) }}" alt="Poster" style="width:160px;aspect-ratio:2/3;object-fit:cover">
              @else
                <span class="text-muted">Chưa có poster</span>
              @endif
            </div>
            <input type="file" name="poster" class="form-control" accept="image/*">
          </div>

          {{-- Banner hero + banners phụ --}}
          <div class="col-md-6">
            <label class="form-label d-flex justify-content-between">
              <span>Banner (Hero)</span>
              <small class="text-muted">Tỉ lệ ngang (16:9 hoặc 21:9)</small>
            </label>
            @php
              $heroBanner = $movie->relationLoaded('banners')
                ? $movie->banners->firstWhere('variant','hero')
                : \App\Models\Banner::where('movie_id',$movie->id)->where('variant','hero')->latest()->first();
            @endphp
            <div class="mb-2">
              @if($heroBanner?->image_path ?? $movie->banner)
                <img class="rounded shadow w-100" style="max-height:140px;object-fit:cover"
                     src="{{ Storage::url($heroBanner?->image_path ?? $movie->banner) }}" alt="Hero banner">
              @else
                <span class="text-muted">Chưa có banner hero</span>
              @endif
            </div>
            <input type="file" name="banner_hero" class="form-control" accept="image/*">
          </div>

          {{-- Danh sách banner phụ hiện có --}}
          <div class="col-12">
            <label class="form-label">Banner phụ hiện có</label>
            <div class="d-flex flex-wrap gap-2">
              @php
                $otherBanners = $movie->relationLoaded('banners')
                  ? $movie->banners->where('variant','!=','hero')
                  : \App\Models\Banner::where('movie_id',$movie->id)->where('variant','!=','hero')->get();
              @endphp
              @forelse($otherBanners as $b)
                <label class="border rounded p-2 d-inline-flex flex-column gap-1" style="width:210px">
                  <img src="{{ Storage::url($b->image_path) }}" class="w-100 rounded" style="height:90px;object-fit:cover" alt="banner">
                  <div class="small text-muted">#{{ $b->id }} • {{ $b->variant ?: 'banner' }}</div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="delete_banners[]" value="{{ $b->id }}" id="del_{{ $b->id }}">
                    <label class="form-check-label" for="del_{{ $b->id }}">Xoá</label>
                  </div>
                </label>
              @empty
                <span class="text-muted">Không có banner phụ.</span>
              @endforelse
            </div>
            <div class="mt-2">
              <label class="form-label">Thêm banner phụ (có thể chọn nhiều)</label>
              <input type="file" name="banners[]" class="form-control" accept="image/*" multiple>
            </div>
          </div>

          {{-- Video (phim lẻ) – chunked --}}
          <div class="col-12" id="block-movie-video" style="{{ $movie->is_series ? 'display:none' : '' }}">
            <label class="form-label">Video hiện tại</label>
            <div class="mb-1">{{ $movie->file_name ?? 'Chưa có video' }}</div>

            <label class="form-label">Tải video mới (mp4/mpeg)</label>
            <input type="file" id="videoInput" class="form-control" accept="video/mp4,video/mpeg">
            <input type="hidden" name="file_name" id="file_name" value="{{ old('file_name', $movie->file_name) }}">
            <div class="progress mt-2">
              <div id="uploadProgress" class="progress-bar bg-success" role="progressbar"
                   style="width: {{ $movie->file_name ? '100' : '0' }}%"> {{ $movie->file_name ? '100%' : '0%' }}</div>
            </div>
            <small class="text-muted">Upload phân mảnh, tự hợp nhất sau khi hoàn tất.</small>
          </div>
        </div>
      </div>

      {{-- ===== PHẦN & TẬP (PHIM BỘ) ===== --}}
      <div class="tab-pane fade" id="tab-episodes">
        <div class="alert alert-secondary py-2">Quản lý phần/tập. Tổng số phần & tổng số tập mỗi phần sẽ tự sinh form.</div>

        <div class="d-flex justify-content-between align-items-center mb-2">
          <h5 class="mb-0">Danh sách phần</h5>
          <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddSeason">+ Thêm phần</button>
          </div>
        </div>

        <div id="seasonsWrap" class="vstack gap-3">
          {{-- Render phần hiện có --}}
          @php
            $seasons = $movie->relationLoaded('seasons')
              ? $movie->seasons->sortBy('season_number')->values()
              : \App\Models\Season::where('movie_id',$movie->id)->orderBy('season_number')->get();
          @endphp

          @foreach($seasons as $idx => $s)
          @php 
            $sid = $s->id; 
          @endphp
          @php
            // Đảm bảo lấy episodes đã được eager load, và sắp xếp theo số tập
            $episodes = $s->relationLoaded('episodes') 
                ? $s->episodes->sortBy('episode_number') 
                : $s->episodes()->orderBy('episode_number')->get();
          @endphp
            <div class="card card-body border-0 bg-dark-subtle text-body season-card">
              <div class="row g-2 align-items-end">
                <div class="col-12 col-md-2">
                  <label class="form-label">Số phần</label>
                  <input type="number" class="form-control season-number-input" name="seasons[{{ $sid }}][number]" min="1" value="{{ old("seasons.$sid.number", $s->season_number) }}">
                </div>
                <div class="col-12 col-md-3">
                  <label class="form-label">Tổng số tập (phần này)</label>
                  <input type="number" class="form-control total-episodes-input" data-season-id="{{ $sid }}"
                         name="seasons[{{ $sid }}][total_episodes]" min="1"
                         value="{{ old("seasons.$sid.total_episodes", $s->total_episodes) }}">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">Ghi chú</label>
                  <input type="text" class="form-control" name="seasons[{{ $sid }}][note]"
                         value="{{ old("seasons.$sid.note", $s->note) }}">
                </div>
                <div class="col-12 col-md-1">
                  <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" name="seasons[{{ $sid }}][delete]" value="1" id="del_season_{{ $sid }}">
                    <label class="form-check-label small" for="del_season_{{ $sid }}">Xoá</label>
                  </div>
                </div>
              </div>

              {{-- Episodes of this season --}}
              <div class="episode-block mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="mb-0">Tập phim • Phần <span class="js-season-title">{{ $s->season_number }}</span></h6>
                  <button type="button" class="btn btn-sm btn-outline-success btnAddEpisode">+ Thêm tập</button>
                </div>

                <div class="vstack gap-2 epWrap">
                  @foreach($episodes as $eIdx => $ep)
                  @php 
                      $eid = "exist_{$ep->id}"; 
                      // Ưu tiên file_name, sau đó là video_path
                      $videoFileName = $ep->file_name ?? basename($ep->video_path ?? '');
                  @endphp
                  <div class="row g-2 align-items-end border rounded-3 p-2 bg-dark-subtle ep-row" data-uploading="0">
                    
                    {{-- Input ẩn ID Episode --}}
                    <input type="hidden" name="seasons[{{ $sid }}][episodes][{{ $eid }}][id]" value="{{ $ep->id }}">

                    <div class="col-md-2">
                      <label class="form-label">Số tập</label>
                      <input type="number" name="seasons[{{ $sid }}][episodes][{{ $eid }}][episode_number]" 
                             class="form-control" value="{{ $ep->episode_number }}" min="1" required>
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">Tên tập</label>
                      <input type="text" name="seasons[{{ $sid }}][episodes][{{ $eid }}][name]" 
                             class="form-control" value="{{ $ep->name ?? '' }}" placeholder="Tên tập (tùy chọn)">
                    </div>
                    
                    {{-- CHUNK UPLOAD CHO TẬP (Video) --}}
                    <div class="col-md-3">
                      <label class="form-label">Video (chunk)</label>
                      <input type="file" class="form-control ep-video-input" accept="video/mp4,video/mpeg">
                      
                      {{-- Input ẩn chứa tên file đã upload thành công --}}
                      <input type="hidden" name="seasons[{{ $sid }}][episodes][{{ $eid }}][video_file_name]" 
                             class="ep-video-fn" 
                             value="{{ $videoFileName }}"> 
                             
                      <div class="progress mt-2" style="height:8px;">
                        <div class="progress-bar ep-progress" role="progressbar" 
                             style="width:{{ $videoFileName ? '100%' : '0%' }}"> 
                        </div>
                      </div>
                      <small class="text-muted ep-status d-block mt-1">
                        {{-- Hiển thị tên file hiện có --}}
                        @if($videoFileName) 
                           Hiện có: **{{ $videoFileName }}**
                        @else 
                           Chưa có video 
                        @endif
                      </small>
                    </div>
                    
                    <div class="col-md-3">
                      <label class="form-label">Link Subtitle (VTT)</label>
                      <input type="url" name="seasons[{{ $sid }}][episodes][{{ $eid }}][subtitle_url]" 
                             class="form-control" value="{{ $ep->subtitle_url ?? '' }}" placeholder="URL VTT">
                    </div>
                    
                    <div class="col-md-1 d-flex align-items-end">
                      <button type="button" class="btn btn-sm btn-outline-danger w-100 btnRemoveEpisode">Xóa</button>
                    </div>
                  </div>
                  @endforeach
                </div>

                {{-- Template 1 dòng tập cho season này (JS sẽ clone) --}}
                <template class="tplEpisode">
                  <div class="row g-2 align-items-end border rounded-3 p-2 bg-dark-subtle ep-row" data-uploading="0">
                    <div class="col-md-2">
                      <label class="form-label">Số tập</label>
                      <input type="number" name="seasons[__SIDX__][episodes][__EIDX__][episode_number]" value="1" class="form-control" min="1">
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">Tên tập</label>
                      <input type="text" name="seasons[__SIDX__][episodes][__EIDX__][name]" class="form-control" placeholder="Tên tập (tùy chọn)">
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">Video (chunk)</label>
                      <input type="file" class="form-control ep-video-input" accept="video/mp4,video/mpeg">
                      <input type="hidden" name="seasons[__SIDX__][episodes][__EIDX__][video_file_name]" class="ep-video-fn">
                      <div class="progress mt-2" style="height:8px;">
                        <div class="progress-bar ep-progress" role="progressbar" style="width:0%">0%</div>
                      </div>
                      <small class="text-muted ep-status d-block mt-1"></small>
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">Link Subtitle (VTT)</label>
                      <input type="url" name="seasons[__SIDX__][episodes][__EIDX__][subtitle_url]" class="form-control" placeholder="URL VTT">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                      <button type="button" class="btn btn-sm btn-outline-danger w-100 btnRemoveEpisode">Xóa</button>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          @endforeach
        </div>

        {{-- Template Season mới --}}
        <template id="tplSeasonRow">
          <div class="card card-body border-0 bg-dark-subtle text-body season-card">
            <div class="row g-2 align-items-end">
              <div class="col-12 col-md-2">
                <label class="form-label">Số phần</label>
                <input type="number" class="form-control season-number-input" name="seasons[__SIDX__][number]" min="1" value="1">
              </div>
              <div class="col-12 col-md-3">
                <label class="form-label">Tổng số tập (phần này)</label>
                <input type="number" class="form-control total-episodes-input" data-season-id="__SIDX__"
                       name="seasons[__SIDX__][total_episodes]" min="1" value="1">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Ghi chú</label>
                <input type="text" class="form-control" name="seasons[__SIDX__][note]" placeholder="VD: Bản Director's Cut">
              </div>
              <div class="col-12 col-md-1">
                <button type="button" class="btn btn-outline-danger w-100 btnRemoveSeason">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>

            <div class="episode-block mt-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Tập phim của phần <span class="js-season-title">__NUM__</span></h6>
                <button type="button" class="btn btn-sm btn-outline-success btnAddEpisode">+ Thêm tập</button>
              </div>
              <div class="vstack gap-2 epWrap"></div>
              <template class="tplEpisode">
                <div class="row g-2 align-items-end border rounded-3 p-2 bg-dark-subtle ep-row">
                  <div class="col-md-2">
                    <label class="form-label">Số tập</label>
                    <input type="number" name="seasons[__SIDX__][episodes][__EIDX__][number]" value="1" class="form-control" min="1">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Tên tập</label>
                    <input type="text" name="seasons[__SIDX__][episodes][__EIDX__][name]" class="form-control" placeholder="Tên tập...">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Video (chunk)</label>
                    <input type="file" class="form-control ep-video-input" accept="video/mp4,video/mpeg">
                    <input type="hidden" name="seasons[__SIDX__][episodes][__EIDX__][video_file_name]" class="ep-video-fn">
                    <div class="progress mt-2" style="height:8px;">
                      <div class="progress-bar ep-progress" role="progressbar" style="width:0%">0%</div>
                    </div>
                    <small class="text-muted ep-status d-block mt-1"></small>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Link Subtitle (VTT)</label>
                    <input type="url" name="seasons[__SIDX__][episodes][__EIDX__][subtitle_url]" class="form-control" placeholder="URL VTT">
                  </div>
                  <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100 btnRemoveEpisode">Xóa</button>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </template>
      </div>
    </div>

    <div class="mt-3 d-flex gap-2">
      <button type="submit" id="btnSubmit" class="btn btn-primary">Cập nhật</button>
      <a href="{{ route('movies.show', $movie) }}" class="btn btn-outline-secondary">Quay lại</a>
    </div>
  </form>
</div>
@endsection
@push('scripts')
<script>
(() => {
  // ====== LẤY DOM ======
  const isSeriesSel        = document.getElementById('is_series');
  const totalSeasonsBlock  = document.getElementById('block-total-seasons');
  const totalSeasonsInput  = document.getElementById('total_seasons');
  const epTabBtn           = document.getElementById('tab-episodes-tab');
  const seasonsWrap        = document.getElementById('seasonsWrap');
  const tplSeason          = document.getElementById('tplSeasonRow')?.innerHTML?.trim() || '';
  const movieForm          = document.getElementById('movieForm');

  // Khối upload phim lẻ
  const videoInputMain     = document.getElementById('videoInput');
  const fileNameInputMain  = document.getElementById('file_name');
  const progressBarMain    = document.getElementById('uploadProgress');
  const blockMovieVideo    = document.getElementById('block-movie-video');

  // ====== TIỆN ÍCH NHỎ ======
  const qs   = (sel, el=document) => el.querySelector(sel);
  const qsa  = (sel, el=document) => Array.from(el.querySelectorAll(sel));
  const val  = (el) => (el?.value ?? '').trim();

  function getSeasonCards(){ return qsa('.season-card', seasonsWrap || document); }
  function getEpisodesWrap(card){ return qs('.epWrap', card); }
  function getSeasonNumberInput(card){ return qs('input.season-number-input', card); }
  function getSeasonTotalInput(card){ return qs('input.total-episodes-input', card); }

  // Khởi tạo index để tạo key season mới, tránh đụng key hiện có (dùng cho form data)
  let sIdx = getSeasonCards().length;

  // ====== TOGGLE UI: Phim lẻ / Phim bộ ======
  function toggleSeriesUI(){
    const isSeries = String(isSeriesSel?.value) === '1';
    if (totalSeasonsBlock) totalSeasonsBlock.style.display = isSeries ? '' : 'none';
    if (epTabBtn) epTabBtn.disabled = !isSeries;
    if (blockMovieVideo) blockMovieVideo.style.display = isSeries ? 'none' : '';
    if(!isSeries){
      const activePane = document.querySelector('#tab-episodes.show');
      if (activePane) document.querySelector('[data-bs-target="#tab-basic"]')?.click();
    }
  }
  isSeriesSel?.addEventListener('change', toggleSeriesUI);
  toggleSeriesUI();

  // Đồng bộ tổng số phần dựa trên DOM
  function syncTotalSeasons(){
    if (totalSeasonsInput) totalSeasonsInput.value = getSeasonCards().length || '';
  }
  
  // Helper để tạo mới 1 dòng tập và set số thứ tự chính xác
  function addEpisodeRow(seasonCard, sKey) {
    const wrap = getEpisodesWrap(seasonCard);
    const tpl  = qs('template.tplEpisode', seasonCard)?.innerHTML;
    if (!wrap || !tpl) return;

    // Lấy số tập hiện tại (cho index key)
    const currentEpisodeCount = wrap.children.length;
    const eKey = currentEpisodeCount; // Dùng index cho key tạm
    const nextEpNumber = currentEpisodeCount + 1; // FIX: Số tập tiếp theo
    
    // Thay thế placeholder
    let html = tpl.replaceAll('__SIDX__', sKey).replaceAll('__EIDX__', String(eKey));
    // FIX: Thay thế value="1" trong template bằng số tập chính xác
    html = html.replace('value="1"', `value="${nextEpNumber}"`); 
    
    const row  = document.createElement('div');
    row.innerHTML = html;
    const epRow = row.firstElementChild;
    wrap.appendChild(epRow);

    // Cập nhật tổng số tập trong input Total Episodes
    const totalEpInp = getSeasonTotalInput(seasonCard);
    if (totalEpInp) totalEpInp.value = wrap.children.length;

    bindEpisodeRow(epRow); // Bind uploader
    return epRow;
  }
  
  // Helper để tạo mới 1 dòng season và set số thứ tự chính xác
  function addSeasonCard(){
    if (!tplSeason || !seasonsWrap) return;
    
    // FIX 1: Số phần tiếp theo
    const newSeasonNumber = getSeasonCards().length + 1; 
    const key = String(sIdx);
    
    let html = tplSeason.replaceAll('__SIDX__', key);
    
    const div  = document.createElement('div');
    div.innerHTML = html;
    const card = div.firstElementChild;
    
    // Lấy inputs
    const numberInput = getSeasonNumberInput(card);
    const titleNum = qs('.js-season-title', card);
    const totalInput = getSeasonTotalInput(card);

    // FIX 1: Set Season Number và tiêu đề
    if (numberInput) numberInput.value = newSeasonNumber;
    if (titleNum) titleNum.textContent = String(newSeasonNumber);
    
    // Bind input change để cập nhật tiêu đề
    numberInput?.addEventListener('input', () => {
      if (titleNum) titleNum.textContent = numberInput.value || '__';
    });
    
    // Thêm vào DOM
    seasonsWrap.appendChild(card);
    sIdx++; // Tăng index cho key tiếp theo
    syncTotalSeasons();
    
    // Bind listener Total Episodes cho season mới
    const seasonKeyMatch = numberInput?.name.match(/^seasons\[(.+?)\]\[number\]$/);
    const sKey = seasonKeyMatch ? seasonKeyMatch[1] : 'x';
    
    // Tự động fill episode nếu giá trị mặc định > 0 (ví dụ: value="1" trong template)
    const initialTotal = parseInt(val(totalInput) || '0', 10);
    if (initialTotal > 0) {
      for(let i = 0; i < initialTotal; i++) {
        addEpisodeRow(card, sKey);
      }
    }
  }

  // Chức năng fill/xoá tập khi Total Episodes thay đổi
  function handleTotalEpisodesChange(inp, sKey, card){
    const total = parseInt(val(inp) || '0', 10);
    if (isNaN(total) || total < 0) return;

    const wrap = getEpisodesWrap(card);
    if (!wrap) return;

    let cur = wrap.children.length;
    
    if (total > cur) {
      // Thêm tập mới
      for (let i = cur; i < total; i++) {
        addEpisodeRow(card, sKey);
      }
    } else if (total < cur) {
      // Xoá tập thừa
      for (let i = cur; i > total; i--) wrap.lastElementChild?.remove();
    }
  }

  // ====== Lắng nghe sự kiện ======
  document.getElementById('btnAddSeason')?.addEventListener('click', () => addSeasonCard());

  // Nhập tổng số phần -> tự sinh/xoá season
  totalSeasonsInput?.addEventListener('input', () => {
    const total = parseInt(val(totalSeasonsInput) || '0', 10);
    if (isNaN(total) || total < 0) return;

    const current = getSeasonCards().length;
    if (total > current) {
      for (let i = current; i < total; i++) addSeasonCard();
    } else if (total < current) {
      for (let i = current; i > total; i--) seasonsWrap.lastElementChild?.remove();
    }
    syncTotalSeasons();
  });
  
  // Listener Tổng số tập & Thêm tập / Xoá tập (Sử dụng Delegation)
  document.addEventListener('click', (e) => {
    // Thêm tập
    const addBtn = e.target.closest('.btnAddEpisode');
    if (addBtn) {
      const seasonCard = addBtn.closest('.season-card');
      const numberInput = getSeasonNumberInput(seasonCard);
      const keyMatch = numberInput?.name.match(/^seasons\[(.+?)\]\[number\]$/);
      const sKey = keyMatch ? keyMatch[1] : 'x';
      
      addEpisodeRow(seasonCard, sKey);
      return;
    }

    // Xoá tập
    const delBtn = e.target.closest('.btnRemoveEpisode');
    if (delBtn) {
      const seasonCard = delBtn.closest('.season-card');
      const wrap = getEpisodesWrap(seasonCard);
      delBtn.closest('.ep-row')?.remove();
      
      // Cập nhật lại Total Episodes và Re-number
      const totalEpInp = getSeasonTotalInput(seasonCard);
      if (totalEpInp) totalEpInp.value = wrap?.children.length || 0;
      
      // Cần gọi lại logic fill để cập nhật lại số thứ tự (Re-number)
      const numberInput = getSeasonNumberInput(seasonCard);
      const keyMatch = numberInput?.name.match(/^seasons\[(.+?)\]\[number\]$/);
      const sKey = keyMatch ? keyMatch[1] : 'x';
      
      handleTotalEpisodesChange(totalEpInp, sKey, seasonCard);
      return;
    }
    
    // Xoá season
    const removeSeasonBtn = e.target.closest('.btnRemoveSeason');
    if (removeSeasonBtn) {
      removeSeasonBtn.closest('.season-card')?.remove();
      syncTotalSeasons();
    }
  });

  // Listener Tổng số tập
  document.addEventListener('input', (e) => {
    const inp = e.target.closest('.total-episodes-input');
    if (!inp) return;
    const card = inp.closest('.season-card');
    const numberInput = getSeasonNumberInput(card);
    const keyMatch = numberInput?.name.match(/^seasons\[(.+?)\]\[number\]$/);
    const sKey = keyMatch ? keyMatch[1] : 'x';
    
    handleTotalEpisodesChange(inp, sKey, card);
  });

  // Listener Số Phần (để cập nhật tiêu đề mini)
  document.addEventListener('input', (e) => {
    const inp = e.target.closest('.season-number-input');
    if (!inp) return;
    const card = inp.closest('.season-card');
    const titleNum = qs('.js-season-title', card);
    if (titleNum) titleNum.textContent = inp.value || '__';
  });


  // ====== CHUNK UPLOAD — HÀM DÙNG CHUNG ======
  async function uploadFileGeneric(file, { onProgress, onMerging, onDone, onError }) {
    const chunkSize = 1024 * 1024; // 1MB
    const totalChunks = Math.ceil(file.size / chunkSize);
    const videoId = Date.now().toString() + '-' + (file.name || 'video').replace(/[^a-z0-9]/gi,'_').toLowerCase();
    let retries = 0;

    for (let i = 0; i < totalChunks; i++) {
      let ok = false;
      while (!ok) {
        try {
          const start = i * chunkSize, end = Math.min(file.size, start + chunkSize);
          const formData = new FormData();
          formData.append('video_id', videoId);
          formData.append('chunk_index', i);
          formData.append('chunk', file.slice(start, end));
          formData.append('_token', '{{ csrf_token() }}');

          const res = await fetch('{{ route('upload.chunk') }}', { method:'POST', body: formData });
          if (!res.ok) throw new Error('status ' + res.status);

          const p = Math.round(((i + 1) / totalChunks) * 100);
          onProgress?.(p);
          ok = true; retries = 0;
        } catch (e) {
          if (++retries > 3) { onError?.('Upload lỗi, thử lại.'); return; }
          await new Promise(r => setTimeout(r, 1200));
        }
      }
    }

    try {
      onMerging?.();
      const mergeRes = await fetch('{{ route('upload.merge') }}', {
        method: 'POST',
        body: new URLSearchParams({
          'video_id': videoId,
          'total_chunks': totalChunks,
          'ext': file.name.split('.').pop() || 'mp4',
          '_token': '{{ csrf_token() }}'
        }),
        headers: { 'Accept':'application/json','Content-Type':'application/x-www-form-urlencoded' }
      });
      if (!mergeRes.ok) throw new Error('merge fail ' + mergeRes.status);
      const data = await mergeRes.json();
      if (data.file_name) onDone?.(data.file_name);
      else onError?.('Không thể hợp nhất video.');
    } catch (e) {
      onError?.('Merge lỗi, thử lại.');
    }
  }

  // ====== CHUNK UPLOAD — PHIM LẺ (MAIN) ======
  async function uploadMainMovie(file) {
    if (!fileNameInputMain || !progressBarMain) return;
    // reset
    fileNameInputMain.value = '';
    progressBarMain.style.width = '0%';
    progressBarMain.textContent = '0%';

    await uploadFileGeneric(file, {
      onProgress: (p) => {
        progressBarMain.style.width = p + '%';
        progressBarMain.textContent = p + '%';
      },
      onMerging: () => { progressBarMain.textContent = '99% (Đang hợp nhất...)'; },
      onDone: (fileName) => {
        progressBarMain.style.width = '100%';
        progressBarMain.textContent = '100%';
        fileNameInputMain.value = fileName;
      },
      onError: (msg) => { alert(msg || 'Có lỗi khi upload.'); }
    });
  }
  videoInputMain?.addEventListener('change', () => {
    const f = videoInputMain.files?.[0];
    if (!f) return;
    uploadMainMovie(f);
  });

  // ====== CHUNK UPLOAD — MỖI TẬP (HOÀN THIỆN) ======
  function bindEpisodeRow(epRow) {
    if (!epRow) return;
    const fileInput = qs('.ep-video-input', epRow);
    const progress  = qs('.ep-progress', epRow);
    const status    = qs('.ep-status', epRow);
    const hiddenFn  = qs('.ep-video-fn', epRow);
    if (!fileInput || !progress || !hiddenFn) return;

    // tránh bind 2 lần
    if (fileInput.dataset.bound === '1') return; 

    fileInput.dataset.bound = '1';
    
    const uploadEpisode = async (file) => {
        // Reset UI
        hiddenFn.value = '';
        progress.style.width = '0%';
        progress.textContent = '0%';
        status.textContent = 'Đang tải lên...';
        epRow.dataset.uploading = '1';

        await uploadFileGeneric(file, {
            onProgress: (p) => {
                progress.style.width = p + '%';
                progress.textContent = p + '%';
            },
            onMerging: () => {
                progress.textContent = '99% (Đang hợp nhất...)';
                status.textContent = 'Đang hợp nhất...';
            },
            onDone: (fileName) => {
                progress.style.width = '100%';
                progress.textContent = '100%';
                hiddenFn.value = fileName;
                status.textContent = `Thành công: ${fileName}`;
                epRow.dataset.uploading = '0';
                fileInput.value = ''; // Clear file input
            },
            onError: (msg) => {
                status.textContent = msg || 'Có lỗi khi upload tập phim.';
                progress.style.width = '0%';
                progress.textContent = '0%';
                hiddenFn.value = '';
                epRow.dataset.uploading = '0';
                fileInput.value = ''; // Clear file input
            }
        });
    };
    if (val(hiddenFn)) {
        progress.style.width = '100%';
        progress.textContent = '100%';
        status.textContent = `Hiện có: ${val(hiddenFn).split('/').pop()}`; // Chỉ lấy tên file cuối
    } else {
        progress.style.width = '0%';
        progress.textContent = '0%';
        status.textContent = 'Chưa có video';
    }
    fileInput.addEventListener('change', () => {
        const f = fileInput.files?.[0];
        if (!f) return;
        uploadEpisode(f);
    });
  } // END of bindEpisodeRow
  
  // ====== BIND VÀ KHỞI TẠO ON LOAD ======
  document.addEventListener('DOMContentLoaded', () => {
      // Bind cho các hàng tập phim hiện có
      qsa('.ep-row').forEach(bindEpisodeRow);
      
      // Bind cho các input số phần để cập nhật tiêu đề ngay (cho trường hợp exist_ID)
      getSeasonCards().forEach(card => {
        const numberInput = getSeasonNumberInput(card);
        const titleNum    = qs('.js-season-title', card);
        const syncNum = () => { if (titleNum && numberInput) titleNum.textContent = numberInput.value || '__'; };
        numberInput?.addEventListener('input', syncNum);
        syncNum();
      });
      
      // Cập nhật lại tổng số tập hiện tại của các phần (nếu chưa có)
      getSeasonCards().forEach(card => {
        const totalInp = getSeasonTotalInput(card);
        const epWrap = getEpisodesWrap(card);
        if (totalInp && epWrap) {
            if (!val(totalInp)) { // Chỉ cập nhật nếu trống
                totalInp.value = epWrap.children.length || 0;
            }
        }
      });
      
      // Force sync total seasons on load
      syncTotalSeasons();
  });
})();
</script>
@endpush
