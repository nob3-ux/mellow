<?php

/*
|--------------------------------------------------------------------------
| サイト全体の設定
|--------------------------------------------------------------------------
|
| 店舗情報（構造化データ・フッター等で使用）、グローバルナビ、プラン一覧。
| ページごとのタイトルや説明文は config/pages.php を参照。
|
*/

return [

    'name' => 'PHOTO STUDIO MELLOW',
    'tagline' => '生花と撮れるフォトスタジオ',
    'description' => '東京・渋谷区神宮前（外苑前駅徒歩6分）の、生花と撮れる完全貸切のフォトスタジオ。お誕生日・七五三・マタニティ・ハーフ成人式など、ご家族の記念日を生花スタイリングで彩ります。',

    'contact' => [
        'email' => 'info@ps-mellow.jp',
        'line_url' => 'https://lin.ee/jZjd06r',
        'line_id' => '@125qxsye',
        'instagram_url' => 'https://www.instagram.com/photo_studio_mellow/',
        'instagram_id' => 'photo_studio_mellow',
        'pinterest_url' => 'https://pin.it/719Evwt',
    ],

    'address' => [
        'postal_code' => '150-0001',
        'region' => '東京都',
        'locality' => '渋谷区',
        'street' => '神宮前2-5-6 アマデウスハウス205',
        'map_url' => 'https://www.google.com/maps/search/?api=1&query='.rawurlencode('東京都渋谷区神宮前2-5-6 アマデウスハウス'),
        'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12964.73954336454!2d139.70442256977537!3d35.672449300000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188c9844087417%3A0x5b0a4da1fa96e3e9!2z44CSMTUwLTAwMDEg5p2x5Lqs6YO95riL6LC35Yy656We5a6u5YmN77yS5LiB55uu77yV4oiS77yWIOOCouODnuODh-OCpuOCueODj-OCpuOCuQ!5e0!3m2!1sja!2sjp!4v1655982244460!5m2!1sja!2sjp',
        // Googleマップで住所検索するとマンション裏へ案内されるため、来店時はアクセスページの地図を案内する
        'stations' => [
            '東京メトロ銀座線「外苑前駅」徒歩6分',
            '銀座線・半蔵門線・千代田線「表参道駅」徒歩13分',
            '都営大江戸線「国立競技場駅」徒歩15分',
            'JR中央線「千駄ヶ谷駅」徒歩15分',
        ],
    ],

    // フォーム送信の通知先
    'mail_to' => env('SITE_MAIL_TO', 'info@ps-mellow.jp'),

    // 空き状況を公開する範囲（ご予約はご希望日の3ヶ月前から受付）
    'schedule_months_ahead' => 3,

    /*
    | グローバルナビ。route が null の項目は子メニューの見出しのみ。
    */
    'navigation' => [
        ['label' => 'プラン', 'route' => 'plans.index', 'children' => [
            ['label' => '生花Stylingプラン', 'route' => 'plans.flower-styling'],
            ['label' => 'Anniversaryプラン', 'route' => 'plans.anniversary'],
            ['label' => '七五三プラン', 'route' => 'plans.shichigosan'],
            ['label' => 'マタニティープラン', 'route' => 'plans.maternity'],
            ['label' => 'ハーフ成人式プラン', 'route' => 'plans.half-seijinshiki'],
            ['label' => 'Mellow wedding', 'route' => 'plans.wedding'],
        ]],
        ['label' => 'スタジオ', 'route' => null, 'children' => [
            ['label' => 'Mellowについて', 'route' => 'about'],
            ['label' => 'スタジオイメージ', 'route' => 'studio'],
            ['label' => 'ギャラリー', 'route' => 'gallery'],
            ['label' => '商品アイテム', 'route' => 'items'],
        ]],
        ['label' => '空き状況', 'route' => 'schedule'],
        ['label' => 'ご予約の流れ', 'route' => 'flow'],
        ['label' => 'アクセス', 'route' => 'access'],
        ['label' => 'お問い合わせ', 'route' => 'contact'],
    ],

    /*
    | 撮影プラン（一覧カード・予約フォームのプラン選択で使用）
    | price: 一覧に表示する「〜」付きの最低料金（平日・税込）。null は料金をページで案内。
    */
    'plans' => [
        'flower-styling' => [
            'name' => '生花Stylingプラン',
            'lead' => '生花でスタイリングする、Mellowいちばん人気の記念日フォト。',
            'target' => 'お誕生日・記念日に生花を使って撮影したい方',
            'price' => 22000,
            'image' => 'home/03',
        ],
        'anniversary' => [
            'name' => 'Anniversaryプラン',
            'lead' => '生花を使わずに、背景紙やインテリアで自然体に残す記念日フォト。',
            'target' => 'お誕生日・記念日を生花なしで撮影したい方',
            'price' => 20000,
            'image' => 'home/04',
        ],
        'shichigosan' => [
            'name' => '七五三プラン',
            'lead' => '趣のあるアンティーク着物で。スタジオと日枝神社ロケから選べます。',
            'target' => '3歳・5歳の七五三',
            'price' => 38000,
            'image' => 'home/05',
        ],
        'maternity' => [
            'name' => 'マタニティープラン',
            'lead' => '産まれてくる赤ちゃんを想う夫婦のカタチを、自然体の飾りたくなる写真で。料金は通常プランを適用します。',
            'target' => '妊娠中のママ・ご家族',
            'price' => 20000,
            'image' => 'home/06',
        ],
        'half-seijinshiki' => [
            'name' => 'ハーフ成人式プラン',
            'lead' => '10歳の節目に。着物・ヘアメイク・着付けまで含めてお任せください。',
            'target' => '10歳のお子様',
            'price' => 35000,
            'image' => 'home/07',
        ],
        'wedding' => [
            'name' => 'Mellow wedding',
            'lead' => 'ドレス貸出無料。ふたりらしく、リラックスして残すウェディングフォト。',
            'target' => 'フォトウェディング・前撮り',
            'price' => 66000,
            'image' => 'plan-wedding/01',
        ],
    ],

];
