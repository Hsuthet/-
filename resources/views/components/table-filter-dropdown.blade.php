@props([
    'tableId', 
    'role' => 'employee', 
    'placeholder' => '全てを表示'
])

<div class="relative min-w-[200px]">
    <select 
        id="{{ $tableId }}_status_filter" 
        class="bg-white border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 shadow-sm transition-all appearance-none cursor-pointer hover:border-slate-300"
    >
        <option value="">{{ $placeholder }}</option>
        
        @if(strtoupper($role) === 'EMPLOYEE')
            <option value="{{ Auth::user()->name }}">自分の依頼 (My Requests)</option>
            <option value="承認待ち">承認待ち (Pending)</option>
            <option value="作業中">作業中 (Working)</option>
            <option value="完了">完了 (Completed)</option>
        
        @elseif(strtoupper($role) === 'MANAGER')
            <option value="承認待ち" selected>承認待ち (Needs Approval)</option>
            <option value="承認済み">承認済み (Approved)</option>
            <option value="完了">完了 (Completed)</option>
        @endif
    </select>

    {{-- Custom Arrow Icon --}}
    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
        <i data-lucide="chevron-down" class="w-4 h-4"></i>
    </div>
</div>

{{-- <div class="inline-flex rounded-xl shadow-sm border border-slate-200 p-1 bg-slate-50">
    <x-filter-button tableId="tasksTable" label="すべて" search="" :active="true" />
    <x-filter-button tableId="tasksTable" label="自分の依頼" :search="Auth::user()->name" />
    <x-filter-button tableId="tasksTable" label="承認待ち" search="承認待ち" />
</div> --}}