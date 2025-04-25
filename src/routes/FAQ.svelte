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

<section id="faq" class="py-12 md:py-16">
  <div class="container mx-auto px-6 max-w-7xl">
    <div class="text-center mb-8 md:mb-10">
      <h2 class="text-2xl md:text-3xl font-bold">FAQ</h2>
    </div>
    <div class="max-w-3xl mx-auto">
      {#each faqs as faq, idx}
        <div class="border-b border-gray-200 mb-4">
          <div class="flex justify-between items-center py-4 cursor-pointer faq-question" on:click={() => toggle(idx)}>
            <span class="font-medium question-text">{faq.q}</span>
            <span class="indicator">{openIdx === idx ? '-' : '+'}</span>
          </div>
          <div class={`pb-4 text-gray-600 faq-answer ${openIdx === idx ? '' : 'hidden'}`}>{faq.a}</div>
        </div>
      {/each}
    </div>
  </div>
</section>
