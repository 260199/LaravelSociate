@extends('layouts.main')

@section('isi')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tables</h1>

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

    <!-- Add Renstra Button -->
    <button class="btn btn-md btn-success mb-3" data-toggle="modal" data-target="#modalRenstra">
        TAMBAH Renstra
    </button>

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
                @forelse ($renstra as $row)
                <tr>
                    <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $no++ }}</td>
                    <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $row->Nama }}</td>
                    <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $row->PeriodeMulai }}</td>
                    <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $row->PeriodeSelesai }}</td>
                    <td class="text-center" style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">
                        @if($row->NA === 'N')
                            <span class="badge badge-success">Aktif</span>
                        @elseif($row->NA == 'Y')
                            <span class="badge badge-danger">Tidak Aktif</span>
                        @endif
                    </td>
                    <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $row->DCreated }}</td>
                    <td class="text-center" style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">
                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('renstra.destroy', $row->RenstraID) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Renstra -->
    <div class="modal fade" id="modalRenstra" tabindex="-1" role="dialog" aria-labelledby="modalRenstraLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('renstra.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRenstraLabel">Tambah Renstra</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Periode Mulai</label>
                            <input type="number" name="periodemulai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Periode Selesai</label>
                            <input type="number" name="periodeselesai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="na" class="form-control" required>
                                <option value="N">Aktif</option>
                                <option value="Y">Non Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection