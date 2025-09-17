<?php
// app/Http/Controllers/Admin/BannerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $banners = Banner::all();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'image' => 'required|image|max:10240', 
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|url',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image_path' => $imagePath,
            'link' => $request->link,
        ]);

        return redirect()->route('banners.index')->with('success', 'Thêm banner thành công!');
    }

    public function edit(Banner $banner)
    {
        $this->authorize('admin');
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'image' => 'nullable|image|max:10240', // Đã tăng giới hạn lên 10MB
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|url',
        ]);
        
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            // Lưu ảnh mới
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy(Banner $banner)
    {
        $this->authorize('admin');
        // Xóa ảnh từ storage
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        // Xóa bản ghi trong database
        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Xóa banner thành công!');
    }
}