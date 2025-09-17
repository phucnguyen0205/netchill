@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Player -->
    <div class="mb-4 text-center">
        <video id="moviePlayer" controls style="max-width:100%; height:auto;">
            <source src="{{ route('movies.stream', $movie) }}" type="video/mp4">
            Trình duyệt của bạn không hỗ trợ video.
        </video>
        <div id="timeDisplay" class="mt-2 text-muted">0:00 / 0:00</div>
    </div>
    <!-- Admin controls: edit/delete movie -->
    @can('admin')
    <div class="mb-3">
        <a href="{{ route('movies.edit',$movie) }}" class="btn btn-warning">Chỉnh sửa</a>
        <form action="{{ route('movies.destroy',$movie) }}" method="POST" style="display:inline-block;">
            @csrf 
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Xóa phim này?')">Xóa</button>
        </form>
    </div>
    @endcan

    <!-- Movie description -->
    <p>{{ $movie->description }}</p>

    <!-- Comments -->
    <h3>Bình luận</h3>
    @auth
    <form method="POST" action="{{ route('comments.store',$movie) }}">
        @csrf
        <textarea name="content" class="form-control mb-2" required></textarea>
        <button type="submit" class="btn btn-success">Gửi</button>
    </form>
    @endauth

    <ul class="list-group mt-3">
        @foreach($movie->comments as $comment)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}</span>
            @can('admin')
            <form action="{{ route('comments.destroy',$comment) }}" method="POST" style="margin:0;">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa bình luận?')">Xóa</button>
            </form>
            @endcan
        </li>
        @endforeach
    </ul>

</div>

<script>
const player = document.getElementById('moviePlayer');
const timeDisplay = document.getElementById('timeDisplay');

// Khi metadata tải xong, biết được tổng thời lượng
player.addEventListener('loadedmetadata', () => { updateTime(); });
player.addEventListener('timeupdate', () => { updateTime(); });

function updateTime() {
    const current = Math.floor(player.currentTime);
    const total = Math.floor(player.duration);
    timeDisplay.textContent = formatTime(current) + ' / ' + formatTime(total);
}

function formatTime(sec) {
    const m = Math.floor(sec / 60);
    const s = sec % 60;
    return m + ':' + (s < 10 ? '0' + s : s);
}

// Resume/pause bằng phím cách
document.addEventListener('keydown', (e) => {
    if(e.code === 'Space'){
        e.preventDefault();
        if(player.paused) player.play();
        else player.pause();
    }
});
</script>

@endsection
