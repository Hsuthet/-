<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl w-full flex bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden border border-slate-100">
            
            <div class="hidden lg:flex lg:w-1/3 bg-gradient-to-b from-[#1a365d] via-[#1e293b] to-[#0f172a] p-10 flex-col justify-between text-white relative overflow-hidden">
                
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="space-y-10 relative z-10">
                    <div class="flex justify-center">
                        <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 shadow-2xl">
                            <img src="{{ asset('images/RESONANT.png') }}" 
                                 alt="Resonant Systems Logo" 
                                 class="h-12 w-auto object-contain brightness-110">
                        </div>
                    </div>

                    <div class="text-center">
                        <h2 class="text-3xl font-black tracking-tight leading-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-blue-100 to-blue-300">
                            業務依頼<br>管理システム
                        </h2>
                        <p class="text-[10px] uppercase tracking-[0.3em] text-blue-400 mt-3 font-bold">Business Request Management</p>
                        <div class="h-1 w-16 bg-gradient-to-r from-blue-600 to-indigo-400 mt-6 mx-auto rounded-full shadow-lg shadow-blue-500/50"></div>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md">
                        <p class="text-xs text-blue-100 leading-relaxed font-medium text-center italic">
                            "Connecting efficiency with innovation."
                        </p>
                    </div>
                </div>

                <div class="relative z-10 text-center">
                     <p class="text-[10px] text-blue-400/60 font-medium tracking-widest uppercase">
                        &copy; 2026 RESONANT SYSTEMS
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
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] text-blue-600 font-bold hover:text-blue-800 transition-colors">
                                    パスワードをお忘れですか？
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-[11px] font-medium" />
                    </div>

                    <div class="flex items-center ml-1">
                        <input id="remember_me" type="checkbox" name="remember" 
                            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all cursor-pointer">
                        <label for="remember_me" class="ms-2 text-xs text-slate-500 font-medium cursor-pointer select-none">
                            次回から自動的にログインする
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-[#1a365d] text-white rounded-xl py-4 text-sm font-bold hover:bg-blue-900 shadow-lg shadow-blue-900/20 transition-all duration-300 tracking-[0.2em] transform active:scale-[0.98]">
                            ログイン
                        </button>
                    </div>

                    <div class="text-center mt-10">
                        <div class="flex items-center justify-center space-x-4 mb-6">
                            <div class="h-px w-12 bg-slate-100"></div>
                            <p class="text-xs text-slate-400 font-medium">アカウントをお持ちでない方</p>
                            <div class="h-px w-12 bg-slate-100"></div>
                        </div>
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