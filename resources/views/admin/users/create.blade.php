<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl w-full flex bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden border border-slate-100">

            <!-- Left Panel -->
            <div class="hidden lg:flex lg:w-1/3 bg-gradient-to-b from-[#1a365d] via-[#1e293b] to-[#0f172a] p-10 flex-col justify-between text-white relative overflow-hidden">
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="space-y-10 relative z-10">
                    <div class="flex justify-center">
                        <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 shadow-2xl">
                            <img src="{{ asset('images/RESONANT.png') }}" alt="Resonant Systems Logo" class="h-12 w-auto object-contain brightness-110">
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

            <!-- Right Panel / Form -->
            <div class="w-full lg:w-2/3 p-10 bg-white">
                <div class="mb-8 border-b border-gray-100 pb-6">
                    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">新規ユーザー登録</h1>
                    <p class="text-xs text-gray-500 mt-2">アカウントを作成するために、以下の情報を入力してください。</p>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}"
      x-data="{
          role: '{{ old('role', '') }}',
          showPassword: false,
          loading: false,
          errors: {name:'', email:'', role:'', password:''}
      }">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-1">
                        <label class="flex justify-between text-xs font-bold text-gray-600">
                            <span>氏名</span>
                            <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded text-[10px]">必須</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                               @blur="errors.name = ($el.value === '') ? '氏名を入力してください' : ''"
                               class="w-full bg-gray-50 border rounded px-4 py-2.5 text-sm transition outline-none"
                               :class="errors.name ? 'border-red-500 focus:ring-red-200' : 'border-gray-300 focus:ring-blue-500 focus:bg-white'"
                               placeholder="例：山田 太郎">
                        <p x-show="errors.name" x-text="errors.name" class="text-[10px] text-red-500 mt-1 font-medium"></p>
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-1 mt-4">
                        <label class="flex justify-between text-xs font-bold text-gray-600">
                            <span>メールアドレス</span>
                            <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded text-[10px]">必須</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               @blur="errors.email = !String($el.value).toLowerCase().match(/^(([^<>()[\]\\.,;:\s@']+(\.[^<>()[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/) ? '有効なメール形式で入力してください' : ''"
                               class="w-full bg-gray-50 border rounded px-4 py-2.5 text-sm transition outline-none"
                               :class="errors.email ? 'border-red-500 focus:ring-red-200' : 'border-gray-300 focus:ring-blue-500 focus:bg-white'"
                               placeholder="shain@company.co.jp">
                        <p x-show="errors.email" x-text="errors.email" class="text-[10px] text-red-500 mt-1 font-medium"></p>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Role -->
                    <div class="mt-4">
                        <label class="flex justify-between text-xs font-bold text-gray-600 mb-2">
                            <span>役割（ロール）</span>
                            <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded text-[10px]">必須</span>
                        </label>
                        <select name="role" x-model="role" required
                                @change="errors.role = (role === '') ? '役割を選択してください' : ''"
                                class="w-full bg-gray-50 border rounded px-4 py-2.5 text-sm focus:ring-1 transition outline-none"
                                :class="errors.role ? 'border-red-500' : 'border-gray-300 focus:ring-blue-500'">
                            <option value="">ロールを選択してください</option>
                            <option value="admin">管理者</option>
                            <option value="manager">マネージャー</option>
                            <option value="employee">従業員</option>
                        </select>
                        <p x-show="errors.role" x-text="errors.role" class="text-[10px] text-red-500 mt-1 font-medium"></p>
                        <x-input-error :messages="$errors->get('role')" class="mt-1" />
                    </div>

                    <!-- Department (conditional) -->
                    <div x-show="role !== 'admin' && role !== ''" x-transition class="mt-4 space-y-2">
                        <label class="flex justify-between text-xs font-bold text-gray-600">
                            <span>所属部署</span>
                            <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded text-[10px]">必須</span>
                        </label>
                        <select id="department_id" name="department_id" :required="role !== 'admin'"
                                class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                            <option value="" disabled selected>部署を選択してください</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Password & Confirmation -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-600 mb-2">パスワード</label>
                            <div class="relative group">
                                <input :type="showPassword ? 'text' : 'password'"
                                       name="password"
                                       required
                                       @blur="errors.password = ($el.value.length < 8) ? '8文字以上で入力してください' : ''"
                                       class="w-full bg-gray-50 border rounded px-4 py-2.5 pr-10 text-sm outline-none transition"
                                       :class="errors.password ? 'border-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                       placeholder="8文字以上">
                                <button type="button" @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600 transition-colors">
                                    <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <p x-show="errors.password" x-text="errors.password" class="text-[10px] text-red-500 mt-1 font-medium"></p>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-600 mb-2">確認用</label>
                            <input :type="showPassword ? 'text' : 'password'"
                                   name="password_confirmation"
                                   required
                                   class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2.5 text-sm outline-none focus:ring-blue-500 focus:bg-white transition">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-6">
                        <button type="submit"
                                @click="loading = true"
                                class="w-full bg-[#1a365d] text-white rounded py-3 text-sm font-bold hover:bg-[#2a4a7d] shadow-md transition-all tracking-widest flex justify-center items-center gap-2">
                            <span x-show="!loading">登録を実行する</span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2 text-white" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                処理中...
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Lucide Icons -->
    <script src="https://cdn.jsdelivr.net/npm/lucide/dist/lucide.min.js"></script>
    <script>document.addEventListener('alpine:init', () => { lucide.createIcons(); });</script>
</x-guest-layout>