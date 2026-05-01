<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Display audit logs.
     */
    public function index(Request $request): View
    {
        $query = \App\Models\AuditLog::with(['user', 'pet.owner', 'report.pet', 'record.pet'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('action_notes', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logs = $query->paginate(12);

        return view('pages.auditlog', compact('logs'));
    }
}