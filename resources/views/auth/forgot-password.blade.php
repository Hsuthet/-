<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full flex bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden border border-slate-100">
            
            <div class="hidden lg:flex lg:w-1/3 bg-gradient-to-b from-[#1a365d] via-[#1a365d] to-[#0f172a] p-10 flex-col justify-center text-white relative overflow-hidden">
                <div class="absolute -top-20 -left-20 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 text-center space-y-6">
                    <div class="p-4 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 inline-block mx-auto">
                        <img src="{{ asset('images/RESONANT.png') }}" alt="Logo" class="h-10 w-auto">
                    </div>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight">パスワード再設定</h2>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-blue-300 mt-2">Password Recovery</p>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-2/3 p-12 bg-white flex flex-col justify-center">
                <div class="mb-8">
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                        パスワードをお忘れですか？
                    </h1>
                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                        {{ __('ご登録のメールアドレスを入力してください。パスワード再設定用のリンクをお送りします。') }}
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl">
                        <p class="text-xs font-bold text-emerald-800 tracking-wide">送信完了</p>
                        <p class="text-[11px] text-emerald-700 mt-1 font-medium">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6" novalidate>
                    @csrf

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

                    <div class="flex flex-col space-y-4">
                        <button type="submit"
                            class="w-full bg-[#1a365d] text-white rounded-xl py-4 text-sm font-bold hover:bg-blue-900 shadow-lg shadow-blue-900/20 transition-all duration-300 tracking-[0.1em] transform active:scale-[0.98]">
                            {{ __('再設定リンクを送信する') }}
                        </button>
                        
                        <a href="{{ route('login') }}" class="text-center text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors duration-200">
                            ← ログイン画面に戻る
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>