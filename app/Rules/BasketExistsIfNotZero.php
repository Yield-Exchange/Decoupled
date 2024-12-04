<?php

namespace App\Rules;

use App\Models\TradeCollateralBasket;
use App\Models\TradeTriBasketThirdParty;
use Illuminate\Contracts\Validation\Rule;

class BasketExistsIfNotZero implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Constructor, if you need to pass any parameters to the rule
    }

    public function passes($attribute, $value)
    {
        // Only validate existence if the value is not zero
        if ($value == 0) {
            return true;
        }

        // Replace this with your logic to check if the basket exists
        // For example, assuming you have a Basket model:
        return TradeTriBasketThirdParty::where('id', $value)->exists();
    }

    public function message()
    {
        return 'The selected basket does not exist or is invalid.';
    }
}
