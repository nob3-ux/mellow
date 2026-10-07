<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 管理画面のログインをメールアドレスではなくユーザー名で行うため、username を追加する。
     * 既存のアカウントは、メールアドレスの @ より前をユーザー名にする。
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('id');
        });

        foreach (DB::table('users')->get(['id', 'email']) as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'username' => strstr($user->email, '@', true) ?: "user{$user->id}",
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->unique()->change();
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
