<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
        .register-container {
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
    <form method="POST" action="{{ route('register') }}" class="register-container">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input id="name" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Enter your name">
            @if ($errors->has('name'))
                <span class="mt-2 text-red-600 text-sm">{{ $errors->first('name') }}</span>
            @endif
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Enter your email">
            @if ($errors->has('email'))
                <span class="mt-2 text-red-600 text-sm">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="password" name="password" required autocomplete="new-password" placeholder="Enter your password">
            @if ($errors->has('password'))
                <span class="mt-2 text-red-600 text-sm">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
            @if ($errors->has('password_confirmation'))
                <span class="mt-2 text-red-600 text-sm">{{ $errors->first('password_confirmation') }}</span>
            @endif
        </div>

        <div class="flex items-center justify-end mt-4" style="gap: 1.5rem;">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <button type="submit" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                {{ __('Register') }}
            </button>
        </div>

        <!-- Google Sign-In/Sign-Up Button -->
        <div class="mt-4">
            <a href="{{ route('login.google') }}" class="g-signin-button">Sign up with Google</a>
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
