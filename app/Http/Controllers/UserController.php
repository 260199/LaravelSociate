<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{
    public function index(){
        $users2 = User::all();
        $users = $users2->sortByDesc('created_at');
        return view('admin.user.index')->with([
            'user' => Auth::user(),
            'users' => $users,
        ]);
    }

    public function create(){
        return view('admin.user.create')->with([
            'user' => Auth::user(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'role' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed', // ← validasi konfirmasi
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $profilePath = '/default/undraw_profile.svg';

        if ($request->hasFile('profile')) {
            $profilePath = $request->file('profile')->store('profiles', 'public');
            $profilePath = '/storage/' . $profilePath;
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile' => $profilePath,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        Storage::delete($user->profile);
        $user->delete();
        return redirect()->route('user.index')->with(['success' => 'Data Berhasil Di Hapus']);
    }



        public function export()
        {
            $users = User::all();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getProperties()
                ->setCreator('ITP')
                ->setTitle('Data User Export')
                ->setSubject('Export Data')
                ->setDescription('Export data user ke file Excel')
                ->setCategory('Export File');

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Username');
            $sheet->setCellValue('D1', 'Role');
            $sheet->setCellValue('E1', 'Email');
            $sheet->setCellValue('F1', 'Profile');

            $row = 2;
            foreach ($users as $index => $user) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $user->name);
                $sheet->setCellValue('C' . $row, $user->username);
                $sheet->setCellValue('D' . $row, $user->role);
                $sheet->setCellValue('E' . $row, $user->email);
                $sheet->setCellValue('F' . $row, $user->profile);
                $row++;
            }

            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $fileName = 'user_export_' . now()->format('Ymd_His') . '.xlsx';
            $writer = new Xlsx($spreadsheet);
            $temp_file = storage_path('app/public/' . $fileName); // simpan ke folder storage/public
            $writer->save($temp_file);

            return response()->download($temp_file, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        }

        public function exportPdf()
{
    $users = User::all();

    $pdf = new \Codedge\Fpdf\Fpdf\Fpdf();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Daftar User', 0, 1, 'C');
    $pdf->Ln(5);

    // Header Table
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 10, 'No', 1);
    $pdf->Cell(40, 10, 'Name', 1);
    $pdf->Cell(30, 10, 'Username', 1);
    $pdf->Cell(30, 10, 'Role', 1);
    $pdf->Cell(60, 10, 'Email', 1);
    $pdf->Ln();

    // Isi Data
    $pdf->SetFont('Arial', '', 10);
    foreach ($users as $index => $user) {
        $pdf->Cell(10, 10, $index + 1, 1);
        $pdf->Cell(40, 10, $user->name, 1);
        $pdf->Cell(30, 10, $user->username, 1);
        $pdf->Cell(30, 10, $user->role, 1);
        $pdf->Cell(60, 10, $user->email, 1);
        $pdf->Ln();
    }

    $pdf->Output('I', 'daftar_user.pdf'); // I = preview di tab baru, D = langsung download
    exit;
}

public function exportPdf2()
{
    $users = User::all();

    $pdf = new \Codedge\Fpdf\Fpdf\Fpdf();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Daftar User (Export)', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 10, 'No', 1);
    $pdf->Cell(40, 10, 'Name', 1);
    $pdf->Cell(30, 10, 'Username', 1);
    $pdf->Cell(30, 10, 'Role', 1);
    $pdf->Cell(60, 10, 'Email', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    foreach ($users as $index => $user) {
        $pdf->Cell(10, 10, $index + 1, 1);
        $pdf->Cell(40, 10, $user->name, 1);
        $pdf->Cell(30, 10, $user->username, 1);
        $pdf->Cell(30, 10, $user->role, 1);
        $pdf->Cell(60, 10, $user->email, 1);
        $pdf->Ln();
    }

    // Langsung download dengan nama file khusus export
    $pdf->Output('D', 'export-user-data.pdf');
    exit;
}


}
