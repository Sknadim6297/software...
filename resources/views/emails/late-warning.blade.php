<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .count-badge {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Attendance Warning Notice</h1>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $user->name }}</strong>,</p>
            
            <p>This is an official notification regarding your attendance records.</p>
            
            <div class="warning-box">
                <h3 style="margin-top: 0;">Late Marks Count</h3>
                <p>You have received <span class="count-badge">{{ $lateCount }}</span> late marks this month.</p>
            </div>
            
            <p><strong>{{ $warningMessage }}</strong></p>
            
            <h4>Attendance Policy Reminder:</h4>
            <ul>
                <li>Check-in deadline: <strong>10:45 AM</strong></li>
                <li>3 late marks = <strong>Warning issued</strong></li>
                <li>4 late marks = <strong>Automatic Half-Day assignment</strong></li>
            </ul>
            
            <p>Please ensure you arrive on time to avoid further penalties. Your punctuality is important to maintain productivity and professionalism.</p>
            
            <p>If you have any concerns or need to discuss this matter, please contact HR immediately.</p>
            
            <p>Best regards,<br>
            <strong>HR Department</strong><br>
            Konnectix Technologies</p>
        </div>
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Konnectix Technologies. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
