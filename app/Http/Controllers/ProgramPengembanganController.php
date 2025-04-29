<?php

namespace App\Http\Controllers;

use App\Models\IsuStrategis;
use App\Models\Pilar;
use App\Models\ProgramPengembangan;
use App\Models\Renstra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramPengembanganController extends Controller
{
    public function index()
    {
        $programs = ProgramPengembangan::with('isuStrategis.pilar.renstra')->get();
        $isu = IsuStrategis::with('pilar.renstra')->get();

        return view('admin.programpengembangan.index', [
            'programs' => $programs,
            'user' => Auth::user(),
            'isu' =>$isu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'IsuID' => 'required|exists:isustrategis,IsuID',
            'nama' => 'required|string|max:255',
            'NA' => 'required|in:Y,N',
        ]);

        ProgramPengembangan::create([
            'IsuID' => $request->IsuID,
            'nama' => $request->nama,
            'NA' => $request->NA,
            'DCreated' => now(),
            'UCreated' => auth()->id(),
        ]);

        return redirect()->route('progpeng.index')->with('success', 'Program Pengembangan berhasil disimpan!');
    }

    
    public function destroy($id)
    {
        ProgramPengembangan::where('ProgramID', $id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}