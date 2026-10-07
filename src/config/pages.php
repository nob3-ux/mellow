<?php

/*
|--------------------------------------------------------------------------
| ページ定義
|--------------------------------------------------------------------------
|
| ルート名 => [
|   path:        URL
|   view:        resources/views/pages 以下のビュー
|   title:       <title>（サイト名は自動で付与）。ホームは null
|   description: meta description（検索結果の説明文。全角80〜120文字目安）
|   parent:      パンくずの親ページ（ルート名）
|   image:       OGP画像（public/images 以下のキー）。省略時はサイト共通
|   priority:    sitemap.xml の priority
| ]
|
*/

return [

    'pages' => [
        'home' => [
            'path' => '/',
            'view' => 'home',
            'title' => null,
            'description' => '東京・渋谷区神宮前（外苑前駅徒歩6分）の生花と撮れるフォトスタジオ。1枠1家族の完全貸切で、お誕生日・七五三・マタニティ・ハーフ成人式を生花スタイリングで彩ります。LINE・予約フォームから受付中。',
            'priority' => '1.0',
        ],
        'about' => [
            'path' => '/about',
            'view' => 'about',
            'title' => 'Mellowについて',
            'description' => 'PHOTO STUDIO MELLOWは、渋谷区外苑前の小さなマンションの一室にあるアットホームなフォトスタジオ。生花で彩る写真をメインテーマに、ご家族らしい自然な表情を残します。',
            'parent' => 'home',
        ],
        'studio' => [
            'path' => '/studio',
            'view' => 'studio',
            'title' => 'スタジオイメージ',
            'description' => '外苑前のフォトスタジオMellowのスタジオ紹介。ミッドセンチュリーの家具や海外インポートの小物、白背景・背景紙・インテリアを活かした撮影シーンをご覧いただけます。',
            'parent' => 'home',
        ],
        'gallery' => [
            'path' => '/gallery',
            'view' => 'gallery',
            'title' => 'ギャラリー',
            'description' => '生花スタイリングフォト・お誕生日・七五三・マタニティなど、PHOTO STUDIO MELLOWで撮影したお写真のギャラリーです。',
            'parent' => 'home',
        ],
        'gallery.wedding' => [
            'path' => '/gallery/wedding',
            'view' => 'gallery-wedding',
            'title' => 'ウェディングギャラリー',
            'description' => 'PHOTO STUDIO MELLOWで撮影したウェディングフォト・ドレスアップ撮影のギャラリーです。',
            'parent' => 'gallery',
        ],
        'gallery.kimono' => [
            'path' => '/gallery/kimono',
            'view' => 'gallery-kimono',
            'title' => '七五三 着物ギャラリー',
            'description' => 'Mellowでご用意している七五三のアンティーク着物ギャラリー。3歳・5歳・7歳の着物を撮影前にご覧いただけます。',
            'parent' => 'plans.shichigosan',
        ],

        'plans.index' => [
            'path' => '/plans',
            'view' => 'plans.index',
            'title' => '撮影プラン・料金',
            'description' => 'PHOTO STUDIO MELLOWの撮影プランと料金一覧。生花Styling・Anniversary・七五三・マタニティ・ハーフ成人式・ウェディング。20カット／50カットから選べます。',
            'parent' => 'home',
            'priority' => '0.9',
        ],
        'plans.flower-styling' => [
            'path' => '/plans/flower-styling',
            'view' => 'plans.flower-styling',
            'title' => '生花Stylingプラン（お誕生日・記念日）',
            'description' => '生花でスタイリングする記念日フォト。20カット平日22,000円〜、50カット平日35,000円〜（税込）。お誕生日やバースデーフォトに。渋谷区外苑前のフォトスタジオMellow。',
            'parent' => 'plans.index',
            'priority' => '0.9',
        ],
        'plans.anniversary' => [
            'path' => '/plans/anniversary',
            'view' => 'plans.anniversary',
            'title' => 'Anniversaryプラン（お誕生日・記念日）',
            'description' => '生花を使わずに背景紙やインテリアで撮影する記念日フォト。20カット平日20,000円〜、50カット平日33,000円〜（税込）。家族写真・兄弟写真も無料で撮影します。',
            'parent' => 'plans.index',
            'priority' => '0.9',
        ],
        'plans.shichigosan' => [
            'path' => '/plans/shichigosan',
            'view' => 'plans.shichigosan',
            'title' => '七五三プラン（スタジオ・日枝神社ロケ）',
            'description' => 'アンティーク着物で撮る七五三。スタジオ着物50カット平日38,000円〜、着物洋服70カット、日枝神社ロケプランも。渋谷区外苑前のフォトスタジオMellow。',
            'parent' => 'plans.index',
            'priority' => '0.9',
        ],
        'plans.maternity' => [
            'path' => '/plans/maternity',
            'view' => 'plans.maternity',
            'title' => 'マタニティープラン',
            'description' => '産まれてくる赤ちゃんを想う夫婦のカタチを、シンプルで自然体の写真に残すマタニティフォト。料金は生花Styling・Anniversaryの通常プランを適用。渋谷区外苑前のフォトスタジオMellow。',
            'parent' => 'plans.index',
            'priority' => '0.9',
        ],
        'plans.half-seijinshiki' => [
            'path' => '/plans/half-seijinshiki',
            'view' => 'plans.half-seijinshiki',
            'title' => 'ハーフ成人式プラン（10歳）',
            'description' => '10歳のハーフ成人式を着物で記念撮影。着物・ヘアメイク・着付け・撮影料込み。スタジオ着物50カット平日35,000円〜（税込）。渋谷区外苑前のフォトスタジオMellow。',
            'parent' => 'plans.index',
            'priority' => '0.9',
        ],
        'plans.wedding' => [
            'path' => '/plans/wedding',
            'view' => 'plans.wedding',
            'title' => 'Mellow wedding（フォトウェディング）',
            'description' => 'ドレス貸出無料のフォトウェディング。プラン価格66,000円（税込）、所要時間トータル2時間。ふたりらしく、リラックスして撮影できます。',
            'parent' => 'plans.index',
            'priority' => '0.9',
        ],

        'options.dress-up-day' => [
            'path' => '/options/dress-up-day',
            'view' => 'options.dress-up-day',
            'title' => 'dress up day（ドレスアップオプション）',
            'description' => 'ママも一緒にドレスアップして撮影できるオプション「dress up day」のご案内です。',
            'parent' => 'plans.index',
        ],
        'options.smash-cake' => [
            'path' => '/options/smash-cake',
            'view' => 'options.smash-cake',
            'title' => 'スマッシュケーキ撮影',
            'description' => '1歳のお誕生日に人気のスマッシュケーキ撮影。ケーキ持ち込み料1,500円（税込・支度料及び片付け料込み）。',
            'parent' => 'plans.index',
        ],
        'options.mama-kimono' => [
            'path' => '/options/mama-kimono',
            'view' => 'options.mama-kimono',
            'title' => 'お母様訪問着レンタル・着付け（七五三）',
            'description' => '七五三撮影でのお母様の訪問着レンタル・着付けのご案内です。',
            'parent' => 'plans.shichigosan',
        ],

        'items' => [
            'path' => '/items',
            'view' => 'items',
            'title' => '商品アイテム（フォトアルバム・フレーム）',
            'description' => '撮影写真で作るフォトアルバム（松・竹・梅）とキャンバスフォトフレーム。30ページ30,000円〜、カジュアルなフォトブック10,000円など。',
            'parent' => 'plans.index',
        ],
        'schedule' => [
            'path' => '/schedule',
            'view' => 'schedule',
            'title' => '空き状況カレンダー',
            'description' => 'PHOTO STUDIO MELLOWの撮影枠の空き状況です。ご希望の日程が決まりましたら、予約フォームまたはLINEからご予約ください。',
            'parent' => 'home',
            'priority' => '0.8',
        ],
        'flow' => [
            'path' => '/flow',
            'view' => 'flow',
            'title' => 'ご予約の流れ',
            'description' => 'ご予約から撮影当日、データ納品までの流れ。事前入金でご予約確定、撮影データは最大2週間以内にお渡しします。',
            'parent' => 'home',
            'priority' => '0.8',
        ],
        'access' => [
            'path' => '/access',
            'view' => 'access',
            'title' => 'アクセス',
            'description' => '東京都渋谷区神宮前2-5-6 アマデウスハウス205。東京メトロ銀座線 外苑前駅から徒歩6分。駐車場のご案内と、駅からの行き方を写真付きでご紹介します。',
            'parent' => 'home',
            'priority' => '0.8',
        ],
        'contact' => [
            'path' => '/contact',
            'view' => 'contact',
            'title' => 'ご予約・お問い合わせ',
            'description' => 'PHOTO STUDIO MELLOWへのご予約・お問い合わせは、予約フォーム・LINE・お問い合わせフォームから受け付けています。',
            'parent' => 'home',
            'priority' => '0.9',
        ],
        'reservation' => [
            'path' => '/reservation',
            'view' => 'reservation',
            'title' => 'ご予約フォーム',
            'description' => 'PHOTO STUDIO MELLOWのご予約フォーム。撮影プラン・ご希望日をお選びください。スタジオの空き状況を確認のうえ、ご連絡いたします。',
            'parent' => 'contact',
            'priority' => '0.9',
        ],
        'inquiry' => [
            'path' => '/inquiry',
            'view' => 'inquiry',
            'title' => 'お問い合わせフォーム',
            'description' => '撮影についてのご質問・メディア関係のお問い合わせはこちらのフォームからお送りください。',
            'parent' => 'contact',
        ],
        'line' => [
            'path' => '/line',
            'view' => 'line',
            'title' => 'LINEでのご予約',
            'description' => 'LINEでのご予約方法のご案内。友だち追加後、メッセージ項目をすべてご記載のうえお送りください。',
            'parent' => 'contact',
        ],
        'cancel-policy' => [
            'path' => '/cancel-policy',
            'view' => 'cancel-policy',
            'title' => 'キャンセルポリシー',
            'description' => '日程変更・キャンセル料・当日の遅刻などについてのご案内です。ご予約前に必ずご確認ください。',
            'parent' => 'flow',
        ],
        'recruit' => [
            'path' => '/recruit',
            'view' => 'recruit',
            'title' => '求人募集',
            'description' => 'PHOTO STUDIO MELLOWのスタッフ募集のご案内です。',
            'parent' => 'home',
            'priority' => '0.3',
        ],
    ],

    /*
    | 旧サイト（WordPress・https://ps-mellow.jp/bb/ 以下）のURL => 新しいルート名（301リダイレクト）
    | redirect_prefix: 旧サイトが置かれていたフォルダ。'' は旧サイトのトップ（/bb/）。
    */
    'redirect_prefix' => 'bb',
    'redirects' => [
        '' => 'home',
        'schedule' => 'schedule',
        'access' => 'access',
        'contact' => 'contact',
        'line' => 'line',
        'recruit' => 'recruit',
        'about-me' => 'about',
        'studio-image' => 'studio',
        'newgallery' => 'gallery',
        'gallery-3' => 'gallery.wedding',
        'kimono_gallery' => 'gallery.kimono',
        'mellow-wedding' => 'plans.wedding',
        'flower-styling-photo' => 'plans.flower-styling',
        'anniversaryplan' => 'plans.anniversary',
        '753plan' => 'plans.shichigosan',
        'maternityplan' => 'plans.maternity',
        '10anniversaryplan' => 'plans.half-seijinshiki',
        'dress-up-day' => 'options.dress-up-day',
        'smashcake' => 'options.smash-cake',
        'mamakimono' => 'options.mama-kimono',
        'item2025' => 'items',
        'item' => 'items',
        'guide' => 'flow',
        'otoiawase' => 'inquiry',
        'yoyaku' => 'reservation',
        'cancelpolicy-2' => 'cancel-policy',
    ],

];
