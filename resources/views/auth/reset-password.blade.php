@extends('auth.layout')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
<div class="auth-card">
    <h2>Nouveau mot de passe</h2>
    <p class="subtitle">Choisissez un nouveau mot de passe sécurisé pour votre compte.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        {{-- Token (hidden) --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email --}}
        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $email) }}"
                autocomplete="email"
                autofocus
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                placeholder="vous@exemple.com"
            >
            @error('email')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- New password --}}
        <div class="form-group">
            <label for="password">Nouveau mot de passe</label>
            <input
                id="password"
                type="password"
                name="password"
                autocomplete="new-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="Minimum 8 caractères"
            >
            @error('password')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm new password --}}
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                autocomplete="new-password"
                placeholder="••••••••"
            >
        </div>

        <button type="submit" class="btn-primary">Réinitialiser le mot de passe</button>
    </form>
</div>
@endsection
