@extends('layouts.app')

@section('content')
<style>
  /* Banner chính */
.banner-custom {
    position: relative;
    width: 100%;
    height: 100vh; /* Full màn hình */
    overflow: hidden;
    margin-top: -80px; 
    z-index: 1;
}

.banner-custom img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Hiệu ứng mờ viền ngoài banner */
.banner-custom::after {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: radial-gradient(circle, rgba(0,0,0,0) 80%, rgba(0,0,0,0.7) 100%);
}

/* Header nổi trên banner */
header, .navbar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 10; /* cao hơn banner */
    background: transparent !important; /* trong suốt để thấy banner */
}

/* Thông tin trong banner */
.banner-info {
    position: absolute;
    bottom: 15%;
    left: 5%;
    z-index: 2;
    color: white;
    text-shadow: 0 2px 8px rgba(0,0,0,0.6);
}

/* Thumbnail chuyển sang bên phải */
.banner-thumbnails {
    position: absolute;
    right: 20px;
    bottom: 20px;
    display: flex;
    flex-direction: row; /* hàng ngang */
    gap: 10px;
    z-index: 3;
}

.banner-thumbnails img {
    width: 80px;
    height: 50px;
    object-fit: cover;
    border: 2px solid transparent;
    border-radius: 6px;
    cursor: pointer;
    transition: transform 0.3s, border-color 0.3s;
}

.banner-thumbnails img.active,
.banner-thumbnails img:hover {
    border-color: #ffc107;
    transform: scale(1.1);
}
<style>
.hide-scrollbar {
    -ms-overflow-style: none;  /* IE và Edge */
    scrollbar-width: none;     /* Firefox */
}
.hide-scrollbar::-webkit-scrollbar {
    display: none;             /* Chrome, Safari, Opera */
}
.title-wrapper h6 {
    height: 2.4em; /* 2 dòng x 1.2em mỗi dòng */
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* số dòng */
    -webkit-box-orient: vertical;
}
/* Ẩn scrollbar nhưng vẫn cho phép cuộn ngang */
.hide-scrollbar {
        -ms-overflow-style: none;  /* IE và Edge */
        scrollbar-width: none;     /* Firefox */
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;             /* Chrome, Safari, Opera */
    }
</style>

</style>
{{-- Banner --}}
@if(isset($banners) && $banners->count() > 0)
    <div id="bannerCarousel" class="carousel slide banner-custom" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner">
            @foreach($banners as $key => $banner)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}">
                    <div class="banner-overlay"></div>
                    <div class="banner-info">
                        <h2>{{ $banner->title }}</h2>
                        <p>{{ Str::limit($banner->description, 120) }}</p>
                        <a href="{{ route('movies.detai', $banner->movie->slug ?? '') }}" class="btn btn-warning">Xem ngay</a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Thumbnail nhỏ bên trái --}}
        <div class="banner-thumbnails">
            @foreach($banners as $key => $banner)
                <img src="{{ Storage::url($banner->image_path) }}" 
                     data-bs-target="#bannerCarousel" 
                     data-bs-slide-to="{{ $key }}" 
                     class="{{ $key == 0 ? 'active' : '' }}">
            @endforeach
        </div>
    </div>
@endif
<div class="container-fluid py-4">
    <h1 class="text-center mb-4">Danh sách Phim</h1>

    <div class="d-flex overflow-auto hide-scrollbar" style="gap: 32px;">
        @foreach($movies->chunk(2) as $twoMovies)
            <div class="d-flex flex-column flex-shrink-0" style="gap: 40px;">
                @foreach($twoMovies as $movie)
                    <div class="card bg-dark text-light border-0 text-center" style="width: 200px;">
                        <a href="{{ route('movies.detai', $movie->slug) }}">
                            <img src="{{ Storage::url($movie->poster) }}" 
                                 alt="{{ $movie->title }}" 
                                 class="img-fluid rounded" 
                                 style="object-fit: cover; height: 300px; width: 100%;"> {{-- Chiều cao = 300px --}}
                        </a>
                        <div class="title-wrapper mt-2">
                            <h6 class="card-title mb-0">{{ Str::title($movie->title) }}</h6>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

    {{-- Pagination links --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $movies->links('pagination::bootstrap-5') }}
    </div>
</div>

<div class="container-fluid">
    <hr class="my-5 bg-secondary">
<h2 class="text-light">Phim Mới Nhất</h2>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-4">
    @foreach($latestMovies as $movie)
        <div class="col">
            <div class="card bg-dark text-light border-0 text-center">
                <div class="poster-wrapper mb-2">
                    <a href="{{ route('movies.detai', $movie->slug) }}">
                        <img src="{{ Storage::url($movie->poster) }}" 
                             alt="{{ $movie->title }}" 
                             class="img-fluid rounded" 
                             style="object-fit: cover; height: 300px; width: 100%;">
                    </a>
                </div>
                <div class="title-wrapper">
                    <h6 class="card-title mb-0">{{ Str::title($movie->title) }}</h6>
                </div>
            </div>
        </div>
    @endforeach
</div>
    <hr class="my-5 bg-secondary">
    {{-- Phần Phim Theo Thể loại --}}
    <h2 class="text-light">Phim Theo Thể loại</h2>
<div class="d-flex overflow-auto hide-scrollbar py-2">
    @foreach($categories as $category)
        <div class="card bg-secondary text-light me-3 flex-shrink-0 clickable-category" 
             style="width: 200px; cursor: pointer;" 
             data-category-id="{{ $category->id }}">
            <div class="card-body text-center">
                <h5 class="card-title">{{ $category->name }}</h5>
                <p class="card-text">Các bộ phim thuộc thể loại {{ $category->name }}</p>
            </div>
        </div>
    @endforeach
</div>


    <hr class="my-5 bg-secondary">
    {{-- Phần Phim Theo Quốc gia --}}
    <h2 class="text-light">Phim Theo Quốc gia</h2>
    <div class="row">
        @foreach($countries as $country)
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-light">
                    <div class="card-body">
                        <h5 class="card-title">{{ $country->name }}</h5>
                        <p class="card-text">Các bộ phim đến từ {{ $country->name }}</p>
                        <a href="#" class="btn btn-secondary">Xem tất cả</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.querySelectorAll('.clickable-category').forEach(card => {
        card.addEventListener('click', function() {
            const categoryId = this.dataset.categoryId;
            window.location.href = `/movies?category=${categoryId}`;
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.querySelector('#bannerCarousel');
    const thumbnails = document.querySelectorAll('.banner-thumbnails img');

    carousel.addEventListener('slide.bs.carousel', function (e) {
        thumbnails.forEach(img => img.classList.remove('active'));
        thumbnails[e.to].classList.add('active'); // e.to = index slide tiếp theo
        });
    });
</script>
@endsection
