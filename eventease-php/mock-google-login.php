<?php
// This is a simulated Google Login page for local testing when real API keys aren't provided.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - Google Accounts</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .google-logo {
            width: 75px;
            margin-bottom: 15px;
        }
        h1 {
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            color: #202124;
            margin-bottom: 30px;
        }
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input:focus {
            outline: none;
            border-color: #1a73e8;
            border-width: 2px;
            padding: 12px 14px;
        }
        .btn-primary {
            background-color: #1a73e8;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            float: right;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background-color: #1557b0;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .dev-badge {
            background: #ffc107;
            color: #000;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            margin-bottom: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="dev-badge">DEVELOPER BYPASS MODE</div>
    <br>
    <svg class="google-logo" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
    <h1>Sign in</h1>
    <p>to continue to EventEase</p>
    
    <form action="auth-google.php" method="GET" class="clearfix">
        <div class="input-group">
            <input type="email" name="mock_email" placeholder="Email or phone" required value="student@college.edu">
        </div>
        <div class="input-group">
            <input type="password" name="mock_password" placeholder="Enter your password" required value="password123">
        </div>
        <button type="submit" class="btn-primary">Next</button>
    </form>
</div>

</body>
</html>
