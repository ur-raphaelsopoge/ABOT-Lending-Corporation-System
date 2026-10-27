<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLedger extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'admin_id',
        'action',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public static function record(?int $userId, string $action, string $description, ?int $adminId = null): self
    {
        return static::create([
            'user_id' => $userId,
            'admin_id' => $adminId,
            'action' => $action,
            'description' => $description,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
