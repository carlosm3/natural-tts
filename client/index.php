<?php
// Include header partial
include_once('partials/header.php');

// Include navbar partial
include_once('partials/navbar.php');
?>

<main class="flex-1">
    <!-- Main Converter Section -->
    <section class="py-8 md:py-12">
        <div class="container mx-auto px-4 max-w-3xl">
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-semibold text-light-100 mb-2">Browser-based Text to Speech</h1>
                <p class="text-light-400 text-sm md:text-base">Convert text to natural voices locally. No server round-trips. Privacy-first design.</p>
            </div>

            <!-- Converter Tool -->
            <div class="border border-light-600 bg-light-900 rounded-md overflow-hidden mb-6 shadow-sm">
                <div class="p-4 border-b border-light-600 bg-light-800 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                        </svg>
                        <span class="text-sm font-medium text-light-100">Text to Speech Converter</span>
                    </div>
                    <div class="flex items-center text-xs text-light-400">
                        <span>v2.3.1</span>
                        <span class="mx-2">|</span>
                        <span>WebAssembly + Web Audio API</span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="relative mb-4">
                        <textarea id="text-input" placeholder="Enter text to convert to speech..."
                            class="w-full h-32 p-3 rounded-md bg-white border border-light-600 text-light-100 placeholder-light-500 font-mono text-sm resize-y focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none transition"></textarea>
                        <div class="absolute bottom-2 right-2 text-xs text-light-400">
                            <span id="char-count">0</span> / 5000
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-xs text-light-300 mb-1.5" for="voice">Voice</label>
                            <select id="voice" class="w-full bg-white border border-light-600 rounded-md p-2 text-sm text-light-100 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none">
                                <option value="en_US_female">en-US Female</option>
                                <option value="en_US_male">en-US Male</option>
                                <option value="en_UK_female">en-UK Female</option>
                                <option value="en_UK_male">en-UK Male</option>
                                <option value="es_female">es Female</option>
                                <option value="fr_female">fr Female</option>
                                <option value="de_female">de Female</option>
                                <option value="it_female">it Female</option>
                                <option value="ja_female">ja Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-light-300 mb-1.5" for="speed">Speed</label>
                            <select id="speed" class="w-full bg-white border border-light-600 rounded-md p-2 text-sm text-light-100 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none">
                                <option value="0.8">0.8x (Slow)</option>
                                <option value="1.0" selected>1.0x (Normal)</option>
                                <option value="1.2">1.2x (Fast)</option>
                                <option value="1.5">1.5x (Very Fast)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-light-300 mb-1.5" for="filename">Output Filename</label>
                            <input type="text" id="filename" value="output" class="w-full bg-white border border-light-600 rounded-md p-2 text-sm text-light-100 font-mono focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button id="generate-btn" class="flex-1 px-4 py-2 bg-[#5d3b96] hover:bg-[#4e2f85] text-white text-sm font-medium rounded-md transition-colors duration-200">
                            Generate Speech
                        </button>
                        <button id="sample-btn" class="px-4 py-2 bg-light-700 hover:bg-light-600 text-light-100 text-sm font-medium rounded-md transition-colors duration-200">
                            Try Sample
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status Area -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                <div class="border border-light-600 bg-light-800 p-3 rounded-md shadow-sm">
                    <div class="text-xs text-light-400 mb-1">Model Size</div>
                    <div class="text-sm text-light-100 font-mono">127MB</div>
                </div>
                <div class="border border-light-600 bg-light-800 p-3 rounded-md shadow-sm">
                    <div class="text-xs text-light-400 mb-1">Engine</div>
                    <div class="text-sm text-light-100 font-mono">Piper v1.2.0</div>
                </div>
                <div class="border border-light-600 bg-light-800 p-3 rounded-md shadow-sm">
                    <div class="text-xs text-light-400 mb-1">Response Time</div>
                    <div class="text-sm text-light-100 font-mono">~47ms/char</div>
                </div>
                <div class="border border-light-600 bg-light-800 p-3 rounded-md shadow-sm">
                    <div class="text-xs text-light-400 mb-1">WASM Status</div>
                    <div class="flex items-center">
                        <span class="h-2 w-2 bg-success rounded-full mr-1.5"></span>
                        <span class="text-sm text-light-100 font-mono">Loaded</span>
                    </div>
                </div>
            </div>

            <!-- Output Area -->
            <div id="player-container" class="hidden border border-light-600 bg-light-900 rounded-md overflow-hidden mb-6 shadow-sm">
                <div class="p-4 border-b border-light-600 bg-light-800 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-success mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm font-medium text-light-100">Generated Audio</span>
                    </div>
                    <div class="text-xs text-light-400" id="audio-duration">00:00 / MP3 · 0.0MB</div>
                </div>
                <div class="p-4">
                    <audio controls id="audio-player" class="w-full h-10 mb-4"></audio>
                    <div class="flex justify-between">
                        <div class="text-xs text-light-400 flex items-center">
                            <span class="inline-block w-3 h-3 rounded-full bg-success mr-1.5"></span>
                            <span>Processing completed in <span class="font-mono">243ms</span></span>
                        </div>
                        <a href="#" id="download-btn" class="px-4 py-1.5 bg-success hover:bg-green-600 text-white text-sm font-medium rounded-md transition-colors duration-200 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download MP3
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Log -->
            <div class="border border-light-600 bg-light-900 rounded-md overflow-hidden shadow-sm">
                <div class="p-4 border-b border-light-600 bg-light-800 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-sm font-medium text-light-100">System Log</span>
                    </div>
                    <button class="text-xs text-light-400 hover:text-light-300 transition-colors">Clear</button>
                </div>
                <div class="terminal text-xs leading-5 h-36 overflow-y-auto">
                    <div class="log-system">[System] NaturalTTS Engine initialized</div>
                    <div class="log-info">[Info] Loading WASM module (127MB)...</div>
                    <div class="log-success">[Success] WASM module loaded in 2341ms</div>
                    <div class="log-info">[Info] Initializing audio context...</div>
                    <div class="log-success">[Success] Audio context ready</div>
                    <div class="log-info">[Info] Loading voice models...</div>
                    <div class="log-success">[Success] en_US_female model loaded (42MB)</div>
                    <div class="log-success">[Success] en_US_male model loaded (38MB)</div>
                    <div class="log-success">[Success] Additional voice models available on demand</div>
                    <div class="log-system">[System] Engine ready - Waiting for input</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-8 md:py-12 border-t border-light-700 bg-light-900">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 class="text-xl font-semibold text-light-100 mb-6">Features</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="border border-light-600 bg-white p-4 rounded-md shadow-sm">
                    <div class="flex items-start mb-2">
                        <span class="text-primary-500 mr-2">🔒</span>
                        <h3 class="text-light-100 font-medium">Privacy-First Design</h3>
                    </div>
                    <p class="text-light-400 text-sm">All processing happens in your browser. Your text never leaves your device, ensuring complete privacy.</p>
                </div>
                <div class="border border-light-600 bg-white p-4 rounded-md shadow-sm">
                    <div class="flex items-start mb-2">
                        <span class="text-primary-500 mr-2">🧠</span>
                        <h3 class="text-light-100 font-medium">Neural TTS Engine</h3>
                    </div>
                    <p class="text-light-400 text-sm">Based on Piper, a state-of-the-art neural text-to-speech synthesis system for natural voices.</p>
                </div>
                <div class="border border-light-600 bg-white p-4 rounded-md shadow-sm">
                    <div class="flex items-start mb-2">
                        <span class="text-primary-500 mr-2">🌍</span>
                        <h3 class="text-light-100 font-medium">Multiple Languages</h3>
                    </div>
                    <p class="text-light-400 text-sm">Support for 12 languages and over 20 voice options to suit global content needs.</p>
                </div>
                <div class="border border-light-600 bg-white p-4 rounded-md shadow-sm">
                    <div class="flex items-start mb-2">
                        <span class="text-primary-500 mr-2">⚡</span>
                        <h3 class="text-light-100 font-medium">High Performance</h3>
                    </div>
                    <p class="text-light-400 text-sm">Optimized WebAssembly runtime delivers fast processing with an average of ~47ms per character.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Voices Section -->
    <section id="voices" class="py-8 md:py-12 border-t border-light-700">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 class="text-xl font-semibold text-light-100 mb-6">Supported Voices</h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                <?php
                // Array of supported languages
                $languages = [
                    ['flag' => '🇺🇸', 'name' => 'English (US)'],
                    ['flag' => '🇬🇧', 'name' => 'English (UK)'],
                    ['flag' => '🇪🇸', 'name' => 'Spanish'],
                    ['flag' => '🇫🇷', 'name' => 'French'],
                    ['flag' => '🇩🇪', 'name' => 'German'],
                    ['flag' => '🇮🇹', 'name' => 'Italian'],
                    ['flag' => '🇯🇵', 'name' => 'Japanese'],
                    ['flag' => '🇨🇳', 'name' => 'Chinese']
                ];

                // Loop through languages and generate voice boxes
                foreach ($languages as $language) {
                    echo '<div class="border border-light-600 bg-white p-3 rounded-md text-center shadow-sm">
                        <div class="text-xl mb-1">' . $language['flag'] . '</div>
                        <div class="text-light-100 text-sm">' . $language['name'] . '</div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-8 md:py-12 border-t border-light-700 bg-light-900">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 class="text-xl font-semibold text-light-100 mb-6">FAQ</h2>

            <div class="space-y-4">
                <?php
                // Array of FAQ items
                $faqItems = [
                    [
                        'question' => 'How does this tool work?',
                        'answer' => '<p>NaturalTTS uses Piper, a neural speech synthesis system that converts text into speech. Everything runs directly in your browser using WebAssembly, which means your text never leaves your device.</p>
                        <p class="mt-2">The system processes your text, analyzes linguistic features, and generates natural-sounding speech that can be played back instantly or downloaded as an MP3 file.</p>'
                    ],
                    [
                        'question' => 'Is there a text limit?',
                        'answer' => '<p>Yes, you can convert up to 5,000 characters at once (approximately 750-1000 words). This limit helps ensure optimal performance in the browser environment.</p>
                        <p class="mt-2">For longer texts, we recommend breaking them into smaller chunks and processing them sequentially.</p>'
                    ],
                    [
                        'question' => 'What file format is the audio downloaded in?',
                        'answer' => '<p>All generated audio is available in MP3 format, which offers excellent audio quality with small file sizes. MP3 files are compatible with virtually all devices and media players.</p>'
                    ],
                    [
                        'question' => 'Is my data private?',
                        'answer' => '<p>Yes, completely. All processing happens locally in your browser, and no data is sent to any server. Your text never leaves your device, ensuring 100% privacy and security.</p>'
                    ]
                ];

                // Loop through FAQ items
                foreach ($faqItems as $item) {
                    echo '<div class="border border-light-600 bg-white rounded-md overflow-hidden shadow-sm">
                        <button class="w-full text-left p-4 flex justify-between items-center focus:outline-none faq-question">
                            <span class="text-light-100 font-medium">' . $item['question'] . '</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-light-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="p-4 pt-0 text-light-400 text-sm hidden faq-answer">
                            ' . $item['answer'] . '
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer partial
include_once('partials/footer.php');
?>