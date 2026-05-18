<template>
  <div class="bg-background text-foreground min-h-screen">
    <div class="w-full p-4">
      <form @submit.prevent="searchMall" class="p-4 bg-background shadow rounded-md mb-4 flex flex-wrap gap-4 items-center">
        <input v-model="filters.party" type="text" name="party" placeholder="Party" class="w-full sm:w-40 h-10 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 bg-background text-foreground">
        <input v-model="filters.input1" type="text" name="input1" placeholder="Gauge" class="w-full sm:w-40 h-10 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 bg-background text-foreground">
        <input v-model="filters.input2" type="text" name="input2" placeholder="Length" class="w-full sm:w-40 h-10 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 bg-background text-foreground">
        <input v-model="filters.input3" type="text" name="input3" placeholder="Width" class="w-full sm:w-40 h-10 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 bg-background text-foreground">
        <input v-model="filters.input4" type="text" name="input4" placeholder="Material" class="w-full sm:w-40 h-10 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 bg-background text-foreground">
        <input v-model="filters.input5" type="text" name="input5" placeholder="Date" class="w-full sm:w-40 h-10 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 bg-background text-foreground">
        <input v-model="filters.input7" type="number" name="input7" placeholder="Quantity" class="w-full sm:w-40 h-10 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 bg-background text-foreground">
        <input v-model="filters.lot" type="text" name="lot" placeholder="Lot" class="w-full sm:w-40 h-10 p-2 border border-blue-400 rounded-lg bg-background text-foreground font-semibold tracking-wide">
        <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-500 text-white dark:bg-blue-400 dark:text-black rounded-lg hover:bg-blue-600 dark:hover:bg-blue-300 transition font-bold shadow">Search</button>
      </form>
      <form @submit.prevent="submitNewStock" class="fw mx-auto p-6 bg-background shadow-lg rounded-md animate-fade-in space-y-6" v-show="showForm">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="party">Select Party:</label>
      <select v-model="newStockForm.party" @change="handlePartyChange" class="w-full bg-background text-foreground">
        <option value="">--Select Party--</option>
        <option v-for="party in parties" :key="party.name" :value="party.name">{{ party.name }}</option>
        <option value="add_new">+ Add New Party</option>
      </select>
    </div>

    <div v-show="showAddPartyInput" class="md:col-span-2">
      <input v-model="newPartyName" placeholder="Enter new party" class="w-full sm:w-40 h-8 p-2 border rounded-md bg-background text-foreground" />
      <button type="button" @click="addParty" class="mt-2 w-full px-4 py-2 bg-blue-500 text-white dark:bg-blue-400 dark:text-black rounded-md hover:bg-blue-600 dark:hover:bg-blue-300">Add</button>
    </div>

    <div><label>Gauge:</label><input v-model="newStockForm.input1" type="number" step="0.01" class="w-full bg-background text-foreground" required></div>
    <div><label>Length:</label><input v-model="newStockForm.input2" type="number" step="0.01" class="w-full bg-background text-foreground" required></div>
    <div><label>Width:</label><input v-model="newStockForm.input3" type="number" step="0.01" class="w-full bg-background text-foreground" required></div>
    <div><label>Material:</label><input v-model="newStockForm.input4" type="text" class="w-full bg-background text-foreground"></div>
    <div><label>Date:</label><input v-model="newStockForm.input5" type="date" class="w-full bg-background text-foreground"></div>
    <div><label>Lot:</label><input v-model="newStockForm.lot" type="text" readonly class="w-full bg-background text-foreground"></div>
    <div><label>Quantity:</label><input v-model="newStockForm.input7" type="text" class="w-full bg-background text-foreground"></div>

    <div class="md:col-span-2">
      <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white dark:bg-green-400 dark:text-black rounded-md hover:bg-green-600 dark:hover:bg-green-300">Add New Stock</button>
    </div>
  </div>
</form>

      <div class="flex center">
        <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white dark:bg-green-400 dark:text-black rounded-md hover:bg-green-600 dark:hover:bg-green-300 transition" @click="showForm = !showForm">Add New Stock</button>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-300 shadow-md rounded-lg animate-fade-in bg-background text-foreground">
          <thead class="bg-background text-foreground">
            <tr>
              <th class="px-4 py-2 text-left chart">Date</th>
              <th class="px-4 py-2 text-left chart">Party</th>
              <th class="px-4 py-2 text-left chart">Widht</th>
              <th class="px-4 py-2 text-left chart">Lenght</th>
              <th class="px-4 py-2 text-left chart">Gauge</th>
              <th class="px-4 py-2 text-left chart">Lot</th>
              <th class="px-4 py-2 text-left chart">Material</th>
              <th class="px-4 py-2 text-left chart">Quantity</th>
              <th class="px-4 py-2 text-left chart textr">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="mall in malls" :key="mall.id" class="hover:bg-background transition">
              <td class="px-4 py-2 chart">{{ mall.input5 }}</td>
              <td class="px-4 py-2 chart">{{ mall.party }}</td>
              <td class="px-4 py-2 chart">{{ mall.input3 }}</td>
              <td class="px-4 py-2 chart">{{ mall.input2 }}</td>
              <td class="px-4 py-2 chart">{{ mall.input1 }}</td>
              <td class="px-4 py-2 chart">{{ mall.lot }}</td>
              <td class="px-4 py-2 chart">{{ mall.input4 }}</td>
              <td class="px-4 py-2 chart">{{ mall.availableqty }}</td>
              <td class="px-4 py-2 chart">
                <div class="flex flex-col sm:flex-row justify-center items-center gap-2">
                  <form @submit.prevent="deleteMall(mall.id)" class="inline-block">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white dark:bg-red-400 dark:text-black px-4 py-2 rounded-md transition">Delete</button>
                  </form>
                  <a :href="route('mall.edit', mall.id)" class="bg-cyan-500 hover:bg-cyan-600 text-white dark:bg-cyan-400 dark:text-black px-4 py-2 rounded-md transition textr">Edit</a>
                  <a :href="route('order.create', mall.id)" class="bg-cyan-500 hover:bg-cyan-600 text-white dark:bg-cyan-400 dark:text-black px-4 py-2 rounded-md transition textr">C Order</a>
                  <a :href="route('makeorder.show', mall.id)" class="bg-cyan-500 hover:bg-cyan-600 text-white dark:bg-cyan-400 dark:text-black px-4 py-2 rounded-md transition textr">M Order</a>
                </div>
              </td>
            </tr>
            <tr v-if="malls.length === 0">
              <td colspan="15" class="px-4 py-4 text-center text-foreground">No stocks found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref, reactive } from 'vue' // ✅ correct
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({
  layout: AppLayout
})

const showForm = ref(false)

function handlePartyChange() {
  if (newStockForm.party === 'add_new') {
    showAddPartyInput.value = true
  } else {
    showAddPartyInput.value = false
  }
}

function addParty() {
  if (newPartyName.value.trim()) {
    parties.value.push({ name: newPartyName.value })
    newStockForm.party = newPartyName.value
    newPartyName.value = ''
    showAddPartyInput.value = false
  }
}

function submitNewStock() {
  router.post(route('addmall.submit'), {
    ...newStockForm
  })
}


const newStockForm = reactive({
  party: '',
  input1: '',
  input2: '',
  input3: '',
  input4: '',
  input5: '',
  lot: '',
  input7: ''
})

const parties = ref([]) // get from props or backend

// if "Add New Party" is selected
const showAddPartyInput = ref(false)
const newPartyName = ref('')

const props = defineProps({
  malls: { type: Array, required: true }
});


const filters = reactive({
  party: '',
  input1: '',
  input2: '',
  input3: '',
  input4: '',
  input5: '',
  input7: '',
  lot: ''
});

function searchMall() {
  router.get(route('mall-view'), { ...filters });
}

function deleteMall(id) {
  if (confirm('Are you sure you want to delete this stock?')) {
    router.post(route('mall.delete', id), { _method: 'DELETE' });
  }
}

function makeMOrder(id) {
  router.post(route('order.make', id));
}
</script>

<style scoped>
@media print {
  .max-w-7xl, .max-w-2xl, .max-w-xl {
    max-width: 100vw !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
  }
}
</style>
