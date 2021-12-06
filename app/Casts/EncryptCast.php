<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;

class EncryptCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if(! is_null($value)) {
            try {
                return Crypt::decryptString($value, true);
            } catch(\Exception $e) {
                return $value;
            }
        }
        return null;
        //return ! is_null($value) ? Crypt::decryptString($value, true) : null;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return [$key => ! is_null($value) ? Crypt::encryptString($value, true) : null];
    }
}
