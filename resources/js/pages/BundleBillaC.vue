<template>
  <div id="contentarea" class="max-w-[650px] border-[6px] border-dashed border-gray-400 bg-white mx-auto h-[425px] p-0 print:max-w-[600px] print:mt-[-5px]">
    <div id="billa">
      <h1 class="text-[70px] text-center font-extrabold m-0 font-sans">
        {{ stock.khana ?? '' }} &nbsp; {{ stock.sheet_size ?? '' }}
      </h1>
      <div id="cline1" class="h-[30px]"></div>
      <table class="w-full mb-0 border-0">
        <tr>
          <td class="align-top text-center w-1/3 p-0">
            <h2 class="text-[40px] font-black m-0 tracking-wider font-sans">
              {{ bundle.sheets_per_bundle ?? '' }}<span class="text-[22px]">ST</span>
            </h2>
          </td>
          <td class="align-top text-center w-1/3 p-0">
            <h2 class="text-[40px] font-black m-0 tracking-wider font-sans">
              ={{ (stock.jalilenght ?? order.jalilenght ?? 0) * (bundle.sheets_per_bundle ?? 0) }}<span class="text-[22px]">F</span>
            </h2>
          </td>
          <td class="align-top text-center w-1/3 p-0">
            <h3 class="text-[40px] font-black m-0 tracking-wider font-sans">
              {{ stock.sheet_size ?? order.cutsheet ?? '' }}
            </h3>
            <hr class="my-2 border-t-2 border-black w-full" />
            <div class="text-[32px] font-black tracking-wide font-sans">
              {{ stock.lot ?? order.lot ?? '' }}
            </div>
          </td>
        </tr>
      </table>
      <div id="bottombilla" class="mt-2">
        <div class="flex items-end justify-between">
          <div class="flex flex-col items-start">
            <div class="text-[28px] font-black tracking-wide font-sans">Date: {{ bundle.date ?? stock.date ?? '' }}</div>
            <div class="text-[28px] font-black tracking-wide font-sans">Mch #: {{ stock.machine_id ?? '' }}</div>
          </div>
          <div class="flex flex-col items-center">
            <div class="text-[44px] font-black leading-none tracking-wider font-sans">{{ stock.party_name ?? order.party_name ?? '' }}</div>
            <div class="text-[28px] font-black tracking-wide font-sans">Pck By: {{ stock.packed_by ?? bundle.packed_by ?? '' }}</div>
          </div>
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
@media print {
  #contentarea {
    max-width: 600px;
    margin-top: -5px;
    border-width: 2px;
  }
  body, html {
    background: white !important;
    padding: 0 !important;
    margin: 0 !important;
  }
}
</style>
