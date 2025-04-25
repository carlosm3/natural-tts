<script lang="ts">
	// Define language type
	type Language = {
		name: string;
		id: string;
		sample: string;
	};

	// Define languages with their data
	const languages: Language[] = [
		{ name: 'English', id: 'en', sample: 'en_sample.mp3' },
		{ name: 'Spanish', id: 'es', sample: 'es_sample.mp3' },
		{ name: 'French', id: 'fr', sample: 'fr_sample.mp3' },
		{ name: 'German', id: 'de', sample: 'de_sample.mp3' },
		{ name: 'Italian', id: 'it', sample: 'it_sample.mp3' },
		{ name: 'Portuguese', id: 'pt', sample: 'pt_sample.mp3' },
		{ name: 'Russian', id: 'ru', sample: 'ru_sample.mp3' },
		{ name: 'Japanese', id: 'ja', sample: 'ja_sample.mp3' },
		{ name: 'Chinese', id: 'zh', sample: 'zh_sample.mp3' },
		{ name: 'Korean', id: 'ko', sample: 'ko_sample.mp3' },
		{ name: 'Arabic', id: 'ar', sample: 'ar_sample.mp3' },
		{ name: 'Hindi', id: 'hi', sample: 'hi_sample.mp3' }
	];

	// Variables for tracking playback state and audio element
	let currentlyPlaying: string | null = null;
	let audioElement: HTMLAudioElement | undefined;

	// Function to play audio sample
	function playSample(languageId: string): void {
		const language = languages.find(lang => lang.id === languageId);
		
		// If language not found, return early
		if (!language) {
			console.error(`Language with ID ${languageId} not found`);
			return;
		}

		// If an audio is already playing, stop it
		if (currentlyPlaying && audioElement) {
			audioElement.pause();
			audioElement.currentTime = 0;
			
			// If the same button was clicked, just stop playing and return
			if (currentlyPlaying === languageId) {
				currentlyPlaying = null;
				return;
			}
		}

		// Create or update audio element
		if (!audioElement) {
			audioElement = new Audio();
		}

		// Set the audio source
		audioElement.src = `/audio-samples/${language.sample}`;
		
		// Play the audio
		audioElement.play()
			.then(() => {
				currentlyPlaying = languageId;
			})
			.catch((error: Error) => {
				console.error('Error playing audio:', error);
				alert('Sorry, could not play the sample audio.');
				currentlyPlaying = null;
			});

		// Reset when audio finishes playing
		audioElement.onended = () => {
			currentlyPlaying = null;
		};
	}
</script>

<section id="languages" class="scroll-mt-20 bg-[var(--color-bg-dark)] py-12 md:py-16">
	<div class="container mx-auto max-w-7xl px-6">
		<div class="mb-8 text-center md:mb-10">
			<h2 class="mb-3 text-2xl font-bold md:text-3xl">Supported Voices</h2>
			<p class="text-gray-600">Available language options - click to hear samples</p>
		</div>
		<div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 md:gap-4 lg:grid-cols-6">
			{#each languages as language}
				<button
					class="rounded-md bg-white p-3 text-center shadow-sm hover:bg-gray-50 cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary transition-all duration-200 flex items-center justify-center gap-2 relative"
					on:click={() => playSample(language.id)}
					aria-label="Play {language.name} voice sample"
				>
					<span>{language.name}</span>
					<!-- Show speaker icon when not playing, show playing animation when playing -->
					{#if currentlyPlaying === language.id}
						<span class="inline-flex items-center">
							<span class="w-1 h-4 bg-primary rounded-full animate-sound-wave-1 mx-px"></span>
							<span class="w-1 h-3 bg-primary rounded-full animate-sound-wave-2 mx-px"></span>
							<span class="w-1 h-5 bg-primary rounded-full animate-sound-wave-3 mx-px"></span>
						</span>
					{:else}
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
						</svg>
					{/if}
				</button>
			{/each}
		</div>
	</div>
</section>

<style>
	@keyframes soundWave1 {
		0%, 100% { height: 4px; }
		50% { height: 12px; }
	}
	@keyframes soundWave2 {
		0%, 100% { height: 3px; }
		30% { height: 8px; }
		60% { height: 10px; }
	}
	@keyframes soundWave3 {
		0%, 100% { height: 5px; }
		40% { height: 15px; }
	}

	:global(.animate-sound-wave-1) {
		animation: soundWave1 0.8s infinite;
	}
	:global(.animate-sound-wave-2) {
		animation: soundWave2 0.9s infinite;
	}
	:global(.animate-sound-wave-3) {
		animation: soundWave3 0.7s infinite;
	}
</style>
