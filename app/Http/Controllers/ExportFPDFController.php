<?php

namespace App\Http\Controllers;
use App\Models\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class ExportFPDFController extends Controller
{
    protected $fpdf;

    public function __construct(Fpdf $fpdf)
    {
        $this->fpdf = $fpdf;
    }

    public function pdf()
    {
        $users = User::all();

        $this->fpdf->AddPage();
        $this->fpdf->SetFont('Arial', 'B', 14);
        $this->fpdf->Cell(0, 10, 'Data User', 0, 1, 'C');
        $this->fpdf->Ln(5);

        $this->fpdf->SetFont('Arial', 'B', 10);
        $this->fpdf->Cell(10, 10, 'No', 1);
        $this->fpdf->Cell(35, 10, 'Name', 1);
        $this->fpdf->Cell(30, 10, 'Username', 1);
        $this->fpdf->Cell(25, 10, 'Role', 1);
        $this->fpdf->Cell(50, 10, 'Email', 1);
        $this->fpdf->Ln();

        $this->fpdf->SetFont('Arial', '', 10);
        $no = 1;
        foreach ($users as $user) {
            $this->fpdf->Cell(10, 8, $no++, 1);
            $this->fpdf->Cell(35, 8, $user->name, 1);
            $this->fpdf->Cell(30, 8, $user->username, 1);
            $this->fpdf->Cell(25, 8, $user->role, 1);
            $this->fpdf->Cell(50, 8, $user->email, 1);
            $this->fpdf->Ln();
        }

        $this->fpdf->Output('I', 'data-user.pdf'); // 'I' untuk preview di tab baru
        exit;
    }
}
