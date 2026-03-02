<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#f0f2f5] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full flex bg-white rounded-lg shadow-xl overflow-hidden border border-gray-200">
            
            <div class="hidden lg:flex lg:w-1/3 bg-[#1a365d] p-10 flex-col justify-between text-white">
                <div>
                    <h2 class="text-2xl font-bold tracking-wider leading-snug">
                        業務依頼<br>管理システム
                    </h2>
                    <div class="h-1 w-10 bg-blue-400 mt-4"></div>
                </div>
                
                <div class="space-y-4">
                    <p class="text-sm text-blue-100 leading-relaxed">
                        アカウントにログインして、業務依頼の作成、承認、または進捗状況の確認を行ってください。
                    </p>
                </div>

                <div class="text-[10px] text-blue-300">
                    &copy; 2026 業務支援ソリューション
                </div>
            </div>

            <div class="w-full lg:w-2/3 p-10 bg-white">
                <div class="mb-8 border-b border-gray-100 pb-6">
                    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">ログイン</h1>
                    <p class="text-xs text-gray-500 mt-2">登録済みのメールアドレスとパスワードを入力してください。</p>
                </div>

                <x-auth-session-status class="mb-4 text-xs font-medium text-green-600" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-2" for="email">
                            メールアドレス
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:bg-white transition outline-none"
                            placeholder="shain@company.co.jp">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="block text-xs font-bold text-gray-600" for="password">
                                パスワード
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] text-blue-600 hover:underline">
                                    パスワードをお忘れですか？
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:bg-white transition outline-none">
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" 
                            class="rounded border-gray-300 text-blue-800 shadow-sm focus:ring-blue-500">
                        <label for="remember_me" class="ms-2 text-xs text-gray-600 cursor-pointer">
                            次回から自動的にログインする
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-[#1a365d] text-white rounded py-3 text-sm font-bold hover:bg-[#2a4a7d] shadow-md transition-all duration-200 tracking-widest">
                            ログイン
                        </button>
                    </div>

                    <div class="text-center mt-8 pt-6 border-t border-gray-50">
                        <p class="text-xs text-gray-500 mb-2">アカウントをお持ちでない方はこちら</p>
                        <a href="{{ route('register') }}" class="text-xs text-blue-600 font-bold hover:text-blue-800 hover:underline">
                            新規ユーザー登録
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>