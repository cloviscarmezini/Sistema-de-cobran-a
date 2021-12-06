<?php

namespace App\Models;

use App\Casts\PriceCast;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountInstallment extends Model
{
    use HasFactory, Uuid;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected $fillable = [
        'account_id', 'value', 'installment', 'status'
    ];

    protected $casts = [
        'value' => PriceCast::class,
    ];
}
