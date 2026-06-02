<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Выберите дверь</h2>
        <p class="text-gray-500 mt-1 text-sm">
          <span v-if="!selectedLeaf">Шаг 1 — выберите количество створок</span>
          <span v-else>Шаг 2 — выберите модель двери ({{ LEAF_LABELS[selectedLeaf] }})</span>
        </p>
      </div>
      <button @click="emit('back')" class="text-sm text-gray-500 hover:text-gray-700">← Назад</button>
    </div>

    <div v-if="loading" class="flex justify-center py-20">
      <svg class="w-10 h-10 animate-spin text-[#2e5f99]" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
      </svg>
    </div>

    <div v-else>

      <!-- ШАГ 1: ВЫБОР КОЛИЧЕСТВА СТВОРОК -->
      <div v-if="!selectedLeaf" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <button v-for="leaf in availableLeafs" :key="leaf.type"
          @click="selectedLeaf = leaf.type"
          class="flex flex-col items-center gap-4 p-6 rounded-2xl border-2 border-gray-100 bg-white hover:border-[#2e5f99] hover:shadow-lg transition-all group"
        >
          <!-- Иконка створок -->
          <div class="flex gap-1 items-end h-16">
            <div v-for="n in leaf.count" :key="n"
              class="rounded-sm border-2 border-gray-300 bg-gray-100 group-hover:border-[#2e5f99] group-hover:bg-[#2e5f99]/10 transition-all"
              :class="leaf.count === 1 ? 'w-10 h-16' : leaf.count === 2 ? 'w-8 h-14' : 'w-6 h-12'"
            />
          </div>
          <div class="text-center">
            <p class="font-bold text-gray-900 text-base group-hover:text-[#2e5f99]">{{ leaf.label }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ leaf.count }} {{ leaf.count === 1 ? 'створка' : 'створки' }}</p>
            <p class="text-xs text-gray-300 mt-1">{{ gatesByLeaf(leaf.type).length }} моделей</p>
          </div>
        </button>
      </div>

      <!-- ШАГ 2: ВЫБОР МОДЕЛИ ДВЕРИ -->
      <div v-else>

        <!-- Назад к выбору створок -->
        <button @click="selectedLeaf = null"
          class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#2e5f99] mb-5 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Изменить количество створок
        </button>

        <!-- Фильтры -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 mb-5 space-y-3">
          <div v-if="categories.length" class="flex flex-wrap gap-2">
            <button @click="activeCategory = null"
              class="px-3 py-1 rounded-full text-xs border transition-all"
              :class="!activeCategory ? 'bg-gray-800 text-white border-gray-800' : 'border-gray-200 text-gray-500 hover:border-gray-400'"
            >Все</button>
            <button v-for="cat in categories" :key="cat.id" @click="activeCategory = cat.id"
              class="px-3 py-1 rounded-full text-xs border transition-all"
              :class="activeCategory === cat.id ? 'bg-gray-800 text-white border-gray-800' : 'border-gray-200 text-gray-500 hover:border-gray-400'"
            >{{ cat.name }}</button>
          </div>
          <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input v-model="search" type="text" placeholder="Поиск по названию..."
              class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#2e5f99] focus:ring-1 focus:ring-[#2e5f99]"/>
          </div>
        </div>

        <!-- Сетка дверей -->
        <div v-if="!filteredGates.length" class="py-16 text-center text-gray-400 text-sm">
          Нет моделей для выбранного типа створок
        </div>
        <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
          <button v-for="gate in filteredGates" :key="gate.id"
            @click="emit('selected', { gate })"
            class="text-left rounded-2xl border-2 border-gray-100 overflow-hidden transition-all hover:border-[#2e5f99] hover:shadow-md group"
          >
            <div class="aspect-3/4 bg-gray-50">
              <img v-if="gate.image_url" :src="gate.image_url" :alt="gate.name"
                class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300"/>
              <div v-else class="w-full h-full flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M4 5a1 1 0 011-1h4a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v14a1 1 0 01-1 1h-4a1 1 0 01-1-1V5z"/>
                </svg>
              </div>
            </div>
            <div class="p-3">
              <p class="text-sm font-bold text-gray-900">{{ gate.name }}</p>
              <p class="text-xs text-gray-400 mt-0.5">{{ LEAF_LABELS[gate.leaf_type] }}</p>
            </div>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const emit = defineEmits(['selected', 'back']);

const loading    = ref(true);
const gates      = ref([]);
const categories = ref([]);

const selectedLeaf   = ref(null);
const activeCategory = ref(null);
const search         = ref('');

const LEAF_LABELS = {
  single: 'Одностворчатая',
  double: 'Двустворчатая',
  triple: 'Трёхстворчатая',
};

const LEAF_DEFS = [
  { type: 'single', label: 'Одностворчатая', count: 1 },
  { type: 'double', label: 'Двустворчатая',  count: 2 },
  { type: 'triple', label: 'Трёхстворчатая', count: 3 },
];

onMounted(async () => {
  try {
    const [gateRes, catRes] = await Promise.all([
      axios.get('/api/gates', { params: { type: 'gate' } }),
      axios.get('/api/categories'),
    ]);
    gates.value      = gateRes.data;
    categories.value = catRes.data;
  } finally {
    loading.value = false;
  }
});

// Только типы у которых есть реальные двери
const availableLeafs = computed(() =>
  LEAF_DEFS.filter(l => gates.value.some(g => g.leaf_type === l.type))
);

const gatesByLeaf = (type) => gates.value.filter(g => g.leaf_type === type);

const filteredGates = computed(() => {
  let list = gates.value.filter(g => g.leaf_type === selectedLeaf.value);
  if (activeCategory.value) {
    list = list.filter(g => g.category_id === activeCategory.value);
  }
  if (search.value.trim()) {
    list = list.filter(g => g.name.toLowerCase().includes(search.value.toLowerCase()));
  }
  return list;
});
</script>