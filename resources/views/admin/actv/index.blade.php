@extends('layouts.main')
@section('isi')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Log Activity</h6>
                </div>
                <div class="card-body">
                   <div class="row align-items-end mb-3">
                    <!-- Ini button tambah aktivitas -->
                    <div class="col-auto">
                        <button class="btn btn-md btn-primary" data-toggle="modal" data-target="#modalTambahDaily"  data-bs-toggle="tooltip"
                        title="Tambah Aktifitas">Tambah Aktifitas
                        </button>
                    </div>

                    <!-- button kirim laporan v3 -->
                    <div class="col-auto">
                        <button type="button" class="btn btn-success btn-icon-split" id="openMassReportModal" data-toggle="modal" data-target="#modalSendReport" disabled>
                            <span class="icon text-white-50 d-flex align-items-center">
                                <input type="checkbox" id="checkAllEligible" data-bs-toggle="tooltip" title="Ceklis Seluruh Aktivitas" style="transform: scale(1.3); cursor: pointer;" />
                            </span>
                            <span class="text">Kirim Laporan!</span>
                        </button>
                    </div>

                    <!-- filter v2 -->
                    <div class="col-12 col-md-4 position-relative">
                        <label for="filterUser" style="position: absolute; top: -8px; left: 15px; background: white; padding: 0 5px; font-size: 12px; z-index: 1;">
                            Pilih User
                        </label>
                        <select name="filterUser" id="filterUser" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-4 position-relative">
                        <label for="filterStatus" style="position: absolute; top: -8px; left: 15px; background: white; padding: 0 5px; font-size: 12px; z-index: 1;">
                            Pilih Status
                        </label>
                        <select name="filterStatus" id="filterStatus" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="progress">Progress</option>
                            <option value="dilaporkan">Dilaporkan</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
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
                            @forelse ($daily as $item)
                            
                            @php
                            $buktiUploaded = $item->filedailies->count() > 0; // Cek apakah sudah ada bukti
                            $canCheck = $item->status === 'progress' && $buktiUploaded; // Cek ini hanya bisa dickelis kalo udah kelar upload bukti
                            @endphp
                            <tr>
                                <td style="text-align: center;">{{ $no++ }} <br>
                                    <input type="checkbox" class="daily-checkbox" 
                                    value="{{ $item->id }}" 
                                    data-status="{{ $item->status }}" 
                                    data-owner-id="{{ $item->user_id }}" 
                                    {{ auth()->id() !== $item->user_id ? 'disabled' : '' }} 
                                    {{ !$canCheck ? 'disabled' : '' }}>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y') }}</td>
                                <td style="text-align: center;">{{ $item->user->name ?? 'USER MANA ?'}}  <br>
                                    @if($item->status == 'diterima')
                                    <span class="badge badge-success">Laporan Diterima</span>
                                    @elseif($item->status == 'dilaporkan')
                                    <span class="badge badge-danger">Waiting Approval</span>
                                    @elseif($item->status =='ditolak')
                                    <span class="badge badge-danger">Ditolak!!</span>
                                    @else
                                    <span class="badge badge-warning">Sedang Proses</span>
                                    @endif
                                </td>
                                <td>{{ $item->kegiatan }}</td>
                                <td>{{ $item->jekeg->kegiatan ?? '-' }}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <div style="display: flex; justify-content: center; align-items: center; gap: 4px; flex-wrap: nowrap; white-space: nowrap;">
                                        @php
                                            $batasWaktu = \Carbon\Carbon::parse($item->created_at)->addDays(3);
                                        @endphp
                                        
                                        {{-- Tombol Edit --}}
                                        @if($item->user_id == auth()->id() && !in_array($item->status, ['dilaporkan', 'diterima', 'ditolak']))
                                        <button class="btn btn-warning btn-sm"
                                                title="Edit"
                                                data-toggle="modal"
                                                data-target="#modalEditDaily{{ $item->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                         @endif
                                    
                                        
                                        @if($item->status == 'dilaporkan')
                                            {{-- Tombol tunggal untuk approve atau tolak --}}
                                            <button type="button" class="btn btn-success btn-sm" title="Approve / Tolak"
                                                    onclick="showApproveConfirmation(
                                                        '{{ route('actv.approve', $item->id) }}',
                                                        '{{ route('actv.reject', $item->id) }}'
                                                    )">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif

                                     
                                     

                                    
                                      {{-- Tombol Hapus --}}
                                        {{-- <form action="{{ route('actv.destroy', $item->id) }}" method="POST" id="deleteForm{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" title="Hapus"
                                                    onclick="showDeleteConfirmation('{{ route('actv.destroy', $item->id) }}')"
                                                    @if($item->user_id != auth()->id()) disabled @endif>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form> --}}

                                        {{-- Info Status --}}
                                        @if($item->status == 'diterima')
                                            <a href="{{ route('actv.info', $item->id) }}" class="btn btn-sm btn-info"
                                                data-bs-toggle="tooltip"
                                                title="Diterima pada: {{ \Carbon\Carbon::parse($item->done_at)->translatedFormat('d F Y') }}">
                                                <i class="fas fa-info"></i>
                                            </a>
                                        @elseif($item->status == 'dilaporkan')
                                            <a href="{{ route('actv.info', $item->id) }}" class="btn btn-sm btn-info"
                                                data-bs-toggle="tooltip"
                                                title="Dilaporkan pada: {{ \Carbon\Carbon::parse($item->done_at)->translatedFormat('d F Y') }}">
                                                <i class="fas fa-info"></i>
                                            </a>
                                        @elseif($item->filedailies->count() > 0)
                                        <a class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Sudah Upload Bukti Kegiatan" href="{{ route('actv.info', ['id' => $item->id]) }}">
                                            <i class="fas fa-info"></i>
                                        </a>
                                        
                                        @else
                                        <button class="btn btn-sm btn-info position-relative" data-bs-toggle="tooltip" title="Belum Upload Bukti Kegiatan" disabled>
                                            <i class="fas fa-info"></i>
                                        </button>
                                        @endif
                                    
                                        {{-- Badge Expired --}}
                                        @if($item->status == 'progress' && now()->greaterThan($batasWaktu))
                                            <span class="badge badge-secondary">Expired</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                                @empty
                                <td colspan="4">Data Tidak Ditemukan</td>
                                @endforelse
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
function showApproveConfirmation(approveUrl, rejectUrl) {
    Swal.fire({
        title: 'Konfirmasi Kegiatan',
        text: 'Apakah Anda ingin menyetujui atau menolak kegiatan ini?',
        icon: 'question',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Setujui',
        denyButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
        denyButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit approve
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = approveUrl;

            let csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            let methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'POST';

            form.appendChild(csrfToken);
            form.appendChild(methodField);

            document.body.appendChild(form);
            form.submit();

        } else if (result.isDenied) {
            // Input alasan penolakan
            Swal.fire({
                title: 'Tolak Kegiatan',
                input: 'textarea',
                inputLabel: 'Alasan Penolakan',
                inputPlaceholder: 'Tulis alasan penolakan di sini...',
                inputAttributes: {
                    'aria-label': 'Tulis alasan'
                },
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33'
            }).then((inputResult) => {
                if (inputResult.isConfirmed && inputResult.value) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = rejectUrl;

                    let csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    let methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'POST';

                    let reasonField = document.createElement('input');
                    reasonField.type = 'hidden';
                    reasonField.name = 'reject_message';
                    reasonField.value = inputResult.value;

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    form.appendChild(reasonField);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    });
}



    function showDeleteConfirmation(actionUrl) {
        Swal.fire({
            title: "Yakin Hapus Data ??",
            text: "Aksi Hapus Data Tidak Bisa Dikembalikan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6", 
            cancelButtonColor: "#d33",    
            confirmButtonText: "Iya, Hapus Data!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Terhapus!",
                    text: "Data Berhasil Dihapus.",
                    icon: "success"
                }).then(() => {
                    let form = document.getElementById('deleteForm' + actionUrl.split('/').pop());
                    form.submit(); 
                });
            }
        });
    }

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
$(document).ready(function () {
    $('#filterUser, #filterStatus').on('change', function () {
        $('#loadingFilter').show();

        setTimeout(function () {
            var selectedUser = $('#filterUser').val();
            var selectedStatus = $('#filterStatus').val();
            var dataFound = false;

            let visibleCount = 0;

            $('#dataTable tbody tr').each(function () {
                var row = $(this);
                var userId = row.find('input.daily-checkbox').data('owner-id').toString();
                var status = row.find('input.daily-checkbox').data('status');

                var matchUser = selectedUser === "" || userId === selectedUser;
                var matchStatus = selectedStatus === "" || status === selectedStatus;

                if (matchUser && matchStatus) {
                    row.removeClass('d-none');
                    visibleCount++;
                } else {
                    row.addClass('d-none');
                }
            });

            // Hapus pesan sebelumnya
            $('#dataTable tbody .not-found-row').remove();

            if (visibleCount === 0) {
                $('#dataTable tbody').append('<tr class="not-found-row"><td colspan="6" class="text-center">Data Tidak Ditemukan</td></tr>');
            }

            $('#loadingFilter').hide();
        }, 10); // Delay kecil untuk visualisasi spinner
    });
});




function addUploadField(id) {
    const container = document.getElementById('imageUploadContainer-' + id);

    // contoh buat div baru
    const inputGroupFile = document.createElement('div');
    inputGroupFile.classList.add('mb-3');  // styling nya 

    //  label  foto
    const labelFile = document.createElement('label');
    labelFile.textContent = 'Bukti Kegiatan';  // Label  
    labelFile.classList.add('form-label');
    
    const inputFile = document.createElement('input');
    inputFile.type = 'file';
    inputFile.name = 'images[]';
    inputFile.classList.add('form-control');
    inputFile.accept = 'image/*';
    inputFile.required = true;

    // add label sma input ke grup nanti pas di add
    inputGroupFile.appendChild(labelFile);
    inputGroupFile.appendChild(inputFile);

    //  div untuk grup input deskripsi
    const inputGroupDesc = document.createElement('div');
    inputGroupDesc.classList.add('mb-3');  

    //  label untuk deskripsi kegiatan
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