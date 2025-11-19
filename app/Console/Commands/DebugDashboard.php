<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;

class DebugDashboard extends Command
{
    protected $signature = 'debug:dashboard {user_id?}';
    protected $description = 'Debug dashboard data for a specific user';

    public function handle()
    {
        $userId = $this->argument('user_id') ?: 1; // Default to user 1
        
        $this->info("=== DASHBOARD DEBUG FOR USER ID: {$userId} ===");
        
        // Check if user exists
        $user = User::find($userId);
        if (!$user) {
            $this->error("User ID {$userId} not found!");
            return;
        }
        
        $this->info("User: {$user->name} ({$user->email})");
        $this->line("");
        
        // Check all leads assigned to this user
        $assignedLeads = Lead::where('assigned_to', $userId)->get();
        $this->info("Total leads assigned to user: " . $assignedLeads->count());
        
        if ($assignedLeads->count() > 0) {
            $this->table(
                ['ID', 'Customer', 'Status', 'Callback Time', 'Meeting Time'],
                $assignedLeads->map(function($lead) {
                    return [
                        $lead->id,
                        $lead->customer_name,
                        $lead->status,
                        $lead->callback_time ? $lead->callback_time->format('Y-m-d H:i:s') : 'NULL',
                        $lead->meeting_time ? $lead->meeting_time->format('Y-m-d H:i:s') : 'NULL',
                    ];
                })->toArray()
            );
        }
        
        $this->line("");
        
        // Check upcoming callbacks
        $upcomingCallbacks = Lead::where('assigned_to', $userId)
            ->where(function ($query) {
                $query->where('status', 'callback_scheduled')
                      ->orWhere(function ($q) {
                          $q->whereNotNull('callback_time')
                            ->where('callback_time', '>=', Carbon::now());
                      });
            })
            ->whereNotNull('callback_time')
            ->where('callback_time', '>=', Carbon::now())
            ->get();
            
        $this->info("Upcoming Callbacks: " . $upcomingCallbacks->count());
        
        // Check upcoming meetings
        $upcomingMeetings = Lead::where('assigned_to', $userId)
            ->where(function ($query) {
                $query->where('status', 'meeting_scheduled')
                      ->orWhere(function ($q) {
                          $q->whereNotNull('meeting_time')
                            ->where('meeting_time', '>=', Carbon::now());
                      });
            })
            ->whereNotNull('meeting_time')
            ->where('meeting_time', '>=', Carbon::now())
            ->get();
            
        $this->info("Upcoming Meetings: " . $upcomingMeetings->count());
        
        // Check did not receive
        $didNotReceive = Lead::where('assigned_to', $userId)
            ->where('status', 'did_not_receive')
            ->get();
            
        $this->info("Did Not Receive: " . $didNotReceive->count());
        
        $this->line("");
        $this->info("Current time: " . Carbon::now()->format('Y-m-d H:i:s'));
        
        // Show all leads with times regardless of user
        $this->line("");
        $this->info("=== ALL LEADS WITH TIMES (ANY USER) ===");
        
        $allLeadsWithTimes = Lead::where(function($query) {
            $query->whereNotNull('callback_time')
                  ->orWhereNotNull('meeting_time');
        })->get(['id', 'customer_name', 'status', 'assigned_to', 'callback_time', 'meeting_time']);
        
        if ($allLeadsWithTimes->count() > 0) {
            $this->table(
                ['ID', 'Customer', 'Status', 'Assigned To', 'Callback Time', 'Meeting Time'],
                $allLeadsWithTimes->map(function($lead) {
                    return [
                        $lead->id,
                        $lead->customer_name,
                        $lead->status,
                        $lead->assigned_to,
                        $lead->callback_time ? $lead->callback_time->format('Y-m-d H:i:s') : 'NULL',
                        $lead->meeting_time ? $lead->meeting_time->format('Y-m-d H:i:s') : 'NULL',
                    ];
                })->toArray()
            );
        } else {
            $this->warn("No leads found with callback_time or meeting_time set!");
        }
    }
}