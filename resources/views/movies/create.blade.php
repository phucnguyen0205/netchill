@extends('layouts.app')

@section('content')
<div class="container py-4">

  {{-- TABS: chỉ đổi khu vực form, không điều hướng --}}
  <ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="tab-basic-tab" data-bs-toggle="tab" data-bs-target="#tab-basic" type="button" role="tab">
        Thông tin cơ bản
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tab-categories-tab" data-bs-toggle="tab" data-bs-target="#tab-categories" type="button" role="tab">
        Thể loại
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tab-countries-tab" data-bs-toggle="tab" data-bs-target="#tab-countries" type="button" role="tab">
        Quốc gia
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tab-media-tab" data-bs-toggle="tab" data-bs-target="#tab-media" type="button" role="tab">
        Media (Poster/Banner/Video)
      </button>
    </li>
    <li class="nav-item" role="presentation">
      {{-- mặc định disable, sẽ bật khi chọn Phim bộ --}}
      <button class="nav-link" id="tab-episodes-tab" data-bs-toggle="tab" data-bs-target="#tab-episodes" type="button" role="tab" disabled>
        Tập phim
      </button>
    </li>
  </ul>

  <h1 class="mb-3">Tạo Phim Mới</h1>

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

  <form id="movieForm" method="POST" action="{{ route('movies.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="tab-content">
    {{-- TAB 1: Thông tin cơ bản --}}
