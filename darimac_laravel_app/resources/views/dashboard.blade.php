<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-[#6B46C1] to-[#D53F8C] text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <div class="relative">
                <button id="userMenu" class="flex items-center space-x-2 focus:outline-none hover:text-gray-200">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <!-- Dropdown Menu -->
                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-md shadow-lg z-10">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">View Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="block w-full text-left">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-left hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <!-- Form Card -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Enter New Form</h2>
            <form method="POST" action="{{ route('forms.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#6B46C1] focus:border-[#6B46C1] p-2" placeholder="Enter name">
                    @if ($errors->has('name'))
                        <span class="mt-2 text-red-600 text-sm">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" id="address" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#6B46C1] focus:border-[#6B46C1] p-2" placeholder="Enter address">
                    @if ($errors->has('address'))
                        <span class="mt-2 text-red-600 text-sm">{{ $errors->first('address') }}</span>
                    @endif
                </div>
                <div>
                    <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="number" name="contact_number" id="contact_number" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#6B46C1] focus:border-[#6B46C1] p-2" placeholder="Enter contact number">
                    @if ($errors->has('contact_number'))
                        <span class="mt-2 text-red-600 text-sm">{{ $errors->first('contact_number') }}</span>
                    @endif
                </div>
                <button type="submit" class="submit-btn inline-block px-4 py-2 bg-[#6B46C1] text-white rounded-md hover:bg-[#553C9A] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6B46C1]">
                    Submit
                </button>
            </form>
        </div>

        <!-- Forms List Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Your Forms</h2>
            @if ($forms->isEmpty())
                <p class="text-gray-500">No forms submitted yet.</p>
            @else
                <ul class="space-y-2">
                    @foreach ($forms as $form)
                        <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                            <span class="text-gray-800">{{ $form->name }}</span>
                            <a href="{{ route('forms.download', $form->id) }}" class="text-[#6B46C1] hover:text-[#553C9A] underline">Download PDF</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- JavaScript for Dropdown Toggle -->
    <script>
        document.getElementById('userMenu').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            const menu = document.getElementById('userMenu');
            if (!menu.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <style>
        body {
            background: linear-gradient(135deg, #a485ee, #f590c4);
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }
        nav {
            background: linear-gradient(to right, #6B46C1, #D53F8C);
        }
        .bg-white {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #E2E8F0;
            border-radius: 5px;
            text-align: left;
        }
        .bg-gray-50 {
            background: #F9FAFB;
        }
        a.text-[#6B46C1] {
            color: #6B46C1;
        }
        a.text-[#6B46C1]:hover {
            color: #553C9A;
        }
        .submit-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
            background: #a485ee;
        }
        .submit-btn:hover {
            background: #553C9A;
        }
        .submit-btn:focus {
            outline: none;
            ring: 2px solid #6B46C1;
            ring-offset: 2px;

        }
    </style>
</body>
</html>
