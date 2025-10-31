@extends('layouts.app')

@section('content')
  {{-- Nội dung riêng của TRANG TÀI KHOẢN --}}
  @push('profileContent')
    {{-- Bạn giữ nguyên form & logic avatar ở đây, KHÔNG lặp lại sidebar nữa --}}
    <style>
      /* style đặc thù cho trang này (nếu có) */
      .btn-text-link{background:none;border:none;color:#ffc107;font-weight:500;padding:.5rem 1rem;cursor:pointer}
      .btn-text-link:hover{color:#e0ac00;text-decoration:underline}
      .avatar-upload-section{width:150px;height:150px;border-radius:50%;background:#2c2c2c;display:flex;align-items:center;justify-content:center;overflow:hidden;box-shadow:0 0 0 4px #ffc107}
      .avatar-upload-section img{width:100%;height:100%;object-fit:cover}
      /* Radio button custom */
.form-check-input[type="radio"] {
  background-color: #1a1a1a;   /* nền đen khi chưa chọn */
  border: 2px solid #444;      /* viền xám đậm */
  cursor: pointer;
}

.form-check-input[type="radio"]:checked {
  background-color: #ffc107;   /* vàng khi chọn */
  border-color: #ffc107;
}

.form-check-input[type="radio"]:focus {
  box-shadow: none; /* tắt glow xanh */
  border-color: #ffc107;
}

.form-check-label {
  color: #d1d5db; /* chữ xám nhạt */
}

    </style>

    <h2 class="text-white mb-4">Tài khoản</h2>
    <h4 class="text-white mb-4">Cập nhật thông tin tài khoản</h4>

    <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">

      @csrf
      @method('PUT')

      <div class="row">
        {{-- Cột trái --}}
        <div class="col-md-7">
          <div class="mb-3 d-flex align-items-center gap-3">
            <label class="mb-0" style="width:120px;color:#ccc">Email</label>
            <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled
                   style="max-width:400px;background:#2c2c2c;border:1px solid #444;color:#fff">
          </div>

          <div class="mb-3 d-flex align-items-center gap-3">
            <label class="mb-0" style="width:120px;color:#ccc">Tên hiển thị</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required
                   style="max-width:400px;background:#2c2c2c;border:1px solid #444;color:#fff">
          </div>

          <div class="mb-3 d-flex align-items-center gap-3">
            <label class="mb-0" style="width:120px;color:#ccc">Giới tính</label>
            <div class="d-flex gap-3">
              <label class="form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="male"   {{ (Auth::user()->gender ?? '')=='male'   ? 'checked' : '' }}> Nam
              </label>
              <label class="form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="female" {{ (Auth::user()->gender ?? '')=='female' ? 'checked' : '' }}> Nữ
              </label>
              <label class="form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="unknown"{{ (Auth::user()->gender ?? '')=='unknown'? 'checked' : '' }}> Không xác định
              </label>
            </div>
          </div>

          <button type="submit" class="btn btn-update">Cập nhật</button>

          <div class="mt-4">
            <span class="text-white">Đặt mật khẩu </span>
            <a href="#" class="password-link" data-bs-toggle="modal" data-bs-target="#changePasswordModal">nhấn vào đây</a>

          </div>
        </div>

        {{-- Cột phải: Avatar --}}
        <div class="col-md-5 d-flex flex-column align-items-center">
          @php
            $seed = Auth::user()->email ?? Auth::user()->id;
            $defaultAvatarUrl = 'https://i.pravatar.cc/150?u=' . urlencode($seed);
            $userAvatar = Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : $defaultAvatarUrl;
          @endphp
          <div class="avatar-upload-section">
            <img id="current-avatar" src="{{ $userAvatar }}" alt="Avatar hiện tại">
          </div>

          <button type="button" class="btn-text-link mt-3" id="btn_upload_trigger">Đổi avatar</button>
          <input type="file" name="avatar" id="avatar_upload" accept="image/*" hidden>
        </div>
      </div>
    </form>
  @endpush
  {{-- Tính toán biến check lỗi --}}
@php
  $pwHasErrors = $errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation');
@endphp

{{-- MODAL ĐỔI MẬT KHẨU — KHÔNG ép show/inline style --}}
<div id="changePasswordModal" class="modal fade" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#2a2f3d; border:none; border-radius:16px;">
      <div class="modal-header border-0">
        <h5 class="modal-title text-white" id="changePasswordLabel">Đặt lại mật khẩu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="{{ route('profile.password.update') }}">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-white-50">Mật khẩu hiện tại</label>
            <input type="password" name="current_password" class="form-control"
                   style="background:#232736;border-color:#3a4052;color:#e9eaee" required>
            @error('current_password') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label text-white-50">Mật khẩu mới</label>
            <input type="password" name="password" class="form-control"
                   style="background:#232736;border-color:#3a4052;color:#e9eaee" required>
            @error('password') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
          </div>

          <div class="mb-1">
            <label class="form-label text-white-50">Xác nhận mật khẩu mới</label>
            <input type="password" name="password_confirmation" class="form-control"
                   style="background:#232736;border-color:#3a4052;color:#e9eaee" required>
            @error('password_confirmation') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="submit" class="btn" style="background:#f2cc68;border-radius:12px;font-weight:600;">Cập nhật</button>
          <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:12px;">Đóng</button>
        </div>
      </form>
    </div>
  </div>
</div>

@include('profile.partials.profile_layout', ['active_menu' => 'profile'])
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Avatar upload
  const trigger = document.getElementById('btn_upload_trigger');
  const input   = document.getElementById('avatar_upload');
  const img     = document.getElementById('current-avatar');
  const form    = document.getElementById('profileForm');

  trigger?.addEventListener('click', () => input?.click());
  input?.addEventListener('change', function(){
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      reader.onload = e => { if (img) img.src = e.target.result; };
      reader.readAsDataURL(this.files[0]);
      form?.submit();
    }
  });

  // Giữ modal mở khi có lỗi (và CHỈ mở khi có lỗi)
  const hasErrors = @json($pwHasErrors);
  const isSuccess = @json(session('pw_changed', false));

  if (hasErrors && !isSuccess) {
    const modalEl = document.getElementById('changePasswordModal');
    if (modalEl && window.bootstrap?.Modal) {
      // ĐỂ BOOTSTRAP QUẢN LÝ STATE -> tắt thủ công được
      new bootstrap.Modal(modalEl, { backdrop: true, keyboard: true }).show();
    } else {
      // Phòng trường hợp bootstrap chưa sẵn sàng
      window.addEventListener('load', function () {
        const el2 = document.getElementById('changePasswordModal');
        if (el2 && window.bootstrap?.Modal) new bootstrap.Modal(el2).show();
      });
    }
  }
});
</script>
@endpush
