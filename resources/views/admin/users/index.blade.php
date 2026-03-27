<x-app-layout>
    {{-- ブラウザのタブタイトルを設定 --}}
    @section('header_title', 'ユーザー管理')

    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('success'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 3000)" {{-- This hides it after 3 seconds --}}
        x-show="show"
        class="fixed top-5 right-5 z-[100]"
    >
        <div class="bg-emerald-500 text-white px-6 py-3 rounded-2xl shadow-2xl">
            {{ session('success') }}
        </div>
    </div>
@endif
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 p-2.5 rounded-xl shadow-lg shadow-indigo-200">
                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">ユーザー管理</h1>
                    <p class="text-sm text-slate-500">全従業員のシステムアクセス権限と役割を管理します。</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- 権限フィルター --}}
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">絞り込み:</span>
                    <x-filter-role
                        name="role" 
                        placeholder="全ての権限"
                        :selected="request('role')"
                        :options="[
                            'admin' => '管理者',
                            'manager' => 'マネージャー ',
                            'employee' => '従業員 '
                        ]" 
                    />
                </div>

                {{-- ユーザー作成ボタン --}}
                <a href="{{ route('users.create') }}" 
                   class="inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 rounded-xl font-bold text-white text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all duration-200">
                    <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                    ユーザー作成
                </a>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden border-t-4 border-t-indigo-500">
            <x-data-table 
                id="usersTable" 
                :headers="['氏名', 'メールアドレス', '部署', '権限', 'アクション']"
                role="admin"
            >
                @foreach($users as $user)
<tr class="hover:bg-slate-50/50 transition-colors border-b border-slate-100 last:border-0 group">
    {{-- 氏名 (Name) --}}
    <td class="px-6 py-4 text-left">
        <div class="flex items-center">
            
            <p class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $user->name }}</p>
        </div>
    </td>

    {{-- メールアドレス (Email) --}}
    <td class="px-6 py-4 text-left">
        <div class="flex items-center text-slate-500">
           
            <span class="text-sm tracking-tight">{{ $user->email }}</span>
        </div>
    </td>

    {{-- 部署 (Department) - Aligned Left --}}
    <td class="px-6 py-4 text-left">
        <div class="flex items-center">
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold text-slate-600 bg-slate-100 border border-slate-200/60">
                
                {{ $user->department->name ?? '未配属' }}
            </span>
        </div>
    </td>

    {{-- 権限バッジ (Role) - Aligned Left --}}
    <td class="px-6 py-4 text-left">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase border shadow-sm
            @if($user->role === 'admin') bg-rose-50 text-rose-600 border-rose-100
            @elseif($user->role === 'manager') bg-amber-50 text-amber-600 border-amber-100
            @else bg-emerald-50 text-emerald-600 border-emerald-100
            @endif">
            <span class="w-1 h-1 rounded-full mr-2 
                @if($user->role === 'admin') bg-rose-500
                @elseif($user->role === 'manager') bg-amber-500
                @else bg-emerald-500
                @endif"></span>
            @if($user->role === 'admin') 管理者
            @elseif($user->role === 'manager') マネージャー
            @else 従業員
            @endif
        </span>
    </td>

    {{-- アクション (Actions) - Aligned Left --}}
    <td class="px-6 py-4 text-left">
    <div class="flex items-center gap-2">
        {{-- Edit Button --}}
        <a href="{{ route('users.edit', $user) }}" 
           class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-indigo-100 bg-indigo-50/50 text-indigo-600 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 hover:shadow-lg hover:shadow-indigo-200/50 transition-all duration-300 group/btn"
           title="編集">
            <i data-lucide="pencil" class="w-3.5 h-3.5 group-hover/btn:rotate-12 transition-transform"></i>
            <span class="text-[11px] font-black uppercase tracking-wider">編集</span>
        </a>

        {{-- Delete Button --}}
        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <button type="submit" 
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-rose-100 bg-rose-50/50 text-rose-600 hover:bg-rose-600 hover:text-white hover:border-rose-600 hover:shadow-lg hover:shadow-rose-200/50 transition-all duration-300 group/btn"
                    onclick="return confirm('このユーザーを削除してもよろしいですか？')"
                    title="削除">
                <i data-lucide="trash-2" class="w-3.5 h-3.5 group-hover/btn:shake transition-transform"></i>
                <span class="text-[11px] font-black uppercase tracking-wider">削除</span>
            </button>
        </form>
    </div>
</td>
</tr>
@endforeach
            </x-data-table>
        </div>
    </div>
</x-app-layout>