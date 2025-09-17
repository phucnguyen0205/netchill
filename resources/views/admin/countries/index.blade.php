@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-md-9">
            <h1 class="text-light">Quản lý Quốc gia</h1>

            <div class="card mb-4 bg-dark text-light">
                <div class="card-header bg-dark text-light">
                    <h5 class="mb-0">Thêm Quốc gia mới</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('countries.store') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" placeholder="Tên quốc gia mới" required>
                            <button type="submit" class="btn btn-primary">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>

            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Quốc gia</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($countries as $country)
                    <tr>
                        <td>{{ $country->id }}</td>
                        <td>{{ $country->name }}</td>
                        <td>
                            @can('admin')
                                {{-- Nút Sửa --}}
                                <a href="{{ route('countries.edit', $country->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                
                                {{-- Form Xóa --}}
                                <form action="{{ route('countries.destroy', $country->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa quốc gia này không?');">Xóa</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection