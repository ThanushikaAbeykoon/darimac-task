<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #a485ee, #f590c4); /* Updated gradient background */
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .profile-container {
            width: 100%;
            max-width: 48rem;
            padding: 1rem;
            box-sizing: border-box;
            margin: 0 auto;
            margin-top: 5rem; /* Adjust for fixed navbar */
        }
        .profile-header {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2D3748;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .profile-section {
            background: white;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            max-width: 36rem;
        }
        .navbar {
            background: linear-gradient(to right, #6B46C1, #D53F8C); /* Consistent gradient */
            color: white;
            padding: 0.75rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            position: fixed;
            width: 100%;
            top: 0;
        }
        .navbar .container {
            max-width: 48rem;
            margin: 0 auto;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            font-size: 1.5rem;
            font-weight: 700;

        }
        .navbar .relative {
            position: relative;
        }
        .navbar button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-weight: 500;
        }
        .navbar button:hover {
            color: #E2E8F0;
        }
        .navbar .dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            color: #2D3748;
            border-radius: 0.25rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 10rem;
            display: none;
        }
        .navbar .dropdown a,
        .navbar .dropdown button {
            display: block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #2D3748;
            width: 100%;
            text-align: left;
        }
        .navbar .dropdown a:hover,
        .navbar .dropdown button:hover {
            background: #EDF2F7;
        }
        .navbar .dropdown.show {
            display: block;
        }
        svg {
            width: 1.25rem;
            height: 1.25rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1>Profile</h1>
            <div class="relative">
                <button id="userMenu" class="flex items-center space-x-2 focus:outline-none">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="dropdownMenu" class="dropdown">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-section">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="profile-section">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="profile-section">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('userMenu').addEventListener('click', function(e) {
        e.preventDefault();
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('show');
    });

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('dropdownMenu');
        const menu = document.getElementById('userMenu');
        if (!menu.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
</script>
</html>
