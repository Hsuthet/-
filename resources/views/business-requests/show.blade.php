<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto bg-white p-10 shadow border border-gray-200">
            <div class="flex justify-between border-b pb-4 mb-6">
                <h1 class="text-2xl font-bold">{{ $request->request_number }}</h1>
                <span class="text-gray-500 text-sm">作成者: {{ $request->user->name }}</span>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="text-xs text-gray-500 block uppercase">件名</label>
                    <p class="text-lg font-semibold">{{ $request->title }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block uppercase">期日</label>
                    <p class="text-lg">{{ $request->due_date }}</p>
                </div>
            </div>

            <div class="mb-10">
                <label class="text-xs text-gray-500 block uppercase font-bold">特記事項</label>
                <div class="p-4 bg-gray-50 border rounded mt-2 min-h-[100px]">
                    {{ $request->requestContent->special_note ?? '記載なし' }}
                </div>
            </div>

            <div class="border-t pt-6">
                @if($request->status == 'pending_approval')
                    <h3 class="text-red-600 font-bold mb-4">【管理者用操作】</h3>
                    <form action="{{ route('business-requests.updateStatus', $request->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="in_progress">
                        <button class="bg-green-600 text-white px-8 py-2 rounded hover:bg-green-700">
                            承認して作業を開始する (Approve)
                        </button>
                    </form>
                @elseif($request->status == 'in_progress')
                    <h3 class="text-blue-600 font-bold mb-4">【担当部署用操作】</h3>
                    <form action="{{ route('business-requests.updateStatus', $request->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button class="bg-black text-white px-8 py-2 rounded hover:bg-gray-800">
                            完了報告を送る (Mark as Complete)
                        </button>
                    </form>
                @else
                    <div class="text-center p-4 bg-green-50 text-green-700 rounded font-bold">
                        ✓ この業務は完了しています。
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>