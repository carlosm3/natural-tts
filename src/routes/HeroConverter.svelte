<script>
  import { onMount } from 'svelte';
  let text = '';
  let charCount = 0;
  let voice = 'en_US_female';
  let speed = '1.0';
  let filename = 'speech';
  let showPlayer = false;
  let audioSrc = '';
  let generating = false;

  // For demo: use sample audio
  const sampleAudio = 'https://cdn.plyr.io/static/demo/Kishi_Bashi_-_Its_Christmas_But_Its_Not_White_Here_in_Our_Town.mp3';

  $: charCount = text.length;
  const maxChars = 5000;

  function clearText() {
    text = '';
  }

  async function generateSpeech() {
    if (text.trim() === '') {
      alert('Please enter some text to convert to speech.');
      return;
    }
    generating = true;
    // Simulate API delay
    setTimeout(() => {
      audioSrc = sampleAudio;
      showPlayer = true;
      generating = false;
    }, 1500);
  }
</script>

<section class="py-12 md:py-16 lg:py-20 text-center">
  <div class="container mx-auto px-6 max-w-7xl">
    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-5 md:mb-6 lg:mb-7">
      <span class="text-primary">Text to Speech</span> Conversion
    </h1>
    <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto mb-10 leading-relaxed">
      A simple browser-based tool using Piper to convert text to speech. No login required, works entirely in your browser.
    </p>
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8 mb-6">
      <div class="relative mb-6">
        <textarea
          bind:value={text}
          placeholder="Enter your text here..."
          class="w-full min-h-[150px] md:min-h-[200px] p-4 border border-gray-200 rounded-md text-base resize-y focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
          maxlength={maxChars}
        ></textarea>
        <span class="absolute bottom-2 right-2 text-sm text-gray-500">
          {charCount} / {maxChars}
        </span>
        {#if text.length > 0}
          <button type="button" class="absolute top-2 right-2 text-gray-500 border-none bg-transparent cursor-pointer" on:click={clearText}>✕</button>
        {/if}
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="flex flex-col">
          <label for="voice" class="font-medium mb-2">Voice:</label>
          <select id="voice" bind:value={voice} class="p-2 border border-gray-200 rounded-md focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            <option value="en_US_female">English (US) - Female</option>
            <option value="en_US_male">English (US) - Male</option>
            <option value="en_UK_female">English (UK) - Female</option>
            <option value="en_UK_male">English (UK) - Male</option>
            <option value="es_female">Spanish - Female</option>
            <option value="fr_female">French - Female</option>
            <option value="de_female">German - Female</option>
            <option value="it_female">Italian - Female</option>
            <option value="ja_female">Japanese - Female</option>
          </select>
        </div>
        <div class="flex flex-col">
          <label for="speed" class="font-medium mb-2">Speed:</label>
          <select id="speed" bind:value={speed} class="p-2 border border-gray-200 rounded-md focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            <option value="0.8">Slow</option>
            <option value="1.0">Normal</option>
            <option value="1.2">Fast</option>
            <option value="1.5">Very Fast</option>
          </select>
        </div>
        <div class="flex flex-col">
          <label for="filename" class="font-medium mb-2">File Name:</label>
          <input type="text" id="filename" bind:value={filename} class="p-2 border border-gray-200 rounded-md focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
      </div>
      <button type="button" class="w-full py-3 bg-primary text-white rounded-md font-medium hover:bg-primary-dark transition-colors" on:click={generateSpeech} disabled={generating}>
        {generating ? 'Generating...' : 'Generate Speech'}
      </button>
      {#if showPlayer}
        <div class="mt-8">
          <audio controls class="w-full" src={audioSrc}></audio>
          <a class="inline-flex items-center mt-4 py-3 px-6 bg-primary text-white rounded-md font-medium hover:bg-primary-dark transition-colors" download={filename + '.mp3'} href={audioSrc}>
            <span class="mr-2">⬇️</span> Download MP3
          </a>
        </div>
      {/if}
    </div>
  </div>
</section>
