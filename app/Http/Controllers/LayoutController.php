<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\jekeg;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LayoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 1) {
            return $this->adminDashboard();
        } elseif ($user->role == 2) {
            return $this->userDashboard();
        }
    }

    public function adminDashboard()
    {
        // Ambil data notifikasi
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notif = Notification::all()->sortByDesc('created_at');
        $notiff = $belom->count();
        $notifications = $notif->sortByDesc('created_at');
        
        // Ambil data daily
        $notclears = Daily::where('status', 'progress')->get();
        $notclear = $notclears->count();
        $dones = Daily::where('status', 'diterima')->get();
        $done = $dones->count();
        $allduty = Daily::all()->count();
        $jekegs = jekeg::all()->count();
        
        // Ambil data user
        $users = User::all();
        $usercount = $users->count();
        $dailyusers = Daily::where('user_id', Auth::id())->get();
        $dailyuser = $dailyusers->count();
        $countusernotyets = $dailyusers->where('status', 'progress')->count();
        $countuserdone = $dailyusers->where('status', 'diterima')->count();
    
        // Ambil data daily per user (status progress, dilaporkan, diterima)
        $userStats = $users->map(function($user) {
            return [
                'name' => $user->name,
                'progress' => Daily::where('user_id', $user->id)->where('status', 'progress')->count(),
                'dilaporkan' => Daily::where('user_id', $user->id)->where('status', 'dilaporkan')->count(),
                'diterima' => Daily::where('user_id', $user->id)->where('status', 'diterima')->count(),
                'ditolak' => Daily::where('user_id', $user->id)->where('status', 'ditolak')->count(),
                'total' => Daily::where('user_id', $user->id)->count(),
            ];
        });
    
        // Total Daily, Progress, Dilaporkan, Diterima
        $totalProgress = $userStats->sum('progress');
        $totalDilaporkan = $userStats->sum('dilaporkan');
        $totalDiterima = $userStats->sum('diterima');
        $totalDaily = $userStats->sum('total');
        $totalDitolak = $userStats->sum('ditolak');
    
        // Format Tanggal dalam Bahasa Indonesia
        $today = Carbon::now()->isoFormat('D MMMM YYYY'); // Tanggal hari ini dalam format Bahasa Indonesia
    
        return view('layouts.home_admin')->with([
            'user' => Auth::user(),
            'notifications' => $notifications,
            'notiff' => $notiff,
            'notclear' => $notclear,
            'done' => $done,
            'userStats' => $userStats,
            'totalProgress' => $totalProgress,
            'totalDilaporkan' => $totalDilaporkan,  // Tambahkan total dilaporkan
            'totalDiterima' => $totalDiterima,
            'totalDaily' => $totalDaily,  // Tambahkan total daily
            'totalDitolak' => $totalDitolak,
            'dailyuser' => $dailyuser,
            'usercount' => $usercount,
            'countusernotyets' => $countusernotyets,
            'countuserdone' => $countuserdone,
            'allduty' => $allduty,
            'today' => $today,  // Tanggal hari ini yang diformat
            'jekegs' => $jekegs
        ]);
    }
    
    
    
    public function filterAdmin(Request $request)
{
    $type = $request->input('type');
    $users = User::all();
    $userStats = [];
    
    $query = Daily::query();

    // Filter berdasarkan tipe
    if ($type === 'daily') {
        $date = Carbon::parse($request->input('date'));
        $query->whereDate('created_at', $date);
    } elseif ($type === 'weekly') {
        $start = Carbon::parse($request->input('start'))->startOfDay();
        $end = Carbon::parse($request->input('end'))->endOfDay();
        $query->whereBetween('created_at', [$start, $end])
              ->orWhereBetween('updated_at', [$start, $end]);
    } elseif ($type === 'monthly') {
        $monthInput = $request->input('month');
        $date = Carbon::createFromFormat('Y-m', $monthInput);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $query->where(function($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('created_at', [$startOfMonth, $endOfMonth])
              ->orWhereBetween('updated_at', [$startOfMonth, $endOfMonth]);
        });
    }

    $allData = $query->get();

    $totalProgress = 0;
    $totalDiterima = 0;
    $totalDilaporkan = 0;
    $totalDaily = 0;

    foreach ($users as $user) {
        $filtered = $allData->where('user_id', $user->id);
        $progress = $filtered->where('status', 'progress')->count();
        $diterima = $filtered->where('status', 'diterima')->count();
        $dilaporkan = $filtered->where('status', 'dilaporkan')->count();

        $userStats[$user->name] = [
            'progress' => $progress,
            'diterima' => $diterima,
            'dilaporkan' => $dilaporkan
        ];

        $totalProgress += $progress;
        $totalDiterima += $diterima;
        $totalDilaporkan += $dilaporkan;
        $totalDaily += $filtered->count();
    }

    // Format tanggal yang difilter, misalnya untuk tipe 'daily'
    if ($type === 'daily') {
        $formattedDate = Carbon::parse($request->input('date'))->isoFormat('D MMMM YYYY');
    } elseif ($type === 'weekly') {
        $startDate = Carbon::parse($request->input('start'))->isoFormat('D MMMM YYYY');
        $endDate = Carbon::parse($request->input('end'))->isoFormat('D MMMM YYYY');
        $formattedDate = "$startDate - $endDate";
    } elseif ($type === 'monthly') {
        $formattedMonth = Carbon::createFromFormat('Y-m', $request->input('month'))->isoFormat('MMMM YYYY');
        $formattedDate = $formattedMonth;
    }

    return response()->json([
        'userStats' => $userStats,
        'totalProgress' => $totalProgress,
        'totalDiterima' => $totalDiterima,
        'totalDilaporkan' => $totalDilaporkan,
        'totalDaily' => $totalDaily,
        'totalUsers' => count($userStats),
        'formattedDate' => $formattedDate, // Kirimkan tanggal yang diformat ke frontend
    ]);
}

    
    
    

    public function userDashboard()
    {
        $user = Auth::user();
        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('read', 0)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $notiff = $unreadNotifications->count();
    
        $progressCount = Daily::where('user_id', $user->id)
            ->where('status', 'progress')
            ->count();
    
        $dilaporkanCount = Daily::where('user_id', $user->id)
            ->where('status', 'dilaporkan')
            ->count();
    
        $diterimaCount = Daily::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->count();
    
        $totalCount = Daily::where('user_id', $user->id)->count();
    
        return view('layouts.home_user')->with([
            'user' => $user,
            'notifications' => $notifications,
            'notiff' => $notiff,
            'progressCount' => $progressCount,
            'dilaporkanCount' => $dilaporkanCount,
            'diterimaCount' => $diterimaCount,
            'totalCount' => $totalCount,
        ]);
    }
    
    public function filterDaily(Request $request)
    {
        $user = Auth::user();
        $type = $request->input('type');
        $date = $request->input('date');
        $start = $request->input('start');
        $end = $request->input('end');
        $month = $request->input('month');
    
        $baseQuery = Daily::where('user_id', $user->id);
    
        // Terapkan filter berdasarkan tipe
        if ($type === 'daily' && $date) {
            $baseQuery = $baseQuery->where(function ($q) use ($date) {
                $q->whereDate('created_at', $date)
                  ->orWhereDate('updated_at', $date);
            });
        } elseif ($type === 'weekly' && $start && $end) {
            $baseQuery = $baseQuery->where(function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->orWhereBetween('updated_at', [$start, $end]);
            });
        } elseif ($type === 'monthly' && $month) {
            // Ambil awal dan akhir bulan
            $startOfMonth = date("Y-m-01", strtotime($month));
            $endOfMonth = date("Y-m-t", strtotime($month));
    
            $baseQuery = $baseQuery->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                  ->orWhereBetween('updated_at', [$startOfMonth, $endOfMonth]);
            });
        }
    
        // Clone query agar filter tidak saling mengganggu
        $totalCount = (clone $baseQuery)->count();
        $progressCount = (clone $baseQuery)->where('status', 'progress')->count();
        $dilaporkanCount = (clone $baseQuery)->where('status', 'dilaporkan')->count();
        $diterimaCount = (clone $baseQuery)->where('status', 'diterima')->count();
    
        // Debug log
        Log::info('Filtered data:', [
            'type' => $type,
            'date' => $date,
            'start' => $start,
            'end' => $end,
            'month' => $month,
            'total' => $totalCount,
            'progress' => $progressCount,
            'dilaporkan' => $dilaporkanCount,
            'diterima' => $diterimaCount,
        ]);
    
        return response()->json([
            'total' => $totalCount,
            'progress' => $progressCount,
            'dilaporkan' => $dilaporkanCount,
            'diterima' => $diterimaCount,
        ]);
    }

    // public function markAsRead($id)
    // {
    //     $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
    //     $notiff = $belom->count();
    //     $notif = Notification::orderBy('created_at', 'desc')->get();
    //     $daily = Daily::all()->sortByDesc('created_at');      

    //     $notif = Notification::findOrFail($id);
    //     $notif->read = true;
    //     $notif->save();
       

    //     return view('admin.notifikasi.index')->with([
    //         'user' => Auth::user(),
    //         'notiff' => $notiff,
    //         'notifications' => $notif,
    //         'daily' => $daily,
    //     ]);
    // }


    public function markAsRead($id)
    {
        $notification = Notification::with('taggable')->findOrFail($id);
    
        // Tandai sebagai dibaca
        if (!$notification->read) {
            $notification->read = true;
            $notification->save();
        }
    
        // Redirect ke halaman detail notifikasi
        return redirect()->route('notifications.detail', $notification->id);
    }

    public function notificationDetail($id)
{
    $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
    $notiff = $belom->count();
    $notification = Notification::with(['taggable', 'taggable.user', 'taggable.filedailies'])->findOrFail($id);
    $notif = Notification::orderBy('created_at', 'desc')->get();
   

    return view('admin.notifikasi.index', [
        'notification' => $notification,
        'user' => Auth::user(),
        'notiff' => $notiff,
        'notifications' => $notif
    ]);
}

    

   


    
    // public function getChartData(Request $request)
    // {
    //     $filter = $request->query('filter');
    //     $user = Auth::user();
    
    //     $start = null;
    //     $end = null;
    
    //     if ($filter === 'daily') {
    //         $date = $request->query('date');
    //         $start = Carbon::parse($date)->startOfDay();
    //         $end = Carbon::parse($date)->endOfDay();
    //     } elseif ($filter === 'weekly') {
    //         $start = Carbon::parse($request->query('start'))->startOfDay();
    //         $end = Carbon::parse($request->query('end'))->endOfDay();
    //     } elseif ($filter === 'monthly') {
    //         $month = $request->query('month');
    //         $start = Carbon::parse($month)->startOfMonth();
    //         $end = Carbon::parse($month)->endOfMonth();
    //     }
    
    //     $labels = collect();
    //     $progressCounts = [];
    //     $doneCounts = [];
    
    //     if ($user->role == 1) {
    //         $users = User::all();
    //         $labels = $users->pluck('name');
    
    //         foreach ($users as $u) {
    //             $progress = Daily::where('user_id', $u->id)
    //                 ->where('status', 'progress')
    //                 ->whereBetween('created_at', [$start, $end])
    //                 ->count();
    //             $done = Daily::where('user_id', $u->id)
    //                 ->where('status', 'selesai')
    //                 ->whereBetween('created_at', [$start, $end])
    //                 ->count();
    //             $progressCounts[] = $progress;
    //             $doneCounts[] = $done;
    //         }
    //     } else {
    //         $labels = collect([$user->name]);

    //         $progressCounts[] = Daily::where('user_id', $user->id)
    //             ->where('status', 'progress')
    //             ->whereBetween('created_at', [$start, $end])
    //             ->count();
    //         $doneCounts[] = Daily::where('user_id', $user->id)
    //             ->where('status', 'selesai')
    //             ->whereBetween('created_at', [$start, $end])
    //             ->count();
    //     }
    
    //     return response()->json([
    //         'labels' => $labels,
    //         'progress' => $progressCounts,
    //         'done' => $doneCounts,
    //     ]);
    // }
    

    public function profile(){
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notif = Notification::all()->sortByDesc('created_at');
        $notiff = $belom->count();
        $notifications = $notif->sortByDesc('created_at');
        return view('layouts.profile')->with([
            'user'=> Auth::user(),
            'notifications' => $notifications,
            'notiff' => $notiff,
        ]);

    }


    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // cek
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama salah.');
        }

        if (Hash::check($request->new_password, $user->password)) {
            return back()->with('error', 'Password baru tidak boleh sama dengan password lama.');
        }

        $user->password = Hash::make($request->new_password);
        $user->is_password_set = true; 
        $user->save();
        return back()->with('success', 'Password berhasil diperbarui!');
    }
  
    
    public function updateProfile(Request $request)
{
    $user = Auth::user();
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return back()->with('success', 'Profile berhasil diperbarui.');
}

}