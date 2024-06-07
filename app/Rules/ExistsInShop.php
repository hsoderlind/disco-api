<?php

namespace App\Rules;

use App\Services\Shop\ShopSession;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ExistsInShop implements ValidationRule
{
    public function __construct(
        protected readonly string $table,
        protected readonly ?string $column = null,
        protected ?int $shopId = null,
        protected ?string $shopIdColumn = null
    ) {
        $this->shopId ??= ShopSession::getId();
        $this->shopIdColumn ??= 'shop_id';
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $column = $this->column ?? $attribute;
        $exists = DB::table($this->table)->where($column, $value)->where($this->shopIdColumn, $this->shopId)->exists();

        if (! $exists) {
            $fail('validation.exists')->translate();
        }
    }
}
