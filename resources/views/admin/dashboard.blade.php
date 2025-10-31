@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-md-9">
            <h1>Quản lý Danh Sách Phim</h1>
            <div class="col-md-9">
            <div class="card bg-dark text-light">
                <div class="card-body">
                   
                    <table class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th>Poster</th>
                                <th>Tiêu đề</th>
                                <th>Thể loại</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movies as $movie)
                                <tr>
                                <td>
                                <img src="{{ Storage::url($movie->poster) }}"
                                    alt="{{ $movie->title }}" 
                                    style="width: 80px; height: 120px; object-fit: cover;">
                                </td>

                                    <td>{{ $movie->title }}</td>
                                    <td>
                                    @php
                                        $names = $movie->categories->pluck('name')->implode(' - ');
                                    @endphp
                                    {{ $names ?: (optional($movie->category)->name ?? 'Chưa gán') }}
                                    </td>

                                    <td>
                                    <a href="{{ route('movies.edit', $movie) }}" class="btn btn-warning btn-sm">Sửa</a>

<form action="{{ route('movies.destroy', $movie) }}" method="POST" class="d-inline-block">
  @csrf
  @method('DELETE')
  <button type="submit" class="btn btn-danger btn-sm"
          onclick="return confirm('Bạn có chắc chắn muốn xóa phim này không?');">Xóa</button>
</form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{-- Pagination links --}}
                    <div class="d-flex justify-content-center">
                        {{ $movies->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection