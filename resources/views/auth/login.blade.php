<x-layout title="Sign in">
    <h1>Welcome back</h1>

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}">
        @error('email') <p class="error">{{ $message }}</p> @enderror

        <label for="password">Password</label>
        <input id="password" name="password" type="password">
        @error('password') <p class="error">{{ $message }}</p> @enderror

        <button type="submit">Sign in</button>
    </form>
</x-layout>
