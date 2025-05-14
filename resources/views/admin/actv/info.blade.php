@extends('layouts.main')

@section('isi')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Kegiatan Harian</h1>
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-4">← Kembali</a>


       <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Rincian Kegiatan :</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-start  text-md-start">
                {{-- Kolom Kiri --}}
                <div class="col-md-5 mb-3">
                    <div class="p-3  rounded h-100">
                        <h5 class="text-primary mb-2">{{ $daily->user->name }}</h5>
                        <p class="mb-1"><strong> kegiatan: {{ $daily->kegiatan }}</strong></p>
                        <p class="mb-1"><strong>Jenis kegiatan: {{ $daily->jekeg->kegiatan }}</strong></p>
                        <p class="mb-0"><strong>Waktu Dibuat:</strong><br>{{ $daily->created_at->translatedFormat('l, d F Y H:i') }}</p>
                        </div>
                        </div>
                        {{-- Kolom Tengah --}}
                        <div class="col-md-3 mb-3">
                            <div class="p-3  rounded h-100 ">
                                <p class="mb-2"><strong>Tanggal Selesai:</strong><br>
                                    {{ $daily->done_at ? \Carbon\Carbon::parse($daily->done_at)->translatedFormat('l, d F Y H:i') : '-' }}
                                </p>
                                <p class="mb-0"><strong>Tanggal Diterima:</strong><br>
                                    {{ $daily->done_at ? \Carbon\Carbon::parse($daily->done_at)->translatedFormat('l, d F Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                            
                            {{-- Kolom Kanan --}}
                            <div class="col-md-4 mb-3">
                                <div class="p-3 rounded h-100">
                                    <h5 class="mb-2"><strong>Status Kegiatan:</strong></h5>
                                    <div class="alert m-0 
                                    @if($daily->status == 'diterima') alert-success
                                    @elseif($daily->status == 'progress') alert-warning
                                    @elseif($daily->status == 'ditolak') alert-danger
                                    @elseif($daily->status == 'dilaporkan') alert-primary
                                    @else alert-secondary @endif">
                                      @if($daily->status == 'diterima')
                                        Laporan diterima
                                    @elseif($daily->status == 'progress')
                                        Sedang diproses
                                    @elseif($daily->status == 'ditolak')
                                        Ditolak
                                    @elseif($daily->status == 'dilaporkan')
                                        Menunggu konfirmasi atasan
                                    @else
                                        Status tidak diketahui
                                    @endif
                                </div>
                                
                                <div class="mt-2">
                                    @if($daily->status == 'dilaporkan')
                                     <button type="button" class="btn btn-success btn-sm" title="Approve / Tolak"
                                                    onclick="showApproveConfirmation(
                                                        '{{ route('actv.approve', $daily->id) }}',
                                                        '{{ route('actv.reject', $daily->id) }}'
                                                    )">
                                                <i class="fas fa-check"></i> Aksi
                                            </button>
                                    @endif
                                </div>


            
                            {{-- Tampilkan alasan ditolak jika status 'Ditolak' --}}
                            @if($daily->status == 'ditolak' && $daily->reject_message)
                            <div class="mt-2 alert alert-danger">
                                <strong>Alasan Ditolak:</strong> {{ $daily->reject_message }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <hr>
                </div>
                {{-- Deskripsi Kegiatan --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><strong>Deskripsi Kegiatan</strong></h5>
                    </div>
                    <div class="card-body" style="padding: 1.25rem; width: 100%; display: ;">
                        <div style="text-align: justify; width: 100%; word-wrap: break-word;">
                            {!! nl2br(e($daily->deskripsi)) !!}
                        </div>
                    </div>
                    <hr>
                    <center><h5 ><i class="fas fa-image me-2"></i>Bukti Kegiatan:</h5></center>
                    @forelse($daily->filedailies as $file)
                    <div class="col-12 mb-4 d-flex justify-content-center">
                        <div style="max-width: 600px;">
                            <img src="{{ asset('storage/' . $file->image_path) }}"
                            alt="Bukti"
                            class="img-fluid img-thumbnail preview-uploaded-img"
                            data-img="{{ asset('storage/' . $file->image_path) }}"
                            data-desc="{{ $file->desc ?: 'Tanpa deskripsi' }}"
                            style="max-height: 400px; object-fit: contain; background: #f8f9fa; cursor: pointer;">
                            <div class="card-body text-center p-2">
                                <small class="text-muted">{{ $file->desc ?? 'Tidak ada deskripsi.' }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted">Tidak ada bukti kegiatan yang diunggah.</p>
                    </div>
                    @endforelse
                </div>	
            </div>
            <hr>
        </div>
    </div>
</div>

<!-- Modal Persetujuan -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Konfirmasi Persetujuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menyetujui kegiatan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('actv.approve', $daily->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Setujui</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Gambar -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img id="modalImagePreview" src="" class="img-fluid w-100" alt="Preview">
                <div class="p-3 bg-light border-top">
                    <p id="modalImageDesc" class="mb-0 text-muted"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Preview Gambar -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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

document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
    const modalImg = document.getElementById('modalImagePreview');
    const modalDesc = document.getElementById('modalImageDesc');

    document.querySelectorAll('.preview-uploaded-img').forEach(img => {
        img.addEventListener('click', function () {
            modalImg.src = this.dataset.img;
            modalDesc.textContent = this.dataset.desc;
            modal.show();
        });
    });
});
</script>
@endsection
