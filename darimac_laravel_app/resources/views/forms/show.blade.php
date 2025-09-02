<!DOCTYPE html>
<html>
<head>
    <title>Form Submitted</title>
    <style>
        body {
            background: linear-gradient(135deg, #a485ee, #f590c4);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #a485ee; /* Purple accent */
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }
        h2 {
            color: #2D3748; /* Dark gray for headings */
            font-size: 1.5rem;
            margin: 1.5rem 0 1rem;
        }
        p {
            color: #4A5568; /* Medium gray for text */
            font-size: 1rem;
            margin: 0.5rem 0;
            line-height: 1.5;
        }
        .qr-code {
            border: 2px solid #f590c4; /* Pink border for QR code */
            padding: 1rem;
            border-radius: 0.25rem;
            margin: 1rem 0;
            background: #fff;
        }
        a {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #a485ee; /* Purple button */
            color: white;
            text-decoration: none;
            border-radius: 0.25rem;
            font-weight: 500;
            margin-top: 1rem;
        }
        a:hover {
            background: #8b5cf6; /* Darker purple on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Form Submitted</h1>
        <p>Name: {{ $form->name }}</p>
        <p>Address: {{ $form->address }}</p>
        <p>Contact: {{ $form->contact_number }}</p>
        <h2>QR Code</h2>
        <div class="qr-code">
            {!! $qrCode !!}
        </div>
        <p><a href="{{ route('forms.download', $form->id) }}">Download PDF</a></p>
        <div>
            <a href="{{ url('dashboard') }}">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
