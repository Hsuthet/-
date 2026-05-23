<x-app-layout>
    @section('header_title', 'ユーザー情報の編集')

    <div class="min-h-screen bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
        {{-- フォームコンテナ --}}
        <div class="max-w-4xl mx-auto">
            
            {{-- ナビゲーション --}}
            <div class="mb-8 flex justify-between items-center px-4">
                <a href="{{ route('admin.users.index') }}" class="group flex items-center text-xs font-bold text-slate-400 hover:text-slate-900 transition-colors tracking-widest">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    ユーザー一覧に戻る
                </a>
                <div class="h-px flex-1 mx-8 bg-slate-200/60"></div>
               
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                
                {{-- ヘッダー --}}
                <div class="px-12 py-10 border-b border-slate-100">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">ユーザー情報の編集</h1>
                            <p class="text-slate-500 mt-2 font-medium"><span class="text-blue-600 font-bold">{{ $user->name }}</span> さんのプロフィール詳細とシステム権限を編集します。</p>
                        </div>
                        <div class="text-right hidden md:block">
                            <span class="text-[10px] font-bold text-slate-400 block mb-1 uppercase">アカウント ID</span>
                            <span class="font-mono text-sm bg-slate-100 px-3 py-1 rounded-md text-slate-600">#{{ $user->id }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-12">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" 
                          x-data="{ 
                            role: '{{ old('role', $user->role) }}', 
                            showPassword: false, 
                            loading: false 
                          }" 
                          @submit="loading = true"
                          class="space-y-12">
                        @csrf
                        @method('PUT')

                        {{-- セクション: 基本情報 --}}
                        <section class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                            <div class="lg:col-span-1">
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest border-l-4 border-blue-600 pl-3">基本情報</h3>
                                <p class="text-xs text-slate-400 mt-4 leading-relaxed">氏名、連絡先、および組織内での役割を設定します。</p>
                            </div>
                            
                            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">氏名</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full bg-white border-2 border-slate-100 rounded-xl px-5 py-3.5 text-sm font-semibold focus:border-blue-600 focus:ring-0 transition-all outline-none">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">メールアドレス</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full bg-white border-2 border-slate-100 rounded-xl px-5 py-3.5 text-sm font-semibold focus:border-blue-600 focus:ring-0 transition-all outline-none">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">役割（ロール）</label>
                                    <select name="role" x-model="role" required
                                        class="w-full bg-white border-2 border-slate-100 rounded-xl px-5 py-3.5 text-sm font-semibold focus:border-blue-600 focus:ring-0 transition-all outline-none appearance-none">
                                        <option value="admin">管理者</option>
                                        <option value="manager">マネージャー</option>
                                        <option value="employee">従業員</option>
                                    </select>
                                </div>

                                <div class="space-y-2" x-show="role !== 'admin'" x-transition>
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">所属部署</label>
                                    <select name="department_id" :required="role !== 'admin'" :disabled="role === 'admin'"
                                        class="w-full bg-white border-2 border-slate-100 rounded-xl px-5 py-3.5 text-sm font-semibold focus:border-blue-600 focus:ring-0 transition-all outline-none appearance-none">
                                        <option value="" disabled>部署を選択してください</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </section>

                        <hr class="border-slate-100">

                        {{-- セクション: セキュリティ --}}
                        <section class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                            <div class="lg:col-span-1">
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest border-l-4 border-slate-300 pl-3">セキュリティ</h3>
                                <p class="text-xs text-slate-400 mt-4 leading-relaxed">認証情報の更新を行います。変更しない場合は入力を省略してください。</p>
                            </div>

                            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 relative">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">新しいパスワード</label>
                                    <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="••••••••"
                                        class="w-full bg-white border-2 border-slate-100 rounded-xl px-5 py-3.5 text-sm font-semibold focus:border-blue-600 focus:ring-0 transition-all outline-none">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-[2.4rem] text-slate-300 hover:text-slate-600 transition-colors">
                                        <i data-lucide="eye" x-show="!showPassword" class="w-5 h-5"></i>
                                        <i data-lucide="eye-off" x-show="showPassword" class="w-5 h-5" x-cloak></i>
                                    </button>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">確認用（再入力）</label>
                                    <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" placeholder="••••••••"
                                        class="w-full bg-white border-2 border-slate-100 rounded-xl px-5 py-3.5 text-sm font-semibold focus:border-blue-600 focus:ring-0 transition-all outline-none">
                                </div>
                            </div>
                        </section>

                        {{-- アクションボタン --}}
                        <div class="pt-8 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-4">
                            
                            {{-- キャンセルボタン --}}
                            <a href="{{ route('admin.users.index') }}" 
                               class="w-full sm:w-auto px-10 py-4 rounded-xl bg-white border-2 border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all text-center tracking-widest">
                                キャンセル
                            </a>

                            {{-- 保存ボタン --}}
                            <button type="submit"
                                    class="w-full sm:w-72 px-10 py-4 rounded-xl bg-blue-600 text-sm font-bold text-white hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/20 active:scale-[0.98] transition-all flex items-center justify-center tracking-widest group">
                                <span x-show="!loading" class="flex items-center">
                                    変更を保存する
                                    <i data-lucide="check" class="w-4 h-4 ml-2 group-hover:scale-110 transition-transform"></i>
                                </span>

                                <span x-show="loading" class="flex items-center" x-cloak>
                                    <svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    更新処理中...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- フッター --}}
            <div class="mt-8 text-center space-y-4">
             
                <div class="flex justify-center opacity-20 grayscale">
                    <img src="{{ asset('images/RESONANT.png') }}" alt="Logo" class="h-6 w-auto">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    });
</script>