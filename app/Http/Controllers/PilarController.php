<?php

namespace App\Http\Controllers;

use App\Models\Pilar;
use App\Models\Renstra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\PilarNotification;
use App\Models\Notification;
use App\Events\PilarCreated;
use Exception;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;

class PilarController extends Controller
{
    public function index(){
        $belom = Notification::get()->where('read',0);
        $notiff = $belom->count();
        $notif = Notification::all();
        $notifications = $notif->sortByDesc('created_at');
        $renstra = Renstra::all();
        $pilars = Pilar::all();
        $pilar = $pilars->sortByDesc('DCreated');
        return view('admin.pilar.index')->with([
            'user' => Auth::user(),
            'pilar' => $pilar,
            'notiff' => $notiff,
            'notif' => $notif,
            'renstra' => $renstra,
            'notifications' => $notifications
        ]);
    }

    public function store(Request $request)
    {
        $pilar = Pilar::create([
            'RenstraID' => $request->renstra_id,
            'nama' => $request->nama,
            'NA' => $request->na,
            'DCreated' => Carbon::now(),
            'UCreated' => Auth::id(),
        ]);
        broadcast(new PilarCreated($pilar));
        $this->sendEmailNotification($pilar);
        return redirect()->back()->with('success', 'Pilar berhasil ditambahkan!');
    }


    private function sendEmailNotification($pilar)
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

            // untuk cek error 
            $mail->SMTPDebug  = 0; 
            $mail->Debugoutput = 'error_log';

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'Laravel App'));
            $mail->addAddress(('ilham.mubara99@gmail.com'), 'user');

            $mail->isHTML(true);
            $mail->Subject = 'Pilar Baru Dibuat!';
            $mail->Body    = '
                <h3>Renstra Baru Telah Dibuat</h3>
                <p><strong>Rencana Strategi:</strong> ' . $pilar->renstras->Nama . '</p>
                <p><strong>Nama Pilar :</strong> ' . $pilar->nama . '</p>
            ';
            $mail->send();
        } catch (Exception $e) {
            Log::error("Email gagal dikirim: {$mail->ErrorInfo}");
        }
    }

    public function destroy($id)
    {
        $pilar = Pilar::find($id);
        $pilar->delete();
        return redirect()->route('pilar.index')->with(['success' => 'Data Berhasil Di Hapus']);
    }
}