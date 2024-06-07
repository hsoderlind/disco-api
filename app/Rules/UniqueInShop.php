<?php

namespace App\Rules;

use App\Services\Shop\ShopSession;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueInShop implements DataAwareRule, ValidationRule
{
    protected $column = 'shop_id';

    protected int $shopId;

    protected bool $ignore = false;

    protected ?string $except = null;

    protected array $data = [];

    public function __construct(
        protected readonly string $table,
        ?string $column = null,
        ?int $shopId = null,
        ?string $except = null
    ) {
        $this->column = $column ?? $this->column;
        $this->shopId = $shopId ?? ShopSession::getId();
        $this->except = $except;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = DB::table($this->table)->where($attribute, $value)->where($this->column, $this->shopId);

        if (! is_null($this->except) && array_key_exists($this->except, $this->data)) {
            $query->where($this->except, '!=', $this->data[$this->except]);
        }

        $exists = $query->exists();

        if (! $this->ignore && $exists) {
            $fail('validation.unique')->translate();
        }
    }

    public function ignore(bool $condition)
    {
        $this->ignore = $condition;

        return $this;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }
}
