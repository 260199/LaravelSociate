@extends('layouts.main')

@section('isi')

<div class="container-fluid">
    <h3 class="mb-4">Aktivitas Pengguna: {{ $user->name }}</h3>

    @foreach ($activities as $activity)
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Kolom Foto Profil -->
                <div class="col-auto">
                    <img src="{{ asset('storage/default/undraw_profile.svg') }}" alt="Profile Picture" class="img-fluid rounded-circle" style="width: 50px; height: 50px;">
                </div>

                <!-- Kolom Informasi Aktivitas -->
                <div class="col">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $user->name }}</strong>
                            <p class="text-muted" style="font-size: 0.9rem;">Kegiatan: {{ $activity->kegiatan }} - {{ \Carbon\Carbon::parse($activity->created_at)->translatedFormat('l, d M Y') }}</p>
                        </div>
                    </div>
                    <!-- Menampilkan Kegiatan Tambahan -->
                    <div class="d-flex justify-content-between">
                        <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 0;">{{ $activity->jekeg->kegiatan }}</p>

                        <!-- Menampilkan Status di Sebelah Kanan -->
                        <p class="mb-0">
                            @if($activity->status == 'selesai') 
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                            @elseif($activity->status == 'diterima') 
                                <span class="badge bg-success"><i class="fas fa-check-double me-1"></i> Kegiatan Telah Diterima!</span>
                            @elseif($activity->status == 'ditolak') 
                                <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                            @else 
                                <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i> Dilaporkan</span>
                            @endif
                        </p>
                    </div>

                    <p>{{ Str::limit($activity->deskripsi, 100) }}</p>

                    <!-- Tombol Cek Detail di Paling Bawah -->
                    <a href="{{ route('actv.info', $activity->id) }}" class="btn btn-sm btn-outline-primary mt-3">Cek Detail</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
