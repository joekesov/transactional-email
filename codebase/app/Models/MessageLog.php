<?php

namespace App\Models;

use App\Models\EmailDeliveryPlatform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'message_log';

    /**
     * Get the post that owns the comment.
     */
    public function emailDeliveryPlatform()
    {
        return $this->belongsTo(EmailDeliveryPlatform::class);
    }
}
