<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ISSNA Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-main-image {
            background-image: url('{{ asset('images/backg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .overlay-dark {
            background-color: rgba(30, 58, 138, 0.85); /* #1e3a8a with transparency */
        }
    </style>
</head>
<body class="bg-main-image h-screen flex items-center justify-center p-4">
    <div class="fixed inset-0 overlay-dark -z-10"></div>
    <div class="max-w-md w-full relative z-10">
        <!-- Logo / Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/ISSNA.png') }}" alt="Logo ISSNA" class="h-20 w-auto mx-auto mb-4 object-contain">
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase">Gestion des Notes</h1>
            <p class="text-blue-200 mt-1 font-medium tracking-widest uppercase text-xs">Portail Académique</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Connexion</h2>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Adresse Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="admin@issna.cm"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Mot de passe</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-600">Se souvenir de moi</label>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold py-3 rounded-lg shadow-lg transform active:scale-[0.98] transition-all"
                >
                    Se connecter
                </button>
            </form>
        </div>

        <p class="text-center text-blue-300 mt-8 text-sm">
            &copy; {{ date('Y') }} Institut Supérieur de Santé - Douala
        </p>
    </div>
</body>
</html>
