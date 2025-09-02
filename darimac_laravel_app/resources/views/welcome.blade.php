<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <!-- Include Tailwind CSS for basic styling -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6B46C1, #D53F8C);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        .login-container h1 {
            font-size: 2.5rem;
            color: #6B46C1;
            margin-bottom: 1rem;
        }
        .login-container p {
            font-size: 0.875rem;
            color: #718096;
            margin-bottom: 2rem;
        }
        .input-field {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #E2E8F0;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            margin: 0.5rem;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-login { background-color: #6B46C1; }
        .btn-signup { background-color: #D53F8C; }
        .btn-login:hover { background-color: #553C9A; }
        .btn-signup:hover { background-color: #B83280; }
        .social-icons a {
            margin: 0 0.5rem;
            color: #718096;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!--<p class="text-sm text-gray-600 mb-4">yourlogo</p>-->
        <h1>Hello, welcome!</h1>
        <!--<input type="text" placeholder="Email address" class="input-field">
        <input type="password" placeholder="Password" class="input-field">
        <div class="text-sm text-gray-500 mb-4">
            <a href="#" class="text-blue-500">Forgot password?</a>
        </div>-->
        <a href="/login" class="btn btn-login">Login</a>
        <a href="/register" class="btn btn-signup">Sign up</a>
        <!--<div class="social-icons mt-4">
            <a href="#">f</a>
            <a href="#">t</a>
            <a href="#">i</a>
        </div>-->
    </div>
</body>
</html>
