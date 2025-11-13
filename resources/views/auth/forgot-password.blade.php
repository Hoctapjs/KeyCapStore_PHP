@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    {{ $errors->first('email') }}
</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <label for="email">Email:</label>
    <input type="email" name="email" value="{{ old('email') }}" required>
    <button type="submit">Gửi link Reset</button>
</form>