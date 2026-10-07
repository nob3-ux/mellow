<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('admin:create {email? : ログイン用メールアドレス} {--name=管理者 : 表示名}')]
#[Description('管理画面（空き状況の管理）にログインするアカウントを作成・パスワード変更する')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $email = $this->argument('email') ?? text('ログイン用メールアドレス', required: true);
        $password = password('パスワード（12文字以上）', required: true);

        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            ['email' => ['required', 'email'], 'password' => ['required', 'min:12']],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => $this->option('name'), 'password' => $password],
        );

        $this->info($user->wasRecentlyCreated
            ? "管理者アカウント {$email} を作成しました。"
            : "管理者アカウント {$email} のパスワードを変更しました。");

        return self::SUCCESS;
    }
}
