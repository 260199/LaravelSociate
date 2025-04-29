@extends('layouts.main')
@section('isi')
<div class="container">
    <h4>Daily Activity Belum Dilaporkan</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('daily.sendReport') }}" method="POST">
        @csrf
        @foreach($daily as $activity)
            <div class="card my-3">
                <div class="card-body">
                    <input type="checkbox" name="activity_ids[]" value="{{ $activity->id }}"
                        {{ $activity->photos->count() == 0 ? 'disabled' : '' }}>
                    <strong>{{ $activity->nama }}</strong><br>
                    Jenis: {{ $activity->jenis }}<br>
                    Tanggal: {{ $activity->created_at }}<br>
                    Deskripsi: {{ $activity->deskripsi }}<br>

                    <div class="mt-2">
                        <form action="{{ route('daily.uploadPhoto', $activity->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="photos[]" multiple required>
                            <button type="submit" class="btn btn-primary">Upload Bukti</button>
                        </form>
                    </div>

                    @if($activity->photos->count())
                        <div class="mt-2">
                            <strong>Bukti Upload:</strong><br>
                            @foreach($activity->photos as $photo)
                                <img src="{{ asset('storage/' . $photo->file_path) }}" width="100" class="me-2 mb-2">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
    </form>
</div>
@endsection