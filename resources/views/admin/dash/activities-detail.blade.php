@extends('layouts.main')

@section('isi')
<div class="container-fluid">
    <h3 class="mb-4">Detail Aktivitas: {{ $activity->kegiatan }}</h3>

    <div class="card">
        <div class="card-body">
            <h5 class="font-weight-bold">{{ $activity->kegiatan }}</h5>
            <p><strong>Deskripsi:</strong> {{ $activity->deskripsi }}</p>
            <p><strong>Waktu Upload:</strong> {{ $activity->created_at->format('d-m-Y H:i') }}</p>
            <p><strong>Status:</strong> {{ $activity->status ? 'Completed' : 'Pending' }}</p>
            <p><strong>Jenis Kegiatan:</strong> {{ $activity->jekeg->name }}</p>

            <hr>

            @foreach ($activity->filedailies as $file)
                <p><a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">Download File: {{ $file->file_name }}</a></p>
            @endforeach
        </div>
    </div>
</div>
@endsection
