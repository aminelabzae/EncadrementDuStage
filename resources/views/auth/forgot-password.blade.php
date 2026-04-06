@extends('auth.layout')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="auth-card">
    <h2>Mot de passe oublié ?</h2>
    <p class="subtitle">
        Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
    </p>

    {{-- Success status --}}
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="email">Adresse e-mail</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autofocus
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                placeholder="vous@exemple.com"
            >
            @error('email')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary">Envoyer le lien de réinitialisation</button>
    </form>
</div>

<div class="auth-footer">
    <a href="{{ route('login') }}" class="text-link">← Retour à la connexion</a>
</div>
@endsection
