@php
  // BẮT BUỘC TRUYỀN: $id, $action, $content
  // TUỲ CHỌN: $rows, $saveLabel, $cancelLabel, $formId
  $rows        = $rows        ?? 3;
  $saveLabel   = $saveLabel   ?? 'Lưu';
  $cancelLabel = $cancelLabel ?? 'Hủy';
  $formId      = $formId      ?? ('edit-form-' . $id);
@endphp

<form id="{{ $formId }}"
      class="edit-form d-none mt-2"
      action="{{ $action }}"
      method="POST">
  @csrf
  @method('PUT')

  <textarea name="content" class="form-control mb-2" rows="{{ $rows }}">{{ old('content', $content) }}</textarea>

  <div class="d-flex gap-2">
    <button type="submit" class="btn btn-sm btn-primary">{{ $saveLabel }}</button>
    <button type="button" class="btn btn-sm btn-secondary" data-cancel>{{ $cancelLabel }}</button>
  </div>
</form>
Cách dùng trong view bình luận
1) Menu “…thêm” (nằm cạnh “Trả lời”)

blade
Sao chép mã
<div class="comment-actions">
  {{-- like / dislike / trả lời ... --}}
  @auth
  @if((int)Auth::id()===(int)$comment->user_id)
    <div class="dropdown more-dropdown ms-1">
      <button type="button" class="more-toggle dropdown-toggle"
              data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-label="Thêm">
        <i class="bi bi-three-dots"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow">
        <li>
          <a class="dropdown-item" href="#"
             data-edit
             data-target="#edit-form-{{ $comment->id }}">
            Chỉnh sửa
          </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="m-0" data-delete>
            @csrf @method('DELETE')
            <button type="submit" class="dropdown-item text-danger">Xóa</button>
          </form>
        </li>
      </ul>
    </div>
  @endif
  @endauth
</div>