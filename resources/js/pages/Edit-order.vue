<template>
  <div class="max-w-2xl mx-auto p-6 bg-background text-foreground rounded-xl shadow mt-10">
    <form @submit.prevent="submitForm">
      <div class="flex justify-center">
        <h1 class="text-xl text-foreground">Edit Order</h1>
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
        <template v-if="order.type === 'm'">
          <input type="number" v-model.number="form.orderedqty" class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground" id="orderedqty" readonly />
          <div class="text-xs text-foreground mt-1">Full quantity will be used for M Order.</div>
        </template>
        <template v-else>
          <input
            type="number"
            v-model.number="form.orderedqty"
            class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground"
            id="orderedqty"
            min="1"
            required
          />
          <div class="text-xs text-foreground mt-1">Available: {{ maxQty }}</div>
        </template>
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
      <input type="hidden" :value="order.id" name="mall_id" />
      <div class="mt-6">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
          Submit
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { defineProps, reactive } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  order: { type: Object, required: true },
  mall: { type: Object, required: false, default: null },
});

const maxQty = props.mall?.availableqty ?? props.order?.orderedqty ?? 100;

const form = reactive({
  ogauge: props.order.ogauge,
  peice: props.order.peice,
  olenght: props.order.olenght,
  orderedqty: props.order.orderedqty,
  dateno: props.order.dateno,
  bundlewidht: props.order.bundlewidht,
  sheetperbundle: props.order.sheetperbundle,
  jalilenght: props.order.jalilenght,
  mall_id: props.order.mall_id, // ensure correct mall_id is sent
});

function submitForm() {
  router.post(route('order.update', props.order.id), form);
}
</script>

<style scoped>
.bg-background, .bg-background * {
  background-color: var(--background) !important;
}
.text-foreground, .text-foreground * {
  color: var(--foreground) !important;
}
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
