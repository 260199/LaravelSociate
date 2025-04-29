
<div class="container">
    <h2>Set Password</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('alert'))
    <div style="background-color: #ffeeba; color: #856404; padding: 10px; border: 1px solid #ffeeba; margin-bottom: 15px;">
        {{ session('alert') }}
    </div>
@endif


    <form method="POST" action="{{ route('setup-password.submit') }}">
        @csrf
        <div>
            <label>Password Baru:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <div>
            <label>Konfirmasi Password:</label><br>
            <input type="password" name="password_confirmation" required>
        </div>
        <br>
        <button type="submit">Simpan Password</button>
    </form>
</div>
