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
        
        <aside class="w-64 bg-slate-900 text-white hidden md:flex flex-col sticky top-0 h-screen shadow-xl">
            <div class="p-6 border-b border-slate-800">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="bg-indigo-600 p-2 rounded-lg">
                        <i data-lucide="layers" class="w-6 h-6"></i>
                    </div>
                    <span class="font-bold text-lg tracking-tight">業務依頼システム</span>
                </a>
            </div>

            <nav class="flex-grow p-4 space-y-2 overflow-y-auto">
    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2">Menu</p>
    
    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
        <span class="text-sm font-medium">ダッシュボード</span>
    </a>

    {{-- General List --}}
    <a href="{{ route('business-requests.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('business-requests.index') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
        <span class="text-sm font-medium">依頼一覧 (List)</span>
    </a>

    {{-- Assigned Tasks: Show only for Employees/Workers --}}
    @if(auth()->user()->role === 'employee')
    <a href="{{ route('business-requests.index') }}#workerTable" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition hover:bg-slate-800 text-slate-400 border border-dashed border-slate-700 mt-4">
        <i data-lucide="briefcase" class="w-5 h-5 text-indigo-400"></i>
        <span class="text-sm font-medium text-slate-200">担当作業 (My Tasks)</span>
    </a>
    @endif

    {{-- Create Request: Show only for Requesters/Employees --}}
    @if(auth()->user()->role === 'REQUESTER' || auth()->user()->role === 'employee')
    <a href="{{ route('business-requests.create') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('business-requests.create') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
        <i data-lucide="plus-circle" class="w-5 h-5"></i>
        <span class="text-sm font-medium">新規依頼 (New)</span>
    </a>
    @endif
</nav>
    
        </aside>

        <div class="flex-grow flex flex-col min-w-0">
                <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-10">
                    <div class="flex items-center">
                        <h2 class="text-lg font-bold text-slate-800">
                            @yield('header_title', 'Dashboard')
                        </h2>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <button class="p-2 text-slate-400 hover:text-indigo-600 transition">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                        </button>

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