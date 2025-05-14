@extends('layouts.main')
@section('isi')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Log Aktifitas {{ $user->name }}</h6>
        </div>
        <div class="card-body">
            <div class="row align-items-end mb-3">
                <!-- Tombol Tambah Daily -->
                <div class="col-auto mb-2">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahDaily">
                        Tambah Aktivitas
                    </button>
                </div>

                <!-- Tombol Kirim Laporan -->
                <div class="col-auto mb-2">
                    <button type="button" class="btn btn-success btn-icon-split" id="openMassReportModal" data-toggle="modal" data-target="#modalSendReport" disabled>
                        <span class="icon text-white-50 d-flex align-items-center">
                            <input type="checkbox" id="checkAllEligible" data-bs-toggle="tooltip" title="Ceklis Seluruh Aktivitas" style="transform: scale(1.3); cursor: pointer;" />
                        </span>
                        <span class="text">Kirim Laporan!</span>
                    </button>
                </div>

                <!-- Form Filter Status -->
                <div class="col-12 col-md-4 position-relative mb-2">
                    <label for="filterStatus" style="position: absolute; top: -8px; left: 15px; background: white; padding: 0 5px; font-size: 12px; z-index: 1;">
                        Pilih Status
                    </label>
                    <select id="filterStatus" class="form-control">
                        <option value="">-- Pilih Status --</option>
                        <option value="progress">Sedang Proses</option>
                        <option value="dilaporkan">Dilaporkan</option>
                        <option value="diterima">Diterima</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="color:black;">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Tgl Dibuat</th>
                            <th>Status</th>
                            <th>Kegiatan</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($daily as $item)
                            @php
                                $buktiUploaded = $item->filedailies->count() > 0;
                                $canCheck = $item->status === 'progress' && $buktiUploaded;
                                $batasWaktu = \Carbon\Carbon::parse($item->created_at)->addDays(3);
                            @endphp
                            <tr>
                                <td class="text-center">
                                    {{ $no++ }}<br>
                                    <input type="checkbox" class="daily-checkbox"
                                           value="{{ $item->id }}"
                                           data-status="{{ $item->status }}"
                                           data-owner-id="{{ $item->user_id }}"
                                           {{ auth()->id() !== $item->user_id || !$canCheck ? 'disabled' : '' }}>
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                                <td class="text-center">
                                    @if($item->status == 'dilaporkan')
                                        <span class="badge badge-primary">Waiting Approve</span>
                                    @elseif($item->status == 'diterima')
                                        <span class="badge badge-success">Diterima Admin</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="badge badge-danger">Bukti Aktivitas Kamu Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Sedang Proses</span>
                                    @endif
                                </td>
                                <td>{{ $item->kegiatan }}</td>
                                <td>{{ $item->jekeg->kegiatan }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center flex-wrap">
                                        {{-- Tombol Edit --}}
                                        @if($item->status == 'progress' && now()->lessThanOrEqualTo($batasWaktu))
                                            <button class="btn btn-warning btn-sm mr-2 mb-1"
                                                    data-toggle="modal"
                                                    data-target="#modalEditDaily{{ $item->id }}"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif

                                        {{-- Tombol Hapus --}}
                                        @if($item->status == 'progress')
                                            <form action="{{ route('daily.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                        class="btn btn-danger btn-sm mr-2 mb-1"
                                                        title="Hapus"
                                                        data-toggle="modal"
                                                        data-target="#confirmDeleteModal"
                                                        onclick="setDeleteAction('{{ route('daily.destroy', $item->id) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                         @if(in_array($item->status, ['dilaporkan', 'diterima']))
                                            <a href="{{ route('daily.detail', $item->id) }}"
                                               class="btn btn-info btn-sm mb-1"
                                               data-toggle="tooltip"
                                               title="Dilaporkan pada: {{ \Carbon\Carbon::parse($item->done_at)->translatedFormat('d F Y') }}">
                                                <i class="fas fa-info"></i>
                                            </a>
                                        @elseif($item->filedailies->count() > 0)
                                            <a class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Sudah Upload Bukti Kegiatan" href="{{ route('daily.detail', ['id' => $item->id]) }}">
                                            <i class="fas fa-info"></i>
                                        </a>
                                        @else
                                            <span class="align-self-center mb-1"> <button class="btn btn-sm btn-info position-relative" data-bs-toggle="tooltip" title="Belum Upload Bukti Kegiatan" disabled>
                                                <i class="fas fa-info"></i>
                                            </button></span>
                                        @endif

                                        {{-- Badge Expired --}}
                                        @if($item->status == 'progress' && now()->greaterThan($batasWaktu))
                                            <span class="badge badge-secondary align-self-center mr-2 mb-1">Expired</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</div>


        @foreach ($daily as $item)
        <!-- Modal Edit -->
        <div class="modal fade" id="modalEditDaily{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditDailyLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('daily.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditDailyLabel{{ $item->id }}">Edit Kegiatan Harian</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="kegiatan{{ $item->id }}">Nama Kegiatan</label>
                                <input type="text" id="kegiatan{{ $item->id }}" name="kegiatan" class="form-control" value="{{ $item->kegiatan }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="jekeg_id{{ $item->id }}">Jenis Kegiatan</label>
                                <select id="jekeg_id{{ $item->id }}" name="jekeg_id" class="form-control" required>
                                    <option value="">-- Pilih Jenis Kegiatan --</option>
                                    @foreach ($jekegs as $jekeg)
                                        <option class="form-control" value="{{ $jekeg->id }}" {{ $jekeg->id == $item->jekeg_id ? 'selected' : '' }}>{{ $jekeg->kegiatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi{{ $item->id }}">Deskripsi</label>
                                <textarea id="deskripsi{{ $item->id }}" name="deskripsi" class="form-control" rows="3">{{ $item->deskripsi }}</textarea>
                            </div>
                            @if($item->filedailies->count())
                            <div class="mb-3">
                                <label><strong>Bukti yang sudah diupload</strong></label><br>
                                @foreach($item->filedailies as $file)
                                    <div class="position-relative d-inline-block mb-2" 
                                            style="width: 100%; transition: all 0.3s ease;"
                                            onmouseover="this.querySelector('.desc-overlay').style.visibility = 'visible'; this.querySelector('.desc-overlay').style.opacity = '1';"
                                            onmouseout="this.querySelector('.desc-overlay').style.visibility = 'hidden'; this.querySelector('.desc-overlay').style.opacity = '0';">
                                            <center><img
                                        src="{{ asset('storage/' . $file->image_path) }}"
                                        alt="Bukti"
                                        class="img-thumbnail"
                                        data-img="{{ asset('storage/' . $file->image_path) }}"
                                        data-desc="{{ $file->desc ?: 'Tanpa deskripsi' }}"
                                        style="max-width: 100%; margin: 5px 0; border:1px solid #ddd; padding:3px; cursor: pointer; text-align:center;">
                                    </center>
                                    <!-- Deskripsi yang akan muncul saat hover -->
                                    <div class="desc-overlay text-light"
                                    style="
                                    position: absolute;
                                    bottom: 0;
                                    width: 100%;
                                    background-color: rgba(74, 66, 66, 0.8); /* Semi-transparan */
                                    padding: 4px 6px;
                                    font-size: 14px;
                                    text-align: center;
                                    border-top: 1px solid #fff8f8;
                                    visibility: hidden; /* Defaultnya tersembunyi */
                                    opacity: 0;
                                    transition: visibility 0s, opacity 0.5s ease-in-out;">
                                    {{ $file->desc ?: 'Tanpa deskripsi' }}
                                </div>
                            </div>
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
                                    {{ $item->status === 'diterima' ? 'readonly' : '' }} required>
                                

                                <small class="text-muted">
                                    Maksimal 3 hari dari tanggal dibuat: {{ $item->created_at->copy()->addDays(3)->format('d-m-Y') }}
                                </small>
                            </div>
                            <div class="form-group mt-3">
                                <label>Bukti Kegiatan</label>
                                <div id="imageUploadContainer-{{ $item->id }}">
                                    <div class="input-group mb-2">
                                        <input type="file" name="images[]" class="form-control" accept="image/*" required>
                                    </div>
                                    <label>Deskripsi Kegiatan</label>
                                    <div class="input-group mb-2">
                                        <textarea name="desc[]" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-info" onclick="addUploadField({{ $item->id }})">
                                    + Tambah Foto
                                </button>
                            </div>
                        </div> <!-- modal-body -->
                        <div class="modal-footer">
                            <button type="submit" id="submitBtn-{{ $item->id }}" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div> <!-- modal-content -->
                </form>
                <!-- Loading Overlay -->
                <div class="loading-overlay d-none" id="loadingOverlay-{{ $item->id }}" style="
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(255,255,255,0.8);
                    z-index: 1055;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                ">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

            </div> <!-- modal-dialog -->
        </div> 
        <!-- tutup modal edit -->
        @endforeach
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
                                <select name="jekeg_id" class="form-control" required>
                                    <option value="">-- Pilih Jenis Kegiatan --</option>
                                    @foreach ($jekegs as $jekeg)
                                        <option value="{{ $jekeg->id }}">{{ $jekeg->kegiatan }}</option>
                                    @endforeach
                                </select>
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
        {{-- tutup modal tambha --}}
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
                        <form id="deleteForm" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
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
                <form id="sendReportForm" action="{{ route('daily.massReport') }}" method="POST">
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
document.addEventListener('DOMContentLoaded', function () {
    @foreach ($daily as $item)
        const form{{ $item->id }} = document.querySelector('#modalEditDaily{{ $item->id }} form');
        const overlay{{ $item->id }} = document.getElementById('loadingOverlay-{{ $item->id }}');
        const submitBtn{{ $item->id }} = document.getElementById('submitBtn-{{ $item->id }}');

        form{{ $item->id }}.addEventListener('submit', function () {
            // Class overlay
            overlay{{ $item->id }}.classList.remove('d-none');
            submitBtn{{ $item->id }}.disabled = true;
            submitBtn{{ $item->id }}.innerText = 'Sedang Di Proses...'; //Semogan ambah tulisan
        });
    @endforeach
});

     function setDeleteAction(url) {
        // Mengubah action form ke URL yang sesuai
        document.getElementById('deleteForm').action = url;
    }

    // Inisialisasi modal ketika page load
    $(document).ready(function () {
        // Ketika modal ditutup, reset form action
        $('#confirmDeleteModal').on('hidden.bs.modal', function () {
            document.getElementById('deleteForm').action = '';
        });
    });
    
    let latestRequest = 0;

$('#filterStatus').on('change', function () {
    let status = $(this).val(); // Ambil nilai status yang dipilih
    latestRequest++;  // Increment counter
    const thisRequest = latestRequest;

    $.ajax({
        url: '{{ route("daily.index") }}',  // Route untuk index yang sudah ada
        type: 'GET',
        data: {
            status: status  // Kirimkan status ke controller
        },
        success: function (response) {
            if (thisRequest !== latestRequest) return;

            // Ambil isi baru dari response (table)
            let newDoc = document.createElement('html');
            newDoc.innerHTML = response;

            // Ambil tbody yang baru
            let newTbody = $(newDoc).find('#dataTable tbody').html();

            if ($.trim(newTbody) === '') {
                $('#dataTable tbody').html(`
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data ditemukan.</td>
                    </tr>
                `);
            } else {
                $('#dataTable tbody').html(newTbody);
            }
        },
        error: function (xhr) {
            console.log('Filter error', xhr.responseText);
        }
    });
});

function addUploadField(id) {
    const container = document.getElementById('imageUploadContainer-' + id);

    const inputGroupFile = document.createElement('div');
    inputGroupFile.classList.add('input-group', 'mb-2');

    const inputFile = document.createElement('input');
    inputFile.type = 'file';
    inputFile.name = 'images[]';
    inputFile.classList.add('form-control');
    inputFile.accept = 'image/*';

    inputGroupFile.appendChild(inputFile);

    const inputGroupDesc = document.createElement('div');
    inputGroupDesc.classList.add('input-group', 'mb-2');

    const inputDesc = document.createElement('textarea');
    inputDesc.name = 'desc[]';
    inputDesc.classList.add('form-control');
    inputDesc.rows = 3;

    inputGroupDesc.appendChild(inputDesc);

    // Tambahkan keduanya ke container
    container.appendChild(inputGroupFile);
    container.appendChild(inputGroupDesc);
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