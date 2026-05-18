<template>
<AppLayout>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-background text-foreground">
    <div v-if="success" class="mb-4 text-green-600 dark:text-green-300">{{ success }}</div>
    <div v-if="message" class="mb-4 text-green-600 dark:text-green-300">{{ message }}</div>
    <div v-if="errors && errors.length" class="mb-4">
      <ul class="text-red-600 dark:text-red-300">
        <li v-for="(error, i) in errors" :key="i">{{ error }}</li>
      </ul>
    </div>
    <div v-if="error" class="mb-4 text-red-600 dark:text-red-300">{{ error }}</div>
    <h1 class="text-2xl font-bold mb-6 text-foreground">Orders</h1>
    <!-- Search form -->
    <form method="GET" action="#" class="space-y-4" id="orderSearchForm" v-show="showSearchForm" @submit.prevent="submitSearch">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label for="from_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">From Date</label>
          <input type="date" name="from_date" id="from_date" v-model="filters.from_date" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="to_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">To Date</label>
          <input type="date" name="to_date" id="to_date" v-model="filters.to_date" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="partyorder" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Party</label>
          <input type="text" name="partyorder" id="partyorder" v-model="filters.partyorder" placeholder="Party" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="lot" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Lot</label>
          <input type="text" name="lot" id="lot" v-model="filters.lot" placeholder="Lot" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="orgsheet" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Original Sheet</label>
          <input type="text" name="orgsheet" id="orgsheet" v-model="filters.orgsheet" placeholder="Original Sheet" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="cutsheet" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Cut Sheet</label>
          <input type="text" name="cutsheet" id="cutsheet" v-model="filters.cutsheet" placeholder="Cut Sheet" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="peice" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Piece</label>
          <input type="text" name="peice" id="peice" v-model="filters.peice" placeholder="Piece" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="ogauge" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Orders Gauge</label>
          <input type="text" name="ogauge" id="ogauge" v-model="filters.ogauge" placeholder="Orders Gauge" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="widht" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Width</label>
          <input type="text" name="widht" id="widht" v-model="filters.widht" placeholder="Width" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="jalilenght" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Jali Length</label>
          <input type="text" name="jalilenght" id="jalilenght" v-model="filters.jalilenght" placeholder="Jali Length" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="sheetperbundle" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sheets Per Bundle</label>
          <input type="text" name="sheetperbundle" id="sheetperbundle" v-model="filters.sheetperbundle" placeholder="Sheets Per Bundle" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="orderedqty" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Ordered Quantity</label>
          <input type="text" name="orderedqty" id="orderedqty" v-model="filters.orderedqty" placeholder="Ordered Quantity" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="cutsheetqty" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Cut Sheet Quantity</label>
          <input type="text" name="cutsheetqty" id="cutsheetqty" v-model="filters.cutsheetqty" placeholder="Cut Sheet Quantity" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>

        <div>
          <label for="rem" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Rem</label>
          <input type="text" name="rem" id="rem" v-model="filters.rem" placeholder="Rem" class="border rounded px-2 py-1 w-full bg-background text-foreground" />
        </div>
      </div>

      <div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white dark:text-black font-bold py-2 px-4 rounded">Filter</button>
      </div>
    </form>
    <button id="orderSearchToggleBtn" @click="showSearchForm = !showSearchForm" type="button" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-400 dark:hover:bg-blue-600 text-white dark:text-black font-bold py-2 px-4 rounded mb-4">
      {{ showSearchForm ? 'Hide Search' : 'Search' }}
    </button>
    <div class="overflow-x-auto bg-background shadow rounded-lg">
      <table class="min-w-full divide-y divide-border bg-background text-foreground">
        <thead class="bg-background">
          <tr>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Date</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Party</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Lot</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Orignal Sheet</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Cuted Sheet</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Peice</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Orders Gauge</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Widht</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Jali Lenght</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Sheets Per Bundle</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Sheets</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Cuted Sheets Quantity</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Rem</th>
            <th class="p-2 py-2 text-left text-md font-medium text-foreground uppercase tracking-wider border-2 border-border">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-background divide-y divide-border">
          <tr v-for="order in orders" :key="order.id" class="hover:bg-accent dark:hover:bg-accent-dark transition-colors">
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.dateno }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.partyorder }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.lot }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.orgsheet }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.cutsheet }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.peice }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.ogauge }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.widht }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.jalilenght }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.sheetperbundle }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.orderedqty }}</td>
            <td class="px-4 py-2 text-md-end text-foreground border-2 border-border">{{ order.cutsheetqty }}</td>
            <td class="px-4 py-2 text-md text-foreground border-2 border-border">{{ order.rem }}</td>
            <td class="px-4 py-2 text-sm text-foreground border-2 border-border">
              <form @submit.prevent="$emit('delete', order.id)" class="inline-block">
                <button type="submit" class="bg-red-500 hover:bg-red-600 dark:bg-red-400 dark:hover:bg-red-600 text-white dark:text-black px-4 py-2 rounded-md transition">Delete</button>
              </form>
              <div class="h-12">
                <Link :href="route('order.toMachine', order.id)" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-400 dark:hover:bg-blue-600 text-white dark:text-black px-4 py-2 rounded-md transition">To M</Link>
              </div>
              <div>
                <Link :href="route('order.edit', order.id)" class="bg-cyan-500 hover:bg-cyan-600 dark:bg-cyan-400 dark:hover:bg-cyan-600 text-white dark:text-black px-4 py-2 rounded-md transition">Edit</Link>
              </div>
            </td>
          </tr>
          <tr v-if="!orders.length">
            <td colspan="14" class="px-4 py-4 text-center text-foreground opacity-60">No orders found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue'

const page = usePage();
const orders = computed(() => page.props.orders || []);
const success = computed(() => page.props.success);
const message = computed(() => page.props.message);
const error = computed(() => page.props.error);
const errors = computed(() => page.props.errors ? Object.values(page.props.errors) : []);
const filters = ref({
  from_date: '',
  to_date: '',
  partyorder: '',
  lot: '',
  orgsheet: '',
  cutsheet: '',
  peice: '',
  ogauge: '',
  widht: '',
  jalilenght: '',
  sheetperbundle: '',
  orderedqty: '',
  cutsheetqty: '',
  rem: ''
});
const showSearchForm = ref(false);

function route(name, id) {
  // This assumes Ziggy or similar is available globally
  return window.route ? window.route(name, id) : `/${name}/${id}`;
}

function submitSearch() {
  router.get(route('order-view'), { ...filters.value }, { preserveState: true, preserveScroll: true });
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
</style>
