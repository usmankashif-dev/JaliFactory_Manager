<template>
  <div id="print-wrapper">
    <div
      id="wrapper"
      class="w-[450px] border-4 border-dashed border-gray-400 bg-white print:rotate-0"
      style="transform: rotate(90deg); transform-origin: center center;"
    >
      <!-- Black top bar -->
      <div
        class="bg-black text-white text-4xl font-extrabold py-3 tracking-wider flex items-center justify-between px-9"
        style="font-size: 50px;"
      >
        <span>{{ stock.khana ?? '' }}</span>
        <span>- {{ stock.ogauge ?? order.ogauge ?? '' }}g-{{ stock.b_width ?? '' }}-{{ stock.jalilenght ?? order.jalilenght ?? '' }}</span>
      </div>

      <!-- Main Numbers Row -->
      <div class="flex justify-around items-center py-6 px-6">
        <div class="text-4xl font-black">
          {{ bundle.sheets_per_bundle ?? '' }}<span class="text-[22px]">St</span>
        </div>
        <div class="text-4xl font-black">
          ={{ (stock.jalilenght ?? order.jalilenght ?? 0) * (bundle.sheets_per_bundle ?? 0) }}<span class="text-[22px]">F</span>
        </div>
        <div class="text-center">
          <div class="text-4xl font-black">{{ stock.sheet_size ?? order.cutsheet ?? '' }}</div>
          <hr class="my-2 border-t-2 border-black w-[90%] mx-auto" />
          <div class="text-2xl font-black">{{ stock.lot ?? order.lot ?? '' }}</div>
        </div>
      </div>

      <!-- Footer Info -->
      <div class="flex justify-between px-6 pb-6">
        <div class="flex flex-col gap-1 text-left text-xl font-bold">
          <div>Date: {{ bundle.date ?? stock.date ?? '' }}</div>
          <div>Mch #: {{ stock.machine_id ?? '' }}</div>
        </div>
        <div class="text-right">
          <div class="text-2xl font-bold">{{ stock.party_name ?? order.party_name ?? '' }}</div>
          <div class="text-lg font-bold">Pck By: {{ stock.packed_by ?? bundle.packed_by ?? '' }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
const props = defineProps({
  bundle: { type: Object, required: true },
  stock: { type: Object, required: true },
  order: { type: Object, required: false, default: () => ({}) },
});
</script>

<style scoped>
#print-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f5f5f5;
  overflow: hidden;
  padding-top: 60px;
  height: 100vh;
}

@media print {
  html,
  body {
    height: 100vh;
    width: 100vw;
    margin: 0 !important;
    padding: 0 !important;
    background: white !important;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #print-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    width: 100vw;
    padding: 0;
    margin: 0;
  }

  #wrapper {
    transform: none !important;
    max-width: 100%;
    max-height: 100%;
    margin: 0 auto;
    border-width: 2px;
  }
}
</style>
