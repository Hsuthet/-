<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow">
                
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">依頼者用一覧画面</h1>
                    <a href="{{ route('business-requests.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md font-bold hover:bg-blue-700 transition">
                        ＋ 新規作成
                    </a>
                </div>

                @php
                    $headers = ['依頼番号', '件名', '依頼区分', '依頼者', '所属部署', '依頼日', 'ステータス', '操作'];
                @endphp

                <x-data-table id="requestsTable" :headers="$headers">
                    @foreach($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-3 text-center">{{ $request->request_number }}</td>
                            <td class="border border-gray-300 px-4 py-3 font-medium">{{ $request->title }}</td>
                            <td class="border border-gray-300 px-4 py-3">
                                @foreach($request->categories as $cat)
                                    <span class="bg-indigo-100 text-indigo-700 text-[10px] px-2 py-0.5 rounded-full">{{ $cat->name }}</span>
                                @endforeach
                            </td>
                            <td class="border border-gray-300 px-4 py-3">{{ $request->user?->name }}</td>
                            <td class="border border-gray-300 px-4 py-3">{{ $request->user?->department?->name }}</td>
                            <td class="border border-gray-300 px-4 py-3 text-center">{{ $request->created_at->format('Y/m/d') }}</td>
                            <td class="border border-gray-300 px-4 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-white text-xs {{ $request->status === 'PENDING' ? 'bg-orange-400' : 'bg-teal-500' }}">
                                    {{ $request->status }}
                                </span>
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-center">
                                <a href="{{ route('business-requests.show', $request) }}" class="text-blue-600 hover:underline">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>

            </div>
        </div>
    </div>
</x-app-layout>