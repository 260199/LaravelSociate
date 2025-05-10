<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\Filedaily;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class DemoDailyController extends Controller
{
    public function index()
    {
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notiff = $belom->count();
    
        $notif = Notification::orderBy('created_at', 'desc')->get();
    
    
        $daily = Daily::with('filedailies')->orderBy('created_at', 'desc')->get();
        return view('admin.daily.daily_demo')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $daily
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Daily::create([
            'user_id' => Auth::id(),
            'kegiatan' => $request->kegiatan,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'status' => 'progress',
        ]);

        return redirect()->route('daily.demo')->with('success', 'Daily berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $daily = Daily::findOrFail($id);

        $request->validate([
            'done_at' => 'nullable|date',
            'bukti.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->filled('done_at')) {
            $daily->done_at = $request->done_at;
            $daily->status = 'selesai';
        }

        $daily->save();

        // Upload file
        if ($request->hasFile('bukti')) {
            foreach ($request->file('bukti') as $file) {
                $filename = $daily->kegiatan . '-' . now()->format('Ymd-His') . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('bukti_kegiatan/' . Auth::user()->name . '/' . $daily->kegiatan, $filename, 'public');

                Filedaily::create([
                    'daily_id' => $daily->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('daily.demo')->with('success', 'Daily berhasil diperbarui!');
    }
}
