<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl w-full flex bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden border border-slate-100">
            
            {{-- LEFT SIDEBAR --}}
            <div class="hidden lg:flex lg:w-1/3 bg-gradient-to-b from-[#1a365d] via-[#1e293b] to-[#0f172a] p-10 flex-col justify-between text-white relative overflow-hidden">
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="space-y-10 relative z-10">
                    <div class="flex justify-center">
                        <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 shadow-2xl">
                            <img src="{{ asset('images/RESONANT.png') }}" alt="Logo" class="h-12 w-auto object-contain brightness-110">
                        </div>
                    </div>

                    <div class="text-center">
                        <h2 class="text-3xl font-black tracking-tight leading-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-blue-100 to-blue-300">
                            アカウント<br>設定管理
                        </h2>
                        <p class="text-[10px] uppercase tracking-[0.3em] text-blue-400 mt-3 font-bold">User Profile Settings</p>
                        <div class="h-1 w-16 bg-gradient-to-r from-blue-600 to-indigo-400 mt-6 mx-auto rounded-full"></div>
                    </div>

                    <div class="space-y-6 mt-12">
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md">
                            <p class="text-sm text-blue-100 leading-relaxed font-medium">
                                ユーザーの権限や所属部署を適切に設定することで、セキュアな業務フローを維持します。
                            </p>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-8 text-center">
                     <p class="text-[10px] text-blue-400/60 font-medium italic">&copy; 2026 RESONANT SYSTEMS.</p>
                </div>
            </div>

            {{-- RIGHT FORM SECTION --}}
            <div class="w-full lg:w-2/3 p-10 bg-white">
                <div class="mb-8 border-b border-gray-100 pb-6 flex justify-between items-end">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">ユーザー情報の編集</h1>
                        <p class="text-xs text-gray-500 mt-2">対象ユーザー：<span class="font-bold text-indigo-600">{{ $user->name }}</span></p>
                    </div>
                    <a href="{{ route('users.index') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center">
                        <i data-lucide="arrow-left" class="w-3 h-3 mr-1"></i> 一覧に戻る
                    </a>
                </div>

                {{-- Unified Alpine.js State --}}
                <form method="POST" action="{{ route('users.update', $user) }}" 
                      x-data="{ 
                        role: '{{ old('role', $user->role) }}', 
                        showPassword: false, 
                        loading: false 
                      }" 
                      @submit="loading = true"
                      class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <label class="flex justify-between text-xs font-bold text-gray-600 mb-2">
                            <span>氏名</span>
                            <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded text-[10px]">必須</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 focus:bg-white transition outline-none">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="flex justify-between text-xs font-bold text-gray-600 mb-2">
                            <span>メールアドレス</span>
                            <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded text-[10px]">必須</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 focus:bg-white transition outline-none">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Role --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2">役割（ロール）</label>
                            <select name="role" x-model="role" required
                                class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 outline-none transition">
                                <option value="admin">管理者</option>
                                <option value="manager">マネージャー</option>
                                <option value="employee">従業員</option>
                            </select>
                        </div>

                        {{-- Department: Hidden if Admin --}}
                        <div x-show="role !== 'admin'" x-transition>
                            <label class="block text-xs font-bold text-gray-600 mb-2">所属部署</label>
                            <select name="department_id" 
                                    :required="role !== 'admin'"
                                    :disabled="role === 'admin'"
                                    class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 outline-none transition">
                                <option value="" disabled>部署を選択してください</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Admin Info Box --}}
                    <template x-if="role === 'admin'">
                        <div class="p-3 bg-blue-50 border border-blue-100 rounded-xl flex items-center gap-3">
                            <i data-lucide="info" class="w-4 h-4 text-blue-500"></i>
                            <p class="text-[11px] text-blue-700 font-medium">管理者は全部署の権限を持つため、部署設定は不要です。</p>
                        </div>
                    </template>

                    {{-- Password Section --}}
                    <div class="mt-8 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <i data-lucide="lock" class="w-4 h-4 text-slate-400"></i>
                            <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest">パスワードの変更</h3>
                        </div>
                        <p class="text-[10px] text-slate-400 mb-4 italic">※ 変更する場合のみ入力してください。現在のパスワードはセキュリティ上表示されません。</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- New Password --}}
                            <div class="relative">
                                <label class="block text-xs font-bold text-gray-600 mb-2">新しいパスワード</label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" 
                                           name="password" 
                                           placeholder="••••••••"
                                           class="w-full bg-white border border-gray-200 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 outline-none transition pr-10">
                                    
                                    {{-- Eye Toggle Button --}}
                                    <button type="button" 
                                            @click="showPassword = !showPassword" 
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                                        <i data-lucide="eye" x-show="!showPassword" class="w-4 h-4"></i>
                                        <i data-lucide="eye-off" x-show="showPassword" class="w-4 h-4" x-cloak></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Confirmation --}}
                            <div class="relative">
                                <label class="block text-xs font-bold text-gray-600 mb-2">確認用（再入力）</label>
                                <input :type="showPassword ? 'text' : 'password'" 
                                       name="password_confirmation" 
                                       placeholder="••••••••"
                                       class="w-full bg-white border border-gray-200 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 outline-none transition">
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Submit Button with Loading State --}}
                    <div class="pt-6">
                        <button type="submit"
                            class="w-full bg-[#1a365d] text-white rounded-xl py-4 text-sm font-black hover:bg-[#0f172a] shadow-xl shadow-blue-900/10 transition-all duration-300 tracking-[0.2em] uppercase flex justify-center items-center">
                            <span x-show="!loading">更新を保存する</span>
                            <span x-show="loading" class="flex items-center" x-cloak>
                                <svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                処理中...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
<script>
    // This ensures icons update when Alpine changes the DOM
    document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
            // Wait for Alpine to finish DOM updates, then refresh icons
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    });
</script>