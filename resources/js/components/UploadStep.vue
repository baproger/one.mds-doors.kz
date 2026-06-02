<template>
  <div class="max-w-2xl mx-auto">
    <div class="text-center mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Загрузите фото вашего дома</h2>
      <p class="text-gray-500 mt-2">Поддерживаются JPG, PNG, WebP до 10 МБ</p>
    </div>

    <!-- Инструкция -->
    <div class="mb-6 rounded-2xl overflow-hidden" style="border: 1px solid rgba(46,95,153,0.25); background: rgba(46,95,153,0.06);">
      <button
        @click="showTips = !showTips"
        class="w-full flex items-center justify-between px-5 py-3 text-left"
      >
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 shrink-0" style="color:#2e5f99" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span class="text-sm font-semibold" style="color:#2e5f99">Как правильно сфотографировать</span>
        </div>
        <svg
          class="w-4 h-4 transition-transform shrink-0"
          :class="showTips ? 'rotate-180' : ''"
          style="color:#2e5f99"
          fill="none" stroke="currentColor" viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>

      <div v-show="showTips" class="px-5 pb-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
          <div v-for="tip in tips" :key="tip.text"
            class="flex items-center gap-2 rounded-xl px-3 py-2"
            style="background: rgba(46,95,153,0.08); border: 1px solid rgba(46,95,153,0.15);"
          >
            <span class="text-base shrink-0">{{ tip.icon }}</span>
            <span class="text-xs text-gray-700">{{ tip.text }}</span>
          </div>
        </div>
      </div>
    </div>

    <div
      class="border-2 border-dashed rounded-2xl p-12 text-center transition-all cursor-pointer"
      :class="isDragging ? 'border-[#2e5f99] bg-[#eef2f5]' : 'border-gray-300 hover:border-[#2e5f99] hover:bg-[#eef2f5]/40'"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="onDrop"
      @click="$refs.fileInput.click()"
    >
      <input ref="fileInput" type="file" class="hidden" accept="image/*" @change="onFileChange" />

      <div v-if="!preview">
        <div class="w-20 h-20 bg-[#eef2f5] rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-10 h-10 text-[#2e5f99]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </div>
        <p class="text-lg font-medium text-gray-700">Перетащите фото сюда</p>
        <p class="text-sm text-gray-400 mt-1">или нажмите для выбора файла</p>
      </div>

      <div v-else class="relative">
        <img :src="preview" alt="Preview" class="max-h-72 mx-auto rounded-xl object-contain shadow" />
        <button
          @click.stop="clearFile"
          class="absolute top-2 right-2 bg-white rounded-full p-1.5 shadow-md text-gray-500 hover:text-red-500"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="error" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
      {{ error }}
    </div>

    <button
      v-if="selectedFile"
      @click="upload"
      :disabled="uploading"
      class="mt-6 w-full py-4 bg-[#2e5f99] hover:bg-[#265285] disabled:bg-[#8aafd1] text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2"
    >
      <svg v-if="uploading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
      </svg>
      <span>{{ uploading ? 'Загружаем...' : 'Продолжить →' }}</span>
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const showTips = ref(true);

const tips = [
  { icon: '📐', text: 'Снимайте прямо, не сбоку' },
  { icon: '📏', text: 'Держите камеру горизонтально' },
  { icon: '☀️', text: 'Снимайте при дневном свете' },
  { icon: '🚗', text: 'Уберите машины из кадра' },
  { icon: '🖼️', text: 'Весь проём должен быть виден' },
  { icon: '📱', text: 'Без зума — основная камера' },
];
import axios from 'axios';

const emit = defineEmits(['uploaded']);

const fileInput = ref(null);
const selectedFile = ref(null);
const preview = ref(null);
const isDragging = ref(false);
const uploading = ref(false);
const error = ref(null);

function onFileChange(e) {
  const file = e.target.files[0];
  if (file) setFile(file);
}

function onDrop(e) {
  isDragging.value = false;
  const file = e.dataTransfer.files[0];
  if (file) setFile(file);
}

function setFile(file) {
  error.value = null;
  if (!file.type.startsWith('image/')) {
    error.value = 'Пожалуйста, выберите изображение.';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    error.value = 'Файл слишком большой. Максимум 10 МБ.';
    return;
  }
  selectedFile.value = file;
  const reader = new FileReader();
  reader.onload = (e) => { preview.value = e.target.result; };
  reader.readAsDataURL(file);
}

function clearFile() {
  selectedFile.value = null;
  preview.value = null;
  if (fileInput.value) fileInput.value.value = '';
}

async function upload() {
  if (!selectedFile.value) return;
  uploading.value = true;
  error.value = null;

  const formData = new FormData();
  formData.append('house_photo', selectedFile.value);

  try {
    const { data } = await axios.post('/api/projects', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    emit('uploaded', data);
  } catch (e) {
    error.value = e.response?.data?.message || 'Ошибка загрузки. Попробуйте снова.';
  } finally {
    uploading.value = false;
  }
}
</script>
