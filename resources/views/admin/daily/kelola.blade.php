@extends('layouts.main')

@section('isi')
<div class="container">
    <h3>Kelola Daily Belum Selesai</h3>

    <form method="POST" action="{{ route('daily.submitMultiple') }}">
        @csrf
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Kegiatan</th>
                    <th>Upload Bukti</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailies as $daily)
                <tr>
                    <td><input type="checkbox" name="selected_dailies[]" value="{{ $daily->id }}"></td>
                    <td>{{ $daily->kegiatan }}</td>
                    <td>
                        <form action="{{ route('daily.uploadBukti', $daily->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="photos[]" multiple required>
                            <button class="btn btn-sm btn-primary mt-1">Upload</button>
                        </form>
                        @if($daily->photos->count())
                            <span class="text-success">Bukti: {{ $daily->photos->count() }} file</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Kirim Laporan untuk Yang Dipilih</button>
    </form>
</div>

<script>
document.getElementById('selectAll').addEventListener('click', function() {
    document.querySelectorAll('input[name="selected_dailies[]"]').forEach(cb => cb.checked = this.checked);
});
</script>
@endsection
