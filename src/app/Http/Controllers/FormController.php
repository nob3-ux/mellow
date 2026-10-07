<?php

namespace App\Http\Controllers;

use App\Mail\FormSubmitted;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class FormController extends Controller
{
    /**
     * 予約・お問い合わせフォームの送信を受け付けて、通知メールを送る。
     */
    public function submit(Request $request, string $form): JsonResponse
    {
        $definition = config("forms.{$form}");

        // 人には見えない項目に入力がある場合はボットとみなし、送信したふりをする
        if ($request->filled('website')) {
            return response()->json(['ok' => true]);
        }

        $fields = collect($definition['fields'])
            ->filter(fn (array $field) => $this->matches($field['when'] ?? null, $request));

        $validated = $request->validate(
            $fields->map(fn (array $field) => $this->rules($field, $request))->all(),
            [
                'required' => ':attributeを入力してください。',
                'email' => 'メールアドレスの形式が正しくありません。',
                'regex' => '電話番号は数字（ハイフン可）で入力してください。',
                'date' => '日付を選択してください。',
                'after_or_equal' => ':attributeは本日以降の日付を選択してください。',
                'in' => ':attributeを選択肢から選んでください。',
                'max' => ':attributeは:max文字以内で入力してください。',
            ],
            $fields->map(fn (array $field) => $field['label'])->all(),
        );

        $values = $fields->map(fn (array $field, string $name) => [
            'label' => $field['label'],
            'value' => $this->display($field, $validated[$name] ?? null),
        ])->all();

        Mail::to(config('site.mail_to'))->send(new FormSubmitted(
            title: $definition['title'],
            values: $values,
            replyToAddress: $validated['email'] ?? null,
        ));

        return response()->json(['ok' => true]);
    }

    /**
     * @param  array{0: string, 1: array<int, string>}|null  $condition
     */
    private function matches(?array $condition, Request $request): bool
    {
        return $condition === null || in_array($request->input($condition[0]), $condition[1], true);
    }

    /**
     * @param  array<string, mixed>  $field
     * @return array<int, mixed>
     */
    private function rules(array $field, Request $request): array
    {
        $required = is_array($field['required']) ? $this->matches($field['required'], $request) : $field['required'];

        return [
            $required ? 'required' : 'nullable',
            'string',
            ...match ($field['type']) {
                'email' => ['email', 'max:255'],
                'tel' => ['regex:/^[0-9０-９+\-ー－() ]{10,20}$/u'],
                'date' => ['date', ...(($field['future'] ?? false) ? ['after_or_equal:today'] : [])],
                'select' => [Rule::in(array_diff(array_keys($field['options']), $field['disabled'] ?? []))],
                'textarea' => ['max:2000'],
                default => ['max:255'],
            },
        ];
    }

    /**
     * @param  array<string, mixed>  $field
     */
    private function display(array $field, ?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return $field['type'] === 'select' ? $field['options'][$value] : $value;
    }
}
