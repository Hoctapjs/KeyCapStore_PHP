@if ($errors->any())
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <label for="email">Email:</label>
    <input type="email" name="email" value="{{ $email ?? old('email') }}" required>

    <label for="password">Mật khẩu mới:</label>
    <input type="password" name="password" required>

    <label for="password_confirmation">Xác nhận Mật khẩu:</label>
    <input type="password" name="password_confirmation" required>

    <button type="submit">Cập nhật Mật khẩu</button>
</form>