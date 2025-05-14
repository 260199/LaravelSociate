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
            <button class="btn btn-md btn-success mb-3" data-toggle="modal" data-target="#modalTambahIsu">
                TAMBAH Isu Strategis
            </button>
             <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Isu Strategis</th>
                                <th>Pilar</th>
                                <th>Renstra</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($isustrategis as $i => $isu)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $isu->nama }}</td>
                                <td>{{ $isu->pilar->nama }}</td>
                                <td>{{ $isu->pilar->renstra->Nama ?? '-' }}</td>


                                <td>
                                    @if($isu->NA == 'N')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>{{ $isu->DCreated }}</td>
                                <td>
                                    <form action="{{ route('isustrategis.destroy', $isu->IsuID) }}" method="POST" onsubmit="return confirm('Hapus data?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

               <!-- Modal Tambah Isu Strategis -->
<div class="modal fade" id="modalTambahIsu" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('isustrategis.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Isu Strategis</h5>
                    <button class="btn-close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilar</label>
                        <select name="PilarID" class="form-control" required>
                            <option value="">-- Pilih Pilar --</option>
                            @foreach($pilar as $p)
                                <option value="{{ $p->PilarID }}">{{ $p->nama }} - ({{ $p->renstra->Nama ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nama Isu</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="NA" class="form-control" required>
                            <option value="N">Aktif</option>
                            <option value="Y">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


            </div>
        </div>
    </div>
</div>
@endsection
