<template>
<AppLayout>
  <div class="max-w-7xl mx-auto p-6 bg-background text-foreground rounded-xl shadow mt-10 print:bg-white print:text-black">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-foreground print:text-black">Verified Bundles</h1>
    </div>
    <form class="space-y-4 mb-6" v-show="showSearchForm" @submit.prevent="submitSearch">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-foreground">Party</label>
          <input v-model="filters.party_name" type="text" class="border rounded px-2 py-1 w-full bg-background text-foreground" placeholder="Party Name" />
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground">Lot</label>
          <input v-model="filters.lot" type="text" class="border rounded px-2 py-1 w-full bg-background text-foreground" placeholder="Lot" />
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground">Date</label>
          <input v-model="filters.date" type="date" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground">Cut Sheet</label>
          <input v-model="filters.cutsheet" type="text" class="border rounded px-2 py-1 w-full bg-background text-foreground" placeholder="Cut Sheet" />
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground">Piece</label>
          <input v-model="filters.peice" type="text" class="border rounded px-2 py-1 w-full bg-background text-foreground" placeholder="Piece" />
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground">Width</label>
          <input v-model="filters.widht" type="text" class="border rounded px-2 py-1 w-full bg-background text-foreground" placeholder="Width" />
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground">Jali Length</label>
          <input v-model="filters.jalilenght" type="text" class="border rounded px-2 py-1 w-full bg-background text-foreground" placeholder="Jali Length" />
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground">Sheets/Bundle</label>
          <input v-model="filters.sheets_per_bundle" type="text" class="border rounded px-2 py-1 w-full bg-background text-foreground" placeholder="Sheets/Bundle" />
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground">Bundle Count</label>
          <input v-model="filters.bundle_count" type="text" class="border rounded px-2 py-1 w-full bg-background text-foreground" placeholder="Bundle Count" />
        </div>
        <div>
          <label class="block text-sm font-medium text-foreground">Packed By</label>
          <input v-model="filters.packed_by" type="text" class="border rounded px-2 py-1 w-full bg-background text-foreground" placeholder="Packed By" />
        </div>
      </div>
      <div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white dark:text-black font-bold py-2 px-4 rounded">Filter</button>
      </div>
    </form>
    <button @click="showSearchForm = !showSearchForm" type="button" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white dark:text-black font-bold py-2 px-4 rounded mb-4">
      {{ showSearchForm ? 'Hide Search' : 'Search' }}
    </button>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-background text-foreground border border-border rounded-lg print:text-xs print:border-black">
        <thead class="bg-background print:bg-white">
          <tr>
            <th v-for="header in tableHeaders" :key="header" class="px-4 py-2 border-b border-border print:border-black print:font-normal print:text-xs text-foreground">{{ header }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(bundle, idx) in bundles" :key="bundle.id" class="hover:bg-accent dark:hover:bg-accent-dark transition-colors print:hover:bg-white">
            <td class="px-4 py-2 border-b border-border print:border-black">{{ idx + 1 }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">{{ bundle.party_name ?? '-' }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">{{ bundle.lot ?? '-' }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">{{ bundle.cutsheet ?? '-' }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">{{ bundle.peice ?? '-' }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">{{ bundle.widht ?? '-' }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">{{ bundle.jalilenght ?? '-' }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">{{ bundle.date }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">{{ bundle.sheets_per_bundle }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">{{ bundle.bundle_count }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">{{ bundle.packed_by ?? '-' }}</td>
            <td class="px-4 py-2 border-b border-border print:border-black">
              <form @submit.prevent="deleteBundle(bundle.id)" class="inline print:hidden">
                <button type="submit" class="bg-red-600 hover:bg-red-700 dark:bg-red-400 dark:hover:bg-red-600 text-white dark:text-black px-3 py-1 rounded transition">Delete</button>
              </form>
            </td>
          </tr>
          <tr v-if="!bundles.length">
            <td colspan="12" class="text-center py-6 text-foreground opacity-60 print:text-black">No verified bundles found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
  bundles: { type: Array, required: true },
});

const showSearchForm = ref(false);
const filters = ref({
  party_name: '',
  lot: '',
  date: '',
  cutsheet: '',
  peice: '',
  widht: '',
  jalilenght: '',
  sheets_per_bundle: '',
  bundle_count: '',
  packed_by: '',
});

const tableHeaders = [
  '#', 'Party', 'Lot', 'Cuted Sheet', 'Peice', 'Widht', 'Jali Lenght', 'Bundle Date', 'Sheets/Bundle', 'Bundle Count', 'Packed By', 'Actions'
];

function submitSearch() {
  router.get(route('verified.bundles'), { ...filters.value }, { preserveState: true, preserveScroll: true });
}

function deleteBundle(id) {
  if (confirm('Are you sure you want to delete this verified bundle?')) {
    router.delete(route('verified.bundle.delete', id));
  }
}
</script>

<style scoped>
:root {
  --color-background: #fff;
  --color-foreground: #111;
  --color-border: #e5e7eb;
  --color-accent: #f3f4f6;
}
.dark :root {
  --color-background: #111;
  --color-foreground: #fff;
  --color-border: #333;
  --color-accent: #23272e;
}
.bg-background { background-color: var(--color-background) !important; }
.text-foreground { color: var(--color-foreground) !important; }
.border-border { border-color: var(--color-border) !important; }
.bg-accent { background-color: var(--color-accent) !important; }
.dark .bg-accent-dark { background-color: var(--color-accent) !important; }
</style>
