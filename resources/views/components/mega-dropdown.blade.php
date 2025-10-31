@props([
  'id' => 'megaDropdown',
  'label' => 'Menu',
  'items' => collect(),
  'routeName' => null,
  'cols' => 4,
])

@php
  $sorted = $items->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE)->values();
  $perCol = (int) ceil(max(1, $sorted->count()) / max(1, $cols));
  $chunked = $sorted->chunk($perCol);
@endphp

<li class="nav-item dropdown mega-dropdown">
  <a class="nav-link dropdown-toggle"
     href="#"
     id="{{ $id }}"
     role="button"
     data-bs-toggle="dropdown"
     data-bs-auto-close="outside"
     aria-expanded="false">
    {{ $label }}
  </a>

  <div class="dropdown-menu mega-menu p-3" aria-labelledby="{{ $id }}">
    <div class="row g-2 row-cols-2 row-cols-md-3 row-cols-lg-{{ $cols }}">
      @foreach($chunked as $col)
        <div class="col">
          <ul class="list-unstyled m-0">
            @foreach($col as $item)
              <li>
                <a class="dropdown-item mega-item"
                   href="{{ $routeName ? route($routeName, $item) : '#' }}">
                  {{ $item->name }}
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      @endforeach
    </div>
  </div>
</li>
