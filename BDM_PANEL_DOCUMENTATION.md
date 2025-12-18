# BDM Panel - Complete Documentation

## Overview
The BDM (Business Development Manager) Panel is a comprehensive system for managing BDM profiles, documents, salary, leaves, targets, and notifications. This system is completely separate from the admin panel and provides BDMs with a dedicated interface.

## Features

### 1. Dashboard
- Profile summary with photo
- Current month target progress (projects & revenue)
- Leave balance display
- Recent leave applications
- Unread notifications count
- Warning status indicator

### 2. Profile Management
- View personal information:
  - Name, Father's Name
  - Date of Birth
  - Highest Education
  - Email, Phone
  - Employee Code
  - Joining Date
  - Current CTC
  - Status (Active/Warned/Terminated)
- Upload/update profile image
- Update phone number

### 3. Document Management
Required 8 documents:
1. Aadhaar Card
2. PAN Card
3. 10th Admit Card
4. 12th Marksheet
5. Graduation Final Year Certificate
6. Last Company Appointment/Offer Letter
7. Salary Slip (Last Company)
8. Last Company Reference Contact Number

Features:
- Upload documents (PDF/Images, max 5MB)
- Download uploaded documents
- Replace existing documents
- Delete documents
- Track missing documents

### 4. Salary & Remuneration
- View current CTC
- View salary history (month-wise)
- Salary breakdown:
  - Basic Salary
  - HRA
  - Other Allowances
  - Gross Salary
  - Deductions
  - Net Salary
- Download monthly salary slips (PDF)

### 5. Leave Management

#### Leave Rules:
- **6-Month Rule**: No CL/SL for first 6 months, only unpaid leave
- **Annual Quota**: 6 Casual Leaves + 6 Sick Leaves per year (after 6 months)
- **Monthly Limit**: Maximum 1 CL + 1 SL per month
- **Casual Leave**: 15 days advance notice required
- **Sick Leave**: Apply before 7:30 AM on same day
- **Unpaid Leave**: Available from day 1

Features:
- View leave balance
- Track monthly usage
- Apply for leaves with reason
- View leave application history
- Check approval status
- View admin remarks

### 6. Target Management

#### Target Types:
- **Monthly Targets**: Evaluated every month
- **Quarterly Targets**: Q1, Q2, Q3, Q4
- **Annual Targets**: Yearly goals

#### Target Metrics:
- **Project Target**: Number of projects to finalize
- **Revenue Target**: Total revenue to generate

#### Business Rules:
- **80% Threshold**: Minimum achievement required
- **Carry Forward**: Unmet targets added to next month
- **Warning System**: 3 consecutive failures = termination
- **Calculation**: Based on current month finalized projects only (not cumulative)

Features:
- View current month target with progress bars
- Track overall achievement percentage
- View all target history (monthly/quarterly/annual)
- Detailed target breakdown
- Carry-forward tracking

### 7. Notifications
Types:
- **Warning**: Performance warnings (1/3, 2/3, 3/3)
- **Target Failure**: Monthly target not met
- **Termination**: Account terminated
- **Leave Status**: Leave approved/rejected
- **General**: Other notifications

Features:
- View all notifications
- Mark individual notifications as read
- Mark all notifications as read
- Filter by read/unread
- Visual indicators for new notifications

## System Architecture

### Database Tables (7 tables):

1. **bdms**: BDM profiles with warning and termination tracking
2. **bdm_documents**: 8 required document uploads
3. **bdm_salaries**: Monthly salary records
4. **bdm_leave_balances**: Leave balance and monthly usage tracking
5. **bdm_leave_applications**: Leave requests with approval workflow
6. **bdm_targets**: Target tracking with carry-forward logic
7. **bdm_notifications**: In-app notification system

### Models (7 models):
- BDM
- BDMDocument
- BDMSalary
- BDMLeaveBalance
- BDMLeaveApplication
- BDMTarget
- BDMNotification

### Controllers:
- **BDMController**: Main controller handling all BDM panel operations

### Mail Notifications (4 types):
- BDMWarningNotification
- BDMTerminationNotification
- BDMTargetFailureNotification
- BDMLeaveStatusNotification

### Console Commands:
- **bdm:evaluate-targets**: Runs monthly to evaluate previous month targets
  - Calculates achievement from finalized contracts
  - Issues warnings if < 80%
  - Terminates after 3 consecutive failures
  - Carries forward unmet targets
  - Sends automated emails

### Scheduled Tasks:
- Target evaluation: 1st of each month at 2:00 AM

## Routes

All BDM routes use `/bdm` prefix and `bdm.` name prefix:

```
/bdm/dashboard - Dashboard
/bdm/profile - Profile view/update
/bdm/documents - Document management
/bdm/salary - Salary history
/bdm/leaves - Leave management
/bdm/targets - Target tracking
/bdm/notifications - Notifications
```

## Access Control

### Authentication:
- BDM must be logged in via Laravel auth
- User must have associated BDM record
- Terminated BDMs cannot login (can_login = false)

### Layout:
- Dedicated BDM layout with purple gradient theme
- Dark sidebar with navigation
- Status indicators (Active/Warned)
- Notification badge in sidebar

## Email Notifications

### 1. Warning Email
Sent when target not met:
- Warning count (1/3, 2/3, 3/3)
- Target period
- Achievement percentage
- Next steps

### 2. Termination Email
Sent on termination:
- Termination reason
- Effective date
- Instructions for exit formalities

### 3. Target Failure Email
Sent when monthly target fails:
- Achievement details
- Project/revenue breakdown
- Performance improvement suggestions

### 4. Leave Status Email
Sent when leave approved/rejected:
- Leave details
- Admin remarks
- Status update

## Target Evaluation Process

### Monthly Evaluation (Automated):
1. **Trigger**: 1st of each month at 2 AM
2. **Process**:
   - Get previous month targets for all BDMs
   - Calculate achievements from finalized contracts (current month only)
   - Update achievement percentage
   - Check if 80% threshold met
   
3. **If Target Met (≥80%)**:
   - Mark target as completed
   - Reset warning count to 0
   - Create next month target (no carry forward)
   
4. **If Target Failed (<80%)**:
   - Mark target as failed
   - Increment warning count
   - Send warning email
   - Send target failure email
   - Create notification
   
5. **If 3 Consecutive Failures**:
   - Terminate BDM
   - Set status = 'terminated'
   - Set can_login = false
   - Send termination email
   - Record termination reason

6. **Carry Forward**:
   - Calculate deficit: (total_target - achieved)
   - Add deficit to next month's target
   - Create next month target with carry forward

## Important Notes

### Leave Eligibility:
- BDMs must wait 6 months from joining date to avail CL/SL
- Unpaid leave is available from day 1
- Monthly limits reset on 1st of each month
- Cannot exceed 1 CL + 1 SL per month even if balance available

### Target Calculation:
- **CRITICAL**: Achievement calculated from current month data only
- Uses `finalized_at` field from contracts table
- Filters by `whereMonth()` and `whereYear()` for current evaluation month
- Not cumulative from previous months

### Carry Forward Logic:
- Only applies when target fails (<80%)
- Deficit = max(0, total_target - achieved)
- Added to next month's base target
- Displayed separately in UI for transparency

### Warning System:
- Warning count ranges from 0 to 3
- Resets to 0 when target met
- Status changes to 'warned' on first failure
- Termination occurs at warning_count = 3

## Setup Instructions

### 1. Database Setup:
```bash
php artisan migrate
```

### 2. Storage Link (for file uploads):
```bash
php artisan storage:link
```

