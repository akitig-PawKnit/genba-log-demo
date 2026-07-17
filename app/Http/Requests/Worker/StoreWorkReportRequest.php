<?php

namespace App\Http\Requests\Worker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->session()->has('worker_id');
    }

    public function rules(): array
    {
        return [
            'site_id' => [
                'required',
                'integer',
                Rule::exists('sites', 'id')->where(
                    fn ($query) => $query->where('is_active', true)
                ),
            ],

            'labor_units' => [
                'required',
                Rule::in(['0.5', '1.0']),
            ],

            'work_shift' => [
                'required',
                Rule::in(['day', 'night']),
            ],

            'work_role' => [
                'required',
                Rule::in(['regular', 'support', 'foreman']),
            ],

            'overtime_hours' => [
                'required',
                'numeric',
                'min:0',
                'max:12',
                'multiple_of:0.5',
            ],

            'highway_cost' => [
                'nullable',
                'integer',
                'min:0',
                'max:1000000',
            ],

            'parking_cost' => [
                'nullable',
                'integer',
                'min:0',
                'max:1000000',
            ],

            'other_cost' => [
                'nullable',
                'integer',
                'min:0',
                'max:1000000',
            ],

            'other_cost_note' => [
                'nullable',
                'string',
                'max:255',
                'required_if:other_cost,1',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'site_id.required' => '現場を選択してください。',
            'site_id.exists' => '選択した現場は現在利用できません。',

            'labor_units.required' => '人工を選択してください。',
            'labor_units.in' => '人工は1人工または0.5人工を選択してください。',

            'work_shift.required' => '勤務区分を選択してください。',
            'work_shift.in' => '勤務区分が正しくありません。',

            'work_role.required' => '役割を選択してください。',
            'work_role.in' => '役割が正しくありません。',

            'overtime_hours.required' => '残業時間を入力してください。',
            'overtime_hours.multiple_of' => '残業時間は0.5時間単位で入力してください。',

            'other_cost_note.required_if' => 'その他経費がある場合は内容を入力してください。',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'highway_cost' => $this->input('highway_cost') ?: 0,
            'parking_cost' => $this->input('parking_cost') ?: 0,
            'other_cost' => $this->input('other_cost') ?: 0,
            'overtime_hours' => $this->input('overtime_hours') ?: 0,
        ]);
    }
}
