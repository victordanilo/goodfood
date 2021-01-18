<!-- =========================================================================================
  File Name: UserEdit.vue
  Description: User Edit Page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div id="page-user-edit">

    <vs-alert color="danger" :title="title_user_not_found" :active.sync="user_not_found">
      <span>{{ $t('notify_user_not_found', {prop:'uuid', value: $route.params.userUUID}) }}</span>
      <span>
        <span>{{ $t('check') }} </span><router-link :to="{name:'admin-users'}" class="text-inherit underline">{{ $t('all_users') }}</router-link>
      </span>
    </vs-alert>

    <vx-card v-if="user_data">
      <user-edit-tab-account class="mt-4" :data="user_data" />
    </vx-card>
  </div>
</template>

<script>
import _ from 'lodash'
import UserEditTabAccount from './UserEditTabAccount.vue'

// Store Module
import moduleUserManagement from '@/store/admin/user-management/moduleUserManagement.js'

export default {
  components: {
    UserEditTabAccount
  },
  data () {
    return {
      title_user_not_found: this.$i18n.t('user_not_found'),
      user_data: null,
      user_not_found: false
    }
  },
  methods: {
    fetch_user_data (userUUID) {
      this.$store.dispatch('userManagement/fetchUser', userUUID)
        .then(res => this.setUser(res.data))
        .catch(err => {
          if (err.response.status === 404) {
            this.user_not_found = true
            return
          }
          console.error(err)
        })
    },
    setUser (user) {
      this.user_data = _.clone(user)
      const role = user.roles.shift()
      this.user_data.role = !_.isEmpty(role) ? role.id : null
    }
  },
  created () {
    // Register Module UserManagement Module
    if (!moduleUserManagement.isRegistered) {
      this.$store.registerModule('userManagement', moduleUserManagement)
      moduleUserManagement.isRegistered = true
    }

    this.fetch_user_data(this.$route.params.userUUID)
  }
}
</script>
