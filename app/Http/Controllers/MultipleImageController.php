<?php

namespace App\Http\Controllers;

use App\Models\multiple;
use Illuminate\Http\Request;

class MultipleImageController extends Controller
{
    public function index()
    {
        return view('multiple.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images.*.image_path' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'images.*.desc' => 'required|string|max:255',
        ]);

        foreach ($request->images as $imgData) {
            $path = $imgData['image_path']->store('uploads', 'public');

            Multiple::create([
                'image_path' => $path,
                'desc' => $imgData['desc'],
            ]);
        }

        return redirect()->route('multiple.index')->with('success', 'Upload berhasil!');
    }
}