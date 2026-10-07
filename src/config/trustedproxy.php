<?php

return [

    /*
    | 信頼するHTTPS中継（リバースプロキシ）
    |
    | ngrok などを経由してアクセスした際に、元のURL（https・ホスト名）で画像等のURLを作るために使う。
    | ローカル確認用は .env で TRUSTED_PROXIES=* 。本番の共用サーバーでは空のまま（信頼しない）。
    | 複数指定はカンマ区切りのIPアドレス。
    */
    'proxies' => env('TRUSTED_PROXIES') ?: null,

];
