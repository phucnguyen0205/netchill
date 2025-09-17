<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    /**
     * Hiển thị danh sách tất cả thể loại.
     */
    public function index()
    {
        $genres = Genre::all();
        return view('admin.genres.index', compact('genres'));
    }

    /**
     * Lưu một thể loại mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:genres|max:255',
        ]);
        
        $genre = new Genre;
        $genre->name = $validatedData['name'];
        $genre->slug = Str::slug($validatedData['name']);
        $genre->save();

        return redirect()->route('genres.index')->with('success', 'Thêm thể loại thành công!');
    }

    /**
     * Xóa một thể loại khỏi cơ sở dữ liệu.
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return back()->with('success', 'Thể loại đã được xóa thành công!');
    }
}