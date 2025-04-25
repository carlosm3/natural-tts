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
	const sampleAudio =
		'https://cdn.plyr.io/static/demo/Kishi_Bashi_-_Its_Christmas_But_Its_Not_White_Here_in_Our_Town.mp3';

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

<section class="py-12 text-center md:py-16 lg:py-20">
	<div class="container mx-auto max-w-7xl px-6">
		<h1 class="mb-5 text-3xl font-bold md:mb-6 md:text-4xl lg:mb-7 lg:text-5xl">
			<span class="text-primary">Text to Speech</span> Conversion
		</h1>
		<p class="mx-auto mb-10 max-w-2xl text-base leading-relaxed text-gray-600 md:text-lg">
			A simple browser-based tool using Piper to convert text to speech. No login required, works
			entirely in your browser.
		</p>
		<div
			class="mb-6 rounded-lg border border-gray-300 bg-white p-6 shadow-2xl md:p-8"
			style="box-shadow: -8px 8px 32px 0 rgba(30,41,59,0.16), 2px 2px 8px 0 rgba(0,0,0,0.08);"
		>
			<div class="relative mb-6">
				<textarea
					bind:value={text}
					placeholder="Enter your text here..."
					class="focus:ring-primary focus:border-primary min-h-[150px] w-full resize-y rounded-md border border-gray-200 p-4 text-base transition outline-none focus:ring-2 md:min-h-[200px]"
					maxlength={maxChars}
				></textarea>
				<span class="absolute right-2 bottom-2 text-sm text-gray-500">
					{charCount} / {maxChars}
				</span>
				{#if text.length > 0}
					<button
						type="button"
						class="absolute top-2 right-2 cursor-pointer border-none bg-transparent text-gray-500"
						on:click={clearText}>✕</button
					>
				{/if}
			</div>
			<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
				<div class="flex flex-col">
					<label for="voice" class="mb-2 font-medium">Voice:</label>
					<select
						id="voice"
						bind:value={voice}
						class="focus:ring-primary focus:border-primary rounded-md border border-gray-200 p-2 outline-none focus:ring-2"
					>
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
					<label for="speed" class="mb-2 font-medium">Speed:</label>
					<select
						id="speed"
						bind:value={speed}
						class="focus:ring-primary focus:border-primary rounded-md border border-gray-200 p-2 outline-none focus:ring-2"
					>
						<option value="0.8">Slow</option>
						<option value="1.0">Normal</option>
						<option value="1.2">Fast</option>
						<option value="1.5">Very Fast</option>
					</select>
				</div>
				<div class="flex flex-col">
					<label for="filename" class="mb-2 font-medium">File Name:</label>
					<input
						type="text"
						id="filename"
						bind:value={filename}
						class="focus:ring-primary focus:border-primary rounded-md border border-gray-200 p-2 outline-none focus:ring-2"
					/>
				</div>
			</div>
			<button
				type="button"
				class="bg-primary hover:bg-primary-dark w-full rounded-md py-3 font-medium text-white transition-colors"
				on:click={generateSpeech}
				disabled={generating}
			>
				{generating ? 'Generating...' : 'Generate Speech'}
			</button>
			{#if showPlayer}
				<div class="mt-8">
					<audio controls class="w-full" src={audioSrc}></audio>
					<a
						class="bg-primary hover:bg-primary-dark mt-4 inline-flex items-center rounded-md px-6 py-3 font-medium text-white transition-colors"
						download={filename + '.mp3'}
						href={audioSrc}
					>
						<span class="mr-2">⬇️</span> Download MP3
					</a>
				</div>
			{/if}
		</div>
	</div>
</section>
