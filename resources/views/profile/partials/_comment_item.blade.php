@php
  // Lấy thông tin người dùng
  $u = $comment->user;
  
  // --- Logic AVATAR ---
  $avatarSeed = $u->email ?? $u->id;
  $defaultAvatarUrl = 'https://i.pravatar.cc/150?u=' . urlencode($avatarSeed);
  $avatar = $u->avatar ? Storage::url($u->avatar) : $defaultAvatarUrl;
  $isOwner = auth()->id() === $comment->user_id;
  $isReply = $isReply ?? false;
  // --- Logic GIỚI TÍNH ---
  $gender = $u->gender ?? 'unknown'; // Giả sử cột 'gender' có thể là 'male' hoặc 'female'
  $genderIcon = '';
  $genderColor = '';

  if ($gender === 'male') {
      $genderIcon = 'bi-gender-male';
      $genderColor = 'text-primary'; // Màu xanh dương cho nam
  } elseif ($gender === 'female') {
      $genderIcon = 'bi-gender-female';
      $genderColor = 'text-danger'; // Màu đỏ cho nữ
  }
@endphp

<div class="comment-item {{ !empty($isReply) ? 'comment-reply' : '' }}" id="cmt-{{ $comment->id }}">
  {{-- AVATAR: Chèn ngay đầu tiên --}}
  <img src="{{ $avatar }}" alt="Avatar" class="comment-avatar">
  
  <div class="comment-content">
    {{-- header: tên + giới tính + thời gian --}}
    <div class="comment-header d-flex align-items-center">
      
      {{-- Tên tác giả --}}
      <span class="comment-author">{{ $u->name }}</span>
      
      {{-- ICON GIỚI TÍNH: Chèn sau tên tác giả --}}
      @if($genderIcon)
        <i class="bi {{ $genderIcon }} {{ $genderColor }} ms-2" title="{{ $gender === 'male' ? 'Nam' : 'Nữ' }}"></i>
      @endif

      <span class="comment-meta ms-2">{{ $comment->created_at->diffForHumans() }}</span>
      
      @if($comment->episode_number)
        <span class="comment-badge ms-2">P.{{ $comment->season_number ?? 1 }} - Tập {{ $comment->episode_number }}</span>
      @endif
    </div>

    {{-- nội dung --}}
    <div class="comment-body">
      <p id="comment-body-{{ $comment->id }}">{{ $comment->content }}</p>
    </div>

    {{-- actions dưới nội dung (có menu ... màu xám nhạt) --}}
    <div class="d-flex align-items-center gap-3">
      <div class="comment-actions d-flex align-items-center gap-3">
        <button class="btn btn-sm p-0 text-muted"><i class="bi bi-hand-thumbs-up"></i></button>
        <button class="btn btn-sm p-0 text-muted"><i class="bi bi-hand-thumbs-down"></i></button>
        @auth
          <button class="btn btn-sm p-0 text-muted" onclick="UI.openReplyForm({{ $comment->id }})">
            <i class="bi bi-reply"></i> Trả lời
          </button>
        @else
          <a class="btn btn-sm p-0 text-muted" href="{{ route('login') }}">
            <i class="bi bi-reply"></i> Trả lời
          </a>
        @endauth
      </div>

      @auth
      @if($isOwner)
        <div class="ms-auto">
          <div class="dropdown">
            <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration:none;">
              <i class="bi bi-three-dots"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-dark">
              <li>
                <a class="dropdown-item" href="#"
                   onclick="UI.openEditForm({{ $comment->id }}, @js($comment->content))">
                  Chỉnh sửa
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <button class="dropdown-item"
                        onclick="UI.deleteComment({{ $comment->id }}, '{{ route('comments.destroy',$comment) }}')">
                  Xóa
                </button>
              </li>
            </ul>
          </div>
        </div>
      @endif
      @endauth
    </div>

    {{-- form trả lời inline (ẩn mặc định) --}}
    @auth
    <form class="reply-form d-none mt-2" id="reply-form-{{ $comment->id }}"
          action="{{ route('comments.store', $comment->movie->slug) }}"
          method="post" onsubmit="return UI.submitAjax(event,this)">
      @csrf
      <input type="hidden" name="parent_id" value="{{ $comment->id }}">
      <div class="comment-input-area p-2">
        <div class="inner-box p-2">
          <textarea name="content" rows="2" placeholder="Viết trả lời..." maxlength="1000"></textarea>
        </div>
        <div class="comment-actions">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_secret" value="1" id="secret-{{ $comment->id }}">
            <label class="form-check-label" for="secret-{{ $comment->id }}">Tiết lộ</label>
          </div>
          <button class="btn btn-send">Gửi <i class="bi bi-send-fill"></i></button>
        </div>
      </div>
    </form>
    @endauth

    {{-- replies: render ngay dưới, thụt phải --}}
    @foreach($comment->replies as $child)
      @include('movies.partials._comment_item', ['comment'=>$child, 'isReply'=>true])
    @endforeach
  </div>
</div>
