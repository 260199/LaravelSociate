@extends('layouts.main')

@section('isi')
<div class="container mt-5">
    <h2>Daily Activity (Demo)</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addDailyModal">+ Tambah Daily</button>

    <!-- Table list daily -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daily as $d)
            <tr>
                <td>{{ $d->kegiatan }}</td>
                <td>{{ $d->jenis }}</td>
                <td>{{ $d->deskripsi }}</td>
                <td>
                    <span class="badge {{ $d->status == 'selesai' ? 'badge-success' : 'badge-warning' }}">
                        {{ ucfirst($d->status) }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $d->id }}">Edit</button>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal{{ $d->id }}">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('daily.update', $d->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Daily: {{ $d->kegiatan }}</h5>
                                <button class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="done_at" class="form-control mb-3">

                                <label>Upload Bukti</label>
                                <div class="bukti-fields mb-2">
                                    <div class="input-group mb-2">
                                        <input type="file" name="bukti[]" class="form-control" accept="image/*">
                                        <button type="button" class="btn btn-danger btn-sm remove-bukti">Hapus</button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-success add-bukti-btn">+ Tambah Bukti</button>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                                <button class="btn btn-secondary" data-dismiss="modal" type="button">Tutup</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Daily -->
<div class="modal fade" id="addDailyModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('daily.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Daily</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="kegiatan" class="form-control mb-2" required>
                    <label>Jenis</label>
                    <input type="text" name="jenis" class="form-control mb-2" required>
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control mb-2" rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Simpan</button>
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-bukti-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const container = btn.previousElementSibling;
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group mb-2';
            inputGroup.innerHTML = `
                <input type="file" name="bukti[]" class="form-control">
                <button type="button" class="btn btn-danger btn-sm remove-bukti">Hapus</button>
            `;
            container.appendChild(inputGroup);
        });
    });

    document.body.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-bukti')) {
            e.target.closest('.input-group').remove();
        }
    });
});
</script>
@endsection
