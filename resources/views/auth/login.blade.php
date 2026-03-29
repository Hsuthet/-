<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl w-full flex bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden border border-slate-100">
            
            <div class="hidden lg:flex lg:w-1/3 bg-gradient-to-b from-[#1a365d] via-[#1a365d] to-[#0f172a] p-10 flex-col justify-between text-white relative overflow-hidden">
    
    {{-- Soft Background Glow --}}
    <div class="absolute -top-20 -left-20 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-indigo-400/10 rounded-full blur-3xl"></div>

    <div class="space-y-12 relative z-10">

        {{-- Logo --}}
        <div class="flex justify-center">
            <div class="p-4 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 shadow-xl">
                <img src="{{ asset('images/RESONANT.png') }}" 
                     alt="Resonant Systems Logo" 
                     class="h-14 w-auto object-contain">
            </div>
        </div>

        {{-- Title --}}
        <div class="text-center space-y-3">
            <h2 class="text-3xl font-extrabold leading-tight tracking-tight">
                業務依頼<br>管理システム
            </h2>

            <p class="text-[11px] uppercase tracking-[0.25em] text-blue-300 font-semibold">
                Business Request Management
            </p>

            <div class="h-[2px] w-14 bg-blue-400/70 mx-auto rounded-full"></div>
        </div>

        {{-- Description --}}
        <div class="p-5 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
            <p class="text-xs text-blue-100 leading-relaxed text-center">
               社内ワークフローと承認プロセスを合理化し、チーム全体の効率性と透明性を向上させる。
            </p>
        </div>

        {{-- Feature Highlights --}}
        <div class="space-y-3 text-xs text-blue-200">
            <div class="flex items-center space-x-3">
                <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
                <span>依頼書作成・編集機能</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="w-2 h-2 bg-indigo-400 rounded-full"></span>
                <span>依頼書一覧表示機能</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="w-2 h-2 bg-cyan-400 rounded-full"></span>
                <span>添付ファイル管理機能</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="w-2 h-2 bg-indigo-400 rounded-full"></span>
                <span>過去依頼検索・閲覧機能</span>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <div class="relative z-10 text-center pt-6">
        <div class="w-full h-px bg-white/10 mb-3"></div>
        <p class="text-[10px] text-blue-300/60 tracking-widest uppercase">
            © 2026 RESONANT SYSTEMS
        </p>
    </div>

</div>

            <div class="w-full lg:w-2/3 p-12 bg-white flex flex-col justify-center">
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">ようこそ <span class="text-blue-600">!</span></h1>
        <p class="text-sm text-slate-500 mt-2 font-medium">登録済みのメールアドレスでログインしてください。</p>
    </div>

    {{-- 1. General Error Alert at the top --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-bold text-red-800 uppercase tracking-wider">ログインエラー</p>
                    <p class="text-[11px] text-red-700 mt-1 font-medium">入力内容を確認してください。</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Added 'novalidate' to trigger Laravel errors instead of browser tooltips --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
        @csrf

        {{-- Email Field --}}
        <div class="group">
            <label class="block text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-2 ml-1" for="email">
                メールアドレス
            </label>
            <div class="relative">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full bg-slate-50 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-200' }} rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none"
                    placeholder="name@resonant.jp">
            </div>
            @if ($errors->has('email'))
                <p class="mt-2 text-[11px] font-bold text-red-600 flex items-center ml-1">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $errors->first('email') }}
                </p>
            @endif
        </div>

        {{-- Password Field --}}
      <div>
    <div class="flex justify-between mb-2 ml-1">
        <label class="block text-[11px] uppercase tracking-widest font-bold text-slate-400" for="password">
            パスワード
        </label>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-[10px] text-blue-600 font-bold hover:text-blue-800 transition-colors">
                パスワードをお忘れですか？
            </a>
        @endif
    </div>

    <!-- Password Input with Eye Icon -->
    <div class="relative">
        <input id="password" type="password" name="password" required autocomplete="current-password"
            class="w-full bg-slate-50 border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-200' }} rounded-xl px-4 py-3.5 pr-10 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none">

        <!-- Eye Icon -->
        <button type="button" onclick="togglePassword()" 
    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
    
    <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>

    <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L4.5 4.5m15 15l-5.62-5.62m-1.42-1.42L12 12m9.542 0a9.997 9.997 0 00-1.563-3.029l-1.42-1.42" />
    </svg>
</button>
    </div>

    @if ($errors->has('password'))
        <p class="mt-2 text-[11px] font-bold text-red-600 flex items-center ml-1">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $errors->first('password') }}
        </p>
    @endif
</div>

<!-- Script -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>
        <div class="pt-4">
            <button type="submit"
                class="w-full bg-[#1a365d] text-white rounded-xl py-4 text-sm font-bold hover:bg-blue-900 shadow-lg shadow-blue-900/20 transition-all duration-300 tracking-[0.2em] transform active:scale-[0.98]">
                ログイン
            </button>
        </div>
    </form>
</div>
        </div>
    </div>
</x-guest-layout>