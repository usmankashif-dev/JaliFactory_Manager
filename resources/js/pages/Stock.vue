<template>
<AppLayout>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-background text-foreground">
    <div v-if="success" class="mb-4 text-green-600 dark:text-green-300">{{ success }}</div>
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">Finished Stock</h1>
    <button @click="showSearch = !showSearch" class="search-toggle-btn bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mb-4 print:hidden">Search</button>
    <form v-show="showSearch" @submit.prevent="filterStock" class="space-y-4 mb-6 print:hidden" id="stockSearchForm">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div v-for="field in searchFields" :key="field.name">
          <label :for="field.name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ field.label }}</label>
          <input :type="field.type" :name="field.name" :id="field.name" v-model="filters[field.name]" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
      </div>
      <div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Filter</button>
      </div>
    </form>
    <div class="overflow-x-auto bg-background shadow rounded-lg print:shadow-none print:border print:border-black">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 print:text-xs print:divide-black bg-background text-foreground">
        <thead class="bg-background text-foreground print:bg-white">
          <tr>
            <th v-for="header in tableHeaders" :key="header" class="px-4 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-black print:border-black print:font-normal print:text-xs">{{ header }}</th>
          </tr>
        </thead>
        <tbody class="bg-background divide-y divide-gray-200 dark:divide-gray-700 print:divide-black">
          <tr v-for="stock in stocks" :key="stock.id" class="hover:bg-gray-50 dark:hover:bg-gray-900 print:hover:bg-white">
            <td class="px-4 py-2 text-md text-foreground border-2 border-black print:text-xs print:border-black">{{ stock.date }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-black print:text-xs print:border-black">{{ stock.party_name }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-black print:text-xs print:border-black">{{ stock.khana }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-black print:text-xs print:border-black">{{ stock.b_width }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-black print:text-xs print:border-black">{{ stock.b_length }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-black print:text-xs print:border-black">{{ stock.sheets_per_bundle }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-black print:text-xs print:border-black">{{ stock.sheet_size }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-black print:text-xs print:border-black">{{ stock.lot }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-black print:text-xs print:border-black">{{ stock.bundle }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-black print:text-xs print:border-black">
              <form @submit.prevent="deleteStock(stock.id)" class="inline-block m-2 print:hidden">
                <button type="submit" class="bg-red-500 hover:bg-red-600 dark:bg-red-400 dark:hover:bg-red-600 text-white px-3 py-1 rounded">Delete</button>
              </form>
              <a @click.prevent="editStock(stock.id)" href="#" class="bg-cyan-500 hover:bg-cyan-600 dark:bg-cyan-400 dark:hover:bg-cyan-600 text-white px-3 py-1 rounded ml-2 print:hidden">Edit</a>
              <a @click.prevent="bundleStock(stock.id)" href="#" class="bg-green-500 hover:bg-green-600 dark:bg-green-400 dark:hover:bg-green-600 text-white px-3 py-1 rounded ml-2 print:hidden">Bundle</a>
            </td>
          </tr>
          <tr v-if="!stocks.length">
            <td colspan="10" class="px-4 py-4 text-center text-foreground print:text-black">No finished stock found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
  stocks: { type: Array, required: true },
  success: String,
  filters: Object,
});

const showSearch = ref(false);
const filters = reactive({ ...props.filters });

const searchFields = [
  { name: 'date', label: 'Date', type: 'date' },
  { name: 'party_name', label: 'Party Name', type: 'text' },
  { name: 'piece', label: 'Piece', type: 'text' },
  { name: 'b_width', label: 'Bundle Width', type: 'text' },
  { name: 'b_length', label: 'Bundle Length', type: 'text' },
  { name: 'lot', label: 'Lot', type: 'text' },
];

const tableHeaders = [
  'Date', 'Party Name', 'Piece', 'Bundle Width', 'Bundle Length', 'Sheets/Bundle', 'Sheet Size', 'Lot', 'Bundle', 'Actions'
];

function filterStock() {
  router.get(route('stock'), { ...filters }, { preserveState: true, replace: true });
}

function deleteStock(id) {
  if (confirm('Are you sure you want to delete this stock entry?')) {
    router.delete(route('stock.delete', id));
  }
}

function editStock(id) {
  router.get(route('stock.edit', id));
}

function bundleStock(id) {
  router.get(route('stock.bundle.form', id));
}
</script>

<style scoped>
@media print {
  .print\:hidden { display: none !important; }
  .print\:bg-white { background-color: #fff !important; }
  .print\:text-black { color: #000 !important; }
  .print\:shadow-none { box-shadow: none !important; }
  .print\:border { border: 1px solid #000 !important; }
  .print\:font-normal { font-weight: normal !important; }
  .print\:text-xs { font-size: 0.75rem !important; }
  .print\:divide-black > * { border-color: #000 !important; }
}
</style>
