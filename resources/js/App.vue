<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <img
            :src="logoUrl"
            alt="MDS DOORS"
            class="h-16 w-auto object-contain"
            @error="logoError = true"
            v-if="!logoError"
          />
          <div v-if="logoError" class="flex items-center gap-2">
            <div class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-sm">MDS</span>
            </div>
            <div>
              <h1 class="text-xl font-bold text-gray-900">MDS DOORS</h1>
              <p class="text-xs text-gray-500">Визуализатор дверей</p>
            </div>
          </div>
        </div>
        <div class="text-sm text-gray-500 hidden sm:block">
          Загрузите фото → выберите дверь → получите результат
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
      <!-- Step indicator -->
      <div class="flex items-center justify-center mb-10">
        <div v-for="(step, i) in steps" :key="i" class="flex items-center">
          <div class="flex flex-col items-center">
            <div
              :class="[
                'w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all',
                currentStep > i ? 'bg-[#2e5f99] text-white' :
                currentStep === i ? 'bg-[#eef2f5] text-[#2e5f99] ring-2 ring-[#2e5f99]' :
                'bg-gray-100 text-gray-400'
              ]"
            >
              <svg v-if="currentStep > i" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
              </svg>
              <span v-else>{{ i + 1 }}</span>
            </div>
            <span class="mt-1 text-xs font-medium" :class="currentStep === i ? 'text-[#2e5f99]' : 'text-gray-400'">
              {{ step }}
            </span>
          </div>
          <div v-if="i < steps.length - 1" class="w-16 sm:w-24 h-0.5 mx-2 mb-4" :class="currentStep > i ? 'bg-[#2e5f99]' : 'bg-gray-200'"/>
        </div>
      </div>

      <UploadStep v-if="currentStep === 0" @uploaded="onHouseUploaded" />

      <GateCatalog
        v-else-if="currentStep === 1"
        @selected="onDoorSelected"
        @back="currentStep = 0"
      />

      <Visualizer
        v-else-if="currentStep === 2"
        :project="project"
        :gate="selectedGate"
        @saved="onResultSaved"
        @back="currentStep = 1"
      />

      <LeadForm
        v-else-if="currentStep === 3"
        :project="project"
        @back="currentStep = 2"
        @done="currentStep = 4"
      />

      <ThankYou v-else-if="currentStep === 4" @restart="restart" />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import UploadStep from './components/UploadStep.vue';
import GateCatalog from './components/GateCatalog.vue';
import Visualizer from './components/Visualizer.vue';
import LeadForm from './components/LeadForm.vue';
import ThankYou from './components/ThankYou.vue';

const logoUrl = '/images/logo.png';
const logoError = ref(false);

const steps = ['Фото дома', 'Выбор двери', 'Визуализация', 'Заявка'];
const currentStep = ref(0);

const project      = ref(null);
const selectedGate = ref(null);

function onHouseUploaded(proj) {
  project.value = proj;
  currentStep.value = 1;
}

function onDoorSelected({ gate }) {
  selectedGate.value = gate;
  currentStep.value  = 2;
}

function onResultSaved(updatedProject) {
  project.value = updatedProject;
  currentStep.value = 3;
}

function restart() {
  currentStep.value  = 0;
  project.value      = null;
  selectedGate.value = null;
}
</script>
