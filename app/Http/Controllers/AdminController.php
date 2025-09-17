<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category; 
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Hiển thị trang dashboard của Admin.
     */
    public function index(): View
    {
        $categories = Category::all();
        $movies = Movie::with('category')
                       ->latest()
                       ->paginate(10);

        return view('admin.dashboard', compact('movies','categories'));
    }
}