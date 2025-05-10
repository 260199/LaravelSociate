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
                        <button class="btn btn-md btn-primary" data-toggle="modal" data-target="#modalTambahDaily"  data-bs-toggle="tooltip"
                        title="Tambah Aktifitas">
                            Tambah Aktifitas
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-info" id="openMassReportModal" style="background-color:rgba(62, 6, 231, 0.804)" data-toggle="modal" data-target="#modalSendReport" disabled>
                        Kirim Laporan!
                    </button>
                </div>  
                {{-- untuk settings jenis --}}
                <div class="col-auto">
                    <button class="btn btn-primary dropdown" style="background-color:rgba(6, 69, 205, 0.816);" type="button" 
                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2"></i>
                    </button>
                    <div class="dropdown-menu animated--fade-in"
                        aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/jekegs">Lihat Data</a>
                        <a class="dropdown-item" data-toggle="modal" data-target="#createModal" style="cursor: pointer">Tambah Jenis Kegiatan</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="filterUser">Pilih User</label>
                    <select id="filterUser" class="form-control">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterStatus">Pilih Status</label>
                    <select id="filterStatus" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="progress">Progress</option>
                        <option value="dilaporkan">Dilaporkan</option>
                        <option value="diterima">Diterima</option>
                    </select>
                </div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="checkAllEligible">
                <label class="form-check-label" for="checkAllEligible">
                    Ceklis Semua yang Bisa Dilaporkan
                </label>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="color:black;">
                    <thead>
                        <tr style="text-align: center;">
                            <th>#</th>
                            <th>Tgl Dibuat</th>
                            <th>Nama</th>
                            <th>Kegiatan</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($daily as $item)
                            @php
                                $buktiUploaded = $item->filedailies->count() > 0; // Cek apakah sudah ada bukti
                                $canCheck = $item->status === 'progress' && $buktiUploaded; // Cek ini hanya bisa dickelis kalo udah kelar upload bukti
                            @endphp
                            <tr>
                                <td style="text-align: center;">{{ $no++ }}
                                    <input type="checkbox" class="daily-checkbox" 
                                           value="{{ $item->id }}" 
                                           data-status="{{ $item->status }}" 
                                           data-owner-id="{{ $item->user_id }}" 
                                           {{ auth()->id() !== $item->user_id ? 'disabled' : '' }} 
                                           {{ !$canCheck ? 'disabled' : '' }}>
                                </td>
                                
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l,d F Y') }}</td>
                                <td style="text-align: center;">{{ $item->user->name }} <br>
                                    @if($item->status == 'diterima')
                                        <span class="badge badge-success">Pelaporan Diterima</span>
                                    @elseif($item->status == 'dilaporkan')
                                        <span class="badge badge-danger">Waiting Approval</span>
                                    @else
                                        <span class="badge badge-warning">Sedang Proses</span>
                                    @endif
                                </td>
                                <td>{{ $item->kegiatan }}</td>
                                <td>{{ $item->jenis }}</td>
                              
                                <td style="text-align: center;">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $batasWaktu = \Carbon\Carbon::parse($item->created_at)->addDays(3);
                                        @endphp
                                
                                        {{-- Tombol Edit --}}
                                        <button class="btn btn-warning btn-sm ml-1" 
                                                title="Edit" 
                                                data-toggle="modal" 
                                                data-target="#modalEditDaily{{ $item->id }}" 
                                                @if($item->status == 'progress' && 'dilaporkan' && 'diterima' && now()->lessThanOrEqualTo($batasWaktu) && $item->user_id != auth()->id()) disabled @endif>
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- Tombol Approve jika status dilaporkan --}}
                                        @if($item->status == 'dilaporkan')
                                           <form id="approveForm_{{ $item->id }}" method="POST" action="{{ route('actv.approve', $item->id) }}">
                                               @csrf
                                               @method('POST')
                                               <button type="button" class="btn btn-success btn-sm ml-1" title="Approve"
                                                       data-toggle="modal" data-target="#approveModal"
                                                       onclick="setApproveFormAction('{{ route('actv.approve', $item->id) }}')">
                                                   <i class="fas fa-check"></i>
                                               </button>
                                           </form>
                                       @endif
                                
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('actv.destroy', $item->id) }}" method="POST" class="d-inline-block" id="deleteForm{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm ml-1" title="Hapus"
                                                    data-toggle="modal" data-target="#confirmDeleteModal"
                                                    onclick="setDeleteAction('{{ route('actv.destroy', $item->id) }}')"
                                                    @if($item->user_id != auth()->id()) disabled @endif>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                
                                        {{-- Info diterima atau dilaporkan --}}
                                        @if($item->status == 'diterima')
                                            <a href="{{ route('actv.info', $item->id) }}" class="btn btn-sm btn-info ml-1"
                                               data-bs-toggle="tooltip"
                                               title="Diterima pada: {{ \Carbon\Carbon::parse($item->done_at)->translatedFormat('d F Y') }}">
                                                <i class="fas fa-info"></i>
                                            </a>
                                        @elseif($item->status == 'dilaporkan')
                                            <a href="{{ route('actv.info', $item->id) }}" class="btn btn-sm btn-info ml-1"
                                               data-bs-toggle="tooltip"
                                               title="Dilaporkan pada: {{ \Carbon\Carbon::parse($item->done_at)->translatedFormat('d F Y') }}">
                                                <i class="fas fa-info"></i>
                                            </a>
                                        @elseif($item->filedailies->count() > 0)
                                            <button class="btn btn-sm btn-info ml-1"
                                                    data-bs-toggle="tooltip"
                                                    title="Sudah Upload Bukti Kegiatan">
                                                <i class="fas fa-info"></i>
                                            </button>
                                        @else
                                            <span class="ml-1">-</span>
                                        @endif
                                
                                        {{-- Status Expired jika progress tapi lewat batas waktu --}}
                                        @if($item->status == 'progress' && now()->greaterThan($batasWaktu))
                                            <span class="badge badge-secondary ml-2">Expired</span>
                                        @endif
                                    </div>
                                </td>
                                
                                
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @foreach ($daily as $item)
        <!-- Modal Edit -->
        <div class="modal fade" id="modalEditDaily{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditDailyLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('actv.update', $item->id) }}" method="POST" enctype="multipart/form-data">
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
                                        <option value="{{ $jekeg->id }}" {{ $jekeg->id == $item->jekeg_id ? 'selected' : '' }}>{{ $jekeg->kegiatan }}</option>
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
                                {{-- <input type="date" name="done_at" id="done_at" class="form-control"
                                    min="{{ $item->created_at->format('Y-m-d') }}"
                                    max="{{ $item->created_at->copy()->addDays(3)->format('Y-m-d') }}"
                                    value="{{ $doneAtValue }}"
                                    {{ $item->status === 'diterima' ? 'readonly' : '' }}> --}}

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
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div> <!-- modal-content -->
                </form>
            </div> <!-- modal-dialog -->
        </div> 
        <!-- tutup modal edit -->
        @endforeach
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
        {{-- Tutup Modal Jenis --}}
        <!-- Modal Approve -->
        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form id="approveForm" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="approveModalLabel">Konfirmasi Approval</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menyetujui kegiatan ini?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Ya, Setujui</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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
    function setApproveFormAction(url) {
        const approveForm = document.getElementById('approveForm');
        approveForm.action = url;
    }
    
    function setDeleteAction(url) {
        document.getElementById('deleteForm').action = url;
    }
    
    $(document).ready(function () {
        $('#confirmDeleteModal').on('hidden.bs.modal', function () {
            document.getElementById('deleteForm').action = '';
        });
    });

    let latestRequest = 0;
    $('#filterUser, #filterStatus').on('change', function () {
        let user_id = $('#filterUser').val();
        let status = $('#filterStatus').val();
        latestRequest++;
        const thisRequest = latestRequest;
    
        $.ajax({
            url: '{{ route("actv.filter") }}',
            type: 'GET',
            data: {
                user_id: user_id,
                status: status
            },
            success: function (response) {
                if (thisRequest !== latestRequest) return;
            
                let newDoc = document.createElement('html');
            newDoc.innerHTML = response;

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

    // Membuat div untuk grup input foto
    const inputGroupFile = document.createElement('div');
    inputGroupFile.classList.add('mb-3');  // Memberikan margin bottom agar spasi lebih rapi

    // Membuat label untuk foto
    const labelFile = document.createElement('label');
    labelFile.textContent = 'Bukti Kegiatan';  // Label untuk foto
    labelFile.classList.add('form-label');
    
    const inputFile = document.createElement('input');
    inputFile.type = 'file';
    inputFile.name = 'images[]';
    inputFile.classList.add('form-control');
    inputFile.accept = 'image/*';
    inputFile.required = true;

    // Menambahkan label dan input file ke dalam grup
    inputGroupFile.appendChild(labelFile);
    inputGroupFile.appendChild(inputFile);

    // Membuat div untuk grup input deskripsi
    const inputGroupDesc = document.createElement('div');
    inputGroupDesc.classList.add('mb-3');  // Memberikan margin bottom untuk spasi yang konsisten

    // Membuat label untuk deskripsi kegiatan
    const labelDesc = document.createElement('label');
    labelDesc.textContent = 'Deskripsi Kegiatan';  // Label untuk deskripsi
    labelDesc.classList.add('form-label');

    const inputDesc = document.createElement('textarea');
    inputDesc.name = 'desc[]';
    inputDesc.classList.add('form-control');
    inputDesc.rows = 3;
    inputDesc.required = true;

    // Menambahkan label dan input deskripsi
    inputGroupDesc.appendChild(labelDesc);
    inputGroupDesc.appendChild(inputDesc);

    // Menambahkan grup input foto dan deskripsi ke dalam container
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
    const adminId = {{ auth()->id() }}; // ID user yang sedang login (admin)
    const checkboxes = document.querySelectorAll('.daily-checkbox'); // Semua checkbox
    const sendReportForm = document.getElementById('sendReportForm'); // Form untuk laporan massal
    const sendReportBtn = document.getElementById('openMassReportModal'); // Tombol kirim laporan
    const selectAllCheckbox = document.getElementById('checkAllEligible'); // Checkbox "Ceklis Semua"

    // Fungsi untuk enable/disable tombol "Send Report" berdasarkan checkbox yang dicentang
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const anyChecked = Array.from(checkboxes).some(box => box.checked); // Cek apakah ada checkbox yang dicentang
            sendReportBtn.disabled = !anyChecked; // Disable tombol jika tidak ada yang dicentang
        });
    });

    // Untuk tombol "Ceklis Semua"
    selectAllCheckbox.addEventListener('change', function () {
        const checked = this.checked;
        checkboxes.forEach(cb => {
            // Pastikan hanya checkbox milik admin yang bisa dicentang
            if (cb.dataset.ownerId == adminId && !cb.disabled) {
                cb.checked = checked;
            }
        });
        // Aktifkan tombol jika ada checkbox yang dicentang
        sendReportBtn.disabled = !Array.from(checkboxes).some(box => box.checked);
    });

    // Sebelum form dikirim, pastikan ID yang dipilih sudah ada dalam input hidden
    sendReportForm.addEventListener('submit', function (e) {
        // Ambil semua ID dari checkbox yang dicentang
        const selectedIds = Array.from(checkboxes)
            .filter(cb => cb.checked)  // Filter checkbox yang dicentang
            .map(cb => cb.value);      // Ambil value (ID) dari checkbox yang dicentang

        // Log untuk debugging
        console.log("Selected IDs:", selectedIds);

        // Isi input hidden dengan ID yang dipilih
        document.getElementById('selectedDailyIds').value = selectedIds.join(',');

        // Jika tidak ada checkbox yang dicentang, hentikan submit form
        if (selectedIds.length === 0) {
            e.preventDefault(); // Mencegah form untuk disubmit
            alert('Harap pilih kegiatan yang ingin dilaporkan.');
        }
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

document.getElementById('myForm').addEventListener('submit', function(event) {
        var doneAt = document.getElementById('done_at').value;
        
        if (!doneAt) {
            event.preventDefault();
            alert('Tanggal Selesai wajib diisi!');
        }
    });
</script>
@endforeach
@endpush
@endsection