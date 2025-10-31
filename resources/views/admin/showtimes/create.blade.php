@extends('layouts.app')
@section('title', 'Thêm lịch chiếu')
@section('content')
<div class="container py-4">
  <h2 class="mb-4 text-light">Thêm lịch chiếu</h2>

  <form method="POST" action="{{ route('admin.showtimes.store') }}">
    @csrf
    <div class="mb-3">
      <label for="movie_id" class="form-label text-light">Phim bộ</label>
      <select id="movie_id" name="movie_id" class="form-select" required>
        <option value="">-- Chọn phim bộ --</option>
        @foreach(\App\Models\Movie::where('is_series',1)->orderBy('title')->get() as $m)
          <option value="{{ $m->id }}">{{ $m->title }}</option>
        @endforeach
      </select>
    </div>

    {{-- Tập kế tiếp --}}
    <div class="mb-3">
    <label class="form-label text-light">Tập chiếu</label>
  <input type="number" id="episode" name="episode_number" class="form-control" readonly> 
</div>
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label text-light">Ngày chiếu</label>
        <input type="date" name="show_date" class="form-control" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label text-light">Giờ bắt đầu</label>
        <input type="time" name="start_time" class="form-control" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label text-light">Giờ kết thúc</label>
        <input type="time" name="end_time" class="form-control">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label text-light">Phòng chiếu</label>
      <input type="text" name="room" class="form-control">
    </div>

    <div class="mb-3 form-check">
      <input type="checkbox" name="is_premiere" value="1" class="form-check-input" id="isPremiere">
      <label class="form-check-label text-light" for="isPremiere">Suất công chiếu đặc biệt</label>
    </div>

    <div class="mb-3">
      <label class="form-label text-light">Ghi chú</label>
      <textarea name="note" class="form-control" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-warning">Lưu lịch chiếu</button>
  </form>
</div>

{{-- Script lấy tập kế tiếp --}}
<script>
document.getElementById('movie_id').addEventListener('change', function(){
  const movieId = this.value;
  const epInput = document.getElementById('episode');

  if(!movieId){ epInput.value = ''; return; }
  fetch(`/admin/movies/${movieId}/next-episode`)
    .then(r => {
        if (!r.ok) {
            throw new Error('Lỗi khi lấy tập kế tiếp');
        }
        return r.json();
    })
    .then(d => { 
        // Lấy giá trị từ key 'next_episode' được trả về
        epInput.value = d.next_episode ?? ''; 
    })
    .catch(error => { 
        console.error("Lỗi:", error);
        epInput.value = ''; // Đặt trống nếu có lỗi
    });
});
</script>
@endsection
