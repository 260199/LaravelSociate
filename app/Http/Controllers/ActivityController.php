<?php

namespace App\Http\Controllers;

use App\Events\ActvCreated;
use App\Models\Daily;
use App\Models\Filedaily;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index()
    {
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notiff = $belom->count();
        $notif = Notification::orderBy('created_at', 'desc')->get();
        $dailys = Daily::where('user_id', Auth::id())->get();
        $daily = $dailys->sortBydesc('created_at');
        
    
        return view('admin.actv.index')->with([
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
        broadcast(new ActvCreated($daily));
        return redirect()->back()->with('success', 'Kegiatan harian berhasil ditambahkan!');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'desc' => 'nullable|array',
            'desc.*' => 'nullable|string|max:255',
        ]);
    
        $daily = Daily::findOrFail($id);
        $now = now();
        $createdAt = $daily->created_at;
        $diffInDays = $createdAt->diffInDays($now);
    
        // Upload gambar (jika ada)
        if ($request->hasFile('images')) {
            if ($diffInDays > 3) {
                return redirect()->back()->with('error', 'Maaf, upload bukti tidak diperbolehkan karena lebih dari 3 hari sejak kegiatan dibuat.');
            }
    
            $descriptions = $request->input('desc', []);
            foreach ($request->file('images') as $index => $image) {
                $user = auth()->user();
                $userFolderName = preg_replace('/[^A-Za-z0-9\-]/', ' ', $user->name);
                $activityFolderName = preg_replace('/[^A-Za-z0-9\-]/', ' ', $daily->kegiatan);
    
                $timestamp = now()->format('Ymd-His');
                $randomStr = \Illuminate\Support\Str::random(2);
                $extension = $image->getClientOriginalExtension();
    
                $fileName = $daily->kegiatan . $timestamp . $randomStr . '.' . $extension;
                $path = $image->storeAs(
                    'Bukti Kegiatan/' . $userFolderName . '/' . $activityFolderName,
                    $fileName,
                    'public'
                );
    
                Filedaily::create([
                    'daily_id' => $daily->id,
                    'image_path' => $path,
                    'desc' => $descriptions[$index] ?? '',
                ]);
            }
        }
    
        // Update done_at jika ada
        if ($request->done_at && $daily->status !== 'selesai') {
            $doneAt = \Carbon\Carbon::parse($request->done_at);
            $maxDate = $daily->created_at->copy()->addDays(3);
    
            if ($doneAt->gt($maxDate)) {
                return redirect()->back()->with('error', 'Tanggal selesai tidak boleh lebih dari 3 hari setelah tanggal dibuat.');
            }
    
            $daily->done_at = $doneAt;
        }
    
        // Simpan data kegiatan utama
        $daily->update([
            'kegiatan' => $request->kegiatan,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'done_at' => $daily->done_at, // pastikan done_at ikut diupdate kalau ada perubahan
        ]);
    
        return redirect()->back()->with('success', 'Data kegiatan berhasil diperbarui!');
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
            $mail->Subject = "{$daily->user->name} - Kegiatan Harian Selesai!";
            
            $filePaths = Filedaily::where('daily_id', $daily->id)->get();
            $body = 
            "
                <h3>Kegiatan Harian {$daily->user->name} Telah Selesai!!</h3>
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
    
    public function massReports(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $dailies = Daily::whereIn('id', $ids)->get();
        foreach ($dailies as $daily) {
            if ($daily->status != 'selesai' && $daily->filedailies()->exists()) {
                $daily->status = 'selesai';
                $daily->save();
            }
        }
        $this->sendEmailNotification($daily);
        return redirect()->back()->with('success', 'Laporan berhasil dikirim.');
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