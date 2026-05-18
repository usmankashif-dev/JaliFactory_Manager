<template>
  <div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Finish Machine Stock</h1>
    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label for="finish_date" class="block text-sm font-medium text-gray-700">Date</label>
        <input type="date" v-model="form.finish_date" id="finish_date" class="border rounded px-2 py-1 w-full" required />
      </div>
      <div class="mb-4">
        <label for="finish_qty" class="block text-sm font-medium text-gray-700">Quantity to Finish</label>
        <input type="number" v-model.number="form.finish_qty" id="finish_qty" :min="1" :max="machine.machineqty" class="border rounded px-2 py-1 w-full" required />
        <div class="text-xs text-gray-500 mt-1">Available: {{ machine.machineqty }}</div>
      </div>
      <div class="mb-4">
        <label for="packed_by" class="block text-sm font-medium text-gray-700">Packed By</label>
        <input type="text" v-model="form.packed_by" id="packed_by" class="border rounded px-2 py-1 w-full" required />
      </div>
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Finish</button>
      <a :href="route('machine-view')" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
  </div>
</template>

<script setup>
import { defineProps, reactive } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  machine: { type: Object, required: true }
});

const form = reactive({
  finish_date: '',
  finish_qty: 1,
  packed_by: ''
});

function submitForm() {
  router.post(route('machine.finish', props.machine.id), form);
}
</script>

<style scoped>
@media print {
  .max-w-xl {
    max-width: 100vw !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
  }
}
</style>
