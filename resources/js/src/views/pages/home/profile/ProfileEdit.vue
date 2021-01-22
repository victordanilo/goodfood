<!-- =========================================================================================
  File Name: ProfileEdit.vue
  Description: Profile Edit Page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div id="page-profile-edit" class="container">

    <vx-card>
      <div slot="no-body" class="tabs-container px-6 pt-6">
        <vs-tabs class="tab-action-btn-fill-conatiner">
          <vs-tab :label="$t('account')" icon-pack="feather" icon="icon-users">
            <div class="tab-text">
              <profile-edit-tab-account class="mt-4" :data="profileData" :go_view="this.go_view" />
            </div>
          </vs-tab>
          <vs-tab :label="$t('address')" icon-pack="feather" icon="icon-map">
            <div class="tab-text">
              <profile-edit-tab-address class="mt-4 z-50" :data="addressData" :go_view="this.go_view" />
            </div>
          </vs-tab>
        </vs-tabs>
      </div>
    </vx-card>

  </div>
</template>

<script>
import _ from 'lodash'
import ProfileEditTabAccount from './ProfileEditTabAccount.vue'
import ProfileEditTabAddress from './ProfileEditTabAddress.vue'

export default {
  components: {
    ProfileEditTabAccount,
    ProfileEditTabAddress
  },
  computed: {
    profileData () {
      return _.clone(this.$store.state.userInfo)
    },
    addressData () {
      let address = {
        'uuid': '',
        'street': '',
        'n': '',
        'complement': '',
        'district': '',
        'zip_code': '',
        'city': '',
        'uf': ''
      }

      if (!_.isEmpty(this.$store.state.userAddress)) {
        address = _.clone(this.$store.state.userAddress)[0]
      }

      return address
    }
  },
  methods: {
    go_view () {
      this.$router.push({name:'home'})
    }
  },
  created () {
    if (this.$store.state.auth.isUserLoggedIn('customer')) {
      //load address
      this.$store.dispatch('loadUserAddress')
    }
  }
}
</script>

<style lang="scss">
#page-profile-edit {
  padding-top: 80px !important;
  padding-bottom: 50px !important;
}
</style>
