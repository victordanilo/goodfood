<template>
    <div :style="{'direction': $vs.rtl ? 'rtl' : 'ltr'}">
      <feather-icon icon="EyeIcon" svgClasses="h-5 w-5 mr-4 hover:text-primary cursor-pointer" @click="viewOrder" />
    </div>
</template>

<script>
export default {
  name: 'CellRendererActions',
  methods: {
    viewOrder () {
      this.$router.push({name: 'order-view', params: {orderUUID: this.params.data.uuid} }).catch(() => {})
    }
  }
}
</script>
