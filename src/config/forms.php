<?php

/*
|--------------------------------------------------------------------------
| フォーム定義
|--------------------------------------------------------------------------
|
| サーバー側の検証（FormController）と、画面側のReactフォームの両方で使う。
|
| fields: 項目名 => [
|   label:       表示名（通知メールにも使用）
|   type:        text / email / tel / date / select / textarea
|   required:    true、または ['項目名', [値...]]（その項目が指定の値のときだけ必須）
|   when:        ['項目名', [値...]]  指定の値のときだけ表示・受付する
|   options:     select の選択肢（値 => 表示名）。表示名に「無効」を付けたい場合は disabled を使う
|   disabled:    選択できない選択肢の値
|   future:      date で本日以降のみ受け付ける
|   placeholder / help / step（予約フォームのステップ番号）
| ]
|
*/

$shichigosan = ['plan', ['shichigosan']];

return [

    'reservation' => [
        'title' => 'ご予約フォーム',
        'steps' => ['プラン・日程', 'お子様の情報', 'ご連絡先', '内容の確認'],
        'fields' => [
            'plan' => [
                'label' => 'ご希望の撮影プラン',
                'type' => 'select',
                'required' => true,
                'step' => 1,
                'options' => [
                    'small' => '洋服20カット SMALLプラン（2026年1月〜 1歳・2歳限定）',
                    'standard' => '洋服50カット STANDARDプラン（2026年1月〜 1歳・2歳限定）',
                    'shichigosan' => '七五三プラン',
                    'outdoor' => '出張撮影プラン（2026年1月〜ご予約不可）',
                    'other' => 'その他（備考欄にご希望の内容をご記入ください）',
                ],
                'disabled' => ['outdoor'],
            ],
            'style' => [
                'label' => '撮影スタイル',
                'type' => 'select',
                'required' => ['plan', ['small', 'standard']],
                'when' => ['plan', ['small', 'standard']],
                'step' => 1,
                'options' => [
                    'flower' => '生花styling（生花ご希望の方）',
                    'anniversary' => 'Anniversary（生花ご希望しない方）',
                    'flower_plus' => '生花stylingプラス（生花の増量をご希望の方 ＋5,500円）',
                ],
            ],
            'shichigosan_plan' => [
                'label' => '七五三のプラン',
                'type' => 'select',
                'required' => $shichigosan,
                'when' => $shichigosan,
                'step' => 1,
                'options' => [
                    'studio_70' => 'スタジオ着物洋服70カットプラン',
                    'studio_50' => 'スタジオ着物50カットプラン',
                    'hie_location' => '日枝神社ロケのみプラン',
                    'studio_hie_100' => 'スタジオ＋日枝神社ロケ100カットプラン',
                ],
            ],
            'flower' => [
                'label' => '生花stylingの有無',
                'help' => '無しの場合、背景の生花もございません。',
                'type' => 'select',
                'required' => $shichigosan,
                'when' => $shichigosan,
                'step' => 1,
                'options' => [
                    'yes' => '生花あり（＋3,300円）',
                    'plus' => '生花stylingプラス（生花の増量をご希望の方 ＋6,000円）',
                    'no' => '生花なし',
                ],
            ],
            'hair_arrange' => [
                'label' => '新日本髪アレンジの有無（肩下10cm以上）',
                'type' => 'select',
                'required' => $shichigosan,
                'when' => $shichigosan,
                'step' => 1,
                'options' => ['yes' => 'あり（＋3,300円）', 'no' => 'なし'],
            ],
            'mother_dressing' => [
                'label' => 'お母様の着物着用（着付け）の有無',
                'type' => 'select',
                'required' => $shichigosan,
                'when' => $shichigosan,
                'step' => 1,
                'options' => ['yes' => '着付け希望（＋9,900円）', 'no' => '着物着用なし'],
            ],
            'mother_rental' => [
                'label' => 'お母様の着物レンタルの有無',
                'type' => 'select',
                'required' => $shichigosan,
                'when' => $shichigosan,
                'step' => 1,
                'options' => ['yes' => '着物レンタル希望（＋5,500円）', 'no' => '利用なし'],
            ],
            'prayer' => [
                'label' => '御祈祷待機の有無',
                'help' => '平日のみ可',
                'type' => 'select',
                'required' => $shichigosan,
                'when' => $shichigosan,
                'step' => 1,
                'options' => ['yes' => '御祈祷希望（＋3,300円）', 'no' => '御祈祷利用なし'],
            ],
            'date_first' => [
                'label' => '第一希望撮影日',
                'type' => 'date',
                'required' => true,
                'future' => true,
                'step' => 1,
            ],
            'date_second' => [
                'label' => '第二希望撮影日',
                'type' => 'date',
                'required' => true,
                'future' => true,
                'step' => 1,
            ],
            'time_slot' => [
                'label' => 'ご希望の時間帯',
                'type' => 'select',
                'required' => true,
                'step' => 1,
                'options' => ['am' => '午前', 'pm' => '午後', 'any' => 'どちらでも'],
            ],

            'child_name' => [
                'label' => 'メインで撮影をされるお子様のフルネーム（フリガナ）',
                'type' => 'text',
                'required' => true,
                'step' => 2,
                'placeholder' => 'メロウ ハナコ',
            ],
            'child_birthday' => [
                'label' => 'お子様の生年月日',
                'type' => 'date',
                'required' => true,
                'step' => 2,
            ],
            'child_gender' => [
                'label' => 'お子様の性別',
                'type' => 'select',
                'required' => true,
                'step' => 2,
                'options' => ['boy' => '男の子', 'girl' => '女の子'],
            ],
            'mother_name' => [
                'label' => 'お母さまのお名前',
                'type' => 'text',
                'required' => true,
                'step' => 2,
            ],

            'email' => [
                'label' => 'メールアドレス',
                'help' => '撮影データをお送りするアドレスが異なる場合は、データ送信先をご記入ください。',
                'type' => 'email',
                'required' => true,
                'step' => 3,
                'placeholder' => 'example@example.jp',
            ],
            'phone' => [
                'label' => 'ご連絡先の携帯電話番号',
                'type' => 'tel',
                'required' => true,
                'step' => 3,
                'placeholder' => '09012345678',
            ],
            'address' => [
                'label' => 'ご住所（任意）',
                'help' => '出張撮影・商品配送の際に使用します。',
                'type' => 'text',
                'required' => false,
                'step' => 3,
                'placeholder' => '〒000-0000 東京都',
            ],
            'payment' => [
                'label' => 'お支払い方法',
                'help' => '当スタジオは事前入金制です。',
                'type' => 'select',
                'required' => true,
                'step' => 3,
                'options' => ['bank' => '銀行振込', 'credit' => 'クレジット決済'],
            ],
            'items' => [
                'label' => '商品（アルバム等）のご購入',
                'help' => '商品は撮影当日にご紹介いたします。',
                'type' => 'select',
                'required' => true,
                'step' => 3,
                'options' => ['consult' => '当日相談希望', 'none' => '購入希望なし'],
            ],
            'instagram' => [
                'label' => 'Instagramアカウント（任意）',
                'type' => 'text',
                'required' => false,
                'step' => 3,
                'placeholder' => '@',
            ],
            'message' => [
                'label' => 'ご質問・ご要望',
                'help' => 'ご兄弟姉妹がいる場合は、お名前・生年月日をご記入ください。お母様の着付け・着物レンタルをご利用の方は、身長／洋服サイズ／足のサイズをご記入ください。',
                'type' => 'textarea',
                'required' => ['plan', ['other']],
                'step' => 3,
            ],
        ],
    ],

    'inquiry' => [
        'title' => 'お問い合わせ',
        'fields' => [
            'name' => [
                'label' => 'お名前',
                'type' => 'text',
                'required' => true,
            ],
            'phone' => [
                'label' => '電話番号（任意）',
                'type' => 'tel',
                'required' => false,
                'placeholder' => '09012345678',
            ],
            'email' => [
                'label' => 'メールアドレス',
                'type' => 'email',
                'required' => true,
                'placeholder' => 'example@example.jp',
            ],
            'topic' => [
                'label' => 'お問い合わせ内容',
                'type' => 'select',
                'required' => true,
                'options' => ['shooting' => '撮影について', 'media' => 'マスコミ関係について', 'other' => 'その他'],
            ],
            'message' => [
                'label' => 'メッセージ本文（任意）',
                'type' => 'textarea',
                'required' => false,
            ],
        ],
    ],

];
