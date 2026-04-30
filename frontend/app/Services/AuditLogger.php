<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log($action, $target = null, $details = null)
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'target' => $target,
            'details' => $details,
        ]);
    }
}
