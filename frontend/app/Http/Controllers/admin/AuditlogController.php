<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditlogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->get()->map(function($log) {
            $actionLower = strtolower($log->action);
            $badge = 'user';
            
            if (str_contains($actionLower, 'delete') || str_contains($actionLower, 'reject')) {
                $badge = 'rejected';
            } elseif (str_contains($actionLower, 'approve') || str_contains($actionLower, 'confirm')) {
                $badge = 'approved';
            } else {
                $badge = 'system';
            }

            return [
                'timestamp' => \Carbon\Carbon::parse($log->created_at)->format('M d, Y h:i A'),
                'action' => $log->action,
                'target' => $log->target ?? 'N/A',
                'details' => $log->details ?? '',
                'performer' => $log->user ? $log->user->user_name : 'System',
                'role' => $log->user ? ucfirst($log->user->role) : 'N/A',
                'badge' => $badge
            ];
        });

        return view('pages.auditlog', compact('logs'));
    }
}