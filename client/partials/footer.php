<!-- Footer -->
<footer class="py-6 border-t border-gray-700 bg-[#333]">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0 flex items-center">
                <span class="text-xl mr-1">🔊</span>
                <span class="text-white font-mono text-sm">NaturalTTS</span>
                <span class="text-gray-400 text-xs ml-2">v2.3.1</span>
            </div>
            <div class="flex items-center mb-4 md:mb-0">
                <span class="h-2 w-2 bg-success rounded-full mr-1.5"></span>
                <span class="text-xs text-gray-300">System Online</span>
            </div>
            <div class="flex flex-wrap justify-center text-center md:text-right">
                <a href="#" class="text-gray-400 hover:text-white text-xs mx-2 transition-colors">Contact</a>
                <a href="#" class="text-gray-400 hover:text-white text-xs mx-2 transition-colors">Privacy</a>
                <a href="#" class="text-gray-400 hover:text-white text-xs mx-2 transition-colors">Terms</a>
                <span class="text-gray-500 text-xs mx-2">© <?php echo date('Y'); ?></span>
            </div>
        </div>
        <div class="mt-4 text-center text-gray-400 text-xs">
            <span>Powered by Piper Neural TTS Engine · </span>
            <span>WebAssembly + Web Audio API · </span>
            <span>No data leaves your device</span>
        </div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Character counter
        const textInput = document.getElementById('text-input');
        const charCount = document.getElementById('char-count');
        const generateBtn = document.getElementById('generate-btn');
        const sampleBtn = document.getElementById('sample-btn');
        const playerContainer = document.getElementById('player-container');
        const audioPlayer = document.getElementById('audio-player');
        const downloadBtn = document.getElementById('download-btn');
        const audioDuration = document.getElementById('audio-duration');
        
        // Mobile menu elements
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuLinks = document.querySelectorAll('#mobile-menu a');
        
        // Mobile menu toggle
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Close mobile menu when clicking a link
        if (mobileMenuLinks.length > 0) {
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });
            });
        }
        
        // FAQ toggles
        const faqQuestions = document.querySelectorAll('.faq-question');

        // Update character count
        if (textInput) {
            textInput.addEventListener('input', function () {
                const length = this.value.length;
                charCount.textContent = length;

                // Visual feedback on character limit
                if (length > 4500) {
                    charCount.classList.add('text-warning');
                } else if (length > 4900) {
                    charCount.classList.remove('text-warning');
                    charCount.classList.add('text-error');
                } else {
                    charCount.classList.remove('text-warning', 'text-error');
                }
            });
        }

        // Try a sample
        if (sampleBtn) {
            sampleBtn.addEventListener('click', function () {
                textInput.value = "Welcome to NaturalTTS, a powerful text-to-speech converter that runs entirely in your browser. This sample demonstrates the natural-sounding voices our system can generate.";
                charCount.textContent = textInput.value.length;
                
                // Add log entry
                const terminal = document.querySelector('.terminal');
                if (terminal) {
                    terminal.innerHTML += '<div class="log-info">[Info] Sample text loaded</div>';
                    terminal.scrollTop = terminal.scrollHeight;
                }
            });
        }

        // Generate speech (mockup)
        if (generateBtn) {
            generateBtn.addEventListener('click', function () {
                if (textInput.value.trim() === '') {
                    // Add log entry
                    const terminal = document.querySelector('.terminal');
                    if (terminal) {
                        terminal.innerHTML += '<div class="log-warning">[Warning] No text entered</div>';
                        terminal.scrollTop = terminal.scrollHeight;
                    }
                    return;
                }

                // Show loading state
                this.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
                this.disabled = true;

                // Add log entries
                const terminal = document.querySelector('.terminal');
                if (terminal) {
                    terminal.innerHTML += '<div class="log-info">[Info] Processing text input ('+textInput.value.length+' chars)...</div>';
                    terminal.scrollTop = terminal.scrollHeight;
                    
                    // Add more entries with delays
                    setTimeout(() => {
                        terminal.innerHTML += '<div class="log-info">[Info] Normalizing text...</div>';
                        terminal.scrollTop = terminal.scrollHeight;
                    }, 300);
                    
                    setTimeout(() => {
                        terminal.innerHTML += '<div class="log-info">[Info] Generating phonemes...</div>';
                        terminal.scrollTop = terminal.scrollHeight;
                    }, 600);
                    
                    setTimeout(() => {
                        terminal.innerHTML += '<div class="log-info">[Info] Synthesizing audio...</div>';
                        terminal.scrollTop = terminal.scrollHeight;
                    }, 900);
                    
                    setTimeout(() => {
                        terminal.innerHTML += '<div class="log-success">[Success] Audio generated in 243ms</div>';
                        terminal.scrollTop = terminal.scrollHeight;
                    }, 1200);
                }

                // Simulate API call delay
                setTimeout(function () {
                    // Show the player
                    playerContainer.classList.remove('hidden');

                    // Set up the audio player (using a sample audio)
                    audioPlayer.src = 'https://cdn.plyr.io/static/demo/Kishi_Bashi_-_Its_Christmas_But_Its_Not_White_Here_in_Our_Town.mp3';

                    // Reset button
                    generateBtn.innerHTML = 'Generate Speech';
                    generateBtn.disabled = false;

                    // Set up download link
                    const filename = document.getElementById('filename').value || 'output';
                    downloadBtn.setAttribute('download', filename + '.mp3');
                    downloadBtn.setAttribute('href', audioPlayer.src);

                    // Set duration
                    audioDuration.textContent = "00:30 / MP3 · 1.2MB";

                    // Scroll to player
                    playerContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 1500);
            });
        }

        // FAQ accordion
        if (faqQuestions.length > 0) {
            faqQuestions.forEach(question => {
                question.addEventListener('click', function () {
                    const answer = this.nextElementSibling;
                    const icon = this.querySelector('svg');
                    
                    // Toggle the current answer
                    answer.classList.toggle('hidden');
                    
                    // Rotate icon when open
                    if (answer.classList.contains('hidden')) {
                        icon.style.transform = 'rotate(0deg)';
                    } else {
                        icon.style.transform = 'rotate(180deg)';
                    }
                });
            });
        }
    });
</script>
</body>
</html>