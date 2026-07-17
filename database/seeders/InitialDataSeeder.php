<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Site;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerRate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * 管理者：Ayaさん
         *
         * メールアドレスとパスワードは、
         * 本番運用前に必ず実際のものへ変更してください。
         */
        User::query()->updateOrCreate(
            ['email' => 'accounts@pawknit.work'],
            [
                'name' => 'admin',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin->value,
                'is_active' => true,
            ],
        );

        /*
         * 閲覧者：社長
         */
        User::query()->updateOrCreate(
            ['email' => 'president@example.com'],
            [
                'name' => '社長',
                'password' => Hash::make('password'),
                'role' => UserRole::Viewer->value,
                'is_active' => true,
            ],
        );

        /*
         * テスト用作業員
         */
        $workers = [
            [
                'employee_code' => 'W001',
                'name' => '作業員 太郎',
                'pin' => '1111',
                'display_order' => 1,
                'daily_rate' => 18000,
            ],
            [
                'employee_code' => 'W002',
                'name' => '作業員 次郎',
                'pin' => '2222',
                'display_order' => 2,
                'daily_rate' => 20000,
            ],
        ];

        foreach ($workers as $workerData) {
            $worker = Worker::query()->updateOrCreate(
                ['employee_code' => $workerData['employee_code']],
                [
                    'name' => $workerData['name'],
                    'pin_hash' => Hash::make($workerData['pin']),
                    'display_order' => $workerData['display_order'],
                    'joined_on' => now()->startOfMonth()->toDateString(),
                    'is_active' => true,
                ],
            );

            WorkerRate::query()->updateOrCreate(
                [
                    'worker_id' => $worker->id,
                    'effective_from' => now()->startOfMonth()->toDateString(),
                ],
                [
                    'daily_rate' => $workerData['daily_rate'],
                    'effective_to' => null,
                ],
            );
        }

        /*
         * テスト用現場
         */
        Site::query()->updateOrCreate(
            ['name' => '日本橋ビル改修工事'],
            [
                'short_name' => '日本橋',
                'contract_amount' => 1200000,
                'starts_on' => now()->startOfMonth()->toDateString(),
                'planned_ends_on' => now()->addMonths(2)->toDateString(),
                'is_active' => true,
            ],
        );

        Site::query()->updateOrCreate(
            ['name' => '新宿マンション工事'],
            [
                'short_name' => '新宿',
                'contract_amount' => 800000,
                'starts_on' => now()->startOfMonth()->toDateString(),
                'planned_ends_on' => now()->addMonth()->toDateString(),
                'is_active' => true,
            ],
        );
    }
}