<div class="tab-pane fade show active" id="tab-basic" role="tabpanel" aria-labelledby="tab-basic-tab">
  <div class="mb-3">
    <label for="title" class="form-label">Tên phim</label>
    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
  </div>

  {{-- ĐỔI: Tên gốc -> Tên tiếng Anh --}}
  <div class="mb-3">
    <label for="english_title" class="form-label">Tên Tiếng Anh</label>
    <input type="text" name="english_title" id="english_title" class="form-control" value="{{ old('english_title') }}">
  </div>

  <div class="row">
    <div class="col-md-3 mb-3">
      <label for="release_year" class="form-label">Năm phát hành</label>
      <input type="number" name="release_year" id="release_year" class="form-control"
             value="{{ old('release_year') }}" min="1900" max="{{ date('Y') }}">
    </div>

    <div class="col-md-3 mb-3">
      <label for="is_series" class="form-label">Loại phim</label>
      <select name="is_series" id="is_series" class="form-select">
        <option value="0" {{ old('is_series') === '0' ? 'selected' : '' }}>Phim lẻ</option>
        <option value="1" {{ old('is_series') === '1' ? 'selected' : '' }}>Phim bộ</option>
      </select>
    </div>

    <div class="col-md-3 mb-3">
      <label for="version" class="form-label">Phiên bản</label>
      <select name="version" id="version" class="form-select">
        @foreach(['sub' => 'Phụ đề','dub' => 'Lồng tiếng','raw' => 'RAW/Không phụ đề'] as $val => $label)
          <option value="{{ $val }}" {{ old('version')===$val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3 mb-3">
      <label for="age_rating" class="form-label">Xếp hạng độ tuổi</label>
      <select name="age_rating" id="age_rating" class="form-select">
        @foreach(['P','K','T13','T16','T18'] as $r)
          <option value="{{ $r }}" {{ old('age_rating')===$r ? 'selected' : '' }}>{{ $r }}</option>
        @endforeach
      </select>
    </div>
  </div>

  {{-- MỚI: Tổng số phần của phim (chỉ hiện khi là Phim bộ) --}}
  <div class="row" id="block-total-seasons" style="display:none;">
    <div class="col-md-3 mb-3">
      <label for="total_seasons" class="form-label">Tổng số phần</label>
      <input type="number" name="total_seasons" id="total_seasons" class="form-control"
             min="1" value="{{ old('total_seasons') }}">
    </div>
  </div>

  <div class="mb-3">
    <label for="description" class="form-label">Mô tả</label>
    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
  </div>
</div>
      {{-- TAB 2: Thể loại (chỉ dùng categories) --}}
      <div class="tab-pane fade" id="tab-categories" role="tabpanel" aria-labelledby="tab-categories-tab">
        @php
          $pickedCategories = collect(old('category_ids', isset($movie) ? $movie->categories?->pluck('id')->all() : []));
        @endphp

        <div class="mb-3">
          <label for="category_ids" class="form-label">Thể loại</label>
          <select name="category_ids[]" id="category_ids" class="form-select" multiple>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ $pickedCategories->contains($cat->id) ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
          <small class="text-muted d-block mt-1">Giữ Ctrl/Cmd để chọn nhiều.</small>
        </div>
      </div>

      {{-- TAB 3: Quốc gia --}}
      <div class="tab-pane fade" id="tab-countries" role="tabpanel" aria-labelledby="tab-countries-tab">
        @php
          $pickedCountries = collect(old('country_ids', isset($movie) ? $movie->countries?->pluck('id')->all() : []));
        @endphp

        <div class="mb-3">
          <label for="country_ids" class="form-label">Quốc gia</label>
          <select name="country_ids[]" id="country_ids" class="form-select" multiple>
            @foreach($countries as $c)
              <option value="{{ $c->id }}" {{ $pickedCountries->contains($c->id) ? 'selected' : '' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
          <small class="text-muted d-block mt-1">Giữ Ctrl/Cmd để chọn nhiều.</small>
        </div>
      </div>

      {{-- TAB 4: Media --}}
      <div class="tab-pane fade" id="tab-media" role="tabpanel" aria-labelledby="tab-media-tab">
      <div class="row">
  <div class="col-md-6 mb-3">
    <label class="form-label">Poster (ảnh dọc) – có thể chọn nhiều</label>
    <input type="file" name="posters[]" class="form-control" accept="image/*" multiple>
    <small class="text-muted">Ảnh đầu sẽ dùng làm poster chính của phim.</small>
  </div>
  <div class="col-md-6 mb-3">
    <label class="form-label">Banner (ảnh ngang) – có thể chọn nhiều</label>
    <input type="file" name="banners[]" class="form-control" accept="image/*" multiple>
  </div>
</div>

<div id="block-movie-video">
  <div class="mb-3">
    <label for="videoInput" class="form-label">Video (chỉ dùng cho Phim lẻ)</label>
    <input type="file" id="videoInput" class="form-control" accept="video/mp4,video/mpeg">
  </div>
  <input type="hidden" name="file_name" id="file_name">
  <div class="progress mt-2">
    <div id="uploadProgress" class="progress-bar bg-success" role="progressbar" style="width:0%">0%</div>
  </div>
</div>
      </div>
      {{-- TAB 5: Tập phim (bật khi is_series=1) --}}
<div class="tab-pane fade" id="tab-episodes" role="tabpanel" aria-labelledby="tab-episodes-tab">
  <div class="alert alert-secondary py-2">
    Quản lý theo <b>phần (season)</b>. Mỗi phần nhập số thứ tự và <b>tổng số tập</b> của phần đó.
  </div>

  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Danh sách phần</h5>
    <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddSeason">
        + Thêm phần
      </button>
    </div>
  </div>

  <div id="seasonsWrap" class="vstack gap-3">
    {{-- Template 1 phần (season) --}}
    <template id="tplSeasonRow">
      <div class="card card-body border-0 bg-dark-subtle text-body">
        <div class="row g-2 align-items-end">
          <div class="col-12 col-md-2">
            <label class="form-label">Số phần</label>
            <input type="number" class="form-control" name="seasons[__SIDX__][number]" min="1" value="1">
          </div>
          <div class="col-12 col-md-3">
            <label class="form-label">Tổng số tập (phần này)</label>
            <input type="number" class="form-control" name="seasons[__SIDX__][total_episodes]" min="1" value="12">
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Ghi chú (tuỳ chọn)</label>
            <input type="text" class="form-control" name="seasons[__SIDX__][note]" placeholder="VD: Bản Director's Cut">
          </div>
          <div class="col-12 col-md-1">
            <button type="button" class="btn btn-outline-danger w-100 btnRemoveSeason">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
        </div>
      </div>
    </template>
  </div>

  <small class="text-muted d-block mt-2">
    Mẹo: Bạn cũng có thể nhập <b>Tổng số phần</b> ở tab “Thông tin cơ bản” để ghi tổng quan.
  </small>
</div>

    </div> {{-- /tab-content --}}

    <button type="submit" class="btn btn-primary mt-3" disabled id="btnSubmit">
  Tạo phim
</button>
  </form>
</div>
@endsection
@push('scripts')
<script>
(function(){
  if (window.__movieFormBound) return;
  window.__movieFormBound = true;

  // ========= LẤY DOM SỚM =========
  const isSeriesSel        = document.getElementById('is_series');
  const totalSeasonsBlock  = document.getElementById('block-total-seasons');
  const totalSeasonsInput  = document.getElementById('total_seasons');
  const epTabBtn           = document.getElementById('tab-episodes-tab');
  const tplSeason          = document.getElementById('tplSeasonRow').innerHTML.trim();
  const seasonsWrap        = document.getElementById('seasonsWrap');
  const blockMovieVideo    = document.getElementById('block-movie-video');
  const submitBtn          = document.getElementById('btnSubmit');
  const videoInput         = document.getElementById('videoInput');   // phim lẻ
  const fileNameInput      = document.getElementById('file_name');    // phim lẻ
  const movieForm          = document.getElementById('movieForm');
  const progressBar        = document.getElementById('uploadProgress');
  let sIdx = 0;

  // ========= TIỆN ÍCH =========
  function getUploadingLeft(){
    return document.querySelectorAll('.ep-row[data-uploading="1"]').length;
  }
  function renumberSeasonsUI(){
    const cards = [...seasonsWrap.querySelectorAll('.card')];
    cards.forEach((card, i) => {
      const numInput = card.querySelector('input[name*="[number]"]');
      if (numInput) numInput.value = i + 1;
      const heading = card.querySelector('.episode-block h6');
      if (heading) heading.textContent = `Tập phim của phần ${i + 1}`;
    });
  }

  // ========= PHIM LẺ / PHIM BỘ =========
  function toggleSeriesUI(){
    const isSeries = String(isSeriesSel.value) === '1';

    totalSeasonsBlock.style.display = isSeries ? '' : 'none';
    epTabBtn.disabled = !isSeries;

    // Video chính chỉ với phim lẻ
    if (blockMovieVideo) blockMovieVideo.style.display = isSeries ? 'none' : '';

    // Chỉ bắt buộc video (và chặn submit) với phim lẻ
    if (videoInput) videoInput.required = !isSeries;

    // Bật nút submit ngay cho PHIM BỘ (vì không cần chờ upload)
    submitBtn.disabled = isSeries ? false : submitBtn.disabled;

    // Nếu đang ở tab Tập phim mà chuyển sang Phim lẻ -> về tab cơ bản
    if(!isSeries && document.querySelector('#tab-episodes.show')){
      document.getElementById('tab-basic-tab').click();
    }
  }
  isSeriesSel.addEventListener('change', toggleSeriesUI);
  toggleSeriesUI();

  // ========= AUTO-TẠO PHẦN THEO "TỔNG SỐ PHẦN" =========
  totalSeasonsInput?.addEventListener('input', () => {
    const total = parseInt(totalSeasonsInput.value || '0', 10);
    if (isNaN(total) || total < 0) return;

    const current = seasonsWrap.querySelectorAll('.card').length;
    if (total > current) {
      for (let i = current; i < total; i++) addSeason(i + 1);
    } else if (total < current) {
      for (let i = current; i > total; i--) {
        seasonsWrap.lastElementChild?.remove();
      }
    }
    renumberSeasonsUI();
  });

  function addSeason(number = sIdx + 1) {
    const html = tplSeason.replaceAll('__SIDX__', String(sIdx++));
    const div = document.createElement('div');
    div.innerHTML = html;
    const seasonCard = div.firstElementChild;

    // Set số phần
    seasonCard.querySelector('input[name*="[number]"]').value = number;

    // Block tập
    const epBlock = document.createElement('div');
    epBlock.className = 'episode-block mt-3';
    epBlock.innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0 text-white">Tập phim của phần ${number}</h6>
        <button type="button" class="btn btn-sm btn-outline-success btnAddEpisode">+ Thêm tập</button>
      </div>
      <div class="vstack gap-2 epWrap"></div>
      <template class="tplEpisode">
        <div class="row g-2 align-items-end border rounded-3 p-2 bg-dark-subtle ep-row" data-uploading="0">
          <div class="col-md-2">
            <label class="form-label">Số tập</label>
            <input type="number" name="seasons[__SIDX__][episodes][__EIDX__][number]" value="1" class="form-control" min="1">
          </div>
          <div class="col-md-5">
            <label class="form-label">Tên tập phim</label>
            <input type="text" name="seasons[__SIDX__][episodes][__EIDX__][title]" class="form-control" placeholder="Tên tập...">
          </div>
          <div class="col-md-4">
            <label class="form-label">Video (chunk)</label>
            <input type="file" class="form-control ep-video-input" accept="video/mp4,video/mpeg">
            <input type="hidden" name="seasons[__SIDX__][episodes][__EIDX__][video_file_name]" class="ep-video-fn">
            <div class="progress mt-2" style="height: 8px;">
              <div class="progress-bar ep-progress" role="progressbar" style="width:0%">0%</div>
            </div>
            <small class="text-muted ep-status d-block mt-1"></small>
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger btnRemoveEpisode w-100"><i class="bi bi-x-lg"></i></button>
          </div>
        </div>
      </template>
    `;
    seasonCard.appendChild(epBlock);

    // Auto sinh số tập theo "Tổng số tập"
    const totalEpInput = seasonCard.querySelector('input[name*="[total_episodes]"]');
    totalEpInput?.addEventListener('input', () => {
      const count = parseInt(totalEpInput.value || '0', 10);
      rebuildEpisodes(seasonCard, count);
    });

    seasonsWrap.appendChild(seasonCard);
  }

  function rebuildEpisodes(seasonCard, count){
    const epWrap = seasonCard.querySelector('.epWrap');
    const tpl    = seasonCard.querySelector('.tplEpisode').innerHTML;
    const seasonIndex = [...seasonsWrap.children].indexOf(seasonCard);

    epWrap.innerHTML = '';
    if (isNaN(count) || count < 1) return;

    for (let j = 0; j < count; j++) {
      const htmlEp = tpl.replaceAll('__SIDX__', seasonIndex).replaceAll('__EIDX__', j);
      const divEp = document.createElement('div');
      divEp.innerHTML = htmlEp;
      const epRow = divEp.firstElementChild;
      epRow.querySelector('input[name*="[number]"]').value = j + 1;
      epWrap.appendChild(epRow);
      bindEpisodeRow(epRow);
    }
  }

  // ======= GẮN SỰ KIỆN CHO TỪNG DÒNG TẬP =======
  function startEpisodeUpload(epRow, file){
    const progress  = epRow.querySelector('.ep-progress');
    const status    = epRow.querySelector('.ep-status');
    const hiddenFn  = epRow.querySelector('.ep-video-fn');
    if (!file || !progress || !hiddenFn) return;

    // reset UI
    progress.style.width = '0%';
    progress.textContent = '0%';
    status.textContent = 'Đang tải lên...';
    hiddenFn.value = '';
    epRow.dataset.uploading = '1'; // đánh dấu đang upload (để chặn submit)

    uploadFileGeneric(file, {
      onProgress: (p) => {
        progress.style.width = p + '%';
        progress.textContent = p + '%';
      },
      onMerging: () => { status.textContent = 'Đang hợp nhất...'; },
      onDone: (fileName) => {
        progress.style.width = '100%';
        progress.textContent = '100%';
        status.textContent = 'Hoàn thành';
        hiddenFn.value = fileName;       // gán vào hidden -> gửi lên server
        epRow.dataset.uploading = '0';   // xong upload
      },
      onError: (msg) => {
        status.textContent = msg || 'Có lỗi khi upload.';
        epRow.dataset.uploading = '0';
      }
    });
  }

  function bindEpisodeRow(epRow) {
    const fileInput = epRow.querySelector('.ep-video-input');
    if (!fileInput) return;
    fileInput.addEventListener('change', () => {
      const f = fileInput.files?.[0];
      if (f) startEpisodeUpload(epRow, f);
    });
  }

  // Thêm/Xoá phần & cập nhật total_seasons
  document.getElementById('btnAddSeason').addEventListener('click', () => {
    const current = seasonsWrap.querySelectorAll('.card').length;
    addSeason(current + 1);
    totalSeasonsInput.value = current + 1; // tự +1
    renumberSeasonsUI();
  });

  document.addEventListener('click', (e)=>{
    const btn = e.target.closest('.btnRemoveSeason');
    if(btn){
      btn.closest('.card')?.remove();
      const remaining = seasonsWrap.querySelectorAll('.card').length;
      totalSeasonsInput.value = remaining; // tự -1
      renumberSeasonsUI();
    }
  });

  document.addEventListener('click', (e) => {
    if (e.target.closest('.btnAddEpisode')) {
      const epBlock = e.target.closest('.episode-block');
      const wrap = epBlock.querySelector('.epWrap');
      const tpl = epBlock.querySelector('.tplEpisode').innerHTML;
      const seasonIndex = [...seasonsWrap.children].indexOf(epBlock.closest('.card'));
      const eIdx = wrap.children.length;
      const html = tpl.replaceAll('__SIDX__', seasonIndex).replaceAll('__EIDX__', eIdx);
      const div = document.createElement('div');
      div.innerHTML = html;
      const row = div.firstElementChild;
      wrap.appendChild(row);
      bindEpisodeRow(row);
    }
    if (e.target.closest('.btnRemoveEpisode')) {
      e.target.closest('.row')?.remove();
    }
  });

  // (phòng hờ) nếu change vào input video tập mà chưa bind
  document.addEventListener('change', (e) => {
    const input = e.target.closest('.ep-video-input');
    if (input) {
      const row = input.closest('.ep-row');
      if (row) {
        bindEpisodeRow(row);
        const f = input.files?.[0];
        if (f) startEpisodeUpload(row, f);
      }
    }
  });

  // ========= CHUNK UPLOAD VIDEO (PHIM LẺ) =========
  async function uploadFileGeneric(file, { onProgress, onMerging, onDone, onError }) {
    const chunkSize = 1024 * 1024; // 1MB
    const totalChunks = Math.ceil(file.size / chunkSize);
    const videoId = Date.now().toString() + '-' + file.name.replace(/[^a-z0-9]/gi, '_').toLowerCase();
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
          const res = await fetch('{{ route('upload.chunk') }}', { method: 'POST', body: formData });
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
      if (data.file_name) {
        onDone?.(data.file_name);
      } else {
        onError?.('Không thể hợp nhất video.');
      }
    } catch (e) {
      onError?.('Merge lỗi, thử lại.');
    }
  }

  // Upload video phim lẻ
  async function uploadSingleMovieFile(file) {
    const chunkSize = 1024 * 1024;
    const totalChunks = Math.ceil(file.size / chunkSize);
    const videoId = Date.now().toString() + '-' + file.name.replace(/[^a-z0-9]/gi, '_').toLowerCase();
    let retries = 0;
    submitBtn.disabled = true;

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
          const res = await fetch('{{ route('upload.chunk') }}', { method: 'POST', body: formData });
          if (!res.ok) throw new Error('status ' + res.status);
          const p = ((i + 1) / totalChunks * 100);
          progressBar.style.width = p + '%';
          progressBar.innerText = Math.round(p) + '%';
          ok = true; retries = 0;
        } catch(e){
          if(++retries > 3){ alert('Upload lỗi, thử lại.'); return; }
          await new Promise(r=>setTimeout(r, 1500));
        }
      }
    }

    try{
      progressBar.innerText = '99% (Đang hợp nhất...)';
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
      if(!mergeRes.ok) throw new Error('merge fail ' + mergeRes.status);
      const data = await mergeRes.json();
      if(data.file_name){
        progressBar.style.width = '100%';
        progressBar.innerText = '100% (Hoàn thành)';
        fileNameInput.value = data.file_name;
        submitBtn.disabled = false; // bật submit cho phim lẻ sau khi merge xong
      } else {
        alert('Không thể hợp nhất video.');
      }
    }catch(e){
      alert('Merge lỗi, thử lại.');
    }
  }

  videoInput?.addEventListener('change', () => {
    const f = videoInput.files?.[0];
    if (!f) return;
    progressBar.style.width = '0%';
    progressBar.innerText = '0%';
    fileNameInput.value = '';
    uploadSingleMovieFile(f);
  });

  // ========= SUBMIT FORM =========
  let isSubmitting = false;
  movieForm.addEventListener('submit', function(e){
    const isSeries = String(isSeriesSel.value) === '1';

    // chặn khi còn hàng đang upload
    if (getUploadingLeft() > 0) {
      e.preventDefault();
      alert('Vui lòng đợi các video tập tải lên xong.');
      return;
    }

    if (!isSeries) {
      // Phim lẻ: cần file_name
      if (!fileNameInput.value) {
        e.preventDefault();
        alert('Vui lòng chờ video tải lên xong (Phim lẻ).');
        return;
      }
    } else {
      // Nếu season nào chưa có tập -> chèn hidden inputs cho Tập 1
      document.querySelectorAll('#seasonsWrap .card').forEach((card, idx) => {
        const epWrap = card.querySelector('.epWrap');
        if (!epWrap || epWrap.children.length === 0) {
          const hidden = document.createElement('div');
          hidden.innerHTML = `
            <input type="hidden" name="seasons[${idx}][episodes][0][number]" value="1">
            <input type="hidden" name="seasons[${idx}][episodes][0][title]"  value="Tập 1">
          `;
          card.appendChild(hidden);
        }
      });
    }

    if (isSubmitting) { e.preventDefault(); return; }
    isSubmitting = true;
    submitBtn.disabled = true;
    submitBtn.innerText = 'Đang tạo phim...';
  });
})();
</script>

@endpush