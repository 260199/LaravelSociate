@extends('layouts.main')
@section('isi')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Log Activity</h6>
        </div>
        
        <div class="card-body">
            <div class="row align-items-end mb-3">
                <div class="col-auto">
                    <button class="btn btn-md btn-primary" data-toggle="modal" data-target="#createModal"  data-bs-toggle="tooltip"
                            title="Tambah Jenis Kegiatan">
                        Tambah Jenis Kegiatan
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="color:black;">
                    <thead>
                        <tr style="text-align: center;">
                            <th>#</th>
                            <th>Jenis Kegiatan</th>
                            <th>Oleh</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($jekegs as $item)
                            <tr>
                                <td style="text-align: center;">{{ $no++ }}</td>
                                <td>{{ $item->kegiatan }}</td>
                                <td>{{ $item->user ? $item->user->name : 'Tidak Ditemukan' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->DCreated)->translatedFormat('l,d F Y') }}</td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        @php
                                            $batasWaktu = \Carbon\Carbon::parse($item->created_at)->addDays(3);
                                        @endphp
                                
                                        {{-- Tombol Edit --}}
                                        <button class="btn btn-warning btn-sm ml-2 mr-2" 
                                                title="Edit" 
                                                data-toggle="modal" 
                                                data-target="#modalEditDaily{{ $item->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('jekegs.destroy', $item->id) }}" method="POST" class="d-inline-block" id="deleteForm{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" title="Hapus"
                                                    data-toggle="modal" data-target="#confirmDeleteModal"
                                                    onclick="setDeleteAction('{{ route('jekegs.destroy', $item->id) }}', {{ $item->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                
                                        {{-- Status Expired --}}
                                        @if($item->status == 'progress' && now()->greaterThan($batasWaktu))
                                            <span class="badge badge-secondary">Expired</span>
                                        @endif
                                    </div>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('jekegs.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kegiatan</h5>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="kegiatan" class="form-control" placeholder="Nama kegiatan" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
            </div>
        </div>

        <!-- Modal Edit -->
        @foreach ($jekegs as $item)
        <div class="modal fade" id="modalEditDaily{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditDailyLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('jekegs.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditDailyLabel{{ $item->id }}">Edit Kegiatan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="kegiatan">Nama Kegiatan</label>
                                <input type="text" name="kegiatan" class="form-control" value="{{ $item->kegiatan }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach

        <!-- Modal Konfirmasi Hapus -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Penghapusan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus data ini? Proses ini tidak dapat dibatalkan.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="deleteButton" onclick="submitDeleteForm()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Menyimpan URL untuk menghapus
    let deleteActionUrl = '';
    let deleteForm = null;

    function setDeleteAction(actionUrl, itemId) {
        // Menyimpan URL untuk form penghapusan
        deleteActionUrl = actionUrl;
        deleteForm = document.getElementById('deleteForm' + itemId); // Menyimpan form yang terkait
    }

    function submitDeleteForm() {
        if (deleteForm && deleteActionUrl) {
            // Update action URL dan menampilkan spinner
            deleteForm.action = deleteActionUrl;

            let deleteBtn = document.getElementById('deleteButton');
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Sedang diproses...
            `;

            // Kirim form penghapusan
            deleteForm.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const allForms = document.querySelectorAll('form');

        allForms.forEach(form => {
            form.addEventListener('submit', function () {
                const submitBtn = form.querySelector('button[type="submit"]');

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Sedang diproses...
                    `;
                }
            });
        });
    });
</script>

@endsection
