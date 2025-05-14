<?php

namespace App\Http\Controllers;

use App\Models\jekeg;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisKegiatanController extends Controller
{
    public function index(){
              $users = User::all();

              // Ambil data notifikasi
              $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
              $notiff = $belom->count();
              $notif = Notification::orderBy('created_at', 'desc')->get();
              $jekegs = jekeg::all()->sortByDesc('created_at');      
              // Kirim data ke view
              return view('admin.jekegs.index', compact('users'))->with([
                  'user' => Auth::user(),
                  'notiff' => $notiff,
                  'notifications' => $notif,
                  'jekegs' => $jekegs
              ]);
    }
    public function create()
    {
        return view('jekegs.create');
    }

    public function store(Request $request)
    {
        $request->validate(['kegiatan' => 'required|string|max:255']);

        Jekeg::create([
            'kegiatan' => $request->kegiatan,
            'DCreated' => now(),
            'UCreated' => Auth::id(),
        ]);

        return redirect()->route('jekegs.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Jekeg $jekeg)
    {
        return view('jekegs.edit', compact('jekeg'));
    }

    public function update(Request $request, Jekeg $jekeg)
    {
        $request->validate(['kegiatan' => 'required|string|max:255']);

        $jekeg->update([
            'kegiatan' => $request->kegiatan,
            'DEdited' => now(),
            'UEdited' => Auth::id(),
        ]);

        return redirect()->route('jekegs.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Jekeg $jekeg)
    {
        $jekeg->delete();
        return redirect()->route('jekegs.index')->with('success', 'Data berhasil dihapus.');
    }
}