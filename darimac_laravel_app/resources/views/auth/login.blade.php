<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        label {
            text-align: left;
            display: block;
        }
        input {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #E2E8F0;
            border-radius: 5px;
            text-align: left;
        }
        button {
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            color: white;
            background-color: #6B46C1;
            text-decoration: none;
            font-weight: bold;
        }
        button:hover {
            background-color: #553C9A;
        }
        .remember-me-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        input.checkbox {
            width: auto;
            margin: 0;
            flex-shrink: 0;
        }
        label.checkbox-label {
            display: inline-block;
            text-align: left;
            margin: 0;
        }
        .g-signin-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #4285F4; /* Google's blue */
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 1rem;
            cursor: pointer;
        }
        .g-signin-button:hover {
            background: #357ABD; /* Darker Google blue on hover */
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('login') }}" class="login-container">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email">
            @if ($errors->has('email'))
                <span class="mt-2 text-red-600 text-sm">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
            @if ($errors->has('password'))
                <span class="mt-2 text-red-600 text-sm">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="block mt-4 remember-me-container">
            <input id="remember_me" type="checkbox" class="checkbox" name="remember">
            <label for="remember_me" class="text-sm text-gray-600 checkbox-label">Remember me</label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <button type="submit" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Log in
            </button>
        </div>

        <!-- Google Sign-In Button -->
        <div class="mt-4">
            <a href="{{ route('login.google') }}" class="g-signin-button">Sign in with Google</a>
        </div>
    </form>

    <!--<script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        function onSignIn(googleUser) {
            const id_token = googleUser.getAuthResponse().id_token;
            // Send token to your backend (e.g., via AJAX or redirect)
            window.location.href = "{{ route('login.google') }}?id_token=" + id_token;
        }
    </script>-->
</body>
</html>
