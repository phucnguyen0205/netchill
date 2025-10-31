@csrf
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Phim</label>
    <select name="movie_id" class="form-select" required>
      <option value="">— Chọn phim —</option>
      @foreach($movies as $id => $title)
        <option value="{{ $id }}" @selected(old('movie_id', $showtime->movie_id ?? '')==$id)>
          {{ $title }}
        </option>
      @endforeach
    </select>
    @error('movie_id')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Ngày chiếu</label>
    <input type="date" name="show_date" class="form-control"
           value="{{ old('show_date', isset($showtime)?$showtime->show_date->toDateString():null) }}" required>
    @error('show_date')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Giờ bắt đầu</label>
    <input type="time" name="start_time" class="form-control"
           value="{{ old('start_time', isset($showtime)?$showtime->start_time->format('H:i'):null) }}" required>
    @error('start_time')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Giờ kết thúc</label>
    <input type="time" name="end_time" class="form-control"
           value="{{ old('end_time', isset($showtime)&&$showtime->end_time?$showtime->end_time->format('H:i'):null) }}">
    @error('end_time')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Phòng</label>
    <input type="text" name="room" class="form-control" maxlength="50"
           value="{{ old('room', $showtime->room ?? '') }}" placeholder="VD: A1">
    @error('room')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-12">
    <label class="form-label">Ghi chú</label>
    <input type="text" name="note" class="form-control" maxlength="255"
           value="{{ old('note', $showtime->note ?? '') }}">
  </div>

  <div class="col-md-3 form-check mt-2">
    <input class="form-check-input" type="checkbox" name="is_premiere" value="1"
           id="isPremiere" {{ old('is_premiere', $showtime->is_premiere ?? false) ? 'checked' : '' }}>
    <label class="form-check-label" for="isPremiere">Khởi chiếu</label>
  </div>
</div>

<div class="mt-3">
  <button class="btn btn-primary">Lưu</button>
  <a href="{{ route('admin.showtimes.index') }}" class="btn btn-outline-secondary">Hủy</a>
</div>
