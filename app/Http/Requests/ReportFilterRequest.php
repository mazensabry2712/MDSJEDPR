<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'filter.pr_number' => 'nullable|string|max:255',
            'filter.name' => 'nullable|string|max:255',
            'filter.technologies' => 'nullable|string|max:255',
            'filter.customer_po' => 'nullable|string|max:255',
            'filter.project_manager' => 'nullable|string|max:255',
            'filter.customer_name' => 'nullable|string|max:255',
            'filter.vendors' => 'nullable|string|max:255',
            'filter.suppliers' => 'nullable|string|max:255',
            'filter.am' => 'nullable|string|max:255',
            'filter.value_min' => 'nullable|numeric|min:0',
            'filter.value_max' => 'nullable|numeric|min:0|gte:filter.value_min',
            'filter.deadline_from' => 'nullable|date',
            'filter.deadline_to' => 'nullable|date|after_or_equal:filter.deadline_from',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'filter.pr_number.string' => 'PR Number must be a valid string',
            'filter.pr_number.max' => 'PR Number cannot exceed 255 characters',
            'filter.name.string' => 'Project Name must be a valid string',
            'filter.name.max' => 'Project Name cannot exceed 255 characters',
            'filter.value_min.numeric' => 'Minimum value must be a number',
            'filter.value_min.min' => 'Minimum value cannot be negative',
            'filter.value_max.numeric' => 'Maximum value must be a number',
            'filter.value_max.min' => 'Maximum value cannot be negative',
            'filter.value_max.gte' => 'Maximum value must be greater than or equal to minimum value',
            'filter.deadline_from.date' => 'Deadline From must be a valid date',
            'filter.deadline_to.date' => 'Deadline To must be a valid date',
            'filter.deadline_to.after_or_equal' => 'Deadline To must be after or equal to Deadline From',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'filter.pr_number' => 'PR Number',
            'filter.name' => 'Project Name',
            'filter.technologies' => 'Technologies',
            'filter.customer_po' => 'Customer PO',
            'filter.project_manager' => 'Project Manager',
            'filter.customer_name' => 'Customer Name',
            'filter.vendors' => 'Vendors',
            'filter.suppliers' => 'Suppliers',
            'filter.am' => 'Account Manager',
            'filter.value_min' => 'Minimum Value',
            'filter.value_max' => 'Maximum Value',
            'filter.deadline_from' => 'Deadline From',
            'filter.deadline_to' => 'Deadline To',
        ];
    }

    /**
     * Check if any filter is active
     */
    public function hasActiveFilters(): bool
    {
        $filters = $this->input('filter', []);
        return count(array_filter($filters, fn($value) => !is_null($value) && $value !== '')) > 0;
    }

    /**
     * Get active filters count
     */
    public function getActiveFiltersCount(): int
    {
        $filters = $this->input('filter', []);
        return count(array_filter($filters, fn($value) => !is_null($value) && $value !== ''));
    }

    /**
     * Get active filters as array
     */
    public function getActiveFilters(): array
    {
        $filters = $this->input('filter', []);
        return array_filter($filters, fn($value) => !is_null($value) && $value !== '');
    }
}
