<template>
  <div class="vx-logo flex items-center cursor-pointer" @click.prevent="$router.push({name:'home'}).catch(() => {})">
    <img src="/images/logo/logo.png" class="w-10 mr-4" alt="">
  </div>
</template>
