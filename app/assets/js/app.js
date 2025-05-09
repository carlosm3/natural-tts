// assets/js/app.js
import { tts } from './common.js';

// Explicitly assign to window to make it globally available
document.addEventListener('DOMContentLoaded', () => {
  // Make sure Alpine.js can find the app function
  window.app = function() {
  return {
    // ────── state ──────
    mobileMenuOpen: false,
    textInput: '',
    charCount: 0,
    voices: [],          // filled at init()
    voiceId: '',
    speed: '1.0',
    filename: 'output',
    processing: false,
    audioGenerated: false,
    audioUrl: '',
    audioDuration: '',
    logs: '<div class="log-system">[System] Initializing…</div>',
    openFaqs: [],
    
    // Add the missing data arrays
    languages: [
      { name: 'English', flag: '🇺🇸' },
      { name: 'Spanish', flag: '🇪🇸' },
      { name: 'French', flag: '🇫🇷' },
      { name: 'German', flag: '🇩🇪' },
      { name: 'Italian', flag: '🇮🇹' },
      { name: 'Japanese', flag: '🇯🇵' },
      { name: 'Chinese', flag: '🇨🇳' },
      { name: 'Russian', flag: '🇷🇺' }
    ],
    
    faqItems: [
      {
        question: 'How does browser-based TTS work?',
        answer: 'NaturalTTS works entirely in your browser using WebAssembly to run the neural TTS engine locally. Your text never leaves your device, ensuring complete privacy and eliminating server round-trips.'
      },
      {
        question: 'What are the system requirements?',
        answer: 'Any modern browser that supports WebAssembly and Web Audio API should work. This includes recent versions of Chrome, Firefox, Safari, and Edge. A decent internet connection is needed for the initial model download.'
      },
      {
        question: 'How large are the voice models?',
        answer: 'Voice models are typically around 50-100MB each. They are downloaded once and then cached in your browser, so subsequent visits will be faster.'
      },
      {
        question: 'Can I use this offline?',
        answer: 'Yes! Once you\'ve downloaded a voice model, it will be cached in your browser. You can then use it offline for as long as your browser cache remains intact.'
      }
    ],

    // ────── life-cycle ──────
    async init() {
      this.addLog('info', 'Fetching voice list…');
      try {
        this.voices = await tts.voices();          // remote list
        this.voiceId = this.voices[0] || '';
        this.addLog('success', `Loaded ${this.voices.length} voices`);
      } catch (e) {
        this.addLog('error', `Failed to load voices: ${e.message}`);
      }
    },

    // ────── UI helpers ──────
    updateCharCount() {
      this.charCount = this.textInput.length;
    },
    loadSample() {
      this.textInput =
        'Welcome to NaturalTTS – a pure-browser neural speech demo. ' +
        'Everything you hear is synthesized locally via WebAssembly!';
      this.updateCharCount();
      this.addLog('info', 'Sample text loaded');
    },

    // ────── main action ──────
    async generateSpeech() {
      if (!this.textInput.trim()) {
        this.addLog('warning', 'No text entered');
        return;
      }
      this.processing = true;
      const vid = this.voiceId;
      const speed = parseFloat(this.speed);

      try {
        // download model if first time
        if (!(await tts.stored()).includes(vid)) {
          this.addLog('info', `Downloading ${vid} …`);
          await tts.download(vid, p =>
            this.addLog('info', `${vid} ${Math.floor(p.loaded / p.total * 100)}%`)
          );
          this.addLog('success', `${vid} cached`);
        }

        // synthesize
        this.addLog('info', 'Synthesizing…');
        const wav = await tts.predict({ text: this.textInput, voiceId: vid, speed });

        // play & expose link
        const blob = new Blob([wav], { type: 'audio/wav' });
        this.audioUrl = URL.createObjectURL(blob);
        this.audioGenerated = true;
        this.audioDuration = `${(blob.size / 1024 / 1024).toFixed(1)} MB WAV`;
        document.getElementById('audio-player').src = this.audioUrl;

        this.addLog('success', 'Audio ready – playing');
        document.getElementById('audio-player').play();
      } catch (e) {
        this.addLog('error', e.message);
      } finally {
        this.processing = false;
      }
    },

    // ────── misc ──────
    addLog(type, msg) {
      this.logs += `<div class="log-${type}">[${type.toUpperCase()}] ${msg}</div>`;
      this.$nextTick(() => {
        const term = document.getElementById('terminal');
        if (term) term.scrollTop = term.scrollHeight;
      });
    },
    clearLogs() {
      this.logs = '<div class="log-system">[System] Logs cleared</div>';
    },
    toggleFaq(i) {
      this.openFaqs.includes(i)
        ? (this.openFaqs = this.openFaqs.filter(k => k !== i))
        : this.openFaqs.push(i);
    }
  };
};
});