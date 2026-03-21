@props([
    'name', 
    'options' => [], 
    'placeholder' => '全て', 
    'selected' => null
])

<form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
    {{-- Capture existing search/other queries so they aren't lost when filtering --}}
    @foreach(request()->except($name) as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <select name="{{ $name }}" onchange="this.form.submit()" 
            class="bg-white border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 shadow-sm transition-all min-w-[160px] cursor-pointer hover:border-slate-300">
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ (string)$selected === (string)$value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    
    @if(request($name))
        <a href="{{ url()->current() }}" 
           class="text-xs text-slate-400 hover:text-rose-500 font-medium px-2 transition-colors"
           title="フィルターをクリア">
            <i data-lucide="x-circle" class="w-4 h-4"></i>
        </a>
    @endif
</form>

{{-- Example usage
  <x-filter-role
    name="role" 
    placeholder="全ての権限"
    :selected="request('role')"
    :options="[
        'admin' => '管理者 (Admin)',
        'manager' => 'マネージャー (Manager)',
        'employee' => '従業員 (Employee)'
    ]" 
/> --}}