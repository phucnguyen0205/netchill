<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netchill</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            /* Bỏ padding-top ở đây, sẽ xử lý bằng CSS cho main content */
            padding-top: 0 !important;
        }
        /* Trong file CSS của bạn, ví dụ: resources/css/app.css */
.card.bg-dark {
    background-color: #1a1a1a !important; /* Ghi đè màu nền cho card */
}

/* Hoặc tạo một lớp mới nếu bạn muốn linh hoạt hơn */
.card-custom-dark {
    background-color: #1a1a1a !important;
}
        .navbar {
            padding: 0.5rem 1rem;
            position: fixed; /* Giữ navbar cố định ở đầu trang */
            top: 0;
            width: 100%;
            z-index: 1030;
            transition: background-color 0.3s ease-in-out;
        }
        .navbar.transparent {
            background-color: transparent !important;
        }
        .navbar.scrolled {
            background-color: #1a1a1a !important;
        }
        .search-box .form-control::placeholder {
    color: #ffffff; /* placeholder màu trắng */
    opacity: 1;     /* đảm bảo placeholder hiển thị đầy đủ màu */
}
/* Search box gần trong suốt */
.search-box {
    background-color: rgba(255, 255, 255, 0.1); /* nền trắng mờ 10% */
    backdrop-filter: blur(5px); /* làm mờ nền phía sau */
    border-radius: 8px;
    padding: 6px 12px;
    display: flex;
    align-items: center;
    margin-right: 20px;
    transition: background-color 0.3s ease;
}

/* Khi hover vào ô tìm kiếm */
.search-box:hover {
    background-color: rgba(255, 255, 255, 0.2); /* nền sáng hơn khi hover */
}
.search-box .form-control:focus {
    outline: none !important;      /* bỏ viền mặc định */
    box-shadow: none !important;   /* bỏ shadow vàng của Bootstrap */
    background-color: transparent;  /* vẫn trong suốt */
    color: #ffffff;                 /* chữ trắng */
}
.search-box .form-control {
    background-color: transparent; /* trong suốt */
    border: none;
    color: #ffffff; /* chữ màu trắng */
}
/* Chữ placeholder */
.search-box .form-control::placeholder {
    color: #ffffff; /* placeholder màu trắng */
    opacity: 1;
    transition: color 0.3s ease;
}

/* Khi hover vào input */
.search-box .form-control:hover {
    color: #ffc107; /* chữ nhập vàng khi hover */
}

/* Nút tìm kiếm */
.search-box .btn-search {
    background: none;
    border: none;
    color: #ffffff;
    padding: 0 8px;
    cursor: pointer;
    transition: color 0.3s ease;
}
.btn-clear {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ffffff; /* nền trắng */
    color: #000000;            /* chữ X màu đen */
    border: none;
    border-radius: 50%;        /* hình tròn */
    width: 20px;
    height: 19px;
    font-size: 17px;
    line-height: 1;
    cursor: pointer;
    margin-left: 8px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Hover đổi màu nhẹ */
.btn-clear:hover {
    background-color: #ffc107; /* nền vàng khi hover */
    color: #000000;             /* chữ X vẫn đen */
}

        .navbar-brand img { height: 40px; margin-right: 10px; }
        .navbar-brand span { font-weight: bold; font-size: 1.5rem; }
        .navbar-brand .text-red { color: #e50914; }
        .navbar-brand .text-yellow { color: #FFC107; }
        .navbar-nav .nav-link { color: #ffffff  !important; font-weight: 500; margin: 0 8px; }
        .navbar-nav .nav-link:hover { color: #ffc107 !important; }
        .nav-link.new-badge { position: relative; }
        .nav-link.new-badge::after { content: 'NEW'; background-color: #FFC107; color: #222222; font-size: 0.65rem; font-weight: bold; padding: 2px 6px; border-radius: 4px; position: absolute; top: -5px; right: -15px; }
        .dropdown-menu { background-color: #2c2c2c; border: none; }
        .dropdown-item { color: #cccccc; }
        .dropdown-item:hover { background-color: #3a3a3a; color: #ffffff; }

        /* Style cho nội dung chính để tránh bị navbar che */
        main {
            padding-top: 80px; /* Chiều cao navbar */
        }
    main.container {
    position: relative;
    z-index: 1;          /* Đảm bảo nội dung hiển thị trên banner */
    margin-top: 0;       /* Không bị padding-top che banner */
}


        .banner-full-screen {
    width: 100%;
    height: 100vh;      /* Chiều cao full màn hình */
    object-fit: cover;  /* Ảnh không méo */
    z-index: 0;         /* Đảm bảo banner nằm sau navbar nếu navbar fixed */
    position: relative;
}
    .banner-full-screen .carousel-item,
    .banner-full-screen img {
        height: 100%;
        width: 100%;
        object-fit: cover; /* Đảm bảo hình ảnh không bị biến dạng */
    }
    .btn-member {
    background-color: #e50914; /* Màu đỏ của Netchill */
    color: #ffffff;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    font-weight: bold;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn-member:hover {
    background-color: #f40a17;
    color: #ffffff;
}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg transparent" id="mainNavbar">
    <div class="container-fluid">
        
    <a class="navbar-brand d-flex align-items-center" href="/">
    <img src="{{ asset('images/logo-netchill.png') }}" alt="Netchill Logo" style="height: 40px; margin-right: 10px;">
    <span><span class="text-red">Net</span><span class="text-yellow">Chill</span></span>
</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
            <li class="nav-item search-box d-flex align-items-center">
    <!-- Nút tìm kiếm (icon kính lúp) -->
    <button class="btn-search">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.085.122l3.085 3.085a1 1 0 0 0 1.414-1.414zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
        </svg>
    </button>

    <!-- Input tìm kiếm -->
    <input class="form-control me-2" type="search" placeholder="Tìm kiếm" aria-label="Search">

</li>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTheLoai" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Thể loại
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownTheLoai">
                    @foreach($categories as $category)
                            <li><a class="dropdown-item" href="#">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPhim" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Phim lẻ
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdownPhim">
        @foreach($genres as $genre)
            <li><a class="dropdown-item" href="#">{{ $genre->name }}</a></li>
        @endforeach
    </ul>
</li>
                 <li class="nav-item"><a class="nav-link" href="#">Phim bộ</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Xem Chung</a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownQuocGia" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Quốc gia
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownQuocGia">
                        @foreach($countries as $country)
                            <li><a class="dropdown-item" href="#">{{ $country->name }}</a></li>
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link new-badge" href="#">Lịch chiếu</a></li>
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                    </li>
                @else
                    @can('admin')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Chỉnh sửa</a></li>
                                <li><a class="dropdown-item" href="{{ route('movies.create') }}">Tạo phim</a></li>
                            </ul>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="btn btn-member"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                            </svg>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
<main>
    <div class="">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const navbar = document.getElementById('mainNavbar');
    const scrollThreshold = 80; // Bạn có thể điều chỉnh giá trị này

    window.addEventListener('scroll', () => {
        if (window.scrollY > scrollThreshold) {
            navbar.classList.add('scrolled');
            navbar.classList.remove('transparent');
        } else {
            navbar.classList.remove('scrolled');
            navbar.classList.add('transparent');
        }
    });
</script>
@yield('scripts')

</body>
</html>