@extends('layouts.main')
@section('isi')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data "{{ $renstra->Nama}} oleh {{ $renstra->users->name }}"</h1>
    <!-- Notifikasi Toast -->
    <div aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 1rem; right: 1rem; z-index: 9999;">
        <div class="toast" id="renstraToast" data-delay="5000">
            <div class="toast-header bg-success text-white">
                <strong class="mr-auto">Renstra Baru</strong>
                <small>Baru saja</small>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">&times;</button>
            </div>
            <div class="toast-body" id="toastBody">
                <!-- Isi notifikasi akan muncul di sini -->
            </div>
        </div>
    </div>
    <!-- DataTable -->
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Periode Mulai</th>
                    <th>Periode Selesai</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Periode Mulai</th>
                    <th>Periode Selesai</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </tfoot>
            <tbody>
                @php($no=1)
                <tr>
                    <td style="{{ $renstra->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $no++ }}</td>
                    <td style="{{ $renstra->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $renstra->Nama }}</td>
                    <td style="{{ $renstra->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $renstra->PeriodeMulai }}</td>
                    <td style="{{ $renstra->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $renstra->PeriodeSelesai }}</td>
                    <td class="text-center" style="{{ $renstra->NA === 'N' ? 'background-color: #d4edda;' : '' }}">
                        @if($renstra->NA === 'N')
                            <span class="badge badge-success">Aktif</span>
                        @elseif($renstra->NA == 'Y')
                            <span class="badge badge-danger">Tidak Aktif</span>
                        @endif
                    </td>
                    <td style="{{ $renstra->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $renstra->DCreated }}</td>
                    <td class="text-center" style="{{ $renstra->NA === 'N' ? 'background-color: #d4edda;' : '' }}">
                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('renstra.destroy', $renstra->RenstraID) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection