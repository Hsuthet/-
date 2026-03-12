<x-app-layout>
<div class="py-12 bg-gray-100 min-h-screen flex justify-center items-start">
    <div class="max-w-lg w-full bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">{{ $request->title }}</h2>
            <button class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>

        {{-- Request Details --}}
        <div class="p-6 space-y-6 text-sm">
            <div class="grid grid-cols-3 gap-y-4 gap-x-4">
                <span class="text-gray-500">依頼者</span>
                <span class="col-span-2 font-medium">{{ $request->user?->name }}</span>

                <span class="text-gray-500">依頼番号</span>
                <span class="col-span-2 font-medium">{{ $request->request_number }}</span>

                <span class="text-gray-500">ステータス</span>
                <span class="col-span-2">
                    <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-xs font-semibold border border-gray-300">
                        未承認
                    </span>
                </span>

                <span class="text-gray-500">期限</span>
                <span class="col-span-2 font-medium">{{ $request->due_date }}</span>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-gray-700 font-semibold mb-1">説明</label>
                <div class="border rounded-lg p-3 bg-gray-50 text-gray-700 min-h-[100px]">
                    {{ $request->description }}
                </div>
            </div>

            {{-- Approve / Reject Form --}}
            <div x-data="{ selectedAction: '' }" class="space-y-4">

                <form action="{{ route('business-requests.assign', $request->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" :value="selectedAction">

                    {{-- Action Buttons --}}
                    <div class="flex gap-3">
                        <button type="button" 
                                @click="selectedAction = 'approve'" 
                                :class="selectedAction === 'approve' ? 'bg-green-700 ring-2 ring-green-300' : 'bg-green-500'"
                                class="flex-1 text-white py-2 rounded-lg font-bold transition shadow hover:shadow-md">
                            承認 (Approve)
                        </button>

                        <button type="button" 
                                @click="selectedAction = 'reject'" 
                                :class="selectedAction === 'reject' ? 'bg-red-700 ring-2 ring-red-300' : 'bg-red-500'"
                                class="flex-1 text-white py-2 rounded-lg font-bold transition shadow hover:shadow-md">
                            却下 (Reject)
                        </button>
                    </div>

                    {{-- Approve Block --}}
<div x-show="selectedAction === 'approve'" x-transition 
     class="p-4 border-2 border-dashed border-blue-200 rounded-lg bg-blue-50/50">
    
    <label for="worker_id" class="block text-gray-700 font-semibold mb-2">担当者 (Assignee)</label>
    
    <select name="worker_id" id="worker_id" 
            class="w-full border rounded-lg p-2 bg-white"
            :required="selectedAction === 'approve'" {{-- Required only when approving --}}
            @if($employees->isEmpty()) disabled @endif>
        <option value="">-- 担当者を選択してください --</option>
        @foreach($employees as $emp)
            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
        @endforeach
    </select>

    @if($employees->isEmpty())
        <p class="text-xs text-red-500 mt-2 text-center font-bold">
            エラー: 対象部署（{{ $request->targetDepartment->name }}）に担当者がいません。
        </p>
    @endif
    <p class="text-xs text-gray-500 mt-2 text-center">※承認時のみ表示</p>
</div>

{{-- Reject Block --}}
<div x-show="selectedAction === 'reject'" x-transition 
     class="p-4 border-2 border-dashed border-red-200 rounded-lg bg-red-50/50">
    
    <label class="block text-gray-700 font-semibold mb-2">却下理由 (Reason)</label>
    
    <textarea name="reason" 
              class="w-full border rounded-lg p-2" 
              placeholder="却下の理由を詳しく入力してください"
              :required="selectedAction === 'reject'"></textarea> {{-- Required only when rejecting --}}
    
    <p class="text-xs text-gray-500 mt-2 text-center">※却下時のみ表示</p>
</div>

                    {{-- Submit / Cancel --}}
                    <div class="flex gap-3 mt-4" x-show="selectedAction !== ''">
                        <button type="submit" 
                                class="flex-1 bg-green-700 text-white py-2 rounded-lg font-bold hover:bg-green-800 transition shadow">
                            確定 (Confirm)
                        </button>
                        <button type="button" 
                                @click="selectedAction = ''" 
                                class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 rounded-lg font-bold hover:bg-gray-50 transition">
                            キャンセル
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
</x-app-layout>