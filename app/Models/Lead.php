<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'date',
        'time',
        'platform',
        'platform_custom',
        'customer_name',
        'phone_number',
        'email',
        'project_type',
        'project_valuation',
        'remarks',
        'status',
        'callback_time',
        'meeting_address',
        'meeting_time',
        'meeting_person_name',
        'meeting_phone_number',
        'meeting_summary',
        'meeting_completed',
        'meeting_completed_summary',
        'callback_completed',
        'call_notes',
        'assigned_to',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'callback_time' => 'datetime',
        'meeting_time' => 'datetime',
        'meeting_completed' => 'boolean',
        'callback_completed' => 'boolean',
        'project_valuation' => 'decimal:2',
    ];

    /**
     * Get the user assigned to this lead
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get leads for dashboard upcoming work
     */
    public static function upcomingWork()
    {
        return self::where('status', 'callback_scheduled')
            ->where('callback_time', '>', now())
            ->where('callback_completed', false)
            ->orderBy('callback_time')
            ->get();
    }

    /**
     * Get today's meetings
     */
    public static function todaysMeetings()
    {
        return self::where('status', 'meeting_scheduled')
            ->whereDate('meeting_time', today())
            ->orderBy('meeting_time')
            ->get();
    }
}