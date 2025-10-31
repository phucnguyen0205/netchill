<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar" style="width: 280px;">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">Admin Quản Lý</span>
    </a>
    <hr>
   
  <ul class="nav nav-pills flex-column mb-auto">
    {{-- List Phim --}}
    <li class="nav-item">
      <a href="{{ route('admin.dashboard') }}"
         class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-collection me-2"></i>
        List Phim
      </a>
    </li>

    {{-- Thống kê --}}
    <li>
      <a href="{{ route('admin.stats.index') }}"
         class="nav-link text-white {{ request()->routeIs('admin.stats.*') ? 'active' : '' }}">
        <i class="bi bi-graph-up-arrow me-2"></i>
        Thống kê
      </a>
    </li>

    {{-- Thể loại phim --}}
    <li>
      <a href="{{ route('categories.index') }}"
         class="nav-link text-white {{ request()->routeIs('categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags me-2"></i>
        Thể loại phim
      </a>
    </li>
    {{-- Quốc gia --}}
    <li>
      <a href="{{ route('countries.index') }}"
         class="nav-link text-white {{ request()->routeIs('countries.*') ? 'active' : '' }}">
        <i class="bi bi-flag me-2"></i>
        Quốc gia
      </a>
    </li>
    {{-- Lịch Chiếu --}}
    <li>
  <a href="{{ route('admin.showtimes.index') }}"
     class="nav-link text-white {{ request()->routeIs('admin.showtimes') ? 'active' : '' }}">
    <i class="bi bi-calendar-week me-2"></i>
    Lịch chiếu
  </a>
</li>
    {{-- Banner --}}
    <li>
      <a href="{{ route('banners.index') }}"
         class="nav-link text-white {{ request()->routeIs('banners.*') ? 'active' : '' }}">
        <i class="bi bi-image me-2"></i>
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
:root{ --sidebar-w: 280px; }
  .sidebar {
    min-height: 100vh;
    position: fixed;
    top: 0; left: 0;
    z-index: 1000;
    padding-top: 4rem !important;
    width: var(--sidebar-w);
  }
  /* Hover + active effect */
  .sidebar .nav-link {
    border-radius: .5rem;
    transition: background .2s ease, transform .12s ease, color .2s ease, box-shadow .2s ease;
  }
  .sidebar .nav-link:hover {
    background: rgba(255,255,255,0.08);
    transform: translateX(2px);
    color: #fff !important;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.06);
  }
  .sidebar .nav-link.active {
    background: linear-gradient(90deg, #ffd54a33, #ffd54a22);
    color: #ffd54a !important;
    box-shadow: inset 0 0 0 1px rgba(255,213,74,.45);
  }
  .sidebar .nav-link i { opacity: .95; }
</style>