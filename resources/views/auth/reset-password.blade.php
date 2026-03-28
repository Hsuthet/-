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
                        <h2 class="text-xl font-bold tracking-tight">パスワード更新</h2>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-blue-300 mt-2">Update Credentials</p>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-2/3 p-12 bg-white flex flex-col justify-center">
                <div class="mb-8">
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                        新しいパスワードの設定
                    </h1>
                    <p class="text-sm text-slate-500 mt-3 font-medium">
                        新しいパスワードを入力して、アカウントを保護してください。
                    </p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5" novalidate>
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="group">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-2 ml-1" for="email">
                            メールアドレス
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                            class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3.5 text-sm text-slate-500 cursor-not-allowed outline-none" 
                            readonly>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-[11px] font-bold text-red-600" />
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-2 ml-1" for="password">
                            新しいパスワード
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" autofocus
                            class="w-full bg-slate-50 border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-200' }} rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-[11px] font-bold text-red-600" />
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-2 ml-1" for="password_confirmation">
                            パスワードの確認
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full bg-slate-50 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-slate-200' }} rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[11px] font-bold text-red-600" />
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-[#1a365d] text-white rounded-xl py-4 text-sm font-bold hover:bg-blue-900 shadow-lg shadow-blue-900/20 transition-all duration-300 tracking-[0.1em] transform active:scale-[0.98]">
                            パスワードを更新する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>