@extends('layouts.main')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
        <!-- Notifikasi Toast -->
        <div aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 1rem; right: 1rem; z-index: 9999;">
            <div class="toast" id="pilarToast" data-delay="5000">
                <div class="toast-header bg-success text-white">
                    <strong class="mr-auto">Pilar Baru</strong>
                    <small>Baru saja</small>
                    <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">&times;</button>
                </div>
                <div class="toast-body" id="toastBody">
                    <!-- Isi notifikasi akan muncul di sini -->
                </div>
            </div>
        </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div>
        <div class="card-body">
        <button class="btn btn-md btn-success mb-3" data-toggle="modal" data-target="#modalPilar">
            TAMBAH Pilar
        </button>
             <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>PilarID</th>
                            <th>RenstraID</th>
                            <th>Nama</th>
                            <th>Periode Mulai</th>
                            <th>Periode Selesai</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>PilarID</th>
                            <th>RenstraID</th>
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
                        @forelse ($pilar as $row)
                        <tr>
                            <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $no++ }}</td>
                            <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $row->PilarID }}</td>
                            <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $row->RenstraID }}</td>
                            <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $row->nama}}</td>
                            <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $row->renstras->PeriodeMulai}}</td>
                            <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $row->renstras->PeriodeSelesai}}</td>
                            <td class="text-center" style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">
                                @if($row->NA === 'N')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($row->NA == 'Y')
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">{{ $row->DCreated}}</td>
                            <td class="text-center" style="{{ $row->NA === 'N' ? 'background-color: #d4edda;' : '' }}">
                                <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('pilar.destroy', $row->PilarID) }}" method="POST">
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
                <div class="modal fade" id="modalPilar" tabindex="-1" role="dialog" aria-labelledby="modalPilarLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="{{ route('pilar.store') }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalPilarLabel">Tambah Pilar</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Renstra -->
                                    <div class="form-group">
                                        <label>Renstra</label>
                                        <select name="renstra_id" class="form-control" required>
                                            @foreach($renstra as $r)
                                                <option value="{{ $r->RenstraID }}">{{ $r->Nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Nama Pilar -->
                                    <div class="form-group">
                                        <label>Nama Pilar</label>
                                        <input type="text" name="nama" class="form-control" required>
                                    </div>

                                    <!-- Status -->
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="na" class="form-control" required>
                                            <option value="N">Aktif</option>
                                            <option value="Y">Tidak Aktif</option>
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
<!-- Pusher dan Echo CDN -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Konfigurasi Echo
    const echo = new Echo({
        broadcaster: 'pusher',
        key: '0e6943fcf6b465eff50f', // Ganti dengan Pusher app key kamu
        cluster: 'ap1', // Ganti dengan cluster kamu
        forceTLS: true
    });

    // Dengarkan channel dan event
    echo.channel('pilar-channel')
        .listen('.pilar.created', (e) => {
            console.log("Notifikasi masuk 🚀", e);

            // Menampilkan pesan pada toast
            const toastBody = document.getElementById('toastBody');
            toastBody.innerHTML = 'pilar baru ditambahkan: ' + e.pilar.nama;

            // Menampilkan toast
            const pilarToast = document.getElementById('pilarToast');
            const bootstrapToast = new bootstrap.Toast(pilarToast);
            bootstrapToast.show();
        });

    // Menampilkan notifikasi toast jika ada session 'success'
    @if(session('success'))
        window.onload = function() {
            const toastBody = document.getElementById('toastBody');
            toastBody.innerHTML = '{{ session('success') }}';
            const pilarToast = document.getElementById('pilarToast');
            const bootstrapToast = new bootstrap.Toast(renstraToast);
            bootstrapToast.show();
        };
    @endif
</script>
@endsection
