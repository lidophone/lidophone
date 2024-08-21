<?php

namespace App\Rules;

use App\Models\Offer;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class OnePriorityOfferForHousingEstate implements ValidationRule, DataAwareRule
{
    private array $data = [];
    private Offer $model;

    public function __construct(Offer $model)
    {
        $this->model = $model;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (
            1 === (int) $this->data['priority']
            && Offer::whereNot('id', $this->model->id)->where([
                'housing_estate_id' => (int) $this->data['housing_estate_id'],
                'priority' => 1,
            ])->exists()
        ) {
            $fail(__('Only one offer can be priority for one housing estate.'));
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }
}
