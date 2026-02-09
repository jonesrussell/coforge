<?php

namespace App\Http\Requests;

use App\Models\Skill;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePitchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:65535'],
            'pitch_type' => ['required', Rule::in(['project', 'seeking'])],
            'role_sought' => ['nullable', 'string', 'max:255'],
            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['integer', Rule::exists(Skill::class, 'id')],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
