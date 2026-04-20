{{-- ─── Reusable Action Modals ─── --}}

{{-- Confirmation Modal (for Approve/Confirm actions) --}}
<div class="modal-overlay" id="action-confirm-modal">
    <div class="modal-container" style="max-width: 420px;">
        <div class="modal-header">
            <h3 class="modal-title" id="confirm-modal-title">Confirm Action</h3>
            <button class="modal-close" data-close-confirm>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <p id="confirm-modal-message" style="font-size:14px; color:#374151; margin-bottom:20px; line-height:1.5;">
            Are you sure you want to proceed?
        </p>

        <div class="modal-footer" style="display:flex; gap:10px; justify-content:flex-end;">
            <button type="button" class="btn-outline" data-close-confirm>Cancel</button>
            <form id="confirm-action-form" method="POST" action="" style="display:inline;">
                @csrf
                <button type="submit" class="btn-submit" style="background:#065f46; color:white;">
                    Yes, Confirm
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Rejection Modal (for Reject actions with reason) --}}
<div class="modal-overlay" id="action-reject-modal">
    <div class="modal-container" style="max-width: 480px;">
        <div class="modal-header">
            <h3 class="modal-title" id="reject-modal-title">Reject</h3>
            <button class="modal-close" data-close-reject>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <p id="reject-modal-message" style="font-size:14px; color:#374151; margin-bottom:14px; line-height:1.5;">
            Please provide a reason for rejection.
        </p>

        <form id="reject-action-form" method="POST" action="">
            @csrf
            <div class="form-group">
                <label class="form-label" for="rejection-reason">Rejection Reason <span style="color:#ef4444;">*</span></label>
                <textarea
                    name="rejection_reason"
                    id="rejection-reason"
                    class="form-input form-textarea"
                    rows="4"
                    required
                    placeholder="e.g., Incomplete documentation, invalid information..."
                    style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:8px; font-family:inherit; font-size:14px; resize:vertical;"
                ></textarea>
            </div>

            <div class="modal-footer" style="display:flex; gap:10px; justify-content:flex-end; margin-top:16px;">
                <button type="button" class="btn-outline" data-close-reject>Cancel</button>
                <button type="submit" class="btn-submit" style="background:#dc2626; color:white;">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>