<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BDMNotification extends Model
{
    protected $table = 'bdm_notifications';
    
    protected $fillable = [
        'bdm_id',
        'type',
        'title',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function bdm(): BelongsTo
    {
        return $this->belongsTo(BDM::class, 'bdm_id');
    }

    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }
}
