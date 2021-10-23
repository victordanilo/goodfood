<template>
  <div class="layout--main navbar-sticky">
    <navbar />
    <div style="margin-top: 60px">
        <router-view></router-view>
    </div>
  </div>
</template>

<script>
import Navbar from './navbar/Navbar'

export default {
  components: {
    Navbar
  }
}
</script>
