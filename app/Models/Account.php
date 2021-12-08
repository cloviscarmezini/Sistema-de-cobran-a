<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory, Uuid;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected $fillable = [
        'client_id', 'user_id', 'description', 'type', 'value', 'readjustment_type', 'installments', 'discount', 'expiration_date'
    ];

    protected $dates = ['expiration_date'];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tradeInstallments() {
        return $this->hasMany(AccountInstallment::class);
    }
}
