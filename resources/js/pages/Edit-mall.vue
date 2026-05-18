<template>
  <div>
    <h2 class="text-xl font-semibold text-foreground leading-tight p-4">
      Edit Mall
    </h2>
    <form @submit.prevent="submitForm" class="max-w-2xl mx-auto p-4 bg-background text-foreground shadow rounded-md animate-fade-in">
      <label for="party" class="block mt-2 text-foreground">Party:</label>
      <input type="text" name="party" id="party" v-model="form.party" required class="w-full p-2 border rounded bg-background text-foreground">

      <label for="input1" class="block mt-2 text-foreground">Gauge:</label>
      <input type="number" step="0.01" name="input1" id="input1" v-model.number="form.input1" required class="w-full p-2 border rounded bg-background text-foreground">

      <label for="input2" class="block mt-2 text-foreground">Length:</label>
      <input type="number" step="0.01" name="input2" id="input2" v-model.number="form.input2" required class="w-full p-2 border rounded bg-background text-foreground">

      <label for="input3" class="block mt-2 text-foreground">Width:</label>
      <input type="number" step="0.01" name="input3" id="input3" v-model.number="form.input3" required class="w-full p-2 border rounded bg-background text-foreground">

      <label for="input4" class="text-foreground">Material of sheet:</label>
      <input type="text" name="input4" id="input4" v-model="form.input4" class="w-full p-2 border rounded bg-background text-foreground">

      <label for="input5" class="text-foreground">Date:</label>
      <input type="date" name="input5" id="input5" v-model="form.input5" class="w-full p-2 border rounded bg-background text-foreground">

      <div class="mb-4">
        <label for="availableqty" class="block font-semibold mb-1 text-foreground">Quantity</label>
        <input
          type="number"
          name="availableqty"
          id="availableqty"
          v-model.number="form.availableqty"
          min="1"
          required
          class="w-full border px-4 py-2 rounded focus:ring focus:ring-blue-400 bg-background text-foreground"
        />
        <div class="text-xs text-foreground mt-1">Available: {{ maxQty }}</div>
      </div>

      <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
        Update Mall
      </button>
    </form>
    <div v-if="errors.length" class="bg-red-500 text-white p-4 rounded mt-4">
      <ul>
        <li v-for="(error, idx) in errors" :key="idx">{{ error }}</li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { defineProps, reactive, ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  mall: { type: Object, required: true },
  errors: { type: Array, default: () => [] }
});

const maxQty = props.mall?.availableqty ?? 100;

const form = reactive({
  party: props.mall.party,
  input1: props.mall.input1,
  input2: props.mall.input2,
  input3: props.mall.input3,
  input4: props.mall.input4,
  input5: props.mall.input5,
  availableqty: props.mall.availableqty,
});

function submitForm() {
  router.post(route('mall.update', props.mall.id), form);
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
