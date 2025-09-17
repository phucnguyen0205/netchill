<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class DashboardController extends Controller
{
    public function index()
    {
        $movies = Movie::with('category')->latest()->paginate(10);

        return view('admin.dashboard', compact('movies'));

    }
}
