{{ $title }}から以下の内容で送信がありました。

@foreach ($values as $item)
■{{ $item['label'] }}
{{ $item['value'] !== '' ? $item['value'] : '（未入力）' }}

@endforeach
--
送信日時: {{ now()->format('Y年n月j日 H:i') }}
{{ config('app.name') }} ({{ url('/') }})
