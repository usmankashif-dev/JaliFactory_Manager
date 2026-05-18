<template>
 <AppLayout>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-background text-foreground">
    <div v-if="success" class="mb-4 text-green-600 dark:text-green-300">
      {{ success }}
    </div>
    <h1 class="text-2xl font-bold mb-6 text-foreground">Machine Orders</h1>
    <!-- Search form -->
    <form class="space-y-4 mb-6" id="machineSearchForm" v-show="showSearchForm" @submit.prevent="submitSearch">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label for="machinedate" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date</label>
          <input type="date" name="machinedate" id="machinedate" v-model="filters.machinedate" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="machinenumber" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Machine No</label>
          <input type="text" name="machinenumber" id="machinenumber" v-model="filters.machinenumber" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="machineqty" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Quantity</label>
          <input type="number" name="machineqty" id="machineqty" v-model="filters.machineqty" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="olenght" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Length</label>
          <input type="text" name="olenght" id="olenght" v-model="filters.olenght" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="peice" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Piece</label>
          <input type="text" name="peice" id="peice" v-model="filters.peice" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="ogauge" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Gauge</label>
          <input type="text" name="ogauge" id="ogauge" v-model="filters.ogauge" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="cutsheet" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Cut Sheet</label>
          <input type="text" name="cutsheet" id="cutsheet" v-model="filters.cutsheet" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="lot" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Lot</label>
          <input type="text" name="lot" id="lot" v-model="filters.lot" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="bundlewidht" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Bundle Width</label>
          <input type="text" name="bundlewidht" id="bundlewidht" v-model="filters.bundlewidht" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="sheetperbundle" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sheets/Bundle</label>
          <input type="text" name="sheetperbundle" id="sheetperbundle" v-model="filters.sheetperbundle" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="partyorder" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Party Order</label>
          <input type="text" name="partyorder" id="partyorder" v-model="filters.partyorder" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="jalilenght" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Jali Length</label>
          <input type="text" name="jalilenght" id="jalilenght" v-model="filters.jalilenght" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
        <div>
          <label for="orderedpeices" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Ordered Pieces</label>
          <input type="text" name="orderedpeices" id="orderedpeices" v-model="filters.orderedpeices" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
      </div>
      <div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white dark:text-black font-bold py-2 px-4 rounded">Filter</button>
      </div>
    </form>
    <button id="machineSearchToggleBtn" @click="showSearchForm = !showSearchForm" type="button" class="search-toggle-btn bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white dark:text-black font-bold py-2 px-4 rounded mb-4">
      {{ showSearchForm ? 'Hide Search' : 'Search' }}
    </button>
    <div class="overflow-x-auto bg-background text-foreground rounded-lg shadow">
      <table class="min-w-full divide-y divide-border bg-background text-foreground">
        <thead class="bg-background">
          <tr>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Date</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Party</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Lot</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Cuted Sheet</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Piece</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Gauge</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Bundle Widht</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Lenght</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Jali Lenght</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Sheet/Bundle</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Machine No</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Quantity</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-background divide-y divide-border">
          <tr v-for="machine in machines" :key="machine.id" class="hover:bg-accent dark:hover:bg-accent-dark transition-colors">
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.machinedate }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.partyorder }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.lot }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.cutsheet }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.peice }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.ogauge }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.bundlewidht }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.olenght }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.jalilenght }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.sheetperbundle }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.machinenumber }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ machine.machineqty }}</td>
            <td class="px-4 py-2 text-sm text-foreground border-2 border-border">
              <form @submit.prevent="deleteMachine(machine.id)">
                <button type="submit" class="bg-red-500 hover:bg-red-700 dark:bg-red-400 dark:hover:bg-red-600 text-white dark:text-black font-bold py-1 px-2 rounded">Delete</button>
              </form>
              <Link :href="route('machine.edit', machine.id)" class="bg-cyan-500 hover:bg-cyan-600 dark:bg-cyan-400 dark:hover:bg-cyan-600 text-white dark:text-black px-4 py-2 rounded-md transition ml-2">Edit</Link>
              <div class="p-4">
                <a :href="route('machine.finishForm', machine.id)" class="bg-green-500 hover:bg-green-700 dark:bg-green-400 dark:hover:bg-green-600 text-white dark:text-black font-bold py-1 px-2 rounded ml-2">Finish</a>
              </div>
            </td>
          </tr>
          <tr v-if="machines.length === 0">
            <td colspan="15" class="px-4 py-4 text-center text-foreground opacity-60">No machine orders found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
   </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { defineProps } from 'vue'
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
  machines: { type: Array, required: true },
  success: { type: String, default: '' }
})

const filters = ref({
  machinedate: '',
  machinenumber: '',
  machineqty: '',
  olenght: '',
  peice: '',
  ogauge: '',
  cutsheet: '',
  lot: '',
  bundlewidht: '',
  sheetperbundle: '',
  partyorder: '',
  jalilenght: '',
  orderedpeices: ''
})

const showSearchForm = ref(false);

function deleteMachine(id) {
  if (confirm('Are you sure you want to delete this machine entry?')) {
    router.post(route('machine.delete', id), { _method: 'DELETE' })
  }
}

function submitSearch() {
  router.get(route('machine-view'), { ...filters.value }, { preserveState: true, preserveScroll: true });
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
.divide-border > :not([hidden]) ~ :not([hidden]) { border-top-color: var(--color-border) !important; }
.border-border { border-color: var(--color-border) !important; }
.bg-accent { background-color: var(--color-accent) !important; }
.dark .bg-accent-dark { background-color: var(--color-accent) !important; }
@media print {
  .max-w-7xl {
    max-width: 100vw !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
  }
}
</style>
