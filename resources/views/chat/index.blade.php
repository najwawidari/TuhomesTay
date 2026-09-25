@extends('layouts.public')

@section('title', 'Chat dengan Admin - Tuhomestay')

@push('styles')
<style>
    .chat-page {
        max-width: 760px;
        margin: 100px auto 60px;
        padding: 0 16px;
    }

    .chat-card {
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 12px 48px rgba(59, 42, 32, 0.10);
        display: flex;
        flex-direction: column;
        height: 600px;
    }

    /* ============ HEADER ============ */
    .chat-header {
        background: linear-gradient(135deg, #7B5E4A 0%, #5C4535 100%);
        color: #fff;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
    }
    .chat-header .avatar-admin {
        width: 46px; height: 46px;
        background: rgba(255,255,255,0.18);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        border: 2px solid rgba(255,255,255,0.25);
    }
    .chat-header-info h4 {
        margin: 0; font-size: 1rem; font-weight: 600;
        letter-spacing: 0.2px;
    }
    .chat-header-info .status {
        font-size: 0.75rem; opacity: 0.9;
        display: flex; align-items: center; gap: 5px;
        margin-top: 3px;
    }
    .chat-header-info .status .dot {
        width: 7px; height: 7px; background: #8FC93A;
        border-radius: 50%; display: inline-block;
        box-shadow: 0 0 0 3px rgba(143, 201, 58, 0.3);
    }

    /* ============ BODY ============ */
    .chat-body {
        flex: 1;
        padding: 28px 24px;
        overflow-y: auto;
        background: #FAF7F2;
        display: flex;
        flex-direction: column;
        gap: 6px;
        background-image:
            radial-gradient(circle at 20% 30%, rgba(209, 184, 154, 0.08) 0%, transparent 40%),
            radial-gradient(circle at 80% 70%, rgba(123, 94, 74, 0.05) 0%, transparent 40%);
    }
    .chat-body::-webkit-scrollbar { width: 6px; }
    .chat-body::-webkit-scrollbar-thumb {
        background: #D1B89A; border-radius: 3px;
    }

    /* ============ BUBBLE / SPEECH BUBBLE ============ */
    .msg-row {
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
        align-items: flex-end;
        animation: fadeInUp 0.25s ease;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .msg-row.me { flex-direction: row-reverse; }

    .msg-avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: #E8DDD0;
        color: #7B5E4A;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.82rem; font-weight: 700;
        flex-shrink: 0;
        border: 2px solid #fff;
        box-shadow: 0 3px 10px rgba(59, 42, 32, 0.08);
        overflow: hidden;
    }
    .msg-avatar img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .msg-row.me .msg-avatar {
        background: #7B5E4A;
        color: #fff;
    }

    .msg-bubble {
        max-width: 68%;
        padding: 14px 20px;
        border-radius: 24px;
        font-size: 0.9rem;
        line-height: 1.55;
        position: relative;
        word-wrap: break-word;
        box-shadow: 0 4px 16px rgba(59, 42, 32, 0.06);
    }

    /* === Bubble dari ADMIN (kiri) === */
    .msg-row:not(.me) .msg-bubble {
        background: #fff;
        color: #3B2A20;
        border: 1px solid #EBE0D2;
        border-bottom-left-radius: 6px;
    }
    /* Ekor speech bubble admin */
    .msg-row:not(.me) .msg-bubble::before {
        content: '';
        position: absolute;
        bottom: -1px;
        left: -8px;
        width: 18px;
        height: 18px;
        background: #fff;
        border-left: 1px solid #EBE0D2;
        border-bottom: 1px solid #EBE0D2;
        border-bottom-left-radius: 18px;
        clip-path: polygon(100% 0, 100% 100%, 0 100%);
    }

    /* === Bubble dari USER (kanan) === */
    .msg-row.me .msg-bubble {
        background: linear-gradient(135deg, #7B5E4A 0%, #5C4535 100%);
        color: #fff;
        border-bottom-right-radius: 6px;
    }
    /* Ekor speech bubble user */
    .msg-row.me .msg-bubble::before {
        content: '';
        position: absolute;
        bottom: -1px;
        right: -8px;
        width: 18px;
        height: 18px;
        background: #5C4535;
        border-bottom-right-radius: 18px;
        clip-path: polygon(0 0, 0 100%, 100% 100%);
    }

    .msg-time {
        font-size: 0.68rem;
        margin-top: 6px;
        opacity: 0.65;
        text-align: right;
        font-weight: 500;
    }
    .msg-row:not(.me) .msg-time { text-align: left; }

    /* ============ EMPTY STATE ============ */
    .empty-chat {
        text-align: center;
        color: #A67C52;
        margin: auto;
        font-size: 0.9rem;
        padding: 40px 20px;
    }
    .empty-chat i {
        font-size: 3rem;
        opacity: 0.25;
        display: block;
        margin-bottom: 14px;
        color: #7B5E4A;
    }
    .empty-chat strong {
        display: block;
        color: #3B2A20;
        font-size: 1rem;
        margin-bottom: 4px;
    }

    /* ============ FOOTER INPUT ============ */
    .chat-footer {
        padding: 16px 20px;
        background: #fff;
        border-top: 1px solid #F0E8DE;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .chat-footer input {
        flex: 1;
        border: 1.5px solid #E8DDD0;
        border-radius: 100px;
        padding: 13px 22px;
        font-size: 0.88rem;
        font-family: 'Poppins', sans-serif;
        color: #3B2A20;
        outline: none;
        transition: all .2s;
        background: #FAF7F2;
    }
    .chat-footer input::placeholder { color: #B8A896; }
    .chat-footer input:focus {
        border-color: #7B5E4A;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(123, 94, 74, 0.08);
    }
    .chat-footer button {
        width: 48px; height: 48px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #7B5E4A 0%, #5C4535 100%);
        color: #fff;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        transition: transform .2s, box-shadow .2s;
        flex-shrink: 0;
    }
    .chat-footer button:hover {
        transform: scale(1.08);
        box-shadow: 0 8px 22px rgba(123, 94, 74, 0.4);
    }
    .chat-footer button:active { transform: scale(0.96); }

    @media (max-width: 600px) {
        .chat-card { height: calc(100vh - 140px); border-radius: 16px; }
        .chat-page { margin-top: 90px; padding: 0 8px; }
        .msg-bubble { max-width: 80%; }
    }
</style>
@endpush

@section('content')
<div class="chat-page">
    <div class="chat-card">

        {{-- HEADER --}}
        <div class="chat-header">
            <div class="avatar-admin"><i class="fa-solid fa-headset"></i></div>
            <div class="chat-header-info">
                <h4>Admin Tuhomestay</h4>
                <div class="status">
                    <span class="dot"></span> Online — siap membantu
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="chat-body" id="chat-box">
            @if($messages->isEmpty())
                <div class="empty-chat">
                    <i class="fa-regular fa-comments"></i>
                    <strong>Mulai percakapan</strong>
                    Tanyakan ketersediaan, fasilitas, atau hal lain seputar kamar.
                </div>
            @else
                @foreach($messages as $msg)
                    @php $isMe = $msg->sender_id == auth()->id(); @endphp
                    <div class="msg-row {{ $isMe ? 'me' : '' }}">
                        <div class="msg-avatar">
                            @if($isMe)
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Me">
                                @else
                                    {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
                                @endif
                            @else
                                <i class="fa-solid fa-headset"></i>
                            @endif
                        </div>
                        <div class="msg-bubble">
                            {{ $msg->message }}
                            <div class="msg-time">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- FOOTER --}}
        <div class="chat-footer">
            <input type="text" id="message-input" placeholder="Ketik pesan..." autocomplete="off">
            <button onclick="sendMessage()" title="Kirim">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;

    function sendMessage() {
        let message = document.getElementById('message-input').value;
        if(message.trim() === '') return;

        fetch("{{ route('chat.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                message: message,
                receiver_id: {{ $adminId }},
                kamar_id: {{ $kamar_id ?? 'null' }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('message-input').value = '';
                location.reload(); 
            }
        })
        .catch(error => console.error('Error:', error));
    }

    document.getElementById('message-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });

    setInterval(() => { location.reload(); }, 5000);
</script>
@endpush