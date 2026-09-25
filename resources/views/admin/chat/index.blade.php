@extends('layouts.admin')

@section('title', 'Chat Masuk — TuhomesTay')

@section('content')

<h1 class="hero">{{ __('Chat Masuk') }}</h1>
<p class="hero-sub">{{ __('Daftar percakapan antara tamu dan admin. Klik salah satu untuk membalas.') }}</p>

<div class="panel has-border">
    <div class="panel-head">
        <h2>{{ __('Daftar Percakapan') }}</h2>
        <span class="badge-brown">{{ $users->count() }} {{ __('percakapan') }}</span>
    </div>

    @if($users->isEmpty())
        <div style="text-align:center; padding: 50px 20px; color: var(--ink-soft);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                 style="width: 60px; height: 60px; opacity: 0.3; margin-bottom: 12px;">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            <p style="font-size: 14px;">{{ __('Belum ada chat masuk.') }}</p>
            <p style="font-size: 12px; margin-top: 4px;">{{ __('Chat akan muncul di sini setelah tamu mengirim pesan.') }}</p>
        </div>
    @else
        <div class="chat-list">
            @foreach($users as $user)
                @php
                    $inisial = strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1));
                    $unread = $user->unread_count ?? 0;
                @endphp
                <a href="{{ route('admin.chat.show', $user->id) }}" class="chat-item">
                    <div class="chat-avatar">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->nama_lengkap }}">
                        @else
                            {{ $inisial }}
                        @endif
                    </div>
                    <div class="chat-info">
                        <div class="chat-name">{{ $user->nama_lengkap }}</div>
                        <div class="chat-meta">{{ $user->email }}</div>
                    </div>
                    @if($unread > 0)
                        <span class="chat-badge">{{ $unread }}</span>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             style="width: 18px; height: 18px; color: var(--ink-soft);">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</div>

@endsection

@section('styles')
<style>
    .chat-list { display: flex; flex-direction: column; gap: 6px; }

    .chat-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid var(--line);
        background: #fff;
        text-decoration: none;
        color: var(--ink);
        transition: background 0.15s, border-color 0.15s, transform 0.1s;
    }
    .chat-item:hover {
        background: var(--brown-tint);
        border-color: var(--brown);
        transform: translateY(-1px);
    }

    .chat-avatar {
        width: 46px; height: 46px;
        border-radius: 50%;
        background: var(--brown-tint);
        color: var(--brown);
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 16px;
        flex-shrink: 0;
        overflow: hidden;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(43,35,32,0.08);
    }
    .chat-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .chat-info { flex: 1; min-width: 0; }
    .chat-name {
        font-size: 14px; font-weight: 700;
        color: var(--ink);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .chat-meta {
        font-size: 12px; color: var(--ink-soft);
        margin-top: 2px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .chat-badge {
        min-width: 22px; height: 22px;
        padding: 0 7px;
        background: #E73D23; color: #fff;
        font-size: 11px; font-weight: 800;
        border-radius: 999px;
        display: inline-flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
</style>
@endsection