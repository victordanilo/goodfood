<template>
  <div class="relative">
    <div class="vx-navbar-wrapper h-auto" :class="classObj">
      <vs-navbar class="vx-navbar navbar-custom navbar-skelton" :color="navbarColorLocal" :class="textColor">
        <logo />
        <vs-spacer />
        <search-bar />
        <cart-drop-down />
        <notification-drop-down v-show="$acl.check('customer')" />
        <profile-drop-down />
      </vs-navbar>
    </div>
  </div>
</template>

<script>
import Logo from './components/Logo'
import SearchBar from './components/SearchBar.vue'
import CartDropDown from './components/CartDropDown'
import NotificationDropDown from './components/NotificationDropDown.vue'
import ProfileDropDown from './components/ProfileDropDown.vue'

export default {
  name: 'Navbar',
  props: {
    navbarColor: {
      type: String,
      default: '#fff'
    }
  },
  components: {
    Logo,
    SearchBar,
    CartDropDown,
    NotificationDropDown,
    ProfileDropDown
  },
  computed: {
    navbarColorLocal () {
      return this.$store.state.theme === 'dark' && this.navbarColor === '#fff' ? '#10163a' : this.navbarColor
    },
    verticalNavMenuWidth () {
      return this.$store.state.verticalNavMenuWidth
    },
    textColor () {
      return {'text-white': (this.navbarColor !== '#10163a' && this.$store.state.theme === 'dark') || (this.navbarColor !== '#fff' && this.$store.state.theme !== 'dark')}
    },
    windowWidth () {
      return this.$store.state.windowWidth
    },

    // NAVBAR STYLE
    classObj () {
      if      (this.verticalNavMenuWidth === 'default') return 'navbar-default'
      else if (this.verticalNavMenuWidth === 'reduced') return 'navbar-reduced'
      else if (this.verticalNavMenuWidth)               return 'navbar-full'
    }
  },
  methods: {}
}
</script>
