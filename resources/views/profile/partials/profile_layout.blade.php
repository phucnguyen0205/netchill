<style>
    /* ------------------- STYLES PROFILE ------------------- */
    /* Đảm bảo style này chỉ áp dụng cho trang Profile */
    .profile-container {
        display: flex;
        /* Trừ đi chiều cao navbar (giả sử 80px) */
        min-height: calc(100vh - 80px); 
        background-color: #1a1a1a;
        padding: 0;
        margin-top: 80px; /* Đẩy nội dung xuống dưới navbar cố định */
    }
    
    /* Sidebar Styling - Giống ảnh Ảnh màn hình 2025-09-26 lúc 14.47.58.png */
    .profile-sidebar {
        width: 300px;
        background-color: #222222; 
        padding: 2rem 0;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
    }
    .sidebar-title {
        padding: 0 2rem;
        margin-bottom: 2rem;
        font-weight: bold;
        font-size: 1.5rem;
    }
    .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 1rem 2rem;
        color: #cccccc;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.2s, color 0.2s;
    }
    .sidebar-menu a:hover:not(.active) {
        background-color: #333333;
        color: #ffffff;
    }
    .sidebar-menu a.active {
        background-color: #1a1a1a; /* Match body background when active */
        color: #ffc107; /* Yellow highlight */
        border-left: 4px solid #ffc107;
    }
    .sidebar-menu i {
        font-size: 1.25rem;
        margin-right: 15px;
        width: 25px;
    }

    /* Main Content Styling */
    .profile-main-content {
        flex-grow: 1;
        padding: 3rem;
        background-color: #1a1a1a;
    }
    .profile-main-content h2 {
        font-weight: bold;
        color: #ffffff;
        margin-bottom: 2rem;
    }
    .profile-main-content h4 {
        color: #d1d5db; /* Cập nhật thông tin tài khoản */
        font-size: 1.1rem;
    }

    /* Form and Input Styling */
    .profile-form-group {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .profile-form-group label {
        width: 120px; /* Giảm width để form gọn hơn */
        font-weight: 500;
        color: #cccccc;
    }
    .profile-form-group .form-control {
        flex-grow: 1;
        background-color: #2c2c2c;
        border: 1px solid #444444;
        color: #ffffff;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        max-width: 400px; /* Giới hạn chiều rộng input */
    }
    .profile-form-group .form-control:focus {
        background-color: #333333;
        border-color: #ffc107;
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
    }
    .profile-form-group .form-check-input {
        background-color: #2c2c2c;
        border-color: #444444;
    }
    .profile-form-group .form-check-input:checked {
        background-color: #ffc107;
        border-color: #ffc107;
    }
    .profile-form-group .form-check-label {
        color: #cccccc;
        font-weight: normal;
        margin-left: 5px;
        width: auto;
    }
    .form-check-inline {
        margin-right: 1.5rem;
    }

    /* Avatar Upload Section - Giống ảnh cờ Việt Nam */
    .avatar-upload-section {
        width: 150px;
        height: 150px;
        border-radius: 50%; /* Tròn như ảnh */
        background-color: #2c2c2c;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        margin-bottom: 10px;
        box-shadow: 0 0 0 4px #ffc107; /* Viền vàng nổi bật */
    }
    .avatar-upload-section img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-column {
        padding-top: 1rem;
        padding-left: 3rem;
    }

    .btn-update {
        background-color: #ffc107;
        color: #1a1a1a;
        font-weight: bold;
        padding: 0.75rem 2.5rem;
        border: none;
        border-radius: 8px;
        transition: background-color 0.2s;
        margin-top: 1rem;
    }
    .btn-update:hover {
        background-color: #e0ac00;
        color: #1a1a1a;
    }
    .password-link {
        color: #ffc107;
        text-decoration: none;
        font-weight: 500;
        margin-top: 1rem;
        display: inline-block;
    }
    .password-link:hover {
        text-decoration: underline;
    }
    /* Thêm style này vào khối <style> ở đầu file profile/index.blade.php */
.btn-text-link {
    background: none;
    border: none;
    color: #ffc107; /* Màu vàng */
    font-weight: 500;
    padding: 0.5rem 1rem;
    cursor: pointer;
    transition: color 0.2s;
}
.btn-text-link:hover {
    color: #e0ac00; /* Màu vàng đậm hơn khi hover */
    text-decoration: underline; /* Thêm gạch chân khi hover */
}
</style>

<div class="profile-container">
{{-- Sidebar (Quản lý tài khoản) --}}
<div class="profile-sidebar">
  <div class="sidebar-title">Quản lý tài khoản</div>
  <div class="sidebar-menu">
    <a href="{{ route('favorites.index') }}"
   class="{{ request()->routeIs('favorites.*') ? 'active' : '' }}">
  <i class="bi bi-heart"></i> Yêu thích
</a>
<a href="{{ route('watchlists.index') }}"
   class="{{ request()->routeIs('watchlists.*') ? 'active' : '' }}">
  <i class="bi bi-plus"></i> Danh sách
</a>
<a href="{{ route('profile.history') }}"
   class="{{ request()->routeIs('profile.history') ? 'active' : '' }}">
  <i class="bi bi-clock-history"></i> Xem tiếp
</a>
<a href="{{ route('notifications.index') }}"
   class="{{ request()->routeIs('profile.notification') ? 'active' : '' }}">
  <i class="bi bi-bell"></i> Thông báo
</a>
<a href="{{ route('profile.index') }}"
   class="{{ request()->routeIs('profile.index') ? 'active' : '' }}">
  <i class="bi bi-person"></i> Tài khoản
</a>

  </div>
</div>

    
    {{-- Main Content (Nội dung từng trang) --}}
    <div class="profile-main-content">
    @stack('profileContent')
    </div>
</div>
