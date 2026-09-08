@extends('layouts.app')

@section('title', 'AI Assistant')

@section('content')

    <div class="flex h-screen overflow-hidden bg-slate-50">

        {{-- SIDEBAR --}}
        <aside class="flex w-72 shrink-0 flex-col border-r border-slate-200 bg-white">

            {{-- Sidebar Header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">

                <div>
                    <h1 class="text-base font-bold text-slate-900">
                        AI Assistant
                    </h1>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Your productivity partner
                    </p>
                </div>

                <a href="{{ route('ai.index') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-50"
                    title="New conversation">
                    +
                </a>

            </div>

            {{-- Conversations --}}
            <div class="flex-1 overflow-y-auto p-3">

                @forelse($conversations as $item)
                    <a href="{{ route('ai.conversation', $item) }}"
                        class="mb-1 block rounded-xl px-3 py-3 transition
                    {{ isset($conversation) && $conversation->id === $item->id ? 'bg-slate-100' : 'hover:bg-slate-50' }}">

                        <div class="truncate text-sm font-medium text-slate-800">
                            {{ $item->title ?: 'New conversation' }}
                        </div>

                        <div class="mt-1 text-xs text-slate-400">
                            {{ $item->created_at->diffForHumans() }}
                        </div>

                    </a>

                @empty

                    <div class="px-3 py-8 text-center">

                        <div class="text-sm font-medium text-slate-500">
                            Belum ada conversation
                        </div>

                        <div class="mt-1 text-xs text-slate-400">
                            Mulai percakapan baru.
                        </div>

                    </div>
                @endforelse

            </div>

        </aside>


        {{-- MAIN CHAT --}}
        <main class="flex min-w-0 flex-1 flex-col">

            {{-- Header --}}
            <header class="flex h-16 shrink-0 items-center border-b border-slate-200 bg-white px-6">

                <div>

                    <h2 class="text-sm font-semibold text-slate-900">
                        {{ isset($conversation) ? ($conversation->title ?: 'Conversation') : 'New Conversation' }}
                    </h2>

                    <p class="text-xs text-slate-400">
                        Gemini AI
                    </p>

                </div>

            </header>


            {{-- Messages --}}
            <div id="chat-container" class="flex-1 overflow-y-auto px-6 py-8">

                <div class="mx-auto max-w-3xl">

                    @if (isset($messages) && $messages->count())

                        @foreach ($messages as $message)
                            @if ($message->role === 'user')
                                {{-- USER MESSAGE --}}
                                <div class="mb-6 flex justify-end">

                                    <div class="max-w-[80%]">

                                        <div
                                            class="rounded-2xl rounded-br-md bg-slate-900 px-4 py-3 text-sm leading-6 text-white">
                                            {{ $message->content }}
                                        </div>

                                    </div>

                                </div>
                            @else
                                {{-- AI MESSAGE --}}
                                <div class="mb-6 flex justify-start">

                                    <div class="max-w-[80%]">

                                        <div class="mb-1 text-xs font-semibold text-slate-400">
                                            AI Assistant
                                        </div>

                                        <div
                                            class="rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm leading-7 text-slate-700 shadow-sm">
                                            {!! nl2br(e($message->content)) !!}
                                        </div>

                                    </div>

                                </div>
                            @endif
                        @endforeach
                    @else
                        {{-- EMPTY STATE --}}
                        <div class="flex min-h-[60vh] items-center justify-center">

                            <div class="max-w-md text-center">

                                <div
                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-900 text-2xl text-white">
                                    AI
                                </div>

                                <h2 class="mt-5 text-xl font-bold text-slate-900">
                                    How can I help you?
                                </h2>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Tanya tentang produktivitas, task,
                                    project, goal, habit, atau hal lainnya.
                                </p>

                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- INPUT --}}
            <div class="shrink-0 border-t border-slate-200 bg-white px-6 py-4">

                <form id="chat-form" method="POST" action="{{ route('ai.chat') }}" class="mx-auto max-w-3xl">

                    @csrf

                    @if (isset($conversation))
                        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                    @endif

                    <div class="flex items-end gap-3">

                        <textarea id="message-input" name="message" rows="1" maxlength="2000" required placeholder="Tulis pesan..."
                            class="max-h-40 min-h-[48px] flex-1 resize-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:bg-white focus:ring-0"
                            onkeydown="handleChatKeydown(event)"></textarea>

                        <button id="send-button" type="submit"
                            class="flex h-12 shrink-0 items-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50">
                            <span id="send-text">
                                Send
                            </span>

                            <span id="send-loading" class="hidden">
                                Thinking...
                            </span>
                        </button>

                    </div>

                    <div class="mt-2 text-center text-xs text-slate-400">
                        Enter untuk mengirim · Shift + Enter untuk baris baru
                    </div>

                </form>

            </div>

        </main>

    </div>


    @push('scripts')
        <script>
            const chatForm = document.getElementById('chat-form');
            const messageInput = document.getElementById('message-input');
            const sendButton = document.getElementById('send-button');

            const sendText = document.getElementById('send-text');
            const sendLoading = document.getElementById('send-loading');

            const chatContainer =
                document.getElementById('chat-container');


            /*
            |--------------------------------------------------------------------------
            | Submit Chat
            |--------------------------------------------------------------------------
            */

            chatForm.addEventListener('submit', async function(event) {

                event.preventDefault();

                const message = messageInput.value.trim();

                if (!message) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Disable Input
                |--------------------------------------------------------------------------
                */

                messageInput.disabled = true;
                sendButton.disabled = true;

                sendText.classList.add('hidden');
                sendLoading.classList.remove('hidden');


                /*
                |--------------------------------------------------------------------------
                | Show User Message Immediately
                |--------------------------------------------------------------------------
                */

                addMessage(
                    'user',
                    message
                );

                messageInput.value = '';


                /*
                |--------------------------------------------------------------------------
                | Loading Bubble
                |--------------------------------------------------------------------------
                */

                const loadingMessage = addLoadingMessage();


                try {

                    const formData = new FormData(chatForm);

                    formData.set(
                        'message',
                        message
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Send Request
                    |--------------------------------------------------------------------------
                    */

                    const response = await fetch(
                        chatForm.action, {
                            method: 'POST',

                            headers: {
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),

                                'Accept': 'application/json',
                            },

                            body: formData,
                        }
                    );


                    const data = await response.json();


                    /*
                    |--------------------------------------------------------------------------
                    | Remove Loading
                    |--------------------------------------------------------------------------
                    */

                    loadingMessage.remove();


                    /*
                    |--------------------------------------------------------------------------
                    | Error
                    |--------------------------------------------------------------------------
                    */

                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Terjadi kesalahan.'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Add AI Message
                    |--------------------------------------------------------------------------
                    */

                    addMessage(
                        'assistant',
                        data.message.content
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Add Conversation ID
                    |--------------------------------------------------------------------------
                    */

                    let conversationInput =
                        chatForm.querySelector(
                            'input[name="conversation_id"]'
                        );


                    if (!conversationInput) {

                        conversationInput =
                            document.createElement('input');

                        conversationInput.type = 'hidden';
                        conversationInput.name = 'conversation_id';

                        chatForm.appendChild(
                            conversationInput
                        );
                    }


                    conversationInput.value =
                        data.conversation_id;

                } catch (error) {

                    loadingMessage.remove();

                    addMessage(
                        'assistant',
                        'Maaf, terjadi kesalahan saat menghubungi AI. Silakan coba lagi.'
                    );

                    console.error(error);

                } finally {

                    /*
                    |--------------------------------------------------------------------------
                    | Enable Input Again
                    |--------------------------------------------------------------------------
                    */

                    messageInput.disabled = false;
                    sendButton.disabled = false;

                    sendText.classList.remove('hidden');
                    sendLoading.classList.add('hidden');

                    messageInput.focus();

                }

            });


            /*
            |--------------------------------------------------------------------------
            | Add Message
            |--------------------------------------------------------------------------
            */

            function addMessage(role, content) {

                const wrapper =
                    document.createElement('div');

                wrapper.className =
                    'mb-6 flex ' +
                    (
                        role === 'user' ?
                        'justify-end' :
                        'justify-start'
                    );


                if (role === 'user') {

                    wrapper.innerHTML = `
            <div class="max-w-[80%]">
                <div class="rounded-2xl rounded-br-md bg-slate-900 px-4 py-3 text-sm leading-6 text-white">
                    ${escapeHtml(content)}
                </div>
            </div>
        `;

                } else {

                    wrapper.innerHTML = `
            <div class="max-w-[80%]">
                <div class="mb-1 text-xs font-semibold text-slate-400">
                    AI Assistant
                </div>

                <div class="rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm leading-7 text-slate-700 shadow-sm">
                    ${formatAIMessage(content)}
                </div>
            </div>
        `;

                }


                const messageWrapper =
                    chatContainer.querySelector(
                        '.mx-auto.max-w-3xl'
                    );

                messageWrapper.appendChild(wrapper);


                /*
                |--------------------------------------------------------------------------
                | Scroll Bottom
                |--------------------------------------------------------------------------
                */

                chatContainer.scrollTo({
                    top: chatContainer.scrollHeight,
                    behavior: 'smooth'
                });

            }


            /*
            |--------------------------------------------------------------------------
            | Loading Message
            |--------------------------------------------------------------------------
            */

            function addLoadingMessage() {

                const wrapper =
                    document.createElement('div');

                wrapper.className =
                    'mb-6 flex justify-start';


                wrapper.innerHTML = `
        <div class="max-w-[80%]">

            <div class="mb-1 text-xs font-semibold text-slate-400">
                AI Assistant
            </div>

            <div class="rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-sm text-slate-400 shadow-sm">
                <span class="animate-pulse">
                    Thinking...
                </span>
            </div>

        </div>
    `;


                const messageWrapper =
                    chatContainer.querySelector(
                        '.mx-auto.max-w-3xl'
                    );

                messageWrapper.appendChild(wrapper);


                chatContainer.scrollTo({
                    top: chatContainer.scrollHeight,
                    behavior: 'smooth'
                });


                return wrapper;

            }


            /*
            |--------------------------------------------------------------------------
            | Enter / Shift + Enter
            |--------------------------------------------------------------------------
            */

            function handleChatKeydown(event) {

                if (
                    event.key === 'Enter' &&
                    !event.shiftKey
                ) {

                    event.preventDefault();

                    chatForm.requestSubmit();

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Format AI Message
            |--------------------------------------------------------------------------
            */

            function formatAIMessage(content) {

                return escapeHtml(content)
                    .replace(/\n/g, '<br>');

            }


            /*
            |--------------------------------------------------------------------------
            | Escape HTML
            |--------------------------------------------------------------------------
            */

            function escapeHtml(text) {

                const div =
                    document.createElement('div');

                div.textContent = text;

                return div.innerHTML;

            }


            /*
            |--------------------------------------------------------------------------
            | Initial Scroll
            |--------------------------------------------------------------------------
            */

            if (chatContainer) {

                chatContainer.scrollTop =
                    chatContainer.scrollHeight;

            }
        </script>
    @endpush

@endsection
