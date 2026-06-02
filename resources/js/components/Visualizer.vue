<template>
  <div>
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Визуализация</h2>
        <p class="text-gray-500 mt-0.5 text-sm">Перемещайте, масштабируйте и вращайте дверь</p>
      </div>
      <button @click="emit('back')" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
        ← Назад
      </button>
    </div>

    <div class="flex flex-col xl:flex-row gap-6">
      <!-- Canvas -->
      <div class="flex-1 min-w-0">
        <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
          <div ref="canvasWrapper" class="relative flex items-center justify-center" style="min-height: 400px;">
            <canvas ref="canvasEl" />
          </div>
        </div>
      </div>

      <!-- Controls -->
      <div class="xl:w-72 space-y-3">
        <ObjectControls
          label="Дверь"
          :item="gate"
          :opacity="gateOpacity"
          :active-color="gateColor"
          :is-active="true"
          @opacity="v => { gateOpacity = v; applyOpacity(v); }"
          @color="v => applyColor(v)"
          @flip="flipDoor"
          @reset="resetDoor"
        />

        <button
          @click="saveResult"
          :disabled="saving"
          class="w-full py-4 bg-[#2e5f99] hover:bg-[#265285] disabled:bg-[#8aafd1] text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2"
        >
          <svg v-if="saving" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
          </svg>
          {{ saving ? 'Сохраняем...' : 'Сохранить и продолжить →' }}
        </button>

        <button
          @click="downloadImage"
          class="w-full py-3 border-2 border-gray-200 text-gray-600 hover:border-[#2e5f99] hover:text-[#2e5f99] font-medium rounded-xl transition-all text-sm"
        >
          Скачать изображение
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Canvas, FabricImage, filters as fabricFilters } from 'fabric';
import axios from 'axios';
import ObjectControls from './ObjectControls.vue';

const props = defineProps({ project: Object, gate: Object });
const emit  = defineEmits(['saved', 'back']);

const canvasEl      = ref(null);
const canvasWrapper = ref(null);
let fabricCanvas    = null;
let doorObj         = null;

const gateOpacity = ref(100);
const gateColor   = ref(null);
const saving      = ref(false);

onMounted(async () => { await initCanvas(); });
onUnmounted(() => { fabricCanvas?.dispose(); });

async function initCanvas() {
  const wrapper  = canvasWrapper.value;
  const maxWidth = Math.min(wrapper.clientWidth || 800, 900);

  const bgImg      = await FabricImage.fromURL(props.project.source_image_url, { crossOrigin: 'anonymous' });
  const aspectRatio = bgImg.height / bgImg.width;
  const canvasWidth  = maxWidth;
  const canvasHeight = Math.min(Math.round(maxWidth * aspectRatio), 620);

  fabricCanvas = new Canvas(canvasEl.value, { width: canvasWidth, height: canvasHeight, selection: false });

  bgImg.set({
    scaleX: canvasWidth / bgImg.width,
    scaleY: canvasHeight / bgImg.height,
    left: 0, top: 0,
    selectable: false, evented: false,
    originX: 'left', originY: 'top',
  });
  fabricCanvas.add(bgImg);

  if (props.gate?.image_url) {
    await loadDoor();
  }

  fabricCanvas.renderAll();
}

async function loadDoor() {
  const img  = await FabricImage.fromURL(props.gate.image_url, { crossOrigin: 'anonymous' });
  const maxW = fabricCanvas.width * 0.55;
  const maxH = fabricCanvas.height * 0.7;
  const scale = Math.min(maxW / img.width, maxH / img.height);

  img.set({
    scaleX: scale, scaleY: scale,
    left: fabricCanvas.width * 0.1,
    top:  fabricCanvas.height * 0.2,
    cornerColor: '#f59e0b', cornerStyle: 'circle',
    borderColor: '#f59e0b', transparentCorners: false,
  });

  fabricCanvas.add(img);
  doorObj = img;
  fabricCanvas.setActiveObject(img);
}

function applyOpacity(value) {
  if (!doorObj) return;
  doorObj.set('opacity', value / 100);
  fabricCanvas.renderAll();
}

function applyColor(hex) {
  if (!doorObj) return;
  gateColor.value = hex;

  if (hex === null) {
    doorObj.set('filters', []);
  } else {
    const r  = parseInt(hex.slice(1, 3), 16) / 255;
    const g  = parseInt(hex.slice(3, 5), 16) / 255;
    const b  = parseInt(hex.slice(5, 7), 16) / 255;
    const lr = 0.299, lg = 0.587, lb = 0.114;
    doorObj.set('filters', [new fabricFilters.ColorMatrix({
      matrix: [
        lr*r, lg*r, lb*r, 0, 0,
        lr*g, lg*g, lb*g, 0, 0,
        lr*b, lg*b, lb*b, 0, 0,
        0,    0,    0,    1, 0,
      ],
    })]);
    doorObj.applyFilters();
  }
  fabricCanvas.renderAll();
}

function flipDoor() {
  if (!doorObj) return;
  doorObj.set('flipX', !doorObj.flipX);
  fabricCanvas.renderAll();
}

function resetDoor() {
  if (!doorObj) return;
  const maxW  = fabricCanvas.width * 0.55;
  const maxH  = fabricCanvas.height * 0.7;
  const scale = Math.min(maxW / doorObj.width, maxH / doorObj.height);

  doorObj.set({
    scaleX: scale, scaleY: scale,
    left: fabricCanvas.width * 0.1,
    top:  fabricCanvas.height * 0.2,
    angle: 0, flipX: false, opacity: 1, filters: [],
  });
  doorObj.applyFilters();
  doorObj.setCoords();
  gateOpacity.value = 100;
  gateColor.value   = null;
  fabricCanvas.setActiveObject(doorObj);
  fabricCanvas.renderAll();
}

async function saveResult() {
  saving.value = true;
  try {
    fabricCanvas.discardActiveObject();
    fabricCanvas.renderAll();
    const dataUrl = fabricCanvas.toDataURL({ format: 'png', quality: 0.95 });
    const { data } = await axios.post(`/api/projects/${props.project.id}/result`, { image: dataUrl });
    emit('saved', data);
  } catch {
    alert('Ошибка сохранения. Попробуйте снова.');
  } finally {
    saving.value = false;
  }
}

function downloadImage() {
  fabricCanvas.discardActiveObject();
  fabricCanvas.renderAll();
  const dataUrl = fabricCanvas.toDataURL({ format: 'png', quality: 0.95 });
  const link = document.createElement('a');
  link.download = `mds-doors-${Date.now()}.png`;
  link.href = dataUrl;
  link.click();
}
</script>
