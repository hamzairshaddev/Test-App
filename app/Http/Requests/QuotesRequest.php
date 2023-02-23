<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class QuotesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'symbol' => 'required|exists:companies,symbol',
            'start_date' => 'required|date|before_or_equal:end_date|before:'.Carbon::now()->addDays(1)->format('Y-m-d'),
            'end_date' => 'required|date|after_or_equal:start_date|before:'.Carbon::now()->addDays(1)->format('Y-m-d'),
            'email' => 'required|email',
        ]; 
    }
}
