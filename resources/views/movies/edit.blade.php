@extends('layouts.app')
@section('content')
<main class="container">
    <h1>Chỉnh sửa phim</h1>

    <form id="movieForm" method="POST" action="{{ route('movies.update', $movie) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div class="mb-3">
            <label>Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ $movie->title }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ $movie->description }}</textarea>
        </div>

        <!-- Category -->
        <div class="mb-3">
            <label>Thể loại</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $movie->category_id==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
<!-- Poster -->
<div class="mb-3">
    <label>Poster hiện tại</label><br>
    @if($movie->poster)
            <img src="{{ Storage::url($movie->poster) }}" alt="Poster" width="150">

    @else
        <span>Chưa có poster</span><br><br>
    @endif

    <div>
        <label>Thay poster mới</label>
        <input type="file" name="poster" class="form-control mt-1">
    </div>
</div>

        <!-- Video (chunked upload) -->
        <div class="mb-3">
            <label>Video hiện tại: {{ $movie->file_name ?? 'Chưa có video' }}</label><br>
            <label>Thay video mới</label>
            <input type="file" id="videoInput" class="form-control">
            <input type="hidden" name="file_name" id="file_name" value="{{ $movie->file_name }}">
            <div class="progress mt-2">
                <div id="uploadProgress" class="progress-bar" role="progressbar" style="width:{{ $movie->file_name ? '100' : '0' }}%"></div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Cập nhật</button>
    </form>
</main>

@section('scripts')
<script>
const videoInput = document.getElementById('videoInput');
const fileNameInput = document.getElementById('file_name');
const movieForm = document.getElementById('movieForm');
const uploadProgress = document.getElementById('uploadProgress');

videoInput.addEventListener('change',()=>{
    const file = videoInput.files[0];
    if(file) uploadFile(file);
});

async function uploadFile(file){
    const chunkSize = 1024*1024; // 1MB
    const totalChunks = Math.ceil(file.size/chunkSize);
    const videoId = Date.now().toString();

    for(let i=0;i<totalChunks;i++){
        let success = false;
        while(!success){
            try{
                const start = i*chunkSize;
                const end = Math.min(file.size,start+chunkSize);
                const chunk = file.slice(start,end);
                const formData = new FormData();
                formData.append('video_id', videoId);
                formData.append('chunk_index', i);
                formData.append('chunk', chunk);

                await fetch('{{ route('upload.chunk') }}',{
                    method:'POST',
                    body: formData,
                    headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'}
                });

                success = true;
                uploadProgress.style.width = ((i+1)/totalChunks*100)+'%';
            } catch(e){
                console.error('Retrying chunk',i);
            }
        }
    }

    // Merge chunks
    const mergeRes = await fetch('{{ route('upload.merge') }}',{
        method:'POST',
        body: new URLSearchParams({
            'video_id': videoId,
            'total_chunks': totalChunks,
            'ext': 'mp4',
            '_token': '{{ csrf_token() }}'
        })
    });
    const data = await mergeRes.json();
    fileNameInput.value = data.file_name;
}

// Chặn submit nếu đang upload
movieForm.addEventListener('submit', function(e){
    if(videoInput.files.length && !fileNameInput.value){
        e.preventDefault();
        alert('Video chưa upload xong, vui lòng chờ.');
    }
});
</script>
@endsection
