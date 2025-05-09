// assets/js/install.js
import { tts } from './common.js';

window.app = function () {
  return {
    mobileMenuOpen: false,
    /* lists */
    available: [],
    installed: [],

    async init() {
      await this.refreshLists();
    },

    /* ────── helpers ────── */
    async refreshLists() {
      const all   = await tts.voices();
      const have  = await tts.stored();
      this.installed = have.map(id => ({ id }));
      this.available = all
        .filter(v => !have.includes(v))
        .map(id => ({ id }));
    },

    /* ────── actions ────── */
    async install(id) {
      await tts.download(id, p =>
        console.log(`DL ${id} ${Math.floor(p.loaded / p.total * 100)}%`)
      );
      await this.refreshLists();
    },
    async remove(id) {
      await tts.remove(id);
      await this.refreshLists();
    }
  };
};
