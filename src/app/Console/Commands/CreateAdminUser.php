<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('admin:create {username? : ログイン用のユーザー名（半角英数字・-・_）} {--name=管理者 : 表示名}')]
#[Description('管理画面（空き状況の管理）にログインするアカウントを作成・パスワード変更する')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $username = $this->argument('username') ?? text('ログイン用のユーザー名', required: true);
        $password = password('パスワード（8文字以上）', required: true);

        $validator = Validator::make(
            ['username' => $username, 'password' => $password],
            [
                'username' => ['required', 'regex:/^[A-Za-z0-9_-]{3,50}$/'],
                'password' => ['required', 'min:8'],
            ],
            [
                'username.regex' => 'ユーザー名は半角英数字・-・_ の3〜50文字にしてください。',
                'password.min' => 'パスワードは8文字以上にしてください。',
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(
            ['username' => $username],
            ['name' => $this->option('name'), 'password' => $password],
        );

        $this->info($user->wasRecentlyCreated
            ? "管理者アカウント {$username} を作成しました。"
            : "管理者アカウント {$username} のパスワードを変更しました。");

        return self::SUCCESS;
    }
}
