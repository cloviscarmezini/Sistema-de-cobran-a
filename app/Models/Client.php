<?php

namespace App\Models;

use App\Casts\EncryptCast;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Client as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuid;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'document', 'zip_code', 'address', 'number', 'district', 'state', 'country'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'document' => EncryptCast::class,
    ];

    public function getAuthPassword()
    {
      return $this->password;
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, 'client_id');
    }
}
