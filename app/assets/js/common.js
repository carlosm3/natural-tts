// assets/js/common.js
// ───────────────────
// 1-liner that loads the ESM build of Piper-TTS from jsDelivr
// and re-exports it so the rest of your code can `import { tts }`.

export * as tts from 'https://cdn.jsdelivr.net/npm/@mintplex-labs/piper-tts-web/dist/index.esm.js';
