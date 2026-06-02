<template>
  <div
    class="bg-white rounded-2xl shadow border-2 transition-all p-4 cursor-pointer"
    :class="isActive ? 'border-[#2e5f99]' : 'border-gray-100'"
    @click="emit('activate')"
  >
    <!-- Header -->
    <div class="flex items-center gap-2 mb-3">
      <div class="w-2 h-2 rounded-full" :class="isActive ? 'bg-[#2e5f99]' : 'bg-gray-300'"/>
      <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ label }}</p>
      <span v-if="isActive" class="ml-auto text-xs text-[#2e5f99] font-medium">Активен</span>
    </div>

    <!-- Item preview -->
    <div class="flex items-center gap-3 mb-3">
      <img v-if="item.image_url" :src="item.image_url" :alt="item.name"
        class="w-12 h-12 object-contain rounded-lg bg-gray-50 p-1 border border-gray-100" />
      <div>
        <p class="font-semibold text-gray-900 text-sm">{{ item.name }}</p>
        <p class="text-xs text-gray-400">{{ item.category?.name }}</p>
      </div>
    </div>

    <!-- Color picker -->
    <div v-if="item.colors && Object.keys(item.colors).length" class="mb-3">
      <p class="text-xs text-gray-400 mb-2">Цвет</p>
      <div class="flex flex-wrap gap-1.5">
        <button
          v-for="(hex, name) in item.colors"
          :key="name"
          :title="name"
          :style="{ backgroundColor: hex }"
          @click.stop="emit('color', hex)"
          class="w-7 h-7 rounded-full border-2 transition-all hover:scale-110"
          :class="activeColor === hex ? 'border-gray-900 scale-110' : 'border-gray-200'"
        />
        <button
          title="Оригинал"
          @click.stop="emit('color', null)"
          class="w-7 h-7 rounded-full border-2 bg-white flex items-center justify-center hover:scale-110 transition-all"
          :class="activeColor === null ? 'border-gray-900' : 'border-gray-200'"
        >
          <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Opacity -->
    <div class="mb-3">
      <div class="flex items-center justify-between mb-1">
        <p class="text-xs text-gray-400">Прозрачность</p>
        <p class="text-xs text-gray-500">{{ opacity }}%</p>
      </div>
      <input
        type="range" min="10" max="100"
        :value="opacity"
        @input="emit('opacity', Number($event.target.value))"
        @click.stop
        class="w-full accent-[#2e5f99]"
      />
    </div>

    <!-- Buttons -->
    <div class="grid grid-cols-2 gap-1.5">
      <button
        @click.stop="emit('flip')"
        class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs text-gray-600 bg-gray-50 hover:bg-[#eef2f5] hover:text-[#2e5f99] rounded-lg border border-gray-200 hover:border-[#2e5f99]/50 transition-all"
      >
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
        </svg>
        Отразить
      </button>
      <button
        @click.stop="emit('reset')"
        class="flex items-center justify-center gap-1 px-2 py-1.5 text-xs text-gray-600 bg-gray-50 hover:bg-[#eef2f5] hover:text-[#2e5f99] rounded-lg border border-gray-200 hover:border-[#2e5f99]/50 transition-all"
      >
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Сбросить
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  item: Object,
  opacity: Number,
  activeColor: { type: String, default: null },
  isActive: Boolean,
});

const emit = defineEmits(['activate', 'opacity', 'color', 'flip', 'reset']);
</script>
