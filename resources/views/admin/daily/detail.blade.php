@extends('layouts.main')

@section('isi')

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Kegiatan Harian</h1>
    <a href="{{ route('actv.index') }}" class="btn btn-secondary mb-4">← Kembali</a>
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                Kegiatan: <strong>{{ $daily->kegiatan }}</strong> oleh <strong>{{ $daily->user->name ?? '-' }}</strong>
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <p><strong>Jenis Kegiatan:</strong> {{ $daily->jenis }}</p>
                    <p><strong>Status:</strong> 
                        @if($daily->status == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-warning">Sedang Proses</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <p><strong>Tanggal Dibuat:</strong> {{ \Carbon\Carbon::parse($daily->created_at)->format('d M Y') }}</p>
                    <p><strong>Tanggal Selesai:</strong> 
                        {{ $daily->done_at ? \Carbon\Carbon::parse($daily->done_at)->format('d M Y') : '-' }}
                    </p>
                </div>
            </div>
            <div class="mb-4">
                <p><strong>Deskripsi Kegiatan:</strong></p>
                <div class="p-3 bg-light rounded border">
                    {!! nl2br(e($daily->deskripsi)) !!}
                </div>
            </div>
            <hr>
            <h5 class="mb-3">Bukti Kegiatan:</h5>
            <div class="row">
                @forelse($daily->filedailies as $file)
                    <div class="col-md-3 col-sm-6 mb-4 d-flex align-items-stretch">
                        <div class="card shadow-sm w-100">
                            <img src="{{ asset('storage/' . $file->image_path) }}"
                                    alt="Bukti"
                                    class="img-thumbnail preview-uploaded-img"
                                    data-img="{{ asset('storage/' . $file->image_path) }}"
                                    data-desc="{{ $file->desc ?: 'Tanpa deskripsi' }}"
                                    style="max-height: 200px; object-fit: contain; background: #f8f9fa; cursor: pointer;">
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
    </div>
</div>
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
<script>
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
</script>
@endsection