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
            <h6 class="m-0 font-weight-bold text-primary">Prog. Pengembangan</h6>
        </div>
        <div class="card-body">
        <button class="btn btn-md btn-success mb-3" data-toggle="modal" data-target="#modalRenstra">
            ADD Prog Pengembangan
        </button>
             <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Program Pengembangan</th>
                            <th>Nama Isu Strategis</th>
                            <th>Pilar</th>
                            <th>Renstra</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programs as $i => $program)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $program->nama }}</td>
                            <td>{{ $program->isuStrategis->nama }}</td> <!-- Memanggil nama dari IsuStrategis -->
                            <td>{{ $program->isuStrategis->pilar->nama }}</td> <!-- Memanggil nama Pilar -->
                            <td>{{ $program->isuStrategis->pilar->renstra->Nama ?? '-' }}</td> <!-- Memanggil nama Renstra -->
                            <td>
                                @if($program->NA == 'N')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>{{ $program->DCreated }}</td>
                            <td>
                                <form action="{{ route('progpeng.destroy', $program->ProgramPengembanganID) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Hapus</button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

<!-- Modal -->
<div class="modal fade" id="modalRenstra" tabindex="-1" role="dialog" aria-labelledby="modalRenstraLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('progpeng.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRenstraLabel">Tambah Program Pengembangan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Nama Program Pengembangan -->
                    <div class="form-group">
                        <label>Nama Program Pengembangan</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <!-- Isu Strategis -->
                    <div class="form-group">
                        <label>Isu Strategis</label>
                        <select name="IsuID" class="form-control" required>
                            <option value="">Pilih Isu Strategis</option>
                            @foreach($isu as $item)
                            <option value="{{ $item->IsuID }}"> {{ $item->pilar->nama }} | {{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label>Status</label>
                        <select name="NA" class="form-control" required>
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
        </div>
    </div>
</div>
@endsection
