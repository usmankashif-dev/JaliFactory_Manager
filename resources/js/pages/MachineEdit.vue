<template>
  <div class="max-w-2xl mx-auto py-8 bg-background text-foreground">
    <h1 class="text-2xl font-bold mb-6 text-foreground">Edit Machine Entry</h1>
    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label for="machinedate" class="block text-sm font-medium text-foreground">Date</label>
        <input type="date" v-model="form.machinedate" id="machinedate" class="border rounded px-2 py-1 w-full bg-background text-foreground" required />
      </div>
      <div class="mb-4">
        <label for="machinenumber" class="block text-sm font-medium text-foreground">Machine No</label>
        <input type="number" v-model.number="form.machinenumber" id="machinenumber" class="border rounded px-2 py-1 w-full bg-background text-foreground" required />
      </div>
      <div class="mb-4">
        <label for="machineqty" class="block text-sm font-medium text-foreground">Quantity</label>
        <select v-model.number="form.machineqty" id="machineqty" class="border rounded px-2 py-1 w-full bg-background text-foreground" required>
          <option value="">Select Quantity</option>
          <option v-for="i in Number(maxQty)" :key="i" :value="i">{{ i }}</option>
        </select>
        <div class="text-xs text-foreground opacity-60 mt-1">Available to send: {{ maxQty }}</div>
      </div>
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white dark:text-black font-bold py-2 px-4 rounded">Update</button>
      <a :href="route('machine-view')" class="ml-4 text-foreground hover:underline">Cancel</a>
    </form>
    <div v-if="order" class="mt-6 text-sm text-foreground">
      <strong>Order Rem:</strong> {{ order.rem }}<br />
      <strong>Order Party:</strong> {{ order.partyorder }}
    </div>
  </div>
</template>

<script setup>
import { defineProps, reactive } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  machine: { type: Object, required: true },
  order: { type: Object, required: false, default: null },
});

const maxQty = props.order ? props.order.rem : props.machine.machineqty;

const form = reactive({
  machinedate: props.machine.machinedate,
  machinenumber: props.machine.machinenumber,
  machineqty: props.machine.machineqty,
});

function submitForm() {
  router.post(route('machine.update', props.machine.id), {
    ...form,
    _method: 'PUT',
  });
}
</script>

<style scoped>
:root {
  --color-background: #fff;
  --color-foreground: #111;
}
.dark :root {
  --color-background: #111;
  --color-foreground: #fff;
}
.bg-background { background-color: var(--color-background) !important; }
.text-foreground { color: var(--color-foreground) !important; }

/* Force all descendants to inherit background and text color */
.bg-background, .bg-background * {
  background-color: var(--color-background) !important;
}
.text-foreground, .text-foreground * {
  color: var(--color-foreground) !important;
}

input[type="date"],
input[type="number"],
select {
  background-color: var(--background) !important;
  color: var(--foreground) !important;
  border-color: var(--border) !important;
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
