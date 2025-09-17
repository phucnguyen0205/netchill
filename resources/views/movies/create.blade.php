@extends('layouts.app')

@section('content')
<div class="container py-4">
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Danh Mục</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Thể loại</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Quốc gia</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Phim</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Tập Phim</a>
        </li>
    </ul>

    <h1 class="mb-4">Tạo Phim Mới</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="movieForm" method="POST" action="{{ route('movies.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Tên phim</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        
        {{-- Thêm trường mô tả phim --}}
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" id="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Thể loại</label>
            <select name="category_id" id="category_id" class="form-control" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="poster" class="form-label">Poster</label>
            <input type="file" name="poster" id="poster" class="form-control">
        </div>

        <div class="mb-3">
            <label for="videoInput" class="form-label">Video</label>
            <input type="file" id="videoInput" class="form-control" accept="video/mp4,video/mpeg" required>
        </div>

        <input type="hidden" name="file_name" id="file_name">

        <div class="progress mt-2">
            <div id="uploadProgress" class="progress-bar bg-success" role="progressbar" style="width: 0%;">0%</div>
        </div>

        <button type="submit" class="btn btn-primary mt-3" disabled>Tạo phim</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const videoInput = document.getElementById('videoInput');
    const fileNameInput = document.getElementById('file_name');
    const movieForm = document.getElementById('movieForm');
    const submitBtn = movieForm.querySelector('button[type="submit"]');
    const progressBar = document.getElementById('uploadProgress');

    async function uploadFile(file) {
        const chunkSize = 1024 * 1024; // 1MB
        const totalChunks = Math.ceil(file.size / chunkSize);
        const videoId = Date.now().toString();
        let retries = 0;

        submitBtn.disabled = true;

        for (let i = 0; i < totalChunks; i++) {
            let chunkSucceeded = false;
            while (!chunkSucceeded) {
                try {
                    const start = i * chunkSize;
                    const end = Math.min(file.size, start + chunkSize);
                    const chunk = file.slice(start, end);

                    const formData = new FormData();
                    formData.append('video_id', videoId);
                    formData.append('chunk_index', i);
                    formData.append('chunk', chunk);
                    formData.append('_token', '{{ csrf_token() }}');

                    const response = await fetch('{{ route('upload.chunk') }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!response.ok) {
                        throw new Error(`Server returned status code ${response.status}`);
                    }

                    const progress = ((i + 1) / totalChunks * 100);
                    progressBar.style.width = progress + '%';
                    progressBar.innerText = Math.round(progress) + '%';
                    chunkSucceeded = true;
                    retries = 0; // Reset retries on success
                } catch (e) {
                    console.error('Lỗi khi tải lên chunk:', e);
                    retries++;
                    if (retries > 3) {
                        alert('Lỗi: Quá trình tải lên thất bại sau nhiều lần thử. Vui lòng thử lại.');
                        return; // Ngừng toàn bộ quá trình upload
                    }
                    console.log(`Thử lại lần ${retries} cho chunk ${i}`);
                    await new Promise(resolve => setTimeout(resolve, 2000));
                }
            }
        }

        // Hợp nhất các chunk
        try {
            const mergeRes = await fetch('{{ route('upload.merge') }}', {
                method: 'POST',
                body: new URLSearchParams({
                    'video_id': videoId,
                    'total_chunks': totalChunks,
                    'ext': 'mp4',
                    '_token': '{{ csrf_token() }}'
                }),
                headers: { 'Accept': 'application/json' }
            });

            const data = await mergeRes.json();
            if (data.file_name) {
                fileNameInput.value = data.file_name;
                submitBtn.disabled = false;
                alert('Video đã tải lên xong, bạn có thể tạo phim!');
            } else {
                alert('Lỗi: Không thể hợp nhất video. Vui lòng thử lại.');
            }
        } catch (e) {
            console.error('Lỗi khi hợp nhất video:', e);
            alert('Lỗi: Không thể hợp nhất video. Vui lòng thử lại.');
        }
    }

    // Khi chọn file thì tự upload
    videoInput.addEventListener('change', () => {
        const file = videoInput.files[0];
        if (file) {
            uploadFile(file);
        }
    });

    // Ngăn người dùng gửi form khi chưa có video
    movieForm.addEventListener('submit', function(e){
        if(!fileNameInput.value){
            e.preventDefault();
            alert('Vui lòng chờ video tải lên hoàn tất.');
        }
    });
</script>
@endsection