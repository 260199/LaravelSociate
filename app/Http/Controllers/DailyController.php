<?php

namespace App\Http\Controllers;

use App\Events\DailyCreated;
use App\Models\Daily;
use App\Models\Filedaily;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DailyController extends Controller
{
    public function index()
    {
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notiff = $belom->count();
    
        $notif = Notification::orderBy('created_at', 'desc')->get();
    
        $daily = Daily::orderBy('created_at', 'desc')->get();
    
        return view('admin.daily.index')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $daily
        ]);
    }

    
    public function edit()
    {
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notiff = $belom->count();
    
        $notif = Notification::orderBy('created_at', 'desc')->get();
    
        $daily = Daily::orderBy('created_at', 'desc')->get();
    
        return view('admin.daily.index')->with([
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
        $daily = Daily::create([
            'user_id' => Auth::id(),
            'kegiatan' => $request->kegiatan,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'status' => 'progress',
        ]);
        broadcast(new DailyCreated($daily));
        return redirect()->back()->with('success', 'Kegiatan harian berhasil ditambahkan!');
    }


    public function massLaporkan(Request $request)
    {
        $request->validate([
            'selected_daily' => 'required|array',
        ]);

        foreach ($request->selected_daily as $id) {
            $daily = Daily::with('filedailies')->find($id);
            if (!$daily || $daily->status == 'selesai') continue;
            if ($daily->filedailies->count() == 0) continue;
            $daily->status = 'selesai';
            $daily->save();
            $this->sendEmailNotification($daily);
        }
        return redirect()->back()->with('success', 'Kegiatan berhasil dilaporkan!');
    }

    private function sendEmailNotification($daily)
    {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
            $mail->Port       = env('MAIL_PORT', 587);

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'Laravel App'));
            $mail->addAddress('ilham.mubara99@gmail.com', 'user'); 
            $mail->isHTML(true);
            $mail->Subject = 'Kegiatan Harian Selesai!';
            
            $filePaths = Filedaily::where('daily_id', $daily->id)->get();
            $body = 
            "
                <h3>Kegiatan Harian Telah Selesai</h3>
                <p><strong>Nama Kegiatan:</strong> {$daily->kegiatan}</p>
                <p><strong>Jenis Kegiatan:</strong> {$daily->jenis}</p>
                <p><strong>Deskripsi Kegiatan:</strong> {$daily->deskripsi}</p>
            ";

            foreach ($filePaths as $file) {
                $path = storage_path('app/public/' . $file->image_path);  
                $mail->addAttachment($path, basename($path));  
            }
            
            $mail->Body = $body;
            $mail->send();
        } catch (\Exception $e) {
            Log::error("Email gagal dikirim: {$mail->ErrorInfo}");
        }
    }


    public function detail($id)
    {
        // Mengecek apakah URL yang digunakan adalah signed URL
        if (!request()->hasValidSignature()) {
            abort(403, 'URL tidak valid atau telah kedaluwarsa.');
        }
    
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notiff = $belom->count();
        $notif = Notification::orderBy('created_at', 'desc')->get();
        $daily = Daily::with(['user', 'filedailies'])->findOrFail($id);
    
        return view('admin.actv.detail')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $daily
        ]);
    }

    public function info($id)
    {
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notiff = $belom->count();
        $notif = Notification::orderBy('created_at', 'desc')->get();
        $daily = Daily::with(['user', 'filedailies'])->findOrFail($id);
        
        return view('admin.actv.info')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $daily
        ]);
    }
    
    

    public function update(Request $request, $id)
    {
        $daily = Daily::with('filedailies')->findOrFail($id);
        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bukti.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
    
        $daily->kegiatan = $request->kegiatan;
        $daily->jenis = $request->jenis;
        $daily->deskripsi = $request->deskripsi;
    
        if ($request->done_at && $daily->status != 'selesai') {
            $doneAt = \Carbon\Carbon::parse($request->done_at);
            $maxDate = $daily->created_at->copy()->addDays(3);
            if ($doneAt->gt($maxDate)) {
                return redirect()->back()->with('error', 'Tanggal selesai tidak boleh lebih dari 3 hari setelah tanggal dibuat.');
            }
            $daily->done_at = $doneAt;
        }
        $daily->save();
        logger('Jumlah file: ' . count($request->file('bukti') ?? []));
        $this->handleFileUploads($request, $daily, 'bukti');

    

        dd($request->all());  
    }
    
    private function handleFileUploads(Request $request, $daily, $inputName)
    {
        $files = $request->file($inputName, []);
    
        foreach ((array) $files as $file) {
            if (!$file || !$file->isValid()) continue;
    
            $user = auth()->user();
            $userFolderName = preg_replace('/[^A-Za-z0-9\-]/', '_', $user->name);
            $activityFolderName = preg_replace('/[^A-Za-z0-9\-]/', '_', $daily->kegiatan);
    
            $timestamp = now()->format('Ymd-His');
            $randomStr = Str::random(5);
            $extension = $file->getClientOriginalExtension();
    
            $fileName = $daily->kegiatan . '-' . $timestamp . '-' . $randomStr . '.' . $extension;
            $path = $file->storeAs('bukti_kegiatan/' . $userFolderName . '/' . $activityFolderName, $fileName, 'public');
    
            Filedaily::create([
                'daily_id' => $daily->id,
                'image_path' => $path,
            ]);
        }
    }
    
    
    public function destroy($id)
    {
        $daily = Daily::with('filedailies')->find($id);
        if (!$daily) {
            return redirect()->route('daily.index')->with(['error' => 'Data tidak ditemukan.']);
        }
        foreach ($daily->filedailies as $file) {
            if ($file->image_path && Storage::disk('public')->exists($file->image_path)) {
                Storage::disk('public')->delete($file->image_path);
            }
            $file->delete();
        }
        $daily->delete();
        return redirect()->route('daily.index')->with(['success' => 'Data berhasil dihapus.']);
    }
}