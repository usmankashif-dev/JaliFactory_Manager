<template>
  <div class="max-w-lg mx-auto py-8 bg-background text-foreground">
    <h1 class="text-2xl font-bold mb-6 text-foreground">Bundle Info</h1>
    <div class="mb-4 p-4 bg-background rounded">
      <h2 class="font-semibold mb-2 text-foreground">Bundle Details</h2>
      <p><strong>Date:</strong> {{ date }}</p>
      <p><strong>Sheets per Bundle:</strong> {{ sheets_per_bundle }}</p>
      <p><strong>Bundles:</strong> {{ bundle_count }}</p>
    </div>
    <div class="mb-4 p-4 bg-background rounded">
      <h2 class="font-semibold mb-2 text-foreground">Original Finished Product Info</h2>
      <p><strong>Party Name:</strong> {{ stock.party_name }}</p>
      <p><strong>Piece:</strong> {{ stock.khana }}</p>
      <p><strong>Bundle Width:</strong> {{ stock.b_width }}</p>
      <p><strong>Bundle Length:</strong> {{ stock.b_length }}</p>
      <p><strong>Sheets/Bundle (Original):</strong> {{ stock.sheets_per_bundle }}</p>
      <p><strong>Sheet Size:</strong> {{ stock.sheet_size }}</p>
      <p><strong>Lot:</strong> {{ stock.lot }}</p>
      <p><strong>Finished Quantity (Before):</strong> {{ stock.bundle }}</p>
    </div>
    <button @click="goBack" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white dark:text-black px-4 py-2 rounded print:hidden">Back to Finished Stock</button>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
  date: { type: String, required: true },
  sheets_per_bundle: { type: [String, Number], required: true },
  bundle_count: { type: [String, Number], required: true },
  stock: { type: Object, required: true },
});

function goBack() {
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
