<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">Xin chào, {{ Auth::user()->name }}!</h3>
                    <p>Email: {{ Auth::user()->email }}</p>
                    <p>Ngày đăng ký: {{ Auth::user()->created_at->format('d/m/Y') }}</p>

                    <hr class="my-4">

                    <h4 class="text-xl font-semibold mb-2">Danh sách phim mới</h4>
                    <ul class="list-disc pl-6">
                        @foreach ($movies as $movie)
                            <li>{{ $movie->title }} ({{ $movie->category->name }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
