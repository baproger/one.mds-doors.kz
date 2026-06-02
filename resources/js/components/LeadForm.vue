<template>
  <div class="max-w-xl mx-auto">
    <div class="text-center mb-8">
      <div class="w-16 h-16 bg-[#eef2f5] rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-[#2e5f99]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
      </div>
      <h2 class="text-2xl font-bold text-gray-900">Оставьте заявку</h2>
      <p class="text-gray-500 mt-2">Наш менеджер свяжется с вами для обсуждения деталей и стоимости</p>
    </div>

    <!-- Result preview -->
    <div v-if="project.result_image_url" class="mb-6 rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
      <img :src="project.result_image_url" alt="Ваша визуализация" class="w-full object-cover max-h-48" />
      <div class="bg-[#eef2f5] px-4 py-2 text-xs text-[#2e5f99] font-medium">
        Ваша визуализация будет приложена к заявке
      </div>
    </div>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ваше имя <span class="text-red-500">*</span></label>
        <input
          v-model="form.name"
          type="text"
          required
          placeholder="Иван Иванов"
          class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2e5f99]"
          :class="errors.name ? 'border-red-300' : 'border-gray-200'"
        />
        <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Телефон <span class="text-red-500">*</span></label>
        <input
          v-model="form.phone"
          type="tel"
          required
          placeholder="+7 (999) 123-45-67"
          class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2e5f99]"
          :class="errors.phone ? 'border-red-300' : 'border-gray-200'"
        />
        <p v-if="errors.phone" class="text-red-500 text-xs mt-1">{{ errors.phone[0] }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input
          v-model="form.email"
          type="email"
          placeholder="ivan@example.com"
          class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2e5f99]"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Сообщение</label>
        <textarea
          v-model="form.message"
          rows="3"
          placeholder="Ваши вопросы или пожелания..."
          class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2e5f99] resize-none"
        />
      </div>

      <div v-if="serverError" class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
        {{ serverError }}
      </div>

      <button
        type="submit"
        :disabled="submitting"
        class="w-full py-4 bg-[#2e5f99] hover:bg-[#265285] disabled:bg-[#8aafd1] text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2"
      >
        <svg v-if="submitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        {{ submitting ? 'Отправляем...' : 'Отправить заявку' }}
      </button>

      <button type="button" @click="emit('back')" class="w-full py-3 text-sm text-gray-400 hover:text-gray-600">
        ← Вернуться к визуализации
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';

const props = defineProps({ project: Object });
const emit = defineEmits(['done', 'back']);

const form = reactive({ name: '', phone: '', email: '', message: '' });
const errors = ref({});
const serverError = ref(null);
const submitting = ref(false);

async function submit() {
  submitting.value = true;
  errors.value = {};
  serverError.value = null;

  try {
    await axios.post('/api/leads', {
      ...form,
      project_id: props.project?.id,
    });
    emit('done');
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {};
    } else {
      serverError.value = 'Произошла ошибка. Попробуйте снова.';
    }
  } finally {
    submitting.value = false;
  }
}
</script>
