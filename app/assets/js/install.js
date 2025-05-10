// assets/js/install.js
import { tts } from './common.js';

// Voice repository details
const VOICE_REPO_URL = 'https://huggingface.co/api/models?search=rhasspy/piper-voices';

window.app = function () {
  return {
    mobileMenuOpen: false,
    /* lists */
    available: [],
    installed: [],
    /* status tracking */
    installing: null,
    progress: 0,
    logs: '<div class="log-system">[System] Voice Manager initialized...</div>',

    async init() {
      this.addLog('info', 'Loading voice lists...');
      await this.refreshLists();
      if (this.available.length === 0 && this.installed.length === 0) {
        // If both lists are empty, something might be wrong with the API
        this.addLog('warning', 'No voices found. Trying alternative method...');
        await this.fetchPiperVoicesList();
      }
    },

    /* ────── helpers ────── */
    async refreshLists() {
      try {
        const all = await tts.voices();
        const have = await tts.stored();
        
        // Format the data for better display
        this.installed = have.map(id => ({ 
          id,
          language: id.split('-')[0],
          speaker: id.split('-')[1],
          quality: id.split('-')[2] || 'standard'
        }));
        
        this.available = all
          .filter(v => !have.includes(v))
          .map(id => ({ 
            id,
            language: id.split('-')[0],
            speaker: id.split('-')[1],
            quality: id.split('-')[2] || 'standard'
          }));
          
        this.addLog('success', `Found ${this.available.length} available voices and ${this.installed.length} installed voices`);
      } catch (e) {
        this.addLog('error', `Failed to load voices: ${e.message}`);
        // If the tts.voices() fails, try our fallback method
        await this.fetchPiperVoicesList();
      }
    },
    
    async fetchPiperVoicesList() {
      this.addLog('info', 'Fetching Piper voices directly from repository...');
      
      try {
        // Pre-populated list of most common Piper voices as a fallback
        const commonVoices = [
          'en_US-lessac-medium',
          'en_US-libritts-high',
          'en_US-ryan-high',
          'en_US-amy-medium',
          'en_GB-alba-medium',
          'en_GB-jenny-medium',
          'es_ES-carlfm-medium',
          'fr_FR-siwis-medium',
          'de_DE-thorsten-medium',
          'it_IT-riccardo-medium',
          'nl_NL-rdh-medium'
        ];
        
        // Try to fetch from Hugging Face API first
        try {
          const response = await fetch(VOICE_REPO_URL);
          
          if (response.ok) {
            const data = await response.json();
            // Extract model IDs and filter for piper-voices
            const voiceModels = data.filter(model => 
              model.id.startsWith('rhasspy/piper-voices') && 
              !model.id.includes('app') && 
              !model.id.includes('engine')
            );
            
            if (voiceModels.length > 0) {
              // Extract voice names from model IDs
              const voiceIds = voiceModels.map(model => {
                const parts = model.id.split('/piper-voices-')[1];
                return parts ? parts.replace(/-/, '_') : null;
              }).filter(id => id !== null);
              
              this.available = voiceIds.map(id => ({
                id,
                language: id.split('_')[0] + '_' + id.split('_')[1],
                speaker: id.split('-')[1] || id.split('_')[2],
                quality: id.split('-')[2] || 'medium'
              }));
              
              this.addLog('success', `Found ${this.available.length} voices from repository`);
              return;
            }
          }
          throw new Error('Failed to parse voice repository data');
        } catch (repoError) {
          this.addLog('warning', `Repository fetch failed: ${repoError.message}`);
          throw repoError; // Continue to fallback
        }
      } catch (e) {
        // Use the pre-populated list as last resort
        this.addLog('info', 'Using pre-populated voice list as fallback');
        this.available = commonVoices.map(id => ({
          id,
          language: id.split('-')[0],
          speaker: id.split('-')[1],
          quality: id.split('-')[2] || 'medium'
        }));
        this.addLog('success', `Loaded ${this.available.length} common voices as fallback`);
      }
    },

    /* ────── actions ────── */
    async install(id) {
      if (this.installing) {
        this.addLog('warning', 'Already installing a voice. Please wait...');
        return;
      }
      
      this.installing = id;
      this.progress = 0;
      this.addLog('info', `Starting download of ${id}...`);
      
      try {
        await tts.download(id, p => {
          const percent = Math.floor(p.loaded / p.total * 100);
          this.progress = percent;
          
          // Only log on significant progress to avoid flooding
          if (percent % 20 === 0 || percent === 100) {
            this.addLog('info', `Downloading ${id}: ${percent}% (${Math.round(p.loaded / 1024 / 1024)}MB / ${Math.round(p.total / 1024 / 1024)}MB)`);
          }
        });
        
        this.addLog('success', `Voice "${id}" installed successfully`);
        await this.refreshLists();
      } catch (e) {
        this.addLog('error', `Failed to install ${id}: ${e.message}`);
      } finally {
        this.installing = null;
        this.progress = 0;
      }
    },
    
    async remove(id) {
      this.addLog('info', `Removing voice "${id}"...`);
      
      try {
        await tts.remove(id);
        this.addLog('success', `Voice "${id}" removed successfully`);
        await this.refreshLists();
      } catch (e) {
        this.addLog('error', `Failed to remove ${id}: ${e.message}`);
      }
    },
    
    /* ────── UI helpers ────── */
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
    
    // Helper to format voice IDs for display
    formatVoiceName(id) {
      const parts = id.split('-');
      if (parts.length < 2) return id;
      return `${parts[0]} - ${parts[1]}`;
    }
  };
};
