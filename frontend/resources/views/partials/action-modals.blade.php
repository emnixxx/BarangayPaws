{{-- Generic Confirm Modal --}}
<div class="modal-overlay" id="generic-confirm-modal">
    <div class="modal-container" style="max-width: 440px;">
        <div class="modal-header">
            <h3 class="modal-title" id="confirm-modal-title">Confirm Action</h3>
            <button type="button" class="modal-close" data-close-action>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <p class="confirm-modal-text" id="confirm-modal-message">Are you sure you want to proceed?</p>
        <form id="generic-confirm-form" method="POST">
            @csrf
            <div class="confirm-modal-actions">
                <button type="button" class="btn-reject" data-close-action style="background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Cancel</button>
                <button type="submit" class="btn-approve">Confirm</button>
            </div>
        </form>
    </div>
</div>

{{-- Generic Reject Modal --}}
<div class="modal-overlay" id="generic-reject-modal">
    <div class="modal-container" style="max-width: 480px;">
        <div class="modal-header">
            <h3 class="modal-title" id="reject-modal-title">Reject Action</h3>
            <button type="button" class="modal-close" data-close-action>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <p class="confirm-modal-text" id="reject-modal-message">Please provide a reason below.</p>
        <form id="generic-reject-form" method="POST">
            @csrf
            <label for="generic_rejection_reason" class="confirm-modal-label">
                Reject Reason <span class="confirm-required">*</span>
            </label>
            <textarea id="generic_rejection_reason" name="rejection_reason" required rows="3"
                class="confirm-modal-textarea"
                placeholder="Enter reason..."></textarea>
            <div class="confirm-modal-actions">
                <button type="button" class="btn-approve" data-close-action style="background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Cancel</button>
                <button type="submit" class="btn-reject">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>
