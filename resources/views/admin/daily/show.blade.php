@extends('layouts.main')

@section('isi')
<div class="container">
    <h3>Detail Kegiatan</h3>
    <p><strong>Kegiatan:</strong> {{ $daily->kegiatan }}</p>
    <p><strong>Jenis:</strong> {{ $daily->jenis }}</p>
    <p><strong>Deskripsi:</strong> {{ $daily->deskripsi }}</p>
    <p><strong>Status:</strong> {{ $daily->status }}</p>

    @if($daily->status == 'belum')
        <form action="{{ route('daily.uploadPhoto', $daily->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="photos[]" multiple required><br><br>
            <button type="submit" class="btn btn-primary">Upload Bukti</button>
        </form>

        @if($daily->photos->count())
        <form action="{{ route('daily.sendReport', $daily->id) }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-success">Kirim Laporan</button>
        </form>
        @endif
    @else
        <p class="text-success">Laporan Selesai</p>
    @endif

    <hr>
    <h5>Bukti Kegiatan:</h5>
    <div class="row">
        @foreach($daily->photos as $photo)
            <div class="col-md-3 mb-3">
                <img src="{{ asset('storage/' . $photo->filepath) }}" class="img-thumbnail" style="max-height: 150px;">
            </div>
        @endforeach
    </div>
</div>
@endsection
