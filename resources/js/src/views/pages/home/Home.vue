<template>
  <div>
    <slide-header id="slide-header" />
  </div>
</template>

<script>
import SlideHeader from './SlideHeader'

export default {
  components: {
    SlideHeader
  }
}
</script>

<style lang="scss">
#slide-header {
  height: 260px;

  @media screen and (min-width:480px) {
    height: calc(100vh - 60px);
    margin-top: 0px;
  }
}
</style>
