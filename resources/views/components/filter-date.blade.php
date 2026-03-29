@props([
    'fromName' => 'from',
    'toName' => 'to',
    'fromValue' => null,
    'toValue' => null
])

@php
    $fromValue = $fromValue ?? request($fromName);
    $toValue = $toValue ?? request($toName);
@endphp

<form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">

    @foreach(request()->except([$fromName, $toName]) as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <input type="date" name="{{ $fromName }}" value="{{ $fromValue }}">
    <span>〜</span>
    <input type="date" name="{{ $toName }}" value="{{ $toValue }}">

    <button type="submit">適用</button>

</form>

{{-- Example usage:
  <x-filter-date 
    fromName="start_date" 
    toName="end_date" 
    fromValue="{{ request('start_date') }}" 
    toValue="{{ request('end_date') }}" 
  />