<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str; // Thêm thư viện Str để tạo slug

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách tất cả thể loại.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Lưu một thể loại mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // 2. Tạo slug từ tên
        $slug = Str::slug($request->name);

        // 3. Tạo thể loại mới và lưu cả tên và slug
        Category::create([
            'name' => $request->name,
            'slug' => $slug, // Bổ sung slug vào đây
        ]);

        return back()->with('success', 'Thể loại đã được thêm thành công!');
    }

    /**
     * Xóa một thể loại khỏi cơ sở dữ liệu.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return back()->with('success', 'Thể loại đã được xóa thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể xóa thể loại này.');
        }
    }
}