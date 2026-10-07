<x-layouts.app :seo="$seo">

    <x-page-hero title="ご予約の流れ" en="Flow" lead="ご予約は4ステップです。" />

    {{-- 4ステップの概要 --}}
    <x-section width="narrow" class="!pt-4 text-center">
        <ol class="grid grid-cols-2 gap-3 text-sm tracking-[0.1em] text-brand-800 sm:grid-cols-4">
            @foreach (['ご予約', '事前入金', 'ご予約確定', '撮影当日'] as $index => $label)
                <li class="rounded-sm bg-white px-3 py-4">
                    <span class="block font-display text-brand-500 italic">{{ $index + 1 }}）</span>
                    {{ $label }}
                </li>
            @endforeach
        </ol>
        <div class="mt-10">
            <x-button :href="route('schedule')" variant="outline" arrow>ご予約空き状況</x-button>
        </div>
    </x-section>

    {{-- 各ステップの詳細 --}}
    <x-section tone="white" width="narrow">
        <x-heading en="Step">ご予約から撮影当日まで</x-heading>

        <x-steps>
            <x-step title="ご予約">
                <p><strong><a href="{{ route('line') }}">LINEアカウント</a></strong><br>
                    <strong><a href="{{ route('reservation') }}">予約フォーム</a></strong><br>
                    <strong>よりご予約をお願いいたします。</strong></p>
                <p><strong>後日、ご予約の内容とご入金方法等をご案内させていただきます。</strong></p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <x-button :href="route('reservation')" arrow>予約フォーム</x-button>
                    <x-button :href="route('line')" variant="line">LINEアカウント</x-button>
                </div>
            </x-step>

            <x-step title="事前入金">
                <p><strong>銀行振込またはクレジットカード決済のどちらかをお選びください。</strong></p>

                <div class="mt-6 rounded-sm border border-brand-200 bg-brand-50 p-5 sm:p-6">
                    <h4 class="tracking-[0.1em] text-brand-800">● お振込み口座 ●</h4>
                    <dl class="mt-3 grid grid-cols-[auto_1fr] gap-x-6 gap-y-1">
                        <dt>銀行</dt><dd><strong>三井住友銀行　恵比寿支店</strong></dd>
                        <dt>口座</dt><dd><strong>普通　9086831</strong></dd>
                        <dt>名義</dt><dd><strong>カワダ マリコ</strong></dd>
                    </dl>
                    <ul class="mt-4 list-disc space-y-1 pl-5">
                        <li>お振込み手数料はお客様負担となります。</li>
                        <li>お振込み名義人は、「撮影日＋お子様のフルネーム」をご記載ください。</li>
                        <li>ご入金期日は仮予約 (ご予約日) ～1週間以内</li>
                    </ul>
                </div>

                <x-notice title="ご入金期日について" tone="alert" class="mt-4">
                    <p><strong>※期日を過ぎても、ご入金確認が取れない場合は、自動キャンセルとなります。お気を付けくださいませ。</strong></p>
                </x-notice>

                <div class="mt-6 rounded-sm border border-brand-200 bg-brand-50 p-5 sm:p-6">
                    <h4 class="tracking-[0.1em] text-brand-800">● クレジットカード決済 ●</h4>
                    <p class="mt-3"><strong>※メールにて決済フォームをお送りいたします。</strong><br>
                        そちらから決済お手続きをお願いします。</p>
                    <p class="mt-3">対応会社<br>VISA / Master / AMEX / discover / JCB / Diners</p>
                </div>
            </x-step>

            <x-step title="ご予約確定">
                <p><strong>ご入金確認後、ご予約確定となります。</strong></p>
                <ul class="list-disc space-y-1 pl-5">
                    <li>確認後、LINEにてご予約確定メールをお送りさせていただきます。</li>
                    <li>最終のご予約確認は、撮影日の前日にご連絡をさせて頂きます。</li>
                </ul>
                <p>ご質問などは随時お受付しております。</p>
            </x-step>

            <x-step title="撮影日当日">
                <p><strong>ご予約時間の5分～10分前に<br>スタジオへお越しくださいませ。</strong></p>
                <p>生花STYLINGを<br>心ゆくまでお楽しみください。</p>
                <p>特別な１日となりますように・・・</p>
            </x-step>
        </x-steps>
    </x-section>

    {{-- 関連ページ --}}
    <x-section width="narrow" class="text-center">
        <p class="text-sm leading-loose">日程変更・キャンセルや、スタジオまでの道順もご確認ください。</p>
        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('cancel-policy')" variant="outline" arrow>キャンセルポリシー</x-button>
            <x-button :href="route('access')" variant="outline" arrow>アクセス</x-button>
        </div>
    </x-section>

</x-layouts.app>
