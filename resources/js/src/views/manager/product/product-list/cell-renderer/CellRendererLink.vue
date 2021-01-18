<template>
  <div class="flex items-center">
    <vs-avatar :src="productImg" class="flex-shrink-0 mr-2" size="30px" @click="$router.push(url)" />
    <router-link :to="url" @click.stop.prevent class="text-inherit hover:text-primary">{{ params.value }}</router-link>
  </div>
</template>

<script>
import helpers from '@/helpers'

export default {
  name: 'CellRendererLink',
  computed: {
    url () {
      return `/manager/product/product-view/${this.params.data.uuid}`
    },
    productImg () {
      let img = require('@assets/images/default_food.jpg')

      if (this.params.data.image) {
        img = helpers.assets(`storage/product/${this.params.data.image}`)
      }

      return img
    }
  }
}
</script>
