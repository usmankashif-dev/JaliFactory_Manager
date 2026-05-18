<template>
  <div class="max-w-xl mx-auto py-8 bg-background text-foreground">
    <h1 class="text-2xl font-bold mb-6 text-foreground">Edit Finished Stock</h1>
    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label for="date" class="block text-sm font-medium text-foreground">Date</label>
        <input type="date" name="date" id="date" v-model="form.date" class="border rounded px-2 py-1 w-full bg-background text-foreground" required />
      </div>
      <div class="mb-4">
        <label for="party_name" class="block text-sm font-medium text-foreground">Party Name</label>
        <input type="text" name="party_name" id="party_name" v-model="form.party_name" class="border rounded px-2 py-1 w-full bg-background text-foreground" required />
      </div>
      <div class="mb-4">
        <label for="khana" class="block text-sm font-medium text-foreground">Piece</label>
        <input type="text" name="khana" id="khana" v-model="form.khana" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
      </div>
      <div class="mb-4">
        <label for="b_width" class="block text-sm font-medium text-foreground">B Width</label>
        <input type="text" name="b_width" id="b_width" v-model="form.b_width" class="border rounded px-2 py-1 w-full bg-background text-foreground" required />
      </div>
      <div class="mb-4">
        <label for="b_length" class="block text-sm font-medium text-foreground">B Length</label>
        <input type="text" name="b_length" id="b_length" v-model="form.b_length" class="border rounded px-2 py-1 w-full bg-background text-foreground" required />
      </div>
      <div class="mb-4">
        <label for="sheets_per_bundle" class="block text-sm font-medium text-foreground">Sheets/Bundle</label>
        <input type="text" name="sheets_per_bundle" id="sheets_per_bundle" v-model="form.sheets_per_bundle" class="border rounded px-2 py-1 w-full bg-background text-foreground" required />
      </div>
      <div class="mb-4">
        <label for="sheet_size" class="block text-sm font-medium text-foreground">Sheet Size</label>
        <input type="text" name="sheet_size" id="sheet_size" v-model="form.sheet_size" class="border rounded px-2 py-1 w-full bg-background text-foreground" required />
      </div>
      <div class="mb-4">
        <label for="lot" class="block text-sm font-medium text-foreground">Lot</label>
        <input type="text" name="lot" id="lot" v-model="form.lot" class="border rounded px-2 py-1 w-full bg-background text-foreground" required />
      </div>
      <div class="mb-4">
        <label for="bundle" class="block text-sm font-medium text-foreground">Bundle</label>
        <select name="bundle" id="bundle" v-model="form.bundle" class="border rounded px-2 py-1 w-full bg-background text-foreground" required>
          <option value="">Select Bundle</option>
          <option v-for="i in stock.bundle" :key="i" :value="i">{{ i }}</option>
        </select>
        <div class="text-xs text-foreground opacity-60 mt-1">Available: {{ stock.bundle }}</div>
      </div>
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white dark:text-black font-bold py-2 px-4 rounded print:hidden">Update</button>
      <button type="button" @click="cancelEdit" class="ml-4 text-foreground hover:underline print:hidden">Cancel</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  stock: { type: Object, required: true },
  old: { type: Object, default: () => ({}) },
});

const form = ref({
  date: props.old.date || props.stock.date,
  party_name: props.old.party_name || props.stock.party_name,
  khana: props.old.khana || props.stock.khana,
  b_width: props.old.b_width || props.stock.b_width,
  b_length: props.old.b_length || props.stock.b_length,
  sheets_per_bundle: props.old.sheets_per_bundle || props.stock.sheets_per_bundle,
  sheet_size: props.old.sheet_size || props.stock.sheet_size,
  lot: props.old.lot || props.stock.lot,
  bundle: props.old.bundle || props.stock.bundle,
});

function submitForm() {
  router.put(route('stock.update', props.stock.id), form.value);
}

function cancelEdit() {
  router.get(route('stock'));
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
</style>
