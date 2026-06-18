<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    protected $fillable = ['user_id', 'message', 'type', 'lu'];

    protected function casts(): array
    {
        return ['lu' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function envoyer(int $userId, string $message, ?string $type = null): void
    {
        self::create([
            'user_id' => $userId,
            'message' => $message,
            'type' => $type,
        ]);
    }
}
