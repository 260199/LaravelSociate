<?php

namespace App\Http\Controllers;

use App\Models\IsuStrategis;
use App\Models\Pilar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsuStrategiController extends Controller
{
    public function index()
    {
        $isus = IsuStrategis::with('pilar.renstra')->get();
        $isustrategis = $isus->sortByDesc('DCreated');
        $pilars = Pilar::with('renstra')->get();
        $pilar = $pilars->sortByDesc('PilarID'); // diperbaiki sorting berdasarkan PilarID

        return view('admin.isustrategis.index')->with([
            'isustrategis' => $isustrategis,
            'pilar' => $pilar,
            'user' => Auth::user(),
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'PilarID' => 'required|exists:pilars,PilarID',
            'nama' => 'required|string|max:255',
            'NA' => 'required|in:Y,N',
        ]);

        // Simpan data
        IsuStrategis::create([
            'PilarID'   => $request->PilarID,
            'nama'      => $request->nama,
            'NA'        => $request->NA,
            'DCreated'  => Carbon::now(),
            'UCreated'  => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        IsuStrategis::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
