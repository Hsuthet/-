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

        <div class="space-y-6 mt-12">
            <div class="p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-inner">
                <p class="text-sm text-blue-100 leading-relaxed font-medium">
                    本システムは、社内の業務依頼、承認、および進捗管理を一元化する次世代プラットフォームです。
                </p>
            </div>

            <ul class="space-y-4 px-2">
                <li class="flex items-center text-xs text-blue-200/80 group">
                    <span class="w-6 h-6 rounded-lg bg-blue-500/20 flex items-center justify-center mr-3 group-hover:bg-blue-500/40 transition-colors">
                        <i data-lucide="zap" class="w-3.5 h-3.5 text-blue-400"></i>
                    </span>
                    迅速な承認フローの実現
                </li>
                <li class="flex items-center text-xs text-blue-200/80 group">
                    <span class="w-6 h-6 rounded-lg bg-indigo-500/20 flex items-center justify-center mr-3 group-hover:bg-indigo-500/40 transition-colors">
                        <i data-lucide="eye" class="w-3.5 h-3.5 text-indigo-400"></i>
                    </span>
                    依頼状況のリアルタイム可視化
                </li>
                <li class="flex items-center text-xs text-blue-200/80 group">
                    <span class="w-6 h-6 rounded-lg bg-cyan-500/20 flex items-center justify-center mr-3 group-hover:bg-cyan-500/40 transition-colors">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-cyan-400"></i>
                    </span>
                    過去案件のアーカイブ検索
                </li>
            </ul>
        </div>
    </div>

    <div class="relative z-10 pt-8">
        <div class="flex flex-col items-center space-y-4">
            <div class="flex space-x-2">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[9px] text-emerald-400/80 font-bold uppercase tracking-widest">System Operational</span>
            </div>
            <div class="w-full h-px bg-gradient-to-r from-transparent via-blue-400/20 to-transparent"></div>
            <p class="text-[10px] text-blue-400/60 font-medium tracking-tighter italic">
                &copy; 2026 RESONANT SYSTEMS. All Rights Reserved.
            </p>
        </div>
    </div>

</div>

            <div class="w-full lg:w-2/3 p-10 bg-white">
                <div class="mb-8 border-b border-gray-100 pb-6">
                    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">新規ユーザー登録</h1>
                    <p class="text-xs text-gray-500 mt-2">アカウントを作成するために、以下の情報を入力してください。</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="flex justify-between text-xs font-bold text-gray-600 mb-2">
                            <span>氏名</span>
                            <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded text-[10px]">必須</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 focus:bg-white transition outline-none"
                            placeholder="例：山田 太郎">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <label class="flex justify-between text-xs font-bold text-gray-600 mb-2">
                            <span>メールアドレス</span>
                            <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded text-[10px]">必須</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 focus:bg-white transition outline-none"
                            placeholder="shain@company.co.jp">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div>
                        <label class="flex justify-between text-xs font-bold text-gray-600 mb-2">
                            <span>所属部署</span>
                            <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded text-[10px]">必須</span>
                        </label>
                        <div class="relative">
                             <select id="department_id" name="department_id" 
                                 class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 focus:bg-white transition outline-none"> 
                                required>
                                <option value="" disabled selected>部署を選択してください</option>

                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" 
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('department_id')" class="mt-1" />
                    </div>

                    <div>
    <label class="flex justify-between text-xs font-bold text-gray-600 mb-2">
        <span>役割（ロール）</span>
        <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded text-[10px]">必須</span>
    </label>

    <select name="role" required
        class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 focus:bg-white transition outline-none">

        <option value="">ロールを選択してください</option>
        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>管理者</option>
        <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>従業員</option>
        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>マネージャー</option>
    </select>

    <x-input-error :messages="$errors->get('role')" class="mt-1" />
</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2">パスワード</label>
                            <input type="password" name="password" required
                                class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 focus:bg-white outline-none">
                        </div>
                        <div>
                        <label class="block text-xs font-bold text-gray-600 mb-2">確認用</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 focus:bg-white outline-none">
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />

                    <div class="pt-6">
                        <button type="submit"
                            class="w-full bg-[#1a365d] text-white rounded py-3 text-sm font-bold hover:bg-[#2a4a7d] shadow-md transition-all duration-200 tracking-widest">
                            登録を実行する
                        </button>
                    </div>

                    <div class="text-center mt-6">
                        <a href="{{ route('login') }}" class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                            既にアカウントをお持ちの方はこちら（ログイン）
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>