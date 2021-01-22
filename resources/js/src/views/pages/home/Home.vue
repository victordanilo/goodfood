<template>
  <div>
    <slide-header id="slide-header" />
    <div class="h-auto">
      <div class="vx-row">
        <div class="vx-col w-full">
          <h1 class="text-center font-bold mt-12">Produtos</h1>
        </div>
        <div class="vx-col w-full">
          <div class="container mx-auto mt-12">
            <shop-browse />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SlideHeader from './SlideHeader'
import ShopBrowse from './shop/Browse'

export default {
  components: {
    SlideHeader,
    ShopBrowse
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
