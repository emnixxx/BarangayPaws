{{-- Confirm Action Modal --}}
<div class="modal-overlay" id="action-confirm-modal">
    <div class="modal-container" style="max-width: 400px; text-align: center;">
        <div class="modal-header" style="justify-content: center; border-bottom: none; padding-bottom: 0;">
            <div style="background: #ecfdf5; color: #10b981; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 16 12 12 12 8"></polyline>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
        </div>
        <h3 id="confirm-modal-title" style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px;">Confirm Action</h3>
        <p id="confirm-modal-message" style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">Are you sure you want to proceed?</p>
        
        <form id="confirm-action-form" method="POST">
            @csrf
            <div class="modal-actions center">
                <button type="button" class="modal-btn modal-btn-secondary" data-close-confirm style="flex:1;">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-success" style="flex:1;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Confirm
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Action Modal --}}
<div class="modal-overlay" id="action-reject-modal">
    <div class="modal-container" style="max-width: 450px;">
        <div class="modal-header">
            <h3 id="reject-modal-title" class="modal-title">Reject Action</h3>
            <button class="modal-close" data-close-reject>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <form id="reject-action-form" method="POST">
            @csrf
            <p id="reject-modal-message" style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">Please provide a reason for rejection.</p>
            
            <div style="margin-bottom: 20px;">
                <textarea id="rejection-reason" name="rejection_reason" rows="3" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; resize: vertical;" placeholder="Enter reason here..." required></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-secondary" data-close-reject>Cancel</button>
                <button type="submit" class="modal-btn modal-btn-danger">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>
