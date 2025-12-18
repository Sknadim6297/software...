<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .status-box { padding: 15px; margin: 20px 0; }
        .approved { background: #d1fae5; border-left: 4px solid #10b981; }
        .rejected { background: #fee2e2; border-left: 4px solid #ef4444; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📅 Leave Application Update</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $leaveApplication->bdm->name }}</strong>,</p>
            
            <div class="status-box {{ $leaveApplication->status === 'approved' ? 'approved' : 'rejected' }}">
                <h3>Leave Application {{ ucfirst($leaveApplication->status) }}</h3>
                <p>Your leave application for <strong>{{ $leaveApplication->leave_date->format('F d, Y') }}</strong> has been <strong>{{ $leaveApplication->status }}</strong>.</p>
            </div>
            
            <p><strong>Leave Details:</strong></p>
            <ul>
                <li>Leave Type: {{ ucfirst($leaveApplication->leave_type) }} Leave</li>
                <li>Leave Date: {{ $leaveApplication->leave_date->format('F d, Y') }}</li>
                <li>Reason: {{ $leaveApplication->reason }}</li>
                <li>Status: {{ ucfirst($leaveApplication->status) }}</li>
                @if($leaveApplication->admin_remarks)
                    <li>Admin Remarks: {{ $leaveApplication->admin_remarks }}</li>
                @endif
            </ul>
            
            @if($leaveApplication->status === 'approved')
                <p>Your leave has been approved. Please ensure all pending work is completed or delegated appropriately.</p>
            @else
                <p>Your leave application has been rejected. Please contact your supervisor if you have any questions.</p>
            @endif
            
            <p>Best regards,<br>Konnectix Management</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} Konnectix Software. All rights reserved.
        </div>
    </div>
</body>
</html>
