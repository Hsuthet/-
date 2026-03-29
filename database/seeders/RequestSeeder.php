<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessRequest;
use App\Models\Department;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RequestSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing data to link relationships
        $departments = Department::all();
        $categories = Category::all();
        $user = User::first() ?? User::factory()->create();

        $titles = [
            '新基幹システムのアカウント発行依頼',
            '第2四半期 経費精算書の確認依頼',
            '社内イベント用バナーデザイン制作',
            '月次売上レポートの集計および分析',
            'リモートワーク用VPN接続の不具合調査',
            '新入社員向けPCセットアップおよび配布',
            '翻訳業務（日本語から英語）の依頼',
            'サーバーメンテナンスに伴う告知文作成',
            '顧客向けパンフレットの増刷修正',
            '勤怠管理システムの不具合修正依頼'
        ];

        $contents = [
            '新しく配属されたメンバー5名分のアカウント作成をお願いします。',
            '今月分の経費精算に不備がないか、承認前のご確認をお願いいたします。',
            '来月の社内親睦会に向けた告知バナー（サイズ：1200x628）の作成をお願いします。',
            '先月の各支店の売上データを集計し、グラフ化して報告書にまとめてください。',
            '一部のユーザーからVPN接続が不安定との報告があるため、ログの調査をお願いします。',
            '4月1日付で入社予定の5名に対し、標準PCの設定をお願いします。',
            '製品マニュアルの第3章について、自然な英語表現への翻訳をお願いします。',
            '来週日曜日の深夜に行われるメンテナンス情報を、全社員へメール通知してください。',
            '住所変更に伴い、既存のパンフレットの裏面を修正して500部発注してください。',
            '打刻時間の修正が反映されないバグが発生しているため、至急調査をお願いします。'
        ];

      $users = User::all();

if ($users->isEmpty()) {
    $users = User::factory()->count(10)->create();
}

foreach (range(0, 9) as $i) {
    $requestNumber = Carbon::now()->format('Ym') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);

    $request = BusinessRequest::create([
        'request_number' => $requestNumber,
        'title'          => $titles[$i],
        'user_id'        => $users->random()->id, // 👈 random requester
        'department_id'  => $departments->random()->id,
        'target_department_id' => rand(1, 6),
        'due_date'       => Carbon::now()->addDays(rand(3, 14))->format('Y-m-d'),
        'status'         => 'PENDING',
        'created_at'     => Carbon::now()->subDays(rand(0, 5)),
    ]);

    // 2. Create the Related Content (In 'request_contents' table)
    // Adjust 'description' and 'special_note' to match your actual column names
    $request->requestContent()->create([
        'description'  => $contents[$i], 
        'special_note' => $i % 3 == 0 ? '至急対応をお願いいたします。' : null,
    ]);

    // 3. Sync Categories (Pivot table)
    $randomCategories = $categories->random(rand(1, 2))->pluck('id');
    $request->categories()->attach($randomCategories);
}
    }
}