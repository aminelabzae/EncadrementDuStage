@extends('auth.layout')

@section('title', 'Connexion')

@section('content')
<div class="auth-card">
    <h2>Bon retour 👋</h2>
    <p class="subtitle">Connectez-vous à votre compte pour continuer.</p>

    {{-- Status message (e.g. after successful password reset) --}}
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                autofocus
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                placeholder="vous@exemple.com"
            >
            @error('email')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input
                id="password"
                type="password"
                name="password"
                autocomplete="current-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="••••••••"
            >
            @error('password')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember / Forgot --}}
        <div class="form-row">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                Se souvenir de moi
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-link">Mot de passe oublié ?</a>
            @endif
        </div>

        <button type="submit" class="btn-primary">Se connecter</button>
    </form>
</div>

<div class="auth-footer">
    Pas encore de compte ?
    <a href="{{ route('register') }}" class="text-link">Créer un compte</a>
</div>
@endsection
