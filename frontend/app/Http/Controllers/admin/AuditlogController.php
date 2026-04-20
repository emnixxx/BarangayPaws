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
        // TODO: Fetch audit logs with filters
        // $query = AuditLog::with('admin')->orderBy('audit_date', 'desc');
        //
        // if ($request->filled('search')) {
        //     $query->where('pet_name', 'like', '%' . $request->search . '%');
        // }
        //
        // if ($request->filled('status')) {
        //     $query->where('status', $request->status);
        // }
        //
        // $logs = $query->paginate(12);

        return view('pages.auditlog');
    }
}