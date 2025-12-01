<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogService
{
    /**
     * Log a user activity.
     */
    public function log(string $action, string $description, int $userId, Request $request): ActivityLog
    {
       return ActivityLog::create([
            'user_id' => $userId,
            'action' => DB::raw("'$action'"),
            'description' => $description,
            'ip_address' => $request->ip(),
        ]);
    }
}