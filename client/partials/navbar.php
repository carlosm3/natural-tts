<!-- Header -->
<header class="border-b border-gray-700 py-4 bg-[#333] shadow-sm">
    <div class="container mx-auto px-4 max-w-5xl flex justify-between items-center">
        <a href="#" class="flex items-center space-x-2">
            <span class="text-2xl">🔊</span>
            <span class="text-xl font-semibold text-white font-mono tracking-tight">NaturalTTS</span>
        </a>
        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-1">
            <a href="index.php" class="text-gray-300 hover:text-white px-3 py-2 text-sm transition-colors">Home</a>
            <a href="index.php#features" class="text-gray-300 hover:text-white px-3 py-2 text-sm transition-colors">Features</a>
            <a href="index.php#voices" class="text-gray-300 hover:text-white px-3 py-2 text-sm transition-colors">Voices</a>
            <a href="index.php#faq" class="text-gray-300 hover:text-white px-3 py-2 text-sm transition-colors">FAQ</a>
        </div>
        
        <!-- Mobile Hamburger Button -->
        <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</header>

<!-- Mobile Menu (hidden by default) -->
<div id="mobile-menu" class="hidden md:hidden fixed w-full bg-[#333] border-t border-gray-700 pt-2 shadow-md z-50" style="top: 64px;">
    <div class="container mx-auto px-4 mb-3">
        <nav class="flex flex-col space-y-2">
            <a href="index.php" class="text-gray-300 hover:text-white px-3 py-2 text-sm">Home</a>
            <a href="index.php#features" class="text-gray-300 hover:text-white px-3 py-2 text-sm">Features</a>
                <a href="index.php#voices" class="text-gray-300 hover:text-white px-3 py-2 text-sm">Voices</a>
                <a href="index.php#faq" class="text-gray-300 hover:text-white px-3 py-2 text-sm">FAQ</a>
            </nav>
        </div>
    </div>
</header>