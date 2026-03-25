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
                    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">おかえりなさい <span class="text-blue-600">!</span></h1>
                    <p class="text-sm text-slate-500 mt-2 font-medium">登録済みのメールアドレスでログインしてください。</p>
                </div>

                <x-auth-session-status class="mb-4 text-xs font-semibold text-emerald-600 bg-emerald-50 p-3 rounded-lg" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div class="group">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-2 ml-1" for="email">
                            メールアドレス
                        </label>
                        <div class="relative">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none"
                                placeholder="name@resonant.jp">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-[11px] font-medium" />
                    </div>

                    <div>
                        <div class="flex justify-between mb-2 ml-1">
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-slate-400" for="password">
                                パスワード
                            </label>
                            {{-- @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] text-blue-600 font-bold hover:text-blue-800 transition-colors">
                                    パスワードをお忘れですか？
                                </a>
                            @endif --}}
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-[11px] font-medium" />
                    </div>

                    {{-- <div class="flex items-center ml-1">
                        <input id="remember_me" type="checkbox" name="remember" 
                            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all cursor-pointer">
                        <label for="remember_me" class="ms-2 text-xs text-slate-500 font-medium cursor-pointer select-none">
                            次回から自動的にログインする
                        </label>
                    </div> --}}

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-[#1a365d] text-white rounded-xl py-4 text-sm font-bold hover:bg-blue-900 shadow-lg shadow-blue-900/20 transition-all duration-300 tracking-[0.2em] transform active:scale-[0.98]">
                            ログイン
                        </button>
                    </div>

                    <div class="text-center mt-10">
                        {{-- <div class="flex items-center justify-center space-x-4 mb-6">
                            <div class="h-px w-12 bg-slate-100"></div>
                            <p class="text-xs text-slate-400 font-medium">アカウントをお持ちでない方</p>
                            <div class="h-px w-12 bg-slate-100"></div>
                        </div> --}}
                        {{-- <a href="{{ route('register') }}" 
                           class="inline-block w-full py-3.5 border-2 border-slate-100 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 hover:border-slate-200 transition-all duration-200">
                            新規ユーザー登録
                        </a> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>