<?php

namespace Tests\Feature;

use App\Mail\FormSubmitted;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FormTest extends TestCase
{
    /**
     * @param  array<string, string>  $overrides
     * @return array<string, string>
     */
    private function reservation(array $overrides = []): array
    {
        return [
            'plan' => 'small',
            'style' => 'flower',
            'date_first' => today()->addDays(10)->toDateString(),
            'date_second' => today()->addDays(11)->toDateString(),
            'time_slot' => 'am',
            'child_name' => 'メロウ ハナコ',
            'child_birthday' => '2025-01-01',
            'child_gender' => 'girl',
            'mother_name' => 'メロウ ママ',
            'email' => 'mama@example.com',
            'phone' => '090-1234-5678',
            'payment' => 'bank',
            'items' => 'consult',
            ...$overrides,
        ];
    }

    public function test_reservation_is_sent_by_mail_with_option_labels(): void
    {
        Mail::fake();

        $this->postJson(route('forms.submit', 'reservation'), $this->reservation())
            ->assertOk()
            ->assertJson(['ok' => true]);

        Mail::assertSent(FormSubmitted::class, fn (FormSubmitted $mail) => $mail->hasTo(config('site.mail_to'))
            && $mail->hasReplyTo('mama@example.com')
            && $mail->values['plan']['value'] === config('forms.reservation.fields.plan.options.small')
            && $mail->values['style']['value'] === config('forms.reservation.fields.style.options.flower'));
    }

    public function test_shichigosan_requires_its_own_fields_only(): void
    {
        Mail::fake();

        $this->postJson(route('forms.submit', 'reservation'), $this->reservation(['plan' => 'shichigosan']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['shichigosan_plan', 'flower', 'hair_arrange', 'mother_dressing', 'mother_rental', 'prayer'])
            ->assertJsonMissingValidationErrors(['style']);

        Mail::assertNothingSent();
    }

    public function test_hidden_fields_are_not_sent(): void
    {
        Mail::fake();

        // 洋服プランでは七五三用の項目は受け付けない（送られてきても無視する）
        $this->postJson(route('forms.submit', 'reservation'), $this->reservation(['prayer' => 'yes']))->assertOk();

        Mail::assertSent(FormSubmitted::class, fn (FormSubmitted $mail) => ! isset($mail->values['prayer']));
    }

    public function test_message_is_required_for_other_plan(): void
    {
        $this->postJson(route('forms.submit', 'reservation'), $this->reservation(['plan' => 'other', 'style' => null]))
            ->assertJsonValidationErrors(['message']);
    }

    public function test_unavailable_plan_and_past_dates_are_rejected(): void
    {
        $this->postJson(route('forms.submit', 'reservation'), $this->reservation([
            'plan' => 'outdoor',
            'date_first' => today()->subDay()->toDateString(),
        ]))->assertJsonValidationErrors(['plan', 'date_first']);
    }

    public function test_inquiry_is_sent_by_mail(): void
    {
        Mail::fake();

        $this->postJson(route('forms.submit', 'inquiry'), [
            'name' => 'テスト 太郎',
            'email' => 'taro@example.com',
            'topic' => 'shooting',
            'message' => 'テストです',
        ])->assertOk();

        Mail::assertSent(FormSubmitted::class, fn (FormSubmitted $mail) => $mail->values['topic']['value'] === '撮影について'
            && $mail->values['message']['value'] === 'テストです');
    }

    public function test_inquiry_validates_required_fields(): void
    {
        $this->postJson(route('forms.submit', 'inquiry'), [])
            ->assertJsonValidationErrors(['name', 'email', 'topic'])
            ->assertJsonMissingValidationErrors(['phone', 'message']);
    }

    public function test_honeypot_submission_is_silently_ignored(): void
    {
        Mail::fake();

        $this->postJson(route('forms.submit', 'inquiry'), ['website' => 'spam'])->assertOk();

        Mail::assertNothingSent();
    }
}
