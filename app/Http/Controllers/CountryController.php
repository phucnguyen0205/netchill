<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:countries|max:255',
        ]);
        
        $country = new Country;
        $country->name = $validatedData['name'];
        $country->slug = Str::slug($validatedData['name']);
        $country->save();

        return redirect()->route('countries.index')->with('success', 'Thêm quốc gia thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        try {
            $country->delete();
            return back()->with('success', 'Quốc gia đã được xóa thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể xóa quốc gia này.');
        }
    }
}