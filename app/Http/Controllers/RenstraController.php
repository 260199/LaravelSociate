<?php

namespace App\Http\Controllers;

use App\Models\Renstra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\RenstraCreated;
use App\Models\Notification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PHPMailer\PHPMailer\PHPMailer;

class RenstraController extends Controller
{
    public function index(){
        $notiff = Notification::count();
        $notif = Notification::all();
        $notifications = $notif->sortByDesc('created_at');
        $rentras = Renstra::all();
        $renstra = $rentras->sortByDesc('DCreated');
        return view('admin.renstra.index')->with([
            'user' => Auth::user(),
            'renstra' => $renstra,
            'notiff' => $notiff,
            'notifications'=>$notifications
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'periodemulai' => 'required|digits:4|integer',
            'periodeselesai' => 'required|digits:4|integer',
            'na' => 'required|in:Y,N',
        ]);
    
        $renstra = Renstra::create([
            'nama' => $request->nama,
            'periodemulai' => $request->periodemulai,
            'periodeselesai' => $request->periodeselesai,
            'na' => $request->na,
            'DCreated' => now(),
            'UCreated' => Auth::id(),
        ]);
    
        //ini untuk sertakan linknya
        $signedUrl = URL::signedRoute('renstra.show', ['renstra' => $renstra->RenstraID]);
    
        broadcast(new RenstraCreated($renstra));
        $this->sendEmailNotification($renstra, $signedUrl);

        return redirect()->route('renstra.index')->with('success', 'Renstra berhasil dibuat!');
    }
    
    private function sendEmailNotification($renstra, $signedUrl)
    {
        $mail = new PHPMailer(true);
    
        try {
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
            $mail->Port       = env('MAIL_PORT', 587);
            $mail->SMTPDebug  = 0;
            $mail->Debugoutput = 'error_log';
    
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'Laravel App'));
            $mail->addAddress('ilham.mubara99@gmail.com', 'user');
    
            $mail->isHTML(true);
            $mail->Subject = 'Renstra Baru Dibuat!';
            $mail->Body    = "
                <h3>Renstra Baru Telah Dibuat</h3>
                <p><strong>Nama Renstra:</strong> {$renstra->nama}</p>
                <p><strong>Periode:</strong> {$renstra->periodemulai} - {$renstra->periodeselesai}</p>
                <p><strong>Detail Renstra:</strong><br>
                <a href=\"{$signedUrl}\">Klik di sini untuk melihat detail</a></p>
            ";
    
            $mail->send();
        } catch (Exception $e) {
            Log::error("Email gagal dikirim: {$mail->ErrorInfo}");
        }
    }
    

    public function destroy($id)
    {
        $renstra = Renstra::find($id);
        $renstra->delete();
        return redirect()->route('renstra.index')->with(['success' => 'Data Berhasil Di Hapus']);
    }

    public function show(Request $request, $id)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Link tidak valid atau kadaluarsa');
        }
        
        $notiff = Notification::count();
        $notif = Notification::all();
        $notifications = $notif->sortByDesc('created_at');
        $renstra = Renstra::findOrFail($id);
        return view('admin.renstra.show', compact('renstra','notiff','notifications'));
    }
}