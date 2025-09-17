<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar" style="width: 280px;">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">Admin Dashboard</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white active" aria-current="page">
                <svg class="bi me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
            List Phim
            </a>
        </li>
        <li>
            <a href="{{ route('categories.index') }}" class="nav-link text-white">
                <svg class="bi me-2" width="16" height="16"><use xlink:href="#categories"></use></svg>
                Thể loại phim
            </a>
        </li>
        <li>
            <a href="{{ route('genres.index') }}" class="nav-link text-white">
                <svg class="bi me-2" width="16" height="16"><use xlink:href="#genres"></use></svg>
                Danh mục
            </a>
        </li>
        <li>
            <a href="{{ route('countries.index') }}" class="nav-link text-white">
                <svg class="bi me-2" width="16" height="16"><use xlink:href="#countries"></use></svg>
                Quốc gia
            </a>
        </li>

        <li>
            <a href="" class="nav-link text-white">
                <svg class="bi me-2" width="16" height="16"><use xlink:href="#episodes"></use></svg>
                Tập phim
            </a>
        </li>
        <li>
            <a href="{{ route('banners.index') }}" class="nav-link text-white">
                <svg class="bi me-2" width="16" height="16"><use xlink:href="#banners"></use></svg>
                Banner
            </a>
        </li>
        
    </ul>
    <hr>
    <div class="dropdown">
      
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>

<style>
    /* Custom CSS to ensure the sidebar stretches vertically and aligns with main content */
    .sidebar {
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        padding-top: 4rem !important;
    }
</style>