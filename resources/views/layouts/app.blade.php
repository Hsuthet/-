<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Gyomu Irai System') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <div class="min-h-screen flex">
        
     <aside class="w-72 bg-[#001a4d] text-white hidden md:flex flex-col sticky top-0 h-screen shadow-2xl">
    
    <div class="py-10 px-8">
        <div class="flex flex-col items-center">
            <img src="{{ asset('images/logo1.png') }}" class="h-10 w-auto object-contain">
           <div class="h-[1px] flex-grow bg-white"></div>
    <span class="text-[10px] font-bold tracking-[0.4em] uppercase text-slate-200">
        業務依頼システム
    </span>
    <div class="h-[1px] flex-grow bg-slate-300"></div>
        </div>
    </div>

    <nav class="flex-grow px-4 space-y-1 overflow-y-auto">

    {{-- Section Header --}}
   <h3 class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] px-4 pt-6 pb-2">
    Main Menu
</h3>
    <x-nav-link-item :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="layout-dashboard" label="ダッシュボード" />


        {{-- Admin Section --}}
    @if(auth()->user()->role === 'admin')
        <h3 class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] px-4 pt-8 pb-2">
            Admin Panel
        </h3>

        <x-nav-link-item 
            :href="route('users.index')" 
            :active="request()->routeIs('users.*')" 
            icon="users" 
            label="ユーザー管理" />

        <x-nav-link-item 
            :href="route('business-requests.index')" 
            :active="request()->routeIs('business-requests.index')" 
            icon="file-text" 
            label="依頼一覧（全体）" />

        <x-nav-link-item 
            :href="route('business-requests.my_tasks')" 
            :active="request()->routeIs('business-requests.my_tasks')" 
            icon="clipboard-list" 
            label="タスク一覧（全体）" />
    @endif

    {{-- Employee / Requester Section --}}
    @if(auth()->user()->role === 'employee')
        <h3 class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] px-4 pt-8 pb-2">Requests</h3>

        <x-nav-link-item 
            :href="route('business-requests.create')" 
            :active="request()->routeIs('business-requests.create')" 
            icon="plus-circle" 
            label="新規依頼作成" />

        <x-nav-link-item 
            :href="route('business-requests.requests')" 
            :active="request()->routeIs('business-requests.requests')" 
            icon="send" 
            label="依頼一覧画面" />

        <x-nav-link-item 
            :href="route('business-requests.my_tasks')" 
            :active="request()->routeIs('business-requests.my_tasks')" 
            icon="clipboard-list" 
            label="担当作業" 
            :badge="$assignedTaskCount ?? null" />
    @endif


    {{-- Manager Section --}}
    @if(auth()->user()->role === 'manager')
        <h3 class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] px-4 pt-8 pb-2">Management</h3>

        <x-nav-link-item 
            :href="route('business-requests.index')" 
            :active="request()->routeIs('business-requests.*')" 
            icon="shield-check" 
            label="依頼承認・管理" />
    @endif

</nav>

    <div class="p-4 mt-auto border-t border-white/5 bg-black/20">
        <div class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/5 transition cursor-pointer">
            <div class="w-10 h-10 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center text-sm font-bold shadow-inner">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-grow overflow-hidden">
                <p class="text-xs font-bold truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-white/40 uppercase tracking-widest font-medium">{{ Auth::user()->role }}</p>
            </div>
        </div>
    </div>
</aside>

        <div class="flex-grow flex flex-col min-w-0">
                <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-10">
                    <div class="flex items-center">
                        <h2 class="text-lg font-bold text-slate-800">
                            @yield('header_title', 'Dashboard')
                        </h2>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                       <div class="relative ml-3" x-data="{ open: false }">
    {{-- Bell Icon Button --}}
    <button @click="open = !open" class="relative p-2 text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
        <i data-lucide="bell" class="w-6 h-6"></i>
        
        {{-- Red Badge: Only shows if there are unread notifications --}}
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-1 right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] text-white items-center justify-center font-bold">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            </span>
        @endif
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden">
        
        <div class="p-4 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm">通知 (Notifications)</h3>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <a href="{{ route('notifications.markAsRead') }}" class="text-[10px] text-indigo-600 font-bold hover:underline">すべて既読にする</a>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse(auth()->user()->unreadNotifications as $notification)
                <div class="p-4 border-b border-slate-50 hover:bg-slate-50 transition relative">
                    <p class="text-xs text-slate-800 font-medium">
                        {{ $notification->data['message'] }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-1">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </div>
            @empty
                <div class="p-8 text-center">
                    <i data-lucide="bell-off" class="w-8 h-8 text-slate-200 mx-auto mb-2"></i>
                    <p class="text-xs text-slate-400">新しい通知はありません</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

                        <div class="relative flex items-center space-x-3 border-l pl-6 border-slate-200">
                            <div class="text-right">
                                <p class="text-sm font-bold text-slate-700 leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-500 font-medium uppercase mt-1">{{ Auth::user()->role }}</p>
                            </div>
                            
                            {{-- Logout form --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition shadow-sm" title="ログアウト">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

            <main class="p-8">
                @if(session('success'))
                    <div class="mb-6 flex items-center bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 flex items-center bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl shadow-sm">
                        <i data-lucide="alert-circle" class="w-5 h-5 mr-3"></i>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <footer class="mt-auto py-6 px-8 text-center text-slate-400 text-[10px] border-t border-slate-200">
                &copy; 2026 Gyomu Irai System Project &bull; Built with Laravel & Tailwind
            </footer>
        </div>
    </div>

    <script>
      lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>