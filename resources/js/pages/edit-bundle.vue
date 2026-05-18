<template>
  <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow mt-10">
    <form @submit.prevent="submitForm">
      <div class="flex justify-center">
        <h1 class="text-xl">Edit Bundle</h1>
      </div>
      <div class="mb-4">
        <label class="block font-semibold mb-1 text-gray-700">Date</label>
        <input type="date" v-model="form.date" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400" id="date" required />
      </div>
      <div class="mb-4">
        <label class="block font-semibold mb-1 text-gray-700">Sheets Per Bundle</label>
        <input type="number" v-model.number="form.sheets_per_bundle" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400" id="sheets_per_bundle" required />
      </div>
      <div class="mb-4">
        <label class="block font-semibold mb-1 text-gray-700">Bundles</label>
        <input type="number" v-model.number="form.bundle_count" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400" id="bundle_count" required />
      </div>
      <div class="mb-4">
        <label class="block font-semibold mb-1 text-gray-700">Total Sheets</label>
        <input type="number" class="w-full border px-4 py-2 rounded bg-gray-100" :value="form.sheets_per_bundle * form.bundle_count" readonly />
      </div>
      <div class="mb-4 p-4 bg-gray-50 rounded">
        <h2 class="font-semibold mb-2">Previous Bundle Info</h2>
        <p><strong>Date:</strong> {{ bundle.date }}</p>
        <p><strong>Sheets per Bundle:</strong> {{ bundle.sheets_per_bundle }}</p>
        <p><strong>Bundles:</strong> {{ bundle.bundle_count }}</p>
        <p><strong>Total Sheets:</strong> {{ bundle.sheets_per_bundle * bundle.bundle_count }}</p>
      </div>
      <div class="mb-4 p-4 bg-gray-50 rounded">
        <h2 class="font-semibold mb-2">Original Finished Product Info</h2>
        <p><strong>Party Name:</strong> {{ bundle.stock.party_name ?? '' }}</p>
        <p><strong>Piece:</strong> {{ bundle.stock.khana ?? '' }}</p>
        <p><strong>Bundle Width:</strong> {{ bundle.stock.b_width ?? '' }}</p>
        <p><strong>Bundle Length:</strong> {{ bundle.stock.b_length ?? '' }}</p>
        <p><strong>Sheets/Bundle (Original):</strong> {{ bundle.stock.sheets_per_bundle ?? '' }}</p>
        <p><strong>Sheet Size:</strong> {{ bundle.stock.sheet_size ?? '' }}</p>
        <p><strong>Lot:</strong> {{ bundle.stock.lot ?? '' }}</p>
        <p><strong>Finished Quantity (Before):</strong> {{ bundle.stock.bundle ?? '' }}</p>
      </div>
      <div class="mt-6">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Submit</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { defineProps, reactive } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  bundle: { type: Object, required: true }
});

const form = reactive({
  date: props.bundle.date,
  sheets_per_bundle: props.bundle.sheets_per_bundle,
  bundle_count: props.bundle.bundle_count,
});

function submitForm() {
  router.post(route('bundle.update', props.bundle.id), {
    date: form.date,
    sheets_per_bundle: form.sheets_per_bundle,
    bundle_count: form.bundle_count,
  });
}
</script>

<style scoped>
@media print {
  .max-w-2xl {
    max-width: 100vw !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
  }
}
</style>
