@extends('auth.layout')

@section('title', 'Inscription')

@section('content')
<div class="auth-card">
    <h2>Créer un compte</h2>
    <p class="subtitle">Rejoignez la plateforme de gestion des stages.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div class="form-group">
            <label for="name">Nom complet</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                autocomplete="name"
                autofocus
                class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
placeholder="Amine Labzae"
            >
            @error('name')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
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
                autocomplete="new-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="Minimum 8 caractères"
            >
            @error('password')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm password --}}
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                autocomplete="new-password"
                placeholder="••••••••"
            >
        </div>

        <button type="submit" class="btn-primary">Créer mon compte</button>
    </form>
</div>

<div class="auth-footer">
    Déjà inscrit ?
    <a href="{{ route('login') }}" class="text-link">Se connecter</a>
</div>
@endsection
