<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-gray-500 text-sm">承認待ち (Pending)</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-gray-500 text-sm">対応中 (In Progress)</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['in_progress'] }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-gray-500 text-sm">下書き (Drafts)</div>
                    <div class="text-3xl font-bold text-gray-600">{{ $stats['draft'] }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">最近の依頼 (Recent Requests)</h3>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="pb-2">依頼番号</th>
                            <th class="pb-2">件名</th>
                            <th class="pb-2">ステータス</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRequests as $req)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">{{ $req->request_number }}</td>
                            <td class="py-3">{{ $req->title }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 text-xs rounded bg-gray-100">{{ $req->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
</x-app-layout>