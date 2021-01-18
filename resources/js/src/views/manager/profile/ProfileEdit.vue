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
              <profile-edit-tab-address class="mt-4 z-50" :data="profileData" :go_view="this.go_view" />
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
    }
  },
  methods: {
    go_view () {
      this.$router.push({name:'manager-dashboard'})
    }
  }
}
</script>

<style lang="scss">
#page-profile-edit {
  padding-top: 30px !important;
}
</style>
