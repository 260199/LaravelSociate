@extends('layouts.main')
@section('isi')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
        For more information about DataTables, please visit the <a target="_blank"
            href="https://datatables.net">official DataTables documentation</a>.</p>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div>
        <div class="card-body">
            <button class="btn btn-md btn-success mb-3" data-toggle="modal" data-target="#modalTambahDaily">
                Tambah Daily
            </button>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kegiatan</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Tgl Dibuat</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kegiatan</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Tgl Dibuat</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @php
                            $no=1;
                        @endphp
                        @foreach ($daily as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->user->name ?? 'N/A' }} <br>
                                    @if($item->status == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-warning">Sedang Proses</span>
                                    @endif
                                </td>
                                <td>{{ $item->kegiatan }}</td>
                                <td>{{ $item->jenis }}</td>
                                <td>{{ $item->deskripsi }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td style="text-align: center;">
                                    @if($item->status == 'selesai')
                                        <a href="{{ route('daily.detail', $item->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Selesai pada: {{ $item->done_at }}">Detail</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @php
                                         $batasWaktu = \Carbon\Carbon::parse($item->created_at)->addDays(3);
                                        @endphp
                                        @if($item->status != 'selesai' && now()->lessThanOrEqualTo($batasWaktu))
                                        <button class="btn btn-warning btn-sm" style="margin-left:5px;" data-toggle="modal" data-target="#modalEditDaily{{ $item->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @elseif($item->status == 'selesai')
                                        <button class="btn btn-warning btn-sm" style="margin-left:5px;" data-toggle="modal" data-target="#modalEditDaily{{ $item->id }}" title="Edit" disabled>
                                            <i class="fas fa-edit"></i>
                                        </button>                                        
                                        @endif
                                    
                                        <form action="{{ route('daily.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" style="margin-left:5px;" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    
                                        @php
                                            $batasWaktu = \Carbon\Carbon::parse($item->created_at)->addDays(3);
                                        @endphp
                                    
                                        @if($item->status != 'selesai' && now()->lessThanOrEqualTo($batasWaktu))
                                            {{-- <button class="btn btn-primary btn-sm" style="margin-left:5px;" data-toggle="modal" data-target="#laporModal{{ $item->id }}" title="Laporkan">
                                                <i class="fas fa-paper-plane"></i>
                                            </button> --}}
                                        @elseif($item->status != 'selesai')
                                            <span class="badge badge-secondary">Expired</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @foreach ($daily as $item)
<!-- Modal Edit -->
<div class="modal fade" id="modalEditDaily{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditDailyLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('actv.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditDailyLabel{{ $item->id }}">Edit Kegiatan Harian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kegiatan{{ $item->id }}">Nama Kegiatan</label>
                        <input type="text" id="kegiatan{{ $item->id }}" name="kegiatan" class="form-control" value="{{ $item->kegiatan }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis{{ $item->id }}">Jenis Kegiatan</label>
                        <input type="text" id="jenis{{ $item->id }}" name="jenis" class="form-control" value="{{ $item->jenis }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi{{ $item->id }}">Deskripsi</label>
                        <textarea id="deskripsi{{ $item->id }}" name="deskripsi" class="form-control" rows="3">{{ $item->deskripsi }}</textarea>
                    </div>
                    @if($item->filedailies->count())
                    <div class="mb-3">
                        <label><strong>Bukti yang sudah diupload</strong></label><br>
                        @foreach($item->filedailies as $file)
                            <img src="{{ asset('storage/' . $file->image_path) }}"
                                 alt="Bukti"
                                 class="img-thumbnail preview-uploaded-img"
                                 data-img="{{ asset('storage/' . $file->image_path) }}"
                                 style="max-width: 100px; margin: 5px; border:1px solid #ddd; padding:3px; cursor: pointer;">
                        @endforeach
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Bukti Kegiatan</label>
                        <div id="imageUploadContainer-{{ $item->id }}">
                            <input type="file" name="images[]" class="form-control mb-2" accept="image/*">
                        </div>
                        <button type="button" class="btn btn-sm btn-info" onclick="addUploadField({{ $item->id }})">
                            + Tambah Foto
                        </button>
                    </div>
                </div> <!-- modal-body -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div> <!-- modal-content -->
        </form>
    </div> <!-- modal-dialog -->
</div> <!-- modal -->
@endforeach

            </div>
        </div>
          <!-- Tambah -->
        <div class="modal fade" id="modalTambahDaily" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('daily.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Kegiatan Harian</h5>
                            <button class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Nama Kegiatan</label>
                                <input type="text" name="kegiatan" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Jenis Kegiatan</label>
                                <input type="text" name="jenis" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="imagePreviewModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img id="modalImagePreview" src="" style="max-width: 100%; height: auto;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
@foreach ($daily as $item)
<script>
function addUploadField(id) {
    const container = document.getElementById('imageUploadContainer-' + id);
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'images[]'; // <- sudah benar
    input.classList.add('form-control', 'mb-2');
    input.accept = 'image/*';
    container.appendChild(input);
}
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});


document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });
});

document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        const modalImg = document.getElementById('modalImagePreview');

        document.querySelectorAll('.preview-uploaded-img').forEach(img => {
            img.addEventListener('click', function () {
                const imgSrc = this.getAttribute('data-img');
                modalImg.src = imgSrc;
                modal.show();
            });
        });
    });


$(document).ready(function() {
        $('#modalEditDaily{{ $item->id }}').on('show.bs.modal', function (event) {
            console.log('Modal Edit akan muncul untuk ID ' + {{ $item->id }});
        });
    });
</script>
@endforeach
@endpush
@endsection