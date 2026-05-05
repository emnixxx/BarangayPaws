<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ProfilePhotoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoApprovalController extends Controller
{
    /**
     * Approve a pending profile photo request.
     * Moves the pending photo to the permanent profile-photos/ folder,
     * updates the resident's profile_icon, and logs the action.
     */
    public function approve(int $id): RedirectResponse
    {
        $photoRequest = ProfilePhotoRequest::where('status', 'pending')->findOrFail($id);
        $resident     = $photoRequest->resident;

        // Delete existing profile photo file if it was managed by storage
        if ($resident->profile_icon && Storage::disk('public')->exists($resident->profile_icon)) {
            Storage::disk('public')->delete($resident->profile_icon);
        }

        // Move pending photo to permanent location
        $filename = basename($photoRequest->new_photo_path);
        $newPath  = 'profile-photos/' . $filename;

        if (Storage::disk('public')->exists($photoRequest->new_photo_path)) {
            Storage::disk('public')->move($photoRequest->new_photo_path, $newPath);
        } else {
            // File already moved or missing — keep original path
            $newPath = $photoRequest->new_photo_path;
        }

        // Update resident's live profile photo
        $resident->update(['profile_icon' => $newPath]);

        // Mark request as approved
        $photoRequest->update([
            'status'      => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'status'       => 'approved',
            'old_status'   => 'pending',
            'new_status'   => 'approved',
            'action_notes' => "Approved profile photo change request for: {$resident->user_name}.",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return back()->with('success', "Profile photo approved for {$resident->user_name}.");
    }

    /**
     * Reject a pending profile photo request.
     * Deletes the pending photo file and keeps the resident's current photo.
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $photoRequest = ProfilePhotoRequest::where('status', 'pending')->findOrFail($id);
        $resident     = $photoRequest->resident;

        // Delete the pending photo — resident keeps their current photo
        if (Storage::disk('public')->exists($photoRequest->new_photo_path)) {
            Storage::disk('public')->delete($photoRequest->new_photo_path);
        }

        $photoRequest->update([
            'status'           => 'rejected',
            'reviewed_at'      => now(),
            'reviewed_by'      => auth()->id(),
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'status'       => 'rejected',
            'old_status'   => 'pending',
            'new_status'   => 'rejected',
            'action_notes' => "Rejected profile photo change request for: {$resident->user_name}."
                . ($request->rejection_reason ? " Reason: {$request->rejection_reason}" : ''),
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return back()->with('success', "Profile photo request rejected for {$resident->user_name}.");
    }
}
