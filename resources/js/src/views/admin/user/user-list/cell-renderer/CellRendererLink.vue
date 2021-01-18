<template>
  <div class="flex items-center">
    <vs-avatar :src="srcAvatar" class="flex-shrink-0 mr-2" size="30px" @click="$router.push(url)" />
    <router-link :to="url" @click.stop.prevent class="text-inherit hover:text-primary">{{ params.value }}</router-link>
  </div>
</template>

<script>
import helpers from '@/helpers'

export default {
  name: 'CellRendererLink',
  computed: {
    url () {
      return `/admin/user/user-view/${this.params.data.uuid}`
    },
    srcAvatar () {
      let img = null

      if (this.params.data.avatar) {
        img = helpers.assets(`storage/user/avatar/${this.params.data.avatar}`)
      }

      return img
    }
  }
}
</script>
