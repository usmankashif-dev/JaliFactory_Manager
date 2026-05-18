

<template>
  <div id="contentarea" class="max-w-[700px] mx-auto mt-0 print:max-w-[760px] print:mt-[-5px]">
    <div id="billa">
      <h1 class="text-[70px] font-bold text-center">{{ billa.lot }}</h1>
      <div id="cline1" class="my-8">
        <table class="w-full border-0">
          <tr>
            <td class="align-top text-center"><h2>{{ billa.sheets }} Sht</h2></td>
            <td class="align-top text-center"><h2>= {{ billa.feet }} F</h2></td>
            <td class="align-top text-center">
              <h3>
                <span>
                  <b>{{ billa.bundle_info }} <hr class="my-2" /> {{ billa.lot_detail }}</b>
                </span>
              </h3>
            </td>
          </tr>
        </table>
      </div>
      <div id="bottombilla" class="mt-8">
        <p>Date: {{ billa.date }}</p>
        <p class="text-[46px]">{{ billa.jw }}</p>
        <p>Mch #: {{ billa.machine_no }}</p>
        <p>Pck By: {{ billa.packed_by }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
const props = defineProps({
  billa: {
    type: Object,
    required: true,
  }
});
</script>

<style scoped>
@media print {
  #contentarea {
    max-width: 760px;
    margin-top: -5px;
  }
}
</style>
