@extends('layouts.admin')

@section('title', 'Chat dengan ' . $user->nama_lengkap . ' — TuhomesTay')

@section('styles')
<style>
    .chat-header-bar {
        display: flex; align-items: center; gap: 14px;
        background: #fff;
        border: 1px solid var(--line-strong);
        border-radius: 16px;
        padding: 14px 18px;
        margin-bottom: 16px;
    }
    .chat-header-bar .avatar {
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
    .chat-header-bar .avatar img { width: 100%; height: 100%; object-fit: cover; }
    .chat-header-bar .info { flex: 1; min-width: 0; }
    .chat-header-bar .info h3 {
        margin: 0; font-size: 15px; font-weight: 800;
        color: var(--ink);
    }
    .chat-header-bar .info p {
        margin: 2px 0 0; font-size: 12px; color: var(--ink-soft);
    }
    .chat-header-bar .back-btn {
        font-size: 12.5px; font-weight: 700;
        padding: 8px 16px;
        border-radius: 999px;
        border: 1px solid var(--line-strong);
        background: #fff;
        color: var(--ink);
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: background 0.15s, border-color 0.15s;
    }
    .chat-header-bar .back-btn:hover {
        background: var(--brown-tint);
        border-color: var(--brown);
    }

    .chat-panel {
        background: #fff;
        border: 1px solid var(--line-strong);
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 280px);
        min-height: 500px;
        max-height: 720px;
    }

    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        background: #FAF7F2;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .chat-body::-webkit-scrollbar { width: 6px; }
    .chat-body::-webkit-scrollbar-thumb { background: #D1B89A; border-radius: 3px; }

    .msg-row {
        display: flex; gap: 10px;
        margin-bottom: 14px;
        align-items: flex-end;
    }
    .msg-row.me { flex-direction: row-reverse; }

    .msg-avatar {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: #E8DDD0;
        color: #7B5E4A;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 800;
        flex-shrink: 0;
        overflow: hidden;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(43,35,32,0.06);
    }
    .msg-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .msg-row.me .msg-avatar {
        background: #7B5E4A;
        color: #fff;
    }

    .msg-bubble {
        max-width: 68%;
        padding: 12px 18px;
        border-radius: 20px;
        font-size: 13.5px;
        line-height: 1.55;
        word-wrap: break-word;
        box-shadow: 0 3px 12px rgba(43,35,32,0.05);
    }
    .msg-row:not(.me) .msg-bubble {
        background: #fff;
        color: var(--ink);
        border: 1px solid #EBE0D2;
        border-bottom-left-radius: 6px;
    }
    .msg-row.me .msg-bubble {
        background: linear-gradient(135deg, #7B5E4A 0%, #5C4535 100%);
        color: #fff;
        border-bottom-right-radius: 6px;
    }

    .msg-time {
        font-size: 10.5px;
        margin-top: 5px;
        opacity: 0.65;
        text-align: right;
    }
    .msg-row:not(.me) .msg-time { text-align: left; }

    .chat-footer {
        padding: 14px 18px;
        background: #fff;
        border-top: 1px solid var(--line);
        display: flex; gap: 10px;
        align-items: center;
    }
    .chat-footer input {
        flex: 1;
        border: 1.5px solid var(--line-strong);
        border-radius: 999px;
        padding: 12px 20px;
        font-family: inherit;
        font-size: 13.5px;
        color: var(--ink);
        outline: none;
        background: #FAF7F2;
        transition: all .2s;
    }
    .chat-footer input:focus {
        border-color: var(--brown);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(123, 94, 74, 0.08);
    }
    .chat-footer button {
        width: 46px; height: 46px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #7B5E4A 0%, #5C4535 100%);
        color: #fff; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px;
        transition: transform .2s, box-shadow .2s;
        flex-shrink: 0;
    }
    .chat-footer button:hover {
        transform: scale(1.06);
        box-shadow: 0 6px 18px rgba(123, 94, 74, 0.4);
    }

    .empty-chat {
        text-align: center;
        margin: auto;
        color: var(--ink-soft);
        padding: 40px 20px;
    }
    .empty-chat svg {
        width: 60px; height: 60px;
        opacity: 0.3; margin-bottom: 12px;
        color: var(--brown);
    }

    .quick-reply {
        display: flex; gap: 6px;
        padding: 8px 18px 0;
        background: #fff;
        flex-wrap: wrap;
    }
    .quick-reply button {
        font-size: 11.5px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 999px;
        border: 1px solid var(--line-strong);
        background: #fff;
        color: var(--ink-soft);
        cursor: pointer;
        font-family: inherit;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
    }
    .quick-reply button:hover {
        background: var(--brown-tint);
        border-color: var(--brown);
        color: var(--brown);
    }

    @media (max-width: 768px) {
        .chat-panel { height: calc(100vh - 220px); }
        .chat-body { padding: 16px; }
        .msg-bubble { max-width: 80%; font-size: 13px; }
    }
</style>
@endsection

@section('content')

{{-- HEADER --}}
<div class="chat-header-bar">
    <div class="avatar">
        @if($user->photo)
            <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->nama_lengkap }}">
        @else
            {{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}
        @endif
    </div>
    <div class="info">
        <h3>{{ $user->nama_lengkap }}</h3>
        <p>{{ $user->email }} @if($user->no_telp) · {{ $user->no_telp }} @endif</p>
    </div>
    <a href="{{ route('admin.chat.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        {{ __('Kembali') }}
    </a>
</div>

{{-- PANEL CHAT --}}
<div class="chat-panel">
    <div class="chat-body" id="admin-chat-box">
        @if($messages->isEmpty())
            <div class="empty-chat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <p style="font-size: 14px;">{{ __('Belum ada pesan.') }}</p>
                <p style="font-size: 12px; margin-top: 4px;">{{ __('Mulai percakapan dengan tamu ini.') }}</p>
            </div>
        @else
            @foreach($messages as $msg)
                @php $isMe = $msg->sender_id == auth()->id(); @endphp
                <div class="msg-row {{ $isMe ? 'me' : '' }}">
                    <div class="msg-avatar">
                        @if($isMe)
                            <i class="fa-solid fa-headset"></i>
                        @else
                            {{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <div class="msg-bubble">
                        {{ $msg->message }}
                        <div class="msg-time">{{ $msg->created_at->format('d/m H:i') }}</div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Quick Reply (kalau ada konteks booking) --}}
    @if($kodeBooking)
        <div class="quick-reply">
            <button onclick="setQuickReply('Halo {{ $user->nama_lengkap }}, terima kasih sudah memesan. Reservasi dengan kode {{ $kodeBooking }} sudah kami terima.')">
                ✓ Konfirmasi terima
            </button>
            <button onclick="setQuickReply('Halo {{ $user->nama_lengkap }}, untuk reservasi {{ $kodeBooking }}, mohon konfirmasi kedatangan Anda.')">
                📅 Konfirmasi kedatangan
            </button>
            <button onclick="setQuickReply('Halo {{ $user->nama_lengkap }}, ada yang bisa kami bantu terkait reservasi {{ $kodeBooking }}?')">
                💬 Tanya kebutuhan
            </button>
        </div>
    @endif

    <div class="chat-footer">
        <input type="text" id="admin-message-input" placeholder="{{ __('Ketik balasan...') }}" autocomplete="off">
        <button onclick="sendAdminReply()" title="{{ __('Kirim') }}">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const adminChatBox = document.getElementById('admin-chat-box');
    adminChatBox.scrollTop = adminChatBox.scrollHeight;

    // Auto-fill pesan jika ada kode_booking
    @if($kodeBooking)
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('admin-message-input');
            input.value = "Halo {{ $user->nama_lengkap }}, saya mau konfirmasi reservasi dengan kode {{ $kodeBooking }}. Apakah ada yang bisa saya bantu?";
            input.focus();
        });
    @endif

    function setQuickReply(text) {
        document.getElementById('admin-message-input').value = text;
        document.getElementById('admin-message-input').focus();
    }

    function sendAdminReply() {
        const input = document.getElementById('admin-message-input');
        let message = input.value.trim();
        if (message === '') return;

        // Disable input sementara
        input.disabled = true;

        fetch("{{ route('admin.chat.reply', $user->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                input.value = '';
                input.disabled = false;
                location.reload();
            } else {
                input.disabled = false;
                alert('{{ __("Gagal mengirim pesan.") }}');
            }
        })
        .catch(err => {
            console.error(err);
            input.disabled = false;
            alert('{{ __("Terjadi kesalahan. Coba lagi.") }}');
        });
    }

    document.getElementById('admin-message-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendAdminReply();
    });

    // Auto-refresh setiap 8 detik (untuk melihat pesan baru)
    // Tapi pause kalau user sedang mengetik
    setInterval(() => {
        const input = document.getElementById('admin-message-input');
        if (document.activeElement !== input && input.value === '') {
            location.reload();
        }
    }, 8000);
</script>
@endsection