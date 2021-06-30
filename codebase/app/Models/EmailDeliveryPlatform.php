<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailDeliveryPlatform extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_delivery_platform';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'from_email',
        'from_name'
    ];

    /**
     * Get the basicAuth associated with the emailDeliveryPlatform.
     */
    public function basicAuth()
    {
        return $this->hasOne(BasicAuth::class);
    }

    /**
     * Get the basicAuth associated with the emailDeliveryPlatform.
     */
    public function barerToken()
    {
        return $this->hasOne(BarerToken::class);
    }
}