### 3. Configure Scheduler:
Add to crontab (Linux) or Task Scheduler (Windows):
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Configure Mail:
Update `.env` with SMTP settings:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Konnectix Software"
```

### 5. Create BDM Users:
BDMs need both a user account and BDM profile:
```php
// Create user first
$user = User::create([...]);

// Create BDM profile
BDM::create([
    'user_id' => $user->id,
    'name' => '...',
    'employee_code' => '...',
    // ... other fields
]);

// Create leave balance
BDMLeaveBalance::create([
    'bdm_id' => $bdm->id,
    'casual_leave_balance' => 0, // Will be 6 after 6 months
    'sick_leave_balance' => 0,   // Will be 6 after 6 months
    'current_month' => now()->format('Y-m'),
]);
```

## Admin Responsibilities

Admins must:
1. Create BDM users and profiles
2. Set monthly/quarterly/annual targets
3. Approve/reject leave applications
4. Upload monthly salary records with slips
5. Assign BDMs to contracts
6. Mark contracts as finalized (triggers target calculation)
7. Monitor warnings and performance
8. Handle terminations and revivals

## File Uploads

### Profile Images:
- Path: `storage/app/public/bdm/profiles/`
- Max size: 2MB
- Formats: JPG, JPEG, PNG

### Documents:
- Path: `storage/app/public/bdm/documents/{bdm_id}/`
- Max size: 5MB
- Formats: PDF, JPG, JPEG, PNG

### Salary Slips:
- Path: `storage/app/public/bdm/salary_slips/`
- Format: PDF only
- Uploaded by admin

## Security Considerations

1. **Authentication**: All routes protected by `auth` middleware
2. **Authorization**: BDM can only access own data (checked via Auth::user()->bdm)
3. **File Validation**: File types and sizes validated before upload
4. **SQL Injection**: Protected via Eloquent ORM
5. **CSRF Protection**: All forms use @csrf token
6. **Terminated Access**: Terminated BDMs automatically logged out

## Testing Checklist

- [ ] Profile image upload
- [ ] Phone number update
- [ ] All 8 documents upload/download/delete
- [ ] Salary slip download
- [ ] Leave application (CL/SL/Unpaid)
- [ ] 6-month eligibility check
- [ ] Monthly leave limit enforcement (1 CL + 1 SL)
- [ ] 15-day advance for CL
- [ ] 7:30 AM cutoff for SL
- [ ] Target achievement calculation
- [ ] Carry-forward logic
- [ ] Warning system (1, 2, 3 strikes)
- [ ] Termination on 3rd strike
- [ ] Email notifications
- [ ] Notification read/unread
- [ ] Scheduler execution

## Troubleshooting

### Issue: Migrations fail
- Check database connection in `.env`
- Ensure MySQL is running
- Drop BDM tables and re-run migrations

### Issue: File uploads fail
- Run `php artisan storage:link`
- Check folder permissions (775 for storage/)
- Verify php.ini upload_max_filesize

### Issue: Scheduler not running
- Verify cron job setup
- Run `php artisan schedule:work` for testing
- Check Laravel logs in `storage/logs/`

### Issue: Emails not sending
- Verify SMTP settings in `.env`
- Check mail logs
- Test with `php artisan tinker` and `Mail::to(...)->send(...)`

### Issue: Target calculation wrong
- Verify contracts have `bdm_id` and `finalized_at` fields
- Check `whereMonth()` and `whereYear()` filters
- Ensure `status = 'finalized'` in contracts

## Version History

- **v1.0** (2025-12-19): Initial release
  - Complete BDM panel implementation
  - 7 database tables
  - 7 models with business logic
  - Full CRUD operations
  - Email notifications
  - Automated target evaluation
  - Leave management with rules
  - Document management system
  - Salary viewing with slips
  - Notification system

## Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Review database for data consistency
- Test scheduler manually: `php artisan bdm:evaluate-targets`
- Verify email configuration

## Credits

Developed for Konnectix Software
Built with Laravel 11.x + Bootstrap 5 + Font Awesome 6
