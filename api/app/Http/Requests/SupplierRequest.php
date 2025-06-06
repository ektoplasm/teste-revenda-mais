<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $brazilianStates = [
            'AC',
            'AL',
            'AP',
            'AM',
            'BA',
            'CE',
            'DF',
            'ES',
            'GO',
            'MA',
            'MT',
            'MS',
            'MG',
            'PA',
            'PB',
            'PR',
            'PE',
            'PI',
            'RJ',
            'RN',
            'RS',
            'RO',
            'RR',
            'SC',
            'SP',
            'SE',
            'TO'
        ];

        $rules = [
            'cpf_cnpj' => [
                'required',
                'cpf_cnpj',
                Rule::unique('suppliers', 'cpf_cnpj'),
            ],
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:100',
            'address' => 'required|min:3|max:255',
            'number' => 'required|min:1|max:5',
            'city' => 'required|min:3|max:100',
            'state' => 'required|regex:/^[A-Za-z]{2}$/|in:' . implode(',', $brazilianStates),
            'address_info' => 'sometimes|min:3|max:255',
            'primary_contact' => 'required|min:3|max:255',
            'primary_contact_email' => 'required|email|max:100',
        ];

        if (!empty($this->route()->parameter('supplier'))) {
            // Se o fornecedor já existe, ignora a validação de CPF/CNPJ para o mesmo registro
            $rules['cpf_cnpj'] = [
                'required',
                'cpf_cnpj',
                Rule::unique('suppliers', 'cpf_cnpj')->ignore($this->route()->parameter('supplier'), 'id'),
            ];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cpf_cnpj.required' => 'The CPF/CNPJ field is required.',
            'cpf_cnpj.cpf_cnpj' => 'The CPF/CNPJ field is invalid.',
            'cpf_cnpj.unique' => 'This CPF/CNPJ already exists.',
            'state.in' => 'The state field must be a valid Brazilian state.',
        ];
    }
}
