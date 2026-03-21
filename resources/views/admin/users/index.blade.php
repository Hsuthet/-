<x-app-layout>
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">User Management</h1>
                <p class="text-sm text-slate-500 mt-1">Manage system access and roles for all employees.</p>
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('users.index') }}" method="GET" class="flex items-center gap-2">
                    <select name="role" onchange="this.form.submit()" 
                            class="bg-white border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 shadow-sm transition-all">
                        <option value="">全ての権限 (All Roles)</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>管理者 (Admin)</option>
                        <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>マネージャー (Manager)</option>
                        <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>従業員 (Employee)</option>
                    </select>
                    
                    @if(request('role'))
                        <a href="{{ route('users.index') }}" class="text-xs text-slate-400 hover:text-red-500 font-medium">Clear</a>
                    @endif
                </form>

                <a href="{{ route('register') }}" 
                   class="bg-[#1a365d] hover:bg-blue-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 transition-all flex items-center">
                    <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                    ユーザー作成
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <x-data-table 
                id="usersTable" 
                :headers="['Name', 'Email', 'Department', 'Role', 'Action']"
                role="admin"
            >
                @foreach($users as $user)
                <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-50 last:border-0">
                    <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        <span class="bg-slate-100 px-2 py-1 rounded-lg text-[11px] font-semibold text-slate-600">
                            {{ $user->department->name ?? 'Unassigned' }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase
                            @if($user->role === 'admin') bg-red-50 text-red-600 border border-red-100
                            @elseif($user->role === 'manager') bg-blue-50 text-blue-600 border border-blue-100
                            @else bg-emerald-50 text-emerald-600 border border-emerald-100
                            @endif">
                            {{ $user->role }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs">Edit</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-slate-300 hover:text-red-600 font-bold text-xs transition-colors"
                                    onclick="return confirm('Delete this user?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </x-data-table>
        </div>

    </div>
</div>
</x-app-layout>