<template>
  <div>
    <div class="mb-6 p-4 bg-background border-l-4 border-blue-500 rounded shadow text-foreground">
      <h2 class="text-xl font-semibold text-blue-800 dark:text-blue-300 mb-2">Mall Information</h2>
      <p><strong>Party:</strong> {{ mall.party }}</p>
      <p><strong>Gauge:</strong> {{ mall.input1 }}</p>
      <p><strong>Length:</strong> {{ mall.input2 }}</p>
      <p><strong>Width:</strong> {{ mall.input3 }}</p>
      <p><strong>Lot:</strong> {{ mall.lot }}</p>
      <p><strong>Quantity:</strong> {{ mall.availableqty }}</p>
    </div>
    <div class="max-w-2xl mx-auto p-6 bg-background text-foreground rounded-xl shadow mt-10">
      <form @submit.prevent="submitForm">
        <div class="flex justify-center">
          <h1 class="text-xl text-foreground">Create New Order</h1>
        </div>
        <div class="mb-4">
          <label class="block font-semibold mb-1 text-foreground">Gauge</label>
          <input type="text" v-model="form.ogauge" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground" id="ogauge" required />
        </div>
        <div class="mb-4">
          <label class="block font-semibold mb-1 text-foreground">Piece</label>
          <input type="text" v-model="form.peice" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground" id="peice" required />
        </div>
        <div class="mb-4">
          <label class="block font-semibold mb-1 text-foreground">Lenght:</label>
          <input type="number" v-model.number="form.olenght" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground" id="olenght" required />
        </div>
        <div class="mb-4">
          <label class="block font-semibold mb-1 text-foreground">Quantity</label>
          <select v-model.number="form.orderedqty" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground" id="orderedqty" :disabled="mall.availableqty < 1" required>
            <option value="">Select Quantity</option>
            <option v-for="i in quantityOptions" :key="i" :value="i">{{ i }}</option>
          </select>
          <div class="text-xs text-foreground opacity-60 mt-1">Available: {{ mall.availableqty }}</div>
        </div>
        <div class="mb-4">
          <label class="block font-semibold mb-1 text-foreground">Date:</label>
          <input type="date" v-model="form.dateno" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground" id="dateno" required />
        </div>
        <div class="mb-4">
          <label class="block font-semibold mb-1 text-foreground">Widht:</label>
          <input type="number" v-model.number="form.bundlewidht" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground" id="bundlewidht" required />
        </div>
        <div class="mb-4">
          <label class="block font-semibold mb-1 text-foreground">Sheets Per Bundle:</label>
          <input type="number" v-model.number="form.sheetperbundle" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground" id="sheetperbundle" required />
        </div>
        <div class="mb-4">
          <label class="block font-semibold mb-1 text-foreground">Jali Lenght:</label>
          <input type="number" v-model.number="form.jalilenght" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground" id="jalilenght" required />
        </div>
        <input type="hidden" :value="mall.id" name="mall_id" />
        <div class="mt-6">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 dark:text-black">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { defineProps, reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  mall: { type: Object, required: true }
});

const form = reactive({
  ogauge: '',
  peice: '',
  olenght: '',
  orderedqty: '',
  dateno: '',
  bundlewidht: '',
  sheetperbundle: '',
  jalilenght: ''
});

const quantityOptions = computed(() => {
  const qty = parseInt(props.mall.availableqty) || 0;
  return Array.from({ length: qty }, (_, i) => i + 1);
});

function submitForm() {
  router.post(route('Addmall.store'), {
    ...form,
    mall_id: props.mall.id
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
