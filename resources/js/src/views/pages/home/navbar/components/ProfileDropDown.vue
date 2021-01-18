<template>
  <div>
    <div class="the-navbar__user-meta flex items-center" v-if="isUserLoggedIn">

      <div class="text-right leading-tight hidden sm:block">
        <p class="font-semibold">{{ activeUserInfo.name }}</p>
        <small></small>
      </div>

      <vs-dropdown vs-custom-content vs-trigger-click class="cursor-pointer">

        <div class="con-img ml-3">
          <img key="onlineImg" :src="avatar" alt="user-img" width="40" height="40" class="rounded-full shadow-md cursor-pointer block"/>
        </div>

        <vs-dropdown-menu class="vx-navbar-dropdown">
          <ul style="min-width: 9rem">
            <li
              class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
              @click="$router.push({name: 'orders'}).catch(() => {})">
              <feather-icon icon="FileTextIcon" svgClasses="w-4 h-4" />
              <span class="ml-2">{{ $t('orders') }}</span>
            </li>
            <li
              class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
              @click="$router.push({name: 'profile'}).catch(() => {})">
              <feather-icon icon="FileTextIcon" svgClasses="w-4 h-4" />
              <span class="ml-2">{{ $t('profile') }}</span>
            </li>
            <vs-divider class="m-1" />
            <li
              class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
              @click="logout">
              <feather-icon icon="LogOutIcon" svgClasses="w-4 h-4"/>
              <span class="ml-2">Logout</span>
            </li>
          </ul>
        </vs-dropdown-menu>
      </vs-dropdown>
    </div>
    <vs-button color="primary" type="border" icon-pack="feather" icon="con-lock" v-else to="/auth/login">Logar</vs-button>
  </div>
</template>

<script>
import helpers from '@/helpers'

export default {
  data () {
    return {}
  },
  computed: {
    activeUserInfo () {
      return this.$store.state.userInfo
    },
    avatar () {
      let img = require('@assets/images/profile/default-user.jpg')

      if (this.activeUserInfo.avatar) {
        img = helpers.assets(`storage/customer/avatar/${this.activeUserInfo.avatar}`)
      }

      return img
    },
    isUserLoggedIn () {
      return this.activeUserInfo.userRole === 'customer'
    }
  },
  methods: {
    logout () {
      // If JWT login
      if (localStorage.getItem('accessToken')) {
        localStorage.removeItem('accessToken')
        localStorage.removeItem('userInfo')

        this.$acl.change('public')
        this.$store.dispatch('resetUser')

        const redirectLogin = localStorage.getItem('redirectLogin')
        if (this.$route.name !== redirectLogin) this.$router.push({name: redirectLogin}).catch(() => {})
      }
    }
  }
}
</script>
