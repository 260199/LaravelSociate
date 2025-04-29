@extends('layouts.main')
@section('isi')
    {{-- Pusher --}}
    <div aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 1rem; right: 1rem; z-index: 9999;">
        <div class="toast" id="dailyToast" data-delay="5000">
            <div class="toast-header bg-success text-white">
                <strong class="mr-auto">daily Baru</strong>
                <small>Baru saja</small>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">&times;</button>
            </div>
            <div class="toast-body" id="toastBody">
            </div>
        </div>
    </div>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Daily Activity</h1>
    <p class="mb-4">Berikut adalah daftar kegiatan harian yang telah direncanakan dan dilaporkan.</p>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Kegiatan Harian</h6>
        </div>
        <div class="card-body">
            <button class="btn btn-md btn-success mb-3" data-toggle="modal" data-target="#modalTambahDaily">
                Tambah Daily
            </button>
          
            <button class="btn btn-danger mb-3" id="openMassReportModal" data-toggle="modal" data-target="#modalSendReport" disabled>
                Send Report
            </button>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="checkAllEligible">
                <label class="form-check-label" for="checkAllEligible">
                    Ceklis Semua yang Bisa Dilaporkan
                </label>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead style="text-align: center;">
                        <tr>
                            <th style="width:5%;">No</th>
                            <th style="width:5%;">Report</th>
                            <th>User</th>
                            <th>Kegiatan</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Dibuat</th>
                            {{-- <th>Tanggal Selesai</th> --}}
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no=1;
                        @endphp
                        @foreach($daily as  $isu)
                            <tr>
                                <td style="text-align: center">
                                 {{ $no++ }}
                                </td>
                                <td style="text-align: center"> 
                                @php
                                $buktiUploaded = $isu->filedailies->count() > 0;
                                @endphp
                                
                                <input type="checkbox" class="daily-checkbox" value="{{ $isu->id }}" {{ $isu->status == 'selesai' || !$buktiUploaded ? 'disabled' : '' }}>
                                </td>

                                <td>
                                    {{ $isu->user->name ?? 'N/A' }} <br>
                                    @if($isu->status == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-warning">Sedang Proses</span>
                                    @endif
                                </td>
                                <td>{{ $isu->kegiatan }}</td>
                                <td>{{ $isu->jenis }}</td>
                                <td>{{ $isu->deskripsi }}</td>
                                <td>{{ $isu->created_at }}</td>
                                {{-- <td>{{ $isu->done_at ? \Carbon\Carbon::parse($isu->done_at)->format('d M Y H:i') : '-' }}</td> --}}
                                <td style="text-align: center;">
                                    @if($isu->status == 'selesai')
                                        <a href="{{ route('daily.detail', $isu->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Selesai pada: {{ $isu->done_at }}">Detail</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- Disable ketika lewat dari 3 hari setelah input daily --}}
                                        @php
                                         $batasWaktu = \Carbon\Carbon::parse($isu->created_at)->addDays(3);
                                        @endphp
                                        @if($isu->status != 'selesai' && now()->lessThanOrEqualTo($batasWaktu))
                                        <button class="btn btn-warning btn-sm" style="margin-left:5px;" data-toggle="modal" data-target="#editModal{{ $isu->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @elseif($isu->status == 'selesai')
                                        <button class="btn btn-warning btn-sm" style="margin-left:5px;" data-toggle="modal" data-target="#editModal{{ $isu->id }}" title="Edit" disabled>
                                            <i class="fas fa-edit"></i>
                                        </button>                                        
                                        @endif
                                    
                                        <form action="{{ route('daily.destroy', $isu->id) }}" method="POST" onsubmit="return confirm('Hapus data?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" style="margin-left:5px;" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    
                                        @php
                                            $batasWaktu = \Carbon\Carbon::parse($isu->created_at)->addDays(3);
                                        @endphp
                                    
                                        @if($isu->status != 'selesai' && now()->lessThanOrEqualTo($batasWaktu))
                                            {{-- <button class="btn btn-primary btn-sm" style="margin-left:5px;" data-toggle="modal" data-target="#laporModal{{ $isu->id }}" title="Laporkan">
                                                <i class="fas fa-paper-plane"></i>
                                            </button> --}}
                                        @elseif($isu->status != 'selesai')
                                            <span class="badge badge-secondary">Expired</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                          
                <!-- Modal Edit -->
                <div class="modal fade" id="editModal{{ $isu->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('daily.update', $isu->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Kegiatan: {{ $isu->kegiatan }}</h5>
                                    <button class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Nama Kegiatan</label>
                                        <input type="text" name="kegiatan" class="form-control" value="{{ $isu->kegiatan }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Jenis Kegiatan</label>
                                        <input type="text" name="jenis" class="form-control" value="{{ $isu->jenis }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3">{{ $isu->deskripsi }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="done_at">Tanggal Selesai</label>
                                        <input type="date" name="done_at" class="form-control" min="{{ \Carbon\Carbon::parse($isu->created_at)->format('Y-m-d') }}" max="{{ \Carbon\Carbon::parse($isu->created_at)->addDays(3)->format('Y-m-d') }}">
                                    </div>

                                    @if($isu->filedailies->count())
                                        <div class="mb-3">
                                            <label><strong>Bukti yang sudah diupload</strong></label><br>
                                            @foreach($isu->filedailies as $file)
                                                <img src="{{ asset('storage/' . $file->image_path) }}"
                                                    alt="Bukti"
                                                    class="img-thumbnail preview-uploaded-img"
                                                    data-img="{{ asset('storage/' . $file->image_path) }}"
                                                    style="max-width: 100px; margin: 5px; border:1px solid #ddd; padding:3px; cursor: pointer;">
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mb-3" id="buktiFields">
                                        <label>Upload Bukti</label>
                                        <input type="file" name="bukti[]" class="form-control mb-2" accept="image/*" multiple>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addBuktiBtn">
                                        + Tambah Bukti Lain
                                    </button>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
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
            <!-- bismillah report-->
            <div class="modal fade" id="modalSendReport" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('daily.massLaporkan') }}" method="POST" id="massReportForm">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Laporan</h5>
                                <button class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin melaporkan daily  ini?</p>
                                <ul id="selectedList"></ul>
                                <div id="selectedDailyContainer"></div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success" type="submit">Ya, Laporkan</button>
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal Preview Gambar -->
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
</div>


