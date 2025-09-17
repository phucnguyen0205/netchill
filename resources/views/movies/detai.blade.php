@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #0d1217;
        color: #d1d5db;
    }
    .movie-banner {
        height: 400px;
        object-fit: cover;
        position: relative;
    }
    .banner-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(13, 18, 23, 1), rgba(13, 18, 23, 0.7));
    }
    .movie-content {
        position: relative;
        z-index: 2;
        margin-top: -150px;
    }
    .movie-poster {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    }
    .btn-action-group .btn {
        background-color: #2c333a;
        border-color: #2c333a;
        color: #fff;
        padding: 1rem 2rem;
        font-weight: bold;
    }
    
    .btn-action-group .btn-play {
        background-image: linear-gradient(to right, #fddb00, #f2c700);
        color: #1a1a1a !important;
        border: none !important;
        padding: 1rem 2.5rem !important;
        border-radius: 50px !important;
        font-weight: bold !important;
        font-size: 1.2rem !important;
        box-shadow: 0 5px 15px rgba(253, 219, 0, 0.4);
        transition: all 0.3s ease;
    }
    .btn-action-group .btn-play:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(253, 219, 0, 0.6);
    }
    .btn-action-group .btn-play .bi-play-fill {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }
    
    .comment-section {
    margin-top: 8px !important;   
    margin-bottom: 8px !important; 
}

.comment-input-area {
    margin-top: 10px !important; 
    padding: 0.5rem !important;  
}

.comment-input-area .inner-box {
    padding: 0.5rem !important;
}

.comment-input-area .comment-actions {
    margin-top: 0.5rem !important;
}

.comment-list .comment-item {
    padding: 0.1rem 0 !important; 
    gap: 10px; 
}

.comment-list .comment-avatar {
    width: 35px;
    height: 35px;
}

    .btn-play {
        background-color: transparent !important; 
        background-image: linear-gradient(to right, #fddb00, #f2c700); 
        color: #1a1a1a !important;
        border: none !important;
        padding: 0.9rem 2.4rem !important;
        border-radius: 50px !important;
        font-weight: bold !important;
        font-size: 1.2rem !important;
        box-shadow: 0 5px 15px rgba(253, 219, 0, 0.4);
        transition: all 0.3s ease;
    }

    .btn-play:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(253, 219, 0, 0.6);
    }

    .btn-play .bi-play-fill {
        font-size: 1.2rem;
        margin-right: 0.5rem;
    }
    .btn-action-icon {
        background: none;
        border: none;
        color: #d1d5db;
        font-size: 14px;
        cursor: pointer;
        text-align: center;
    }
    .btn-action-icon .bi {
        font-size: 2.5rem;
        display: block;
    }
    .nav-tabs {
        border-bottom: 2px solid #2c333a;
    }
    .nav-tabs .nav-link {
        color: #d1d5db;
        border: none;
        background: none;
    }
    .nav-tabs .nav-link.active {
        background-color: #2c333a !important;
        border-color: #2c333a;
        color: #fff;
        border-radius: 8px 8px 0 0;
    }
    .episode-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    .episode-button {
        background-color: #1e252e;
        color: #fff;
        border: 1px solid #2c333a;
        border-radius: 8px;
        text-align: center;
        padding: 1rem;
        transition: background-color 0.3s;
        text-decoration: none;
        display: block;
    }
    .episode-button:hover {
        background-color: #2c333a;
    }
    .badge-rounded-pill-dark {
        background-color: #1e252e;
        border: 1px solid #2c333a;
        color: #d1d5db;
    }
    .form-switch .form-check-input:checked {
        background-color: #ffc107;
        border-color: #ffc107;
    }
    .replies-toggle.collapsed i {
    transform: rotate(0deg);
    transition: transform 0.3s;
}
.replies-toggle i {
    transform: rotate(180deg);
    transition: transform 0.3s;
}

    /* New Comment Section Styles */
    .comment-section h4 {
        color: #fff;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .comment-section .comment-tabs {
        display: inline-flex;
        border: 1px solid #3d4a57;
        border-radius: 8px;
        overflow: hidden;
    }
    .comment-section .comment-tabs button {
        background-color: #1e252e;
        border: none;
        color: #d1d5db;
        padding: 8px 15px;
        font-size: 1rem;
        transition: background-color 0.3s;
    }
    .comment-section .comment-tabs button.active {
        background-color: #3d4a57;
        color: #fff;
    }
    .comment-input-area {
        background-color: #1e252e;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #3d4a57;
    }
    .comment-input-area .inner-box {
        background-color: #1a1a1a; 
        border-radius: 8px;
        padding: 1rem;
    }
    .comment-input-area textarea {
        background-color: transparent;
        border: none;
        color: #d1d5db;
        width: 100%;
        resize: vertical;
        padding: 0;
        outline: none;
    }
    .comment-input-area .comment-actions {
        display: flex;
        justify-content: flex-end; 
        align-items: center;
        margin-top: 1rem;
    }
    .comment-input-area .comment-actions .btn-send {
        background: none;
        border: none;
        color: #fddb00;
        font-size: 1.2rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    .comment-input-area .comment-actions .btn-send:hover {
        color: #f2c700;
    }
    .comment-list .comment-item {
        display: flex;
        gap: 15px;
        padding: 1rem 0;
        border-bottom: 1px solid #2c333a;
    }
    .comment-list .comment-item:last-child {
        border-bottom: none;
    }
    .comment-list .comment-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }
    .comment-list .comment-content {
        flex-grow: 1;
    }
    .comment-list .comment-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 5px;
    }
    .comment-list .comment-header .comment-author {
        font-weight: bold;
        color: #fff;
    }
    .comment-list .comment-header .comment-meta {
        font-size: 0.8rem;
        color: #7f8994;
    }
    .comment-list .comment-body p {
        margin-bottom: 5px;
    }
    .comment-list .comment-actions {
        display: flex;
        gap: 20px;
        font-size: 0.9rem;
    }
    .comment-list .comment-actions button {
        background: none;
        border: none;
        color: #7f8994;
        cursor: pointer;
        transition: color 0.3s;
    }
    .comment-list .comment-actions button:hover {
        color: #d1d5db;
    }
    .comment-badge {
        background-color: #2c333a;
        color: #d1d5db;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
    }

    .dropdown-menu {
        background-color: #1e252e;
        border: 1px solid #2c333a;
    }
    .dropdown-item {
        color: #d1d5db;
    }
    .dropdown-item:hover {
        background-color: #2c333a;
        color: #fff;
    }

    .comment-reply {
        margin-left: 20px; 
    }
    .replies-toggle {
        font-size: 0.9rem;
        color: #fddb00;
        cursor: pointer;
        margin-top: 5px;
        display: block;
        text-decoration: none;
    }
</style>

<div class="movie-detail">
    {{-- Banner --}}
    <div class="movie-banner position-relative">
        <img src="{{ Storage::url($movie->banner) }}" class="w-100 h-100" style="object-fit: cover;">
        <div class="banner-overlay"></div>
    </div>

    {{-- Poster + Info + Tabs --}}
    <div class="container movie-content">
        <div class="row">
            {{-- Cột bên trái: Poster và các thông tin cơ bản --}}
            <div class="col-md-3 text-center">
                <img src="{{ Storage::url($movie->poster) }}" alt="{{ $movie->title }}" class="img-fluid rounded shadow movie-poster">
                <h3 class="mt-3 text-white">{{ $movie->title }}</h3>
                <p class="text-muted">{{ $movie->original_title }}</p>

                {{-- Các tag thông tin ngắn --}}
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-secondary">T13</span>
                    <span class="badge bg-secondary">{{ $movie->release_year ?? 'N/A' }}</span>
                    @if($movie->season_number)
                        <span class="badge bg-secondary">Phần {{ $movie->season_number }}</span>
                    @endif
                    <span class="badge bg-secondary">Tập {{ $movie->episodes ? $movie->episodes->count() : '0' }}</span>
                </div>

                {{-- Thể loại --}}
                <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
                    @forelse($movie->categories ?? [] as $cat)
                        <span class="badge badge-rounded-pill-dark rounded-pill">{{ $cat->name }}</span>
                    @empty
                        <span class="badge badge-rounded-pill-dark rounded-pill">Đang cập nhật</span>
                    @endforelse
                </div>
                <p class="text-sm">Đã chiếu: {{ $movie->episodes ? $movie->episodes->count() : '0' }} / {{ $movie->total_episodes ?? 'N/A' }} tập</p>

                {{-- Giới thiệu --}}
                <div class="mt-4 text-start">
                    <h5 class="text-white">Giới thiệu</h5>
                    <p class="text-white">{{ $movie->description }}</p>
                    <p><strong>Thời lượng:</strong> {{ $movie->duration ?? 'Đang cập nhật' }}</p>
                    <p><strong>Quốc gia:</strong> {{ $movie->country->name ?? 'Đang cập nhật' }}</p>
                    <p><strong>Sản xuất:</strong> {{ $movie->production ?? 'Đang cập nhật' }}</p>
                </div>
            </div>

            {{-- Cột bên phải: Nút chức năng, Tabs và nội dung Tabs --}}
            <div class="col-md-9">
                {{-- Nút chức năng và Đánh giá --}}
                <div class="d-flex align-items-center gap-4 mb-4">
                    <a href="{{ route('movies.show', $movie->slug) }}" class="btn btn-lg btn-action-group btn-play">
                        <i class="bi bi-play-fill"></i> Xem Ngay
                    </a>
                    <button class="btn-action-icon">
                        <i class="bi bi-heart"></i> 
                        <span>Yêu thích</span>
                    </button>
                    <button class="btn-action-icon">
                        <i class="bi bi-plus-circle"></i> 
                        <span>Thêm vào</span>
                    </button>
                    <button class="btn-action-icon">
                        <i class="bi bi-share"></i> 
                        <span>Chia sẻ</span>
                    </button>
                    <button class="btn-action-icon">
                        <i class="bi bi-chat"></i> 
                        <span>Bình luận</span>
                    </button>
                    <div class="ms-auto text-end">
                        <span class="rating-badge">
                            {{ $movie->rating ?? '7.0' }} ⭐
                        </span>
                    </div>
                </div>

                {{-- Tabs và điều khiển --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <ul class="nav nav-tabs border-bottom-0" id="movieTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="episodes-tab" data-bs-toggle="tab" data-bs-target="#episodes" type="button" role="tab" aria-controls="episodes" aria-selected="true">Tập phim</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button" role="tab" aria-controls="gallery" aria-selected="false">Gallery</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="actors-tab" data-bs-toggle="tab" data-bs-target="#actors" type="button" role="tab" aria-controls="actors" aria-selected="false">Diễn viên</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="recommend-tab" data-bs-toggle="tab" data-bs-target="#recommend" type="button" role="tab" aria-controls="recommend" aria-selected="false">Đề xuất</button>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-sm btn-outline-secondary">Phần 1</button>
                        <button class="btn btn-sm btn-outline-secondary">Rút gọn</button>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                        </div>
                    </div>
                </div>

                {{-- Nội dung Tabs --}}
                <div class="tab-content py-4">
                    {{-- Danh sách tập phim --}}
                    <div class="tab-pane fade show active" id="episodes" role="tabpanel" aria-labelledby="episodes-tab">
                        <div class="episode-grid">
                            @forelse($movie->episodes ?? [] as $ep)
                                <a href="{{ route('episodes.show', $ep->slug) }}" class="episode-button">
                                    <i class="bi bi-play-fill me-2"></i> Tập {{ $loop->index + 1 }}
                                </a>
                            @empty
                                <p class="text-muted">Danh sách tập phim đang được cập nhật.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">...</div>
                    <div class="tab-pane fade" id="actors" role="tabpanel" aria-labelledby="actors-tab">...</div>
                    <div class="tab-pane fade" id="recommend" role="tabpanel" aria-labelledby="recommend-tab">...</div>
                </div>

                {{-- Phần Bình luận --}}
                <div class="mt-5 comment-section">
                    <div class="d-flex align-items-center gap-3">
                        <h4>
                            <i class="bi bi-chat-dots"></i>
                            Bình luận ({{ $movie->comments ? $movie->comments->count() : '0' }})
                        </h4>
                        <div class="comment-tabs">
                            <button class="active">Bình luận</button>
                            <button>Đánh giá</button>
                        </div>
                    </div>
                    
                    {{-- Khung nhập bình luận --}}
                    <div class="comment-input-area mt-4">
                        @auth
                            {{-- Phần này hiển thị khi người dùng đã đăng nhập --}}
                            <form method="POST" action="{{ route('comments.store', $movie->slug) }}" id="commentForm">
                                @csrf
                                <div class="inner-box">
                                    <textarea name="content" id="comment-text-auth" rows="3" placeholder="Viết bình luận" maxlength="1000"></textarea>
                                </div>
                                <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                <div class="comment-actions">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="secretComment">
                                        <label class="form-check-label" for="secretComment">Tiết lộ</label>
                                    </div>
                                    <div>
                                        <span id="charCountAuth" class="text-white me-2">0/1000</span>
                                        <button type="submit" class="btn btn-send">
                                            Gửi <i class="bi bi-send-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            {{-- Phần này hiển thị khi người dùng chưa đăng nhập --}}
                            <div class="inner-box">
                                <textarea name="content" id="comment-text" rows="3" placeholder="Viết bình luận" maxlength="1000"></textarea>
                            </div>
                            <div class="comment-actions">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="secretComment">
                                    <label class="form-check-label" for="secretComment">Tiết lộ</label>
                                </div>
                                <div>
                                    <span id="charCountGuest" class="text-white me-2">0/1000</span>
                                    <button type="button" class="btn btn-send" onclick="handleCommentSubmit(event, '{{ route('login') }}');">
                                        Gửi <i class="bi bi-send-fill"></i>
                                    </button>
                                </div>
                            </div>
                        @endauth
                    </div>

                    {{-- Danh sách bình luận --}}
                    <div class="mt-4 comment-list">
                        @php
                            $comments = $movie->comments->whereNull('parent_id')->sortByDesc('created_at');
                        @endphp
                        @forelse($comments as $comment)
                            <div class="comment-item">
                                <img src="{{ generateUserAvatar($comment->user->email ?? $comment->user->name) }}" alt="Avatar" class="comment-avatar">
                                <div class="comment-content">
                                    <div class="comment-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="comment-author">{{ $comment->user->name }}</span>
                                            <span class="comment-meta ms-2">{{ $comment->created_at->diffForHumans() }}</span>
                                            @if($comment->episode_number)
                                                <span class="comment-badge ms-2">P.{{ $comment->season_number ?? 1 }} - Tập {{ $comment->episode_number }}</span>
                                            @endif
                                        </div>
                                        
                                        {{-- Nút menu ... --}}
                                        @auth
                                            @if(Auth::id() === $comment->user_id)
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-link text-muted dropdown-toggle" type="button" id="commentMenu{{ $comment->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="commentMenu{{ $comment->id }}">
                                                        <li><a class="dropdown-item" href="#" onclick="editComment({{ $comment->id }}, '{{ $comment->content }}')">Chỉnh sửa</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return false;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item" onclick="confirmDelete(event)">Xóa</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                    <div class="comment-body">
                                        <p id="comment-body-{{ $comment->id }}">{{ $comment->content }}</p>
                                    </div>
                                    <div class="comment-actions">
                                        <button><i class="bi bi-hand-thumbs-up"></i></button>
                                        <button><i class="bi bi-hand-thumbs-down"></i></button>
                                        <button onclick="replyToComment('{{ $comment->user->name }}')">Trả lời</button>
                                    </div>
                                </div>
                            </div>
                            @if($comment->replies && $comment->replies->count() > 0)
    <a class="replies-toggle collapsed" data-bs-toggle="collapse" href="#replies-{{ $comment->id }}" role="button" aria-expanded="false" aria-controls="replies-{{ $comment->id }}">
        <i class="bi bi-chevron-down"></i> {{ $comment->replies->count() }} bình luận
    </a>
    <div class="collapse mt-2" id="replies-{{ $comment->id }}">
    @foreach($comment->replies as $reply)
    <div class="comment-item comment-reply">
        <img src="{{ generateUserAvatar($reply->user->email ?? $reply->user->name) }}" alt="Avatar" class="comment-avatar">
        <div class="comment-content">
            <div class="comment-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="comment-author">{{ $reply->user->name }}</span>
                    <span class="comment-meta ms-2">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <div class="comment-body">
                <p>{{ $reply->content }}</p>
            </div>
        </div>
    </div>
@endforeach

    </div>
@endif


                        @empty
                            <p class="text-muted text-center mt-5">Chưa có bình luận nào.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cập nhật số ký tự khi gõ
        const textareaAuth = document.getElementById('comment-text-auth');
        const textareaGuest = document.getElementById('comment-text');

        if (textareaAuth) {
            textareaAuth.addEventListener('input', function() {
                updateCharCount(this, 'charCountAuth');
            });
        }
        if (textareaGuest) {
            textareaGuest.addEventListener('input', function() {
                updateCharCount(this, 'charCountGuest');
            });
        }
    });

    function updateCharCount(textarea, counterId) {
        const charCount = document.getElementById(counterId);
        if (charCount) {
            charCount.textContent = `${textarea.value.length}/1000`;
        }
    }
    
    function handleCommentSubmit(event, loginUrl) {
        event.preventDefault(); 
        const commentText = document.getElementById('comment-text').value;

        if (commentText.trim() === '') {
            console.log('Vui lòng nhập bình luận trước khi gửi.');
            // Thay thế bằng modal thông báo tùy chỉnh
            return false;
        }

        // Thay thế confirm bằng modal xác nhận tùy chỉnh
        if (window.confirm('Bạn cần đăng nhập để gửi bình luận. Bạn có muốn đăng nhập ngay bây giờ không?')) {
            window.location.href = loginUrl;
        }
    }

    function replyToComment(username) {
        const commentTextarea = document.getElementById('comment-text-auth') || document.getElementById('comment-text');
        if (commentTextarea) {
            commentTextarea.value = `@${username} `;
            commentTextarea.focus();
            updateCharCount(commentTextarea, commentTextarea.id === 'comment-text-auth' ? 'charCountAuth' : 'charCountGuest');
        }
    }
    
    function editComment(commentId, content) {
        const newContent = window.prompt('Chỉnh sửa bình luận:', content);
        if (newContent !== null && newContent.trim() !== '' && newContent !== content) {
            console.log(`Đã gửi yêu cầu chỉnh sửa bình luận ID ${commentId} với nội dung mới: "${newContent}"`);
        }
    }
    
    function confirmDelete(event) {
        event.preventDefault();
        if (window.confirm('Bạn có chắc chắn muốn xóa bình luận này không?')) {
            event.target.closest('form').submit();
        }
    }

    function generateUserAvatar(seed) {
        let hash = 0;
        if (seed.length === 0) return 'https://i.pravatar.cc/150'; 
        for (let i = 0; i < seed.length; i++) {
            const char = seed.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash |= 0; 
        }
        const avatarId = Math.abs(hash) % 1000; 
        return `https://i.pravatar.cc/150?u=${seed}`;
    }
</script>
@endsection
