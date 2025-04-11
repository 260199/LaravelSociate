@extends('layouts.main')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
        For more information about DataTables, please visit the <a target="_blank"
            href="https://datatables.net">official DataTables documentation</a>.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div>
        <div class="card-body">
            <a href="{{ route('user.create') }}" class="btn btn-md btn-success mb-3">TAMBAH USER</a>
            <a href="{{ route('user.export') }}" class="btn btn-md btn-primary mb-3">EXPORT TO EXCEL</a>
            {{-- PDF Dengan fpdf --}}
            {{-- <a href="{{ route('user.export.pdf') }}" class="btn btn-md btn-danger mb-3">EXPORT PDF</a> --}}
            {{-- fpdf --}}
            <a href="{{ route('user.pdf') }}" target="_blank" class="btn btn-md btn-danger mb-3">TO PDF</a>
            {{-- Export FPDF --}}
            {{-- <a href="{{ route('user.export.pdf') }}" class="btn btn-success">Export PDF</a> --}}

             <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Profile</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Profile</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @php($no=1)
                        @forelse ($users as $row)
                        <tr>
                            <td>1</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->username }}</td>
                            <td>{{ $row->role }}</td>
                            <td>{{ $row->email }}</td>
                            <td> <img class="img-profile rounded-circle" src="{{$user->profile}}"></td>

                            <td class="text-center">
                                <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('user.destroy', $row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
