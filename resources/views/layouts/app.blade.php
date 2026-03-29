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
        
    <aside class="w-72 bg-[#001a4d] text-white hidden md:flex flex-col sticky top-0 h-screen shadow-[4px_0_24px_rgba(0,0,0,0.1)] z-20">
    {{-- Brand Logo Section --}}
    <div class="py-10 px-8 flex flex-col items-center">
        <div class="relative group cursor-pointer">
            <img src="{{ asset('images/RESONANT.png') }}" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
            {{-- Decorative glow behind logo --}}
            <div class="absolute -inset-1 bg-white/10 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
        
        <div class="mt-4 flex items-center w-full gap-3">
            <div class="h-[1px] flex-grow bg-gradient-to-r from-transparent via-slate-400 to-transparent"></div>
            <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] whitespace-nowrap">
                業務依頼システム
            </span>
            <div class="h-[1px] flex-grow bg-gradient-to-r from-transparent via-slate-400 to-transparent"></div>
        </div>
    </div>

    {{-- Navigation Scroll Area --}}
    <nav class="flex-grow px-4 space-y-1.5 overflow-y-auto scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
        
        {{-- Menu Section Factory --}}
        @php
            $sections = [
                ['label' => 'Main Menu', 'role' => 'all'],
                ['label' => 'Admin Panel', 'role' => 'admin'],
                ['label' => 'Requests', 'role' => 'employee'],
                ['label' => 'Management', 'role' => 'manager'],
            ];
        @endphp

        {{-- Main Menu --}}
        <h3 class="text-[10px] font-bold text-slate-400/80 uppercase tracking-[0.2em] px-4 pt-4 pb-2">Main Menu</h3>
        <x-nav-link-item :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="layout-dashboard" label="ダッシュボード" />

        {{-- Admin Section --}}
        @if(auth()->user()->role === 'admin')
            <h3 class="text-[10px] font-bold text-indigo-300/60 uppercase tracking-[0.2em] px-4 pt-8 pb-2">管理者コントロール</h3>
            <x-nav-link-item :href="route('users.index')" :active="request()->routeIs('users.*')" icon="users" label="ユーザー管理" />
            <x-nav-link-item :href="route('business-requests.requests')" :active="request()->routeIs('business-requests.requests')" icon="file-text" label="依頼一覧" />
            <x-nav-link-item :href="route('business-requests.my_tasks')" :active="request()->routeIs('business-requests.my_tasks')" icon="clipboard-list" label="担当作業一覧" />
        @endif

        {{-- Employee Section --}}
        @if(auth()->user()->role === 'employee')
            <h3 class="text-[10px] font-bold text-emerald-300/60 uppercase tracking-[0.2em] px-4 pt-8 pb-2">社員業務</h3>
            <x-nav-link-item :href="route('business-requests.create')" :active="request()->routeIs('business-requests.create')" icon="plus-circle" label="新規依頼作成" />
            <x-nav-link-item :href="route('business-requests.requests')" :active="request()->routeIs('business-requests.requests')" icon="send" label="依頼一覧" />
            <x-nav-link-item :href="route('business-requests.my_tasks')" :active="request()->routeIs('business-requests.my_tasks')" icon="check-square" label="担当作業一覧" :badge="$assignedTaskCount ?? null" />
        @endif

       {{-- Manager Section --}}
        @if(auth()->user()->role === 'manager')
            <h3 class="text-[10px] font-bold text-amber-300/60 uppercase tracking-[0.2em] px-4 pt-8 pb-2">
                マネージャーコントロール
            </h3>

            {{-- Change 'business-requests.*' to 'business-requests.index' to avoid overlapping --}}
            <x-nav-link-item 
                :href="route('business-requests.index')" 
                :active="request()->is('business-requests') || request()->routeIs('business-requests.index')" 
                icon="shield-check" 
                label="依頼承認・管理" />

            <x-nav-link-item 
                :href="route('business-requests.my_tasks')" 
                :active="request()->routeIs('business-requests.my_tasks')" 
                icon="check-square" 
                label="担当作業一覧" />
        @endif
    </nav>

    {{-- User Profile Footer --}}
   <div class="p-4 mt-auto">
    {{-- Check if user exists before rendering to avoid 'Property on null' errors --}}
    @if(Auth::check())
        <div class="bg-white/5 border border-white/10 rounded-2xl p-3 flex items-center gap-3 hover:bg-white/10 transition-all duration-300 group cursor-default">
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-sm font-bold shadow-lg group-hover:rotate-3 transition-transform text-white">
                    {{-- Use mb_substr for Japanese character compatibility --}}
                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                </div>
                {{-- Status Indicator --}}
                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 border-2 border-[#001a4d] rounded-full"></div>
            </div>
            
            <div class="flex-grow overflow-hidden">
                <p class="text-xs font-bold text-white truncate" title="{{ Auth::user()->name }}">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-[10px] text-white/40 uppercase tracking-widest font-black">
                    {{-- Standardize role display --}}
                    @if(Auth::user()->role === 'admin') 管理者 
                    @elseif(Auth::user()->role === 'manager') マネージャー 
                    @else 従業員 @endif
                </p>
            </div>
        </div>
    @else
        {{-- Fallback if session expires --}}
        <a href="{{ route('login') }}" class="block text-center p-3 text-xs text-slate-400 hover:text-white transition">
            ログインしてください
        </a>
    @endif
</div>
</aside>
        <div class="flex-grow flex flex-col min-w-0">
              <header class="h-20 bg-white border-b border-slate-100 flex items-center justify-between px-10 sticky z-50 top-0">
    {{-- 左側：タイトルとブランド --}}
    <div class="flex items-center space-x-6">
        <div class="flex flex-col border-l-2 border-indigo-600 pl-4">
            <h2 class="text-lg font-black text-slate-800 tracking-tighter flex items-center">
                @yield('header_title')
            </h2>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-0.5">
                RESONANT / 業務管理システム
            </p>
        </div>
    </div>
    
    {{-- 右側：日付とユーザー情報 --}}
    <div class="flex items-center space-x-8">
        {{-- 日付表示（和暦ではなくモダンな形式） --}}
        <div class="hidden md:block text-right pr-6 border-r border-slate-100">
            <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">本日</p>
            <p class="text-xs font-bold text-slate-600 tabular-nums">
                {{ now()->format('Y.m.d') }} <span class="text-slate-400 ml-1">({{ now()->isoFormat('ddd') }})</span>
            </p>
        </div>

        {{-- ユーザーカプセル：よりシンプルで清潔なデザイン --}}
        <div class="flex items-center bg-slate-50 border border-slate-200/60 p-1.5 rounded-xl pl-4 pr-2 hover:bg-white hover:border-indigo-200 transition-all duration-300">
            <div class="mr-3 text-right">
                <p class="text-[11px] font-black text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                <p class="text-[9px] text-indigo-500 font-bold uppercase tracking-tighter">
                    @switch(Auth::user()->role)
                        @case('admin') 管理者 @break
                        @case('manager') マネージャー @break
                        @case('employee') 従業員 @break
                    @endswitch
                </p>
            </div>
            
            {{-- ユーザーアイコン（枠線のみのミニマルスタイル） --}}
            <div class="h-8 w-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>

            {{-- ログアウト：シンプルに統合 --}}
            <form method="POST" action="{{ route('logout') }}" class="ml-2 border-l border-slate-200 pl-2">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition-colors" title="ログアウト">
                    <i data-lucide="power" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</header>

            <main class="p-8">
                {{-- @if(session('success'))
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
                @endif --}}

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
    <script>
        $(document).ready(function() {
            // Global Table Filter Listener
            $(document).on('click', '.filter-btn', function(e) {
                const $btn = $(this);
                const tableId = $btn.data('table');
                const searchTerm = $btn.data('search') || '';
                
                // Find the DataTable instance
                const table = $('#' + tableId).DataTable();
                
                // 1. Filter the table
                table.search(searchTerm).draw();

                // 2. Update UI Classes (Remove from others, add to this one)
                $btn.closest('.inline-flex').find('.filter-btn')
                    .removeClass('bg-white shadow-sm border border-slate-200 text-blue-600 font-bold')
                    .addClass('text-slate-500 font-medium');

                $btn.addClass('bg-white shadow-sm border border-slate-200 text-blue-600 font-bold')
                    .removeClass('text-slate-500 font-medium');
            });
        });
    </script>
    <script>
    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });

    // Refresh icons when Alpine toggles the dropdown
    document.addEventListener('alpine:initialized', () => {
        Alpine.effect(() => {
            // This runs every time any Alpine variable (like 'open') changes
            lucide.createIcons();
        });
    });
</script>
{{-- Notification Toast --}}
@if(session('success'))
    <div id="success-toast" 
         class="fixed top-5 right-5 z-[100] transform transition-all duration-500 ease-in-out">
        <div class="bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-700">
            <div class="bg-emerald-500 p-1.5 rounded-lg">
                <i data-lucide="check" class="w-4 h-4 text-white"></i>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
            {{-- Manual Close Button --}}
            <button onclick="dismissToast()" class="ml-2 text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
@endif
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('success-toast');
            if (toast) {
                setTimeout(() => { dismissToast(); }, 3000);
            }
        });

        function dismissToast() {
            const toast = document.getElementById('success-toast');
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100px)';
                setTimeout(() => { toast.remove(); }, 500);
            }
        }
    </script>
</body>
</html>