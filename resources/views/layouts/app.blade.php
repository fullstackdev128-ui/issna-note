<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ISSNA Notes</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        .bg-main-image {
            background-image: url('{{ asset('images/backg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        .overlay-blur {
            backdrop-filter: blur(2px);
            background-color: rgba(255, 255, 255, 0.85);
        }
    </style>
</head>
<body class="bg-main-image font-sans antialiased" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden bg-white/10">
        <!-- Sidebar -->
        <aside 
            class="bg-[#1e3a8a]/95 backdrop-blur-md text-white transition-all duration-300 flex flex-col shadow-2xl"
            :class="sidebarOpen ? 'w-64' : 'w-20'"
        >
            <!-- Logo Area -->
            <div class="p-4 flex items-center justify-between border-b border-blue-800/50">
                <div x-show="sidebarOpen" class="flex items-center">
                    <img src="{{ asset('images/ISSNA.png') }}" alt="Logo ISSNA" class="h-12 w-auto object-contain">
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-1 hover:bg-blue-800 rounded transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded hover:bg-blue-800 {{ request()->routeIs('dashboard') ? 'bg-blue-800' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('etudiants.index') }}" class="flex items-center p-2 rounded hover:bg-blue-800 {{ request()->routeIs('etudiants.*') ? 'bg-blue-800' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3">Étudiants</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('notes.saisie') }}" class="flex items-center p-2 rounded hover:bg-blue-800 {{ request()->routeIs('notes.*') ? 'bg-blue-800' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3">Notes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('resultats.calculer') }}" class="flex items-center p-2 rounded hover:bg-blue-800 {{ request()->routeIs('resultats.calculer', 'resultats.preview', 'resultats.show') ? 'bg-blue-800' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3">Résultats Semestriels</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('resultats.annuels.calculer') }}" class="flex items-center p-2 rounded hover:bg-blue-800 {{ request()->routeIs('resultats.annuels.*') ? 'bg-blue-800' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3">Résultats Annuels</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('releves') }}" class="flex items-center p-2 rounded hover:bg-blue-800 {{ request()->routeIs('releves') ? 'bg-blue-800' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3">Relevés PDF</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('referentiel.index') }}" class="flex items-center p-2 rounded hover:bg-blue-800 {{ request()->routeIs('referentiel.*') ? 'bg-blue-800' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3">Référentiel</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-blue-800">
                <div class="flex items-center mb-4" x-show="sidebarOpen">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-xs font-bold">
                        {{ substr(auth()->user()->nom, 0, 1) }}{{ substr(auth()->user()->prenoms, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold truncate">{{ auth()->user()->nom_complet }}</p>
                        <p class="text-xs text-blue-300 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-2 rounded hover:bg-red-700 transition-colors text-red-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overlay-blur overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white/90 backdrop-blur-sm shadow-sm z-10 border-b border-gray-100">
                <div class="px-4 py-3 flex items-center justify-between">
                    <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>
                    
                    <div class="flex items-center space-x-4">
                        @php
                            $anneeActive = \App\Models\AnneeAcademique::where('active', true)->first();
                        @endphp
                        @if($anneeActive)
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-bold border border-green-200">
                                {{ $anneeActive->libelle }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-bold border border-red-200">
                                Aucune année active
                            </span>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button @click="show = false" class="text-green-600 hover:text-green-800">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 p-4 bg-red-100 border border-red-200 text-red-800 rounded-lg flex justify-between items-center">
                        <span>{{ session('error') }}</span>
                        <button @click="show = false" class="text-red-600 hover:text-red-800">&times;</button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
