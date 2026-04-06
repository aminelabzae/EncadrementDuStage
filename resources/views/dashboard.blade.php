<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-deep:   #f0f6ff;
            --bg-sidebar:#ffffff;
            --bg-card:   #ffffff;
            --border:    #dde8f8;
            --accent:    #2563eb;
            --accent-glow: rgba(37,99,235,0.2);
            --text-main: #1e3a5f;
            --text-muted:#64748b;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-deep);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        aside {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 20;
            box-shadow: 2px 0 8px rgba(37,99,235,0.06);
        }

        .brand {
            padding: 1.5rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.1rem;
            font-weight: 700;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }

        .brand-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--accent), #6366f1);
            display: flex; align-items: center; justify-content: center;
        }

        .brand-icon svg { width: 18px; height: 18px; color: #fff; }

        .nav-menu {
            padding: 1.5rem 1rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
            padding-left: 0.75rem;
            font-weight: 600;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-item:hover {
            background: rgba(37,99,235,0.07);
            color: var(--accent);
        }

        .nav-item.active {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }

        .nav-item svg { width: 20px; height: 20px; opacity: 0.8; }
        .nav-item.active svg { opacity: 1; }

        .user-profile {
            padding: 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: rgba(37,99,235,0.12);
            display: flex; align-items: center; justify-content: center;
            font-weight: 600;
            color: var(--accent);
        }

        .user-info { flex-grow: 1; overflow: hidden; }
        .user-name { font-size: 0.9rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 0.75rem; color: var(--text-muted); }

        .btn-logout {
            background: transparent;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.2s;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.08); }
        .btn-logout svg { width: 18px; height: 18px; }

        /* Main content */
        .main-wrapper {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            background-image:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(37,99,235,0.05) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 90%, rgba(99,102,241,0.04) 0%, transparent 60%);
        }

        header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header-title { font-size: 1.25rem; font-weight: 600; color: var(--text-main); }

        main { padding: 2rem; max-width: 1400px; margin: 0 auto; }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }
        .h1-accent { color: var(--accent); }

        .lead {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Stat cards grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
            transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: var(--card-color, var(--border));
        }

        .stat-card:hover {
            border-color: #bfdbfe;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37,99,235,0.1);
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon svg { width: 24px; height: 24px; }

        .stat-info { flex-grow: 1; }
        .stat-value { font-size: 1.8rem; font-weight: 700; line-height: 1; margin-bottom: 0.4rem; color: var(--text-main); }
        .stat-label { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; }

        /* Card Colors */
        .color-blue { --card-color: #3b82f6; }
        .color-blue .stat-icon { background: rgba(59,130,246,0.1); color: #3b82f6; }

        .color-indigo { --card-color: #6366f1; }
        .color-indigo .stat-icon { background: rgba(99,102,241,0.1); color: #6366f1; }

        .color-purple { --card-color: #8b5cf6; }
        .color-purple .stat-icon { background: rgba(139,92,246,0.1); color: #8b5cf6; }

        .color-pink { --card-color: #ec4899; }
        .color-pink .stat-icon { background: rgba(236,72,153,0.1); color: #ec4899; }

        .color-emerald { --card-color: #10b981; }
        .color-emerald .stat-icon { background: rgba(16,185,129,0.1); color: #10b981; }

        .color-amber { --card-color: #f59e0b; }
        .color-amber .stat-icon { background: rgba(245,158,11,0.1); color: #f59e0b; }

        /* Quick actions & Recent activity */
        .dashboard-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 1024px) {
            .dashboard-layout { grid-template-columns: 1fr; }
        }

        .panel {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .panel-title { font-size: 1.1rem; font-weight: 600; color: var(--text-main); }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            background: rgba(37,99,235,0.07);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text-main);
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-sm:hover { background: rgba(37,99,235,0.14); border-color: #bfdbfe; color: var(--accent); }

        /* Activity List */
        .activity-list { display: flex; flex-direction: column; gap: 1rem; }
        .activity-item {
            display: flex; gap: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(37,99,235,0.08);
        }
        .activity-item:last-child { border-bottom: none; padding-bottom: 0; }

        .activity-icon {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: rgba(37,99,235,0.08);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .activity-icon svg { width: 14px; height: 14px; color: var(--accent); }

        .activity-content p { font-size: 0.9rem; margin-bottom: 0.2rem; }
        select, select option {
            background-color: #f8faff !important;
            color: var(--text-main) !important;
        }

        input, textarea, select {
            background: #f8faff !important;
            border: 1px solid var(--border) !important;
            color: var(--text-main) !important;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside>
        <div class="brand">
            <div class="brand-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            StagesApp
        </div>

        <nav class="nav-menu">
            <div class="nav-label">Tableau de bord</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Vue d'ensemble
            </a>

            @if(!Auth::user()->hasRole('STAGIAIRE'))
            <div class="nav-label" style="margin-top: 1.5rem;">Gestion</div>
            <a href="{{ route('stages.index') }}" class="nav-item {{ request()->routeIs('stages.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Stages
            </a>
            <a href="{{ route('entreprises.index') }}" class="nav-item {{ request()->routeIs('entreprises.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Entreprises
            </a>
            @if(Auth::user()->hasRole('ADMIN'))
            <a href="{{ route('encadrants.index') }}" class="nav-item {{ request()->routeIs('encadrants.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Encadrants
            </a>
            <a href="{{ route('stagiaires.index') }}" class="nav-item {{ request()->routeIs('stagiaires.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Stagiaires
            </a>
            @endif
            @endif

            <div class="nav-label" style="margin-top: 1.5rem;">Suivi</div>
            <a href="{{ route('evaluations.index') }}" class="nav-item {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138z"/></svg>
                Évaluations
            </a>
            <a href="{{ route('visites.index') }}" class="nav-item {{ request()->routeIs('visites.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Prochaines Visites
                @if(isset($visites_count) && $visites_count > 0)
                    <span style="margin-left: auto; background: rgba(245,158,11,0.2); color: #f59e0b; font-size: 0.7rem; padding: 0.1rem 0.4rem; border-radius: 4px; font-weight: 600;">{{ $visites_count }}</span>
                @endif
            </a>
            <a href="{{ route('journaux.index') }}" class="nav-item {{ request()->routeIs('journaux.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Journaux
            </a>
        </nav>


    </aside>

    <!-- Main Content -->
    <div class="main-wrapper">
        <header>
            <div class="header-title">@yield('title', "Vue d'ensemble")</div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <span style="font-size: 0.85rem; color: var(--text-muted); background: rgba(37,99,235,0.06); padding: 0.4rem 0.8rem; border-radius: 100px; border: 1px solid var(--border);">
                    Année Universitaire: {{ $current_year ?? '2025-2026' }}
                </span>

                @if(Auth::user()->hasRole('ADMIN'))
                <div style="display: flex; gap: 0.5rem; align-items: center; margin-left: 0.5rem;">
                    <a href="{{ route('export.stages.excel') }}" class="btn-sm" title="Exporter tous les stages">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Stages
                    </a>
                    <a href="{{ route('export.stagiaires.excel') }}" class="btn-sm" title="Exporter tous les stagiaires">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Stagiaires
                    </a>
                </div>
                @endif
                <div style="display: flex; align-items: center; gap: 0.75rem; padding-left: 1rem; border-left: 1px solid var(--border);">
                    <div class="avatar" style="width:34px;height:34px;font-size:0.85rem;">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</div>
                    <div>
                        <div style="font-size:0.85rem;font-weight:600;color:var(--text-main);">{{ Auth::user()->name ?? 'Utilisateur' }}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted);">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-logout" title="Se déconnecter">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main>
            @hasSection('content')
                @yield('content')
            @else
            <h1>Bonjour, <span class="h1-accent">{{ explode(' ', Auth::user()->name ?? 'Utilisateur')[0] }}</span></h1>
            <p class="lead">
                Voici un aperçu de l'activité sur la plateforme d'encadrement des stages pour l'année en cours.
            </p>
            @if(Auth::user()->hasRole('STAGIAIRE'))
                {{-- ── Stagiaire Personal Dashboard ── --}}
                @if(isset($my_stage) && $my_stage)

                {{-- Stat cards --}}
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem;margin-bottom:2rem;">
                    <div class="stat-card color-indigo">
                        <div class="stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                        <div class="stat-info">
                            <div class="stat-value" style="font-size:1.1rem;">{{ $my_statut }}</div>
                            <div class="stat-label">Statut du Stage</div>
                        </div>
                    </div>
                    <div class="stat-card color-blue">
                        <div class="stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $my_journaux_count }}</div>
                            <div class="stat-label">Entrées de Journal</div>
                        </div>
                    </div>
                    <div class="stat-card color-emerald">
                        <div class="stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138z"/></svg></div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $my_eval_label }}</div>
                            <div class="stat-label">Note Moyenne ({{ $my_eval_count }} éval.)</div>
                        </div>
                    </div>
                    <div class="stat-card color-amber">
                        <div class="stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                        <div class="stat-info">
                            <div class="stat-value" style="font-size:1.1rem;">{{ $my_days_left }}</div>
                            <div class="stat-label">Jours restants</div>
                        </div>
                    </div>
                </div>

                {{-- Stage details + quick actions --}}
                <div class="dashboard-layout">
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-title">Mon Stage</div>
                            <a href="{{ route('stages.index') }}" class="btn-sm">Voir détails</a>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:0.85rem;">
                            @foreach($my_stage_rows as $row)
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.85rem 1rem;background:rgba(37,99,235,0.04);border:1px solid rgba(37,99,235,0.1);border-radius:8px;">
                                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;">{{ $row[0] }}</div>
                                <div style="font-weight:600;text-align:right;max-width:65%;">{{ $row[1] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-header"><div class="panel-title">Actions rapides</div></div>
                        <div style="display:flex;flex-direction:column;gap:0.75rem;">
                            <a href="{{ route('journaux.create') }}" style="display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;background:rgba(37,99,235,0.07);border:1px solid rgba(37,99,235,0.2);border-radius:10px;text-decoration:none;color:var(--text-main);">
                                <div style="width:36px;height:36px;background:rgba(37,99,235,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="#4f6ef7" stroke-width="2" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:0.95rem;">Rédiger un journal</div>
                                    <div style="font-size:0.78rem;color:var(--text-muted);">Ajouter une nouvelle entrée</div>
                                </div>
                            </a>
                            <a href="{{ route('journaux.index') }}" style="display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;background:rgba(37,99,235,0.04);border:1px solid rgba(37,99,235,0.1);border-radius:10px;text-decoration:none;color:var(--text-main);">
                                <div style="width:36px;height:36px;background:rgba(37,99,235,0.08);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:0.95rem;">Mes journaux</div>
                                    <div style="font-size:0.78rem;color:var(--text-muted);">{{ $my_journaux_count }} entrée(s) rédigée(s)</div>
                                </div>
                            </a>
                            <a href="{{ route('evaluations.index') }}" style="display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;background:rgba(37,99,235,0.04);border:1px solid rgba(37,99,235,0.1);border-radius:10px;text-decoration:none;color:var(--text-main);">
                                <div style="width:36px;height:36px;background:rgba(37,99,235,0.08);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138z"/></svg>
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:0.95rem;">Mes évaluations</div>
                                    <div style="font-size:0.78rem;color:var(--text-muted);">{{ $my_eval_count }} évaluation(s) reçue(s)</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                @else
                <div class="panel" style="padding:3rem;text-align:center;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="width:56px;height:56px;margin:0 auto 1rem;opacity:.4;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <h2 style="font-size:1.1rem;margin-bottom:.5rem;">Aucun stage assigné</h2>
                    <p style="color:var(--text-muted);font-size:.9rem;">Votre stage n'a pas encore été enregistré. Contactez votre administrateur.</p>
                </div>
                @endif
            @elseif(Auth::user()->hasRole('ENCADRANT'))
                {{-- ── Encadrant Personal Dashboard ── --}}
                <div class="stats-grid">
                    <!-- Mes Stagiaires -->
                    <div class="stat-card color-blue">
                        <div class="stat-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $my_stagiaires_count ?? 0 }}</div>
                            <div class="stat-label">Mes Stagiaires</div>
                        </div>
                    </div>

                    <!-- Mes Stages Actifs -->
                    <div class="stat-card color-indigo">
                        <div class="stat-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $my_active_stages_count ?? 0 }}</div>
                            <div class="stat-label">Stages en cours</div>
                        </div>
                    </div>

                    <!-- Évaluations -->
                    <div class="stat-card color-emerald">
                        <div class="stat-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138z"/></svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $my_evaluations_count ?? 0 }}</div>
                            <div class="stat-label">Évaluations réalisées</div>
                        </div>
                    </div>

                    <!-- Visites -->
                    <div class="stat-card color-amber">
                        <div class="stat-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $my_upcoming_visites_count ?? 0 }}</div>
                            <div class="stat-label">Prochaines visites</div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-layout">
                    <!-- Left Column: Journals of my stagiaires -->
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-title">Derniers Journaux de mes Stagiaires</div>
                            <a href="{{ route('journaux.index') }}" class="btn-sm">Voir tout</a>
                        </div>
                        <div class="activity-list">
                            @forelse($my_recent_journaux ?? [] as $journal)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div class="activity-content">
                                    <p><strong>{{ $journal->stage->stagiaire->utilisateur->name }}</strong> a soumis une entrée de journal.</p>
                                    <div class="activity-time" style="font-size: 0.75rem; color: var(--text-muted);">Stage: {{ $journal->stage->entreprise->nom ?? 'N/A' }} — {{ $journal->date->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            @empty
                            <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 1rem;">Aucune activité de vos stagiaires.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Right Column: My upcoming visits -->
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-title">Mes Prochaines Visites</div>
                            <a href="{{ route('visites.index') }}" class="btn-sm">Agenda</a>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @forelse($my_upcoming_visites ?? [] as $visite)
                            <div style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); border-left: 3px solid #f59e0b; padding: 1rem; border-radius: 8px;">
                                <div style="font-weight: 600; font-size: 0.95rem; margin-bottom: 0.25rem;">{{ $visite->type }} : {{ $visite->stage->entreprise->nom ?? 'N/A' }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $visite->date->format('d M Y') }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Stagiaire: {{ $visite->stage->stagiaire->utilisateur->name ?? 'N/A' }}
                                </div>
                            </div>
                            @empty
                            <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 1rem;">Aucune visite prévue.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                {{-- ── Admin Global Dashboard ── --}}
                <div class="stats-grid">
                    <!-- Stagiaires -->
                    <a href="{{ route('stagiaires.index') }}" class="stat-card color-blue" style="text-decoration: none;">
                        <div class="stat-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $stagiaires_count }}</div>
                            <div class="stat-label">Stagiaires inscrits</div>
                        </div>
                    </a>

                    <!-- Stages -->
                    <a href="{{ route('stages.index') }}" class="stat-card color-indigo" style="text-decoration: none;">
                        <div class="stat-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $stages_count }}</div>
                            <div class="stat-label">Stages en cours</div>
                        </div>
                    </a>

                    <!-- Encadrants -->
                    <a href="{{ route('encadrants.index') }}" class="stat-card color-purple" style="text-decoration: none;">
                        <div class="stat-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $encadrants_count }}</div>
                            <div class="stat-label">Encadrants actifs</div>
                        </div>
                    </a>

                    <!-- Entreprises -->
                    <a href="{{ route('entreprises.index') }}" class="stat-card color-pink" style="text-decoration: none;">
                        <div class="stat-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $entreprises_count }}</div>
                            <div class="stat-label">Entreprises partenaires</div>
                        </div>
                    </a>

                    <!-- Évaluations -->
                    <a href="{{ route('evaluations.index') }}" class="stat-card color-emerald" style="text-decoration: none;">
                        <div class="stat-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $evaluations_count }}</div>
                            <div class="stat-label">Évaluations totales</div>
                        </div>
                    </a>

                    <!-- Visites -->
                    <a href="{{ route('visites.index') }}" class="stat-card color-amber" style="text-decoration: none;">
                        <div class="stat-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $visites_count }}</div>
                            <div class="stat-label">Prochaines visites</div>
                        </div>
                    </a>
                </div>

                <div class="dashboard-layout">
                    <!-- Global Activity (Admin) -->
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-title">Derniers Journaux d'Activité</div>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('journaux.create') }}" class="btn-sm" style="background: var(--accent); color: white; border: none;">+ Nouveau</a>
                                <a href="{{ route('journaux.index') }}" class="btn-sm">Voir tout</a>
                            </div>
                        </div>
                        <div class="activity-list">
                            @forelse($recent_journaux as $journal)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div class="activity-content">
                                    <p><strong>{{ $journal->stage->stagiaire->utilisateur->name }}</strong> a soumis une entrée de journal pour le stage chez <strong>{{ $journal->stage->entreprise->nom ?? 'N/A' }}</strong>.</p>
                                    <div class="activity-time" style="font-size: 0.75rem; color: var(--text-muted);">{{ $journal->date->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            @empty
                            <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 1rem;">Aucune activité récente.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-title">Prochaines Visites</div>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('visites.create') }}" class="btn-sm" style="background: #f59e0b; color: #1e3a5f; border: none; font-weight: 600;">+ Nouvelle</a>
                                <a href="{{ route('visites.index') }}" class="btn-sm">Agenda</a>
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @forelse($upcoming_visites as $visite)
                            <div style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); border-left: 3px solid #f59e0b; padding: 1rem; border-radius: 8px;">
                                <div style="font-weight: 600; font-size: 0.95rem; margin-bottom: 0.25rem;">{{ $visite->type }} : {{ $visite->stage->entreprise->nom ?? 'N/A' }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $visite->date->format('d M Y') }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Stagiaire: {{ $visite->stage->stagiaire->utilisateur->name ?? 'N/A' }}
                                </div>
                            </div>
                            @empty
                            <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 1rem;">Aucune visite à venir.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
            @endif

        </main>
    </div>

</body>
</html>
