@extends('layouts.main')
@section('isi')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Log Activity</h6>
        </div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif


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
                                <td>{{ $no++ }}
                                    @php
                                    $buktiUploaded = $item->filedailies->count() > 0;
                                    @endphp
                                    <input type="checkbox" class="daily-checkbox" value="{{ $item->id }}" {{ $item->status == 'selesai' || !$buktiUploaded ? 'disabled' : '' }}>
                                </td>
                                <td>{{ $item->user->name ?? 'N/A' }} <br>
                                    @if($item->status == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-warning">Sedang Proses</span>
                                    @endif
                                </td>
                                <td>{{ $item->kegiatan }}</td>
                                <td>{{ $item->jenis }}</td>
                                {{-- <td>{{ $item->deskripsi }}</td> --}}
                                <td style="text-align: center;">
                                    <button class="btn btn-primary btn-sm" style="margin-left:5px;" data-toggle="modal" data-target="#modalDeskripsi{{ $item->id }}" title="Deskripsi">
                                        Detail
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
                                <td style="text-align: center;">
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
                                    
                                        <form action="{{ route('actv.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data?')">
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
    </div>
</div>
@push('scripts')
@foreach ($daily as $item)
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
@endforeach
@endpush
@endsection