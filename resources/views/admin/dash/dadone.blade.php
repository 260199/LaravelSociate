@extends('layouts.main')
@section('isi')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Log Activity(Selesai)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="color:black;">
                    <thead>
                        <tr style="text-align: center;">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kegiatan</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Tgl Dibuat</th>
                            <th>Bukti</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr style="text-align: center;">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kegiatan</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Tgl Dibuat</th>
                            <th>Bukti</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @php
                            $no=1;
                        @endphp
                        @foreach ($daily as $item)
                            <tr>
                                <td  style="text-align:center;">{{ $no++ }}</td>
                                <td>{{ $item->user->name ?? 'N/A' }} <br>
                                    @if($item->status == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-warning">Sedang Proses</span>
                                    @endif
                                </td>
                                <td>{{ $item->kegiatan }}</td>
                                <td>{{ $item->jenis }}</td>
                                <td style="text-align: center;">
                                    <button class="btn btn-secondary btn-sm" style="margin-left:5px;" data-toggle="modal" data-target="#modalDeskripsi{{ $item->id }}" title="Deskripsi">
                                        Deskripsi
                                    </button>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                                <td style="text-align: center;">
                                    @if($item->status == 'selesai')
                                        <a href="{{ route('actv.detail', $item->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Selesai pada: {{ \Carbon\Carbon::parse($item->done_at)->translatedFormat('d F Y') }}">Detail</a>
                                    @else
                                        -
                                    @endif
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
                                        <img
                                            src="{{ asset('storage/' . $file->image_path) }}"
                                            alt="Bukti"
                                            class="img-thumbnail preview-uploaded-img"
                                            data-img="{{ asset('storage/' . $file->image_path) }}"
                                            data-desc="{{ $file->desc ?: 'Tanpa deskripsi' }}"
                                            style="max-width: 100px; margin: 5px; border:1px solid #ddd; padding:3px; cursor: pointer;">
                                    @endforeach
                                </div>
                                @endif
                                @php
                                    $doneAtValue = old('done_at') ?? ($item->done_at ? \Carbon\Carbon::parse($item->done_at)->format('Y-m-d') : '');
                                @endphp
                                <div class="form-group mt-3">
                                    <label for="done_at">Tanggal Selesai</label>
                                    <input type="date" name="done_at" id="done_at" class="form-control"
                                        min="{{ $item->created_at->format('Y-m-d') }}"
                                        max="{{ $item->created_at->copy()->addDays(3)->format('Y-m-d') }}"
                                        value="{{ $doneAtValue }}"
                                        {{ $item->status === 'selesai' ? 'readonly' : '' }}>
                                    <small class="text-muted">
                                        Maksimal 3 hari dari tanggal dibuat: {{ $item->created_at->copy()->addDays(3)->format('d-m-Y') }}
                                    </small>
                                </div>
                            
                                <div class="form-group">
                                    <label>Bukti Kegiatan</label>
                                    <div id="imageUploadContainer-{{ $item->id }}">
                                        <div class="input-group mb-2">
                                            <input type="file" name="images[]" class="form-control" accept="image/*">
                                            <input type="text" name="desc[]" class="form-control" placeholder="Deskripsi foto">
                                        </div>
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

                @foreach ($daily as $item)
                <!-- Modal Desc daily -->
                <div class="modal fade" id="modalDeskripsi{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditDailyLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditDailyLabel{{ $item->id }}">Deskripsi Kegiatan {{ $item->kegiatan }} :</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <p>{{ $item->deskripsi }}</p>
                                </div>
                            </div> <!-- modal-body -->
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
                <form action="{{ route('actv.store') }}" method="POST">
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
                            <button  type="submit" class="btn btn-primary">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Preview Gambar -->
        <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                <img id="modalImagePreview" src="" class="img-fluid w-100 mb-3"
                    style="max-height: 80vh; object-fit: contain; border-bottom: 1px solid #dee2e6;" alt="Preview">
                <p id="modalImageDesc" class="text-muted small mb-0"></p>
                </div>
                <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                </div>
            </div>
            </div>
        </div>
  
        <!-- Modal Send Report -->
        <div class="modal fade" id="modalSendReport" tabindex="-1">
            <div class="modal-dialog">
                <form id="sendReportForm" action="{{ route('actv.massReports') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ids" id="selectedDailyIds">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Laporan</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin melaporkan kegiatan yang dipilih?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Ya, Laporkan</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
@foreach ($daily as $item)
<script>
function addUploadField(id) {
    const container = document.getElementById('imageUploadContainer-' + id);
    
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group', 'mb-2');
    
    const inputFile = document.createElement('input');
    inputFile.type = 'file';
    inputFile.name = 'images[]';
    inputFile.classList.add('form-control');
    inputFile.accept = 'image/*';
    
    const inputDesc = document.createElement('input');
    inputDesc.type = 'text';
    inputDesc.name = 'desc[]';
    inputDesc.placeholder = 'Deskripsi foto';
    inputDesc.classList.add('form-control');
    
    inputGroup.appendChild(inputFile);
    inputGroup.appendChild(inputDesc);
    
    container.appendChild(inputGroup);
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
    const modalDesc = document.getElementById('modalImageDesc');

    document.querySelectorAll('.preview-uploaded-img').forEach(img => {
        img.addEventListener('click', function () {
            const imgSrc = this.getAttribute('data-img');
            const desc = this.getAttribute('data-desc');
            modalImg.src = imgSrc;
            modalDesc.textContent = desc;
            modal.show();
        });
    });
});

$(document).ready(function() {
        $('#modalEditDaily{{ $item->id }}').on('show.bs.modal', function (event) {
            console.log('Modal Edit akan muncul untuk ID ' + {{ $item->id }});
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.daily-checkbox');
    const sendReportBtn = document.getElementById('openMassReportModal');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const anyChecked = Array.from(checkboxes).some(box => box.checked);
            sendReportBtn.disabled = !anyChecked;
        });
    });

    // Untuk "Ceklis Semua"
    document.getElementById('checkAllEligible').addEventListener('change', function () {
        const checked = this.checked;
        checkboxes.forEach(cb => {
            if (!cb.disabled) cb.checked = checked;
        });
        sendReportBtn.disabled = !Array.from(checkboxes).some(box => box.checked);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('openMassReportModal').addEventListener('click', function () {
        const checkedIds = Array.from(document.querySelectorAll('.daily-checkbox:checked')).map(cb => cb.value);
        document.getElementById('selectedDailyIds').value = checkedIds.join(',');
    });
});


// validasi untuk tanda send report bisa jadi loading spinner
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
@endforeach
@endpush
@endsection