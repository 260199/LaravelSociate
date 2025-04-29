<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


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

    private function adminDashboard()
    {
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notif = Notification::all()->sortByDesc('created_at');
        $notiff = $belom->count();
        $notifications = $notif->sortByDesc('created_at');
        $notclears = Daily::where('status', 'progress')->get();
        $notclear = $notclears->count();
        $dones = Daily::where('status', 'selesai')->get();
        $done = $dones->count();
        $allduty = Daily::all()->count();

        // Ambil data semua user
        $users = User::all();
        $usercount = $users->count();
        $dailyusers = Daily::where('user_id', Auth::id())->get();
        $dailyuser = $dailyusers->count();
        $countusernotyets = $dailyusers->where('status', 'progress')->count();
        $countuserdone = $dailyusers->where('status', 'selesai')->count();

        // Ambil data daily per user (status progress dan selesai)
        $userStats = $users->map(function($user) {
            $progressCount = Daily::where('user_id', $user->id)->where('status', 'progress')->count();
            $selesaiCount = Daily::where('user_id', $user->id)->where('status', 'selesai')->count();
            $totalCount = Daily::where('user_id', $user->id)->count();
            return [
                'name' => $user->name,
                'progress' => $progressCount,
                'selesai' => $selesaiCount,
                'total' => $totalCount,
            ];
        });

        $totalProgress = $userStats->sum('progress');
        $totalSelesai = $userStats->sum('selesai');

        return view('layouts.home_admin')->with([
            'user' => Auth::user(),
            'notifications' => $notifications,
            'notiff' => $notiff,
            'notclear' => $notclear,
            'done' => $done,
            'userStats' => $userStats,
            'totalProgress' => $totalProgress,
            'totalSelesai' => $totalSelesai,
            'dailyuser' => $dailyuser,
            'usercount' => $usercount,
            'countusernotyets' => $countusernotyets,
            'countuserdone' => $countuserdone,
            'allduty' => $allduty
        ]);
    }

    private function userDashboard()
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
    
        $selesaiCount = Daily::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->count();
    
        $totalCount = Daily::where('user_id', $user->id)->count();
    
        return view('layouts.home_user')->with([
            'user' => $user,
            'notifications' => $notifications,
            'notiff' => $notiff,
            'progressCount' => $progressCount,
            'selesaiCount' => $selesaiCount,
            'totalCount' => $totalCount,
        ]);
    }
    
    
    public function filterDaily(Request $request)
    {
        $type = $request->input('type');
        $user = Auth::user(); 
        $query = Daily::where('user_id', $user->id); 
        
        if ($type === 'daily') {
            $date = Carbon::parse($request->input('date'));
            $query->whereDate('created_at', $date);
        } elseif ($type === 'weekly') {
            $start = Carbon::parse($request->input('start'))->startOfDay();
            $end = Carbon::parse($request->input('end'))->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($type === 'monthly') {
            $monthInput = $request->input('month'); 
            $date = Carbon::createFromFormat('Y-m', $monthInput);
            $query->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year);
        }

        $filtered = $query->get();

        return response()->json([
            'progress' => $filtered->where('status', 'progress')->count(),
            'selesai' => $filtered->where('status', 'selesai')->count(),
            'total' => $filtered->count()
        ]);
    }
    
    public function filterAdmin(Request $request)
    {
        $type = $request->input('type');
        $users = User::all();  
        $userStats = [];


        $query = Daily::query();

       
        if ($type === 'daily') {
            $date = Carbon::parse($request->input('date'));
            $query->whereDate('created_at', $date);
        } elseif ($type === 'weekly') {
            $start = Carbon::parse($request->input('start'))->startOfDay();
            $end = Carbon::parse($request->input('end'))->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($type === 'monthly') {
            $monthInput = $request->input('month'); // format yyyy-mm
            $date = Carbon::createFromFormat('Y-m', $monthInput);
            $query->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year);
        }

       
        $allData = $query->get(); 

       
        foreach ($users as $user) {
           
            $filtered = $allData->where('user_id', $user->id);
            $userStats[$user->name] = [
                'progress' => $filtered->where('status', 'progress')->count(),
                'selesai' => $filtered->where('status', 'selesai')->count(),
            ];
        }

        return response()->json($userStats);
    }

    public function markAsRead($id)
    {
        $notif = Notification::findOrFail($id);
        $notif->read = true;
        $notif->save();
        return redirect()->back();
    }
   


    
    public function getChartData(Request $request)
    {
        $filter = $request->query('filter');
        $user = Auth::user();
    
        $start = null;
        $end = null;
    
        if ($filter === 'daily') {
            $date = $request->query('date');
            $start = Carbon::parse($date)->startOfDay();
            $end = Carbon::parse($date)->endOfDay();
        } elseif ($filter === 'weekly') {
            $start = Carbon::parse($request->query('start'))->startOfDay();
            $end = Carbon::parse($request->query('end'))->endOfDay();
        } elseif ($filter === 'monthly') {
            $month = $request->query('month');
            $start = Carbon::parse($month)->startOfMonth();
            $end = Carbon::parse($month)->endOfMonth();
        }
    
        $labels = collect();
        $progressCounts = [];
        $doneCounts = [];
    
        if ($user->role == 1) {
            $users = User::all();
            $labels = $users->pluck('name');
    
            foreach ($users as $u) {
                $progress = Daily::where('user_id', $u->id)
                    ->where('status', 'progress')
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
                $done = Daily::where('user_id', $u->id)
                    ->where('status', 'selesai')
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
                $progressCounts[] = $progress;
                $doneCounts[] = $done;
            }
        } else {
            $labels = collect([$user->name]);

            $progressCounts[] = Daily::where('user_id', $user->id)
                ->where('status', 'progress')
                ->whereBetween('created_at', [$start, $end])
                ->count();
            $doneCounts[] = Daily::where('user_id', $user->id)
                ->where('status', 'selesai')
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }
    
        return response()->json([
            'labels' => $labels,
            'progress' => $progressCounts,
            'done' => $doneCounts,
        ]);
    }
    

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
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profile_picture')) {
            $userName = preg_replace('/[^A-Za-z0-9\-]/', '_', $user->name);

            $path = $request->file('profile_picture')->storeAs(
                'profile/' . $userName,
                time().'_'.$request->file('profile_picture')->getClientOriginalName(),
                'public'
            );

            $user->profile = 'storage/' . $path; // <--- fix ini: yang dipakai di blade kan $user->profile
        }
        $user->save();
        return back()->with('success', 'Profile berhasil diperbarui.');
    }
}