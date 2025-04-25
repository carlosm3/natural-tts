<script>
	let openIdx = null;
	const faqs = [
		{
			q: 'How does this tool work?',
			a: 'NaturalTTS uses Piper, a neural speech synthesis system that converts text into speech. Everything runs directly in your browser.'
		},
		{
			q: 'Is there a text limit?',
			a: 'Yes, you can convert up to 5,000 characters at once (approximately 750-1000 words).'
		},
		{
			q: 'What file format is the audio downloaded in?',
			a: 'MP3 format, which is compatible with most devices and media players.'
		},
		{
			q: 'Is my data private?',
			a: 'Yes, completely. All processing happens in your browser, and no data is sent to any server.'
		}
	];
	function toggle(idx) {
		openIdx = openIdx === idx ? null : idx;
	}
</script>

<section id="faq" class="scroll-mt-20 py-12 md:py-16">
	<div class="container mx-auto max-w-7xl px-6">
		<div class="mb-8 text-center md:mb-10">
			<h2 class="text-2xl font-bold md:text-3xl">FAQ</h2>
		</div>
		<div class="mx-auto max-w-3xl">
			{#each faqs as faq, idx}
				<div class="mb-4 border-b border-gray-200">
					<div
						class="faq-question flex cursor-pointer items-center justify-between py-4"
						on:click={() => toggle(idx)}
					>
						<span class="question-text font-medium">{faq.q}</span>
						<span class="indicator">{openIdx === idx ? '-' : '+'}</span>
					</div>
					<div class={`faq-answer pb-4 text-gray-600 ${openIdx === idx ? '' : 'hidden'}`}>
						{faq.a}
					</div>
				</div>
			{/each}
		</div>
	</div>
</section>
