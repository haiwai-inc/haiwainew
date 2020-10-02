<template>
      <div class="p-3">
        <haiwai-logo-white></haiwai-logo-white>
      </div>
</template>
<script>
import {HaiwaiLogoWhite} from '@/components/Icons';
export default {
  name: 'left-bar',
 
  data() {
    return {
      checkboxes: {
        unchecked: false,
        checked: true,
        disabledUnchecked: false,
        disabledChecked: true
      }
    };
  },
  components: {
    HaiwaiLogoWhite
  }
};
</script>
<style>

</style>
