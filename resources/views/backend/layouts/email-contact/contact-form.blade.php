<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #eeeeee;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #4CAF50;
        }
        .content {
            padding: 20px 0;
        }
        .content h2 {
            margin: 0 0 15px 0;
            font-size: 22px;
            color: #333333;
            border-bottom: 2px solid #4CAF50;
            display: inline-block;
            padding-bottom: 5px;
        }
        .content p {
            margin: 10px 0;
            font-size: 16px;
            color: #555555;
        }
        .content p strong {
            display: inline-block;
            width: 120px;
            color: #333333;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 2px solid #eeeeee;
            margin-top: 20px;
        }
        .footer p {
            margin: 0;
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Contact Form Submission</h1>
    </div>
    <div class="content">
        <h2>Contact Details</h2>
        <p><strong>Full Name:</strong> {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
        <p><strong>Email:</strong> {{ $data['email'] }}</p>
        <p><strong>Phone:</strong> {{ $data['phone'] }}</p>
        <p><strong>Company:</strong> {{ $data['company'] }}</p>
        <h2>Message:</h2>
        <p>{{ $data['message'] }}</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>
</body>
</html>
