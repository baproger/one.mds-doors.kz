<template>
  <div class="max-w-md mx-auto text-center py-12">
    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
      <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
    </div>

    <h2 class="text-3xl font-bold text-gray-900 mb-3">Заявка принята!</h2>
    <p class="text-gray-500 mb-2">Спасибо за ваш интерес к дверям MDS DOORS.</p>
    <p class="text-gray-500 mb-8">Наш менеджер свяжется с вами в ближайшее время.</p>

    <div class="bg-[#eef2f5] border border-[#2e5f99]/25 rounded-2xl p-5 mb-8 text-left">
      <p class="text-sm font-semibold text-[#1e3f66] mb-2">Что дальше?</p>
      <ul class="space-y-1.5 text-sm text-[#2e5f99]">
        <li class="flex items-start gap-2">
          <span class="mt-0.5">✓</span>
          Менеджер обработает вашу заявку
        </li>
        <li class="flex items-start gap-2">
          <span class="mt-0.5">✓</span>
          Рассчитает стоимость и сроки установки
        </li>
        <li class="flex items-start gap-2">
          <span class="mt-0.5">✓</span>
          Свяжется с вами по телефону или email
        </li>
      </ul>
    </div>

    <button
      @click="emit('restart')"
      class="px-8 py-3 bg-[#2e5f99] hover:bg-[#265285] text-white font-semibold rounded-xl transition-all"
    >
      Создать новую визуализацию
    </button>
  </div>
</template>

<script setup>
const emit = defineEmits(['restart']);
</script>