<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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


    // Konfigurasi Echo
    const echo = new Echo({
        broadcaster: 'pusher',
        key: '0e6943fcf6b465eff50f', // Ganti dengan Pusher app key kamu
        cluster: 'ap1', // Ganti dengan cluster kamu
        forceTLS: true
    });

    // Dengarkan channel dan event
    echo.channel('daily-channel')
        .listen('.daily.created', (e) => {
            console.log("Notifikasi masuk 🚀", e);

            // Menampilkan pesan pada toast
            const toastBody = document.getElementById('toastBody');
            toastBody.innerHTML = 'daily baru ditambahkan: ' + e.daily.nama;

            // Menampilkan toast
            const dailyToast = document.getElementById('dailyToast');
            const bootstrapToast = new bootstrap.Toast(dailyToast);
            bootstrapToast.show();
        });

    // Menampilkan notifikasi toast jika ada session 'success'
    @if(session('success'))
        window.onload = function() {
            const toastBody = document.getElementById('toastBody');
            toastBody.innerHTML = '{{ session('success') }}';
            const dailyToast = document.getElementById('dailyToast');
            const bootstrapToast = new bootstrap.Toast(dailyToast);
            bootstrapToast.show();
        };
    @endif

    document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.daily-checkbox');
    const openModalBtn = document.getElementById('openMassReportModal');
    const selectedContainer = document.getElementById('selectedDailyContainer');
    const selectedList = document.getElementById('selectedList');

    function toggleButtonState() {
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        openModalBtn.disabled = !anyChecked;
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', toggleButtonState);
    });

    openModalBtn.addEventListener('click', () => {
        selectedContainer.innerHTML = '';
        selectedList.innerHTML = '';

        checkboxes.forEach(cb => {
            if (cb.checked) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'selected_daily[]';
                hiddenInput.value = cb.value;
                selectedContainer.appendChild(hiddenInput);

                // Ambil data user_id dan kegiatan dari atribut data-* checkbox
                const userId = cb.getAttribute('data-user-id');
                const kegiatan = cb.getAttribute('data-kegiatan');

                // Tampilkan informasi di modal
                const listItem = document.createElement('li');
                listItem.textContent = `ID Daily: ${cb.value} - User ID: ${cd.user_id} - Kegiatan: ${cb.kegiatan}`;
                selectedList.appendChild(listItem);
            }
        });
    });
});

    document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.daily-checkbox');
    const checkAllBox = document.getElementById('checkAllEligible');
    const openModalBtn = document.getElementById('openMassReportModal');

    function toggleButtonState() {
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        openModalBtn.disabled = !anyChecked;
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', toggleButtonState);
    });

    checkAllBox.addEventListener('change', function () {
        checkboxes.forEach(cb => {
            if (!cb.disabled) {
                cb.checked = this.checked;
            }
        });
        toggleButtonState();
    });
    toggleButtonState();
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


document.addEventListener('DOMContentLoaded', function () {
    const addBtn = document.getElementById('addBuktiBtn');
    const container = document.getElementById('buktiFields');

    addBtn.addEventListener('click', function () {
        const newInput = document.createElement('input');
        newInput.type = 'file';
        newInput.name = 'bukti[]';
        newInput.className = 'form-control mb-2';
        newInput.accept = 'image/*';
        container.appendChild(newInput);
    });
});

</script>
@endsection