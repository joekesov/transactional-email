<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicAuth extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'basic_auth';

    /**
     * Get the emailDeliveryPlatform that owns the basicAuth.
     */
    public function emailDeliveryPlatform()
    {
        return $this->belongsTo(EmailDeliveryPlatform::class, 'email_delivery_platform_id');
    }
}
