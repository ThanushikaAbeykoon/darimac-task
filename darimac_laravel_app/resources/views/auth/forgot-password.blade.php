<form method="POST" action="{{ route('password.email') }}" class="forgot-password-container">
    @csrf

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <!-- Instruction Text -->
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div><br>

    <!-- Email Address -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
        @if ($errors->has('email'))
            <span class="mt-2 text-red-600 text-sm">{{ $errors->first('email') }}</span>
        @endif
    </div>

    <div class="flex items-center justify-end mt-4">
        <button type="submit" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            {{ __('Email Password Reset Link') }}
        </button>
    </div>
</form>

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
    .forgot-password-container {
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
</style>
