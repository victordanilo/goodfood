<!-- =========================================================================================
  File Name: UserView.vue
  Description: User View page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div id="page-user-view">
    <vs-alert color="danger" :title="title_user_not_found" :active.sync="user_not_found">
      <span>{{ $t('notify_user_not_found',{prop:'uuid', value: $route.params.userUUID}) }}</span>
      <span>
        <span>{{ $t('check') }} </span><router-link :to="{name:'admin-users'}" class="text-inherit underline">{{ $t('all_users') }}</router-link>
      </span>
    </vs-alert>

    <div id="user-data" v-if="user_data">
      <vx-card :title="title_account" class="mb-base">
        <template slot="actions">
          <div class="vx-card__code-toggler sm:block hidden">
            <vs-button color="dark" type="flat" :to="{name: 'admin-users'}">{{$t('back')}}</vs-button>
          </div>
        </template>

        <div class="vx-row">
          <!-- Avatar Col -->
          <div class="vx-col" id="avatar-col">
            <div class="img-container mb-4">
              <img :src="srcAvatar" class="rounded w-full" />
            </div>
          </div>
          <!-- /Avatar Col -->

          <!-- Information - Col 1 -->
          <div class="vx-col flex-1" id="account-info-col-1">
            <table>
              <tr>
                <td class="font-semibold">{{ $t('name') }}</td>
                <td>{{ user_data.name }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Email</td>
                <td>{{ user_data.email }}</td>
              </tr>
            </table>
          </div>
          <!-- /Information - Col 1 -->

          <!-- Information - Col 2 -->
          <div class="vx-col flex-1" id="account-info-col-2">
            <table>
              <tr>
                <td class="font-semibold">{{ $t('role') }}</td>
                <td>{{ user_data.role.name }}</td>
              </tr>
            </table>
          </div>
          <!-- /Information - Col 2 -->

          <div class="vx-col w-full flex" id="account-manage-buttons">
            <vs-button icon-pack="feather" icon="icon-edit" class="mr-4" :to="{name: 'admin-user-edit', params: {userUUID: $route.params.userUUID}}">{{ $t('edit') }}</vs-button>
            <vs-button type="border" color="danger" icon-pack="feather" icon="icon-trash" @click="confirmDeleteRecord">{{ $t('delete') }}</vs-button>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import helpers from '@/helpers'
import moduleUserManagement from '@/store/admin/user-management/moduleUserManagement.js'

export default {
  data () {
    return {
      title_account: this.$i18n.t('account'),
      title_user_not_found: this.$i18n.t('user_not_found'),
      user_data: null,
      user_not_found: false
    }
  },
  computed: {
    srcAvatar () {
      let img = require('@assets/images/profile/default-user.jpg')

      if (this.user_data.avatar) {
        img = helpers.assets(`storage/user/avatar/${this.user_data.avatar}`)
      }

      return img
    }
  },
  methods: {
    confirmDeleteRecord () {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: this.$i18n.t('delete_confirm.title'),
        text: this.$i18n.t('delete_confirm.text', {username: this.user_data.name}),
        accept: this.deleteRecord,
        acceptText: 'Delete'
      })
    },
    deleteRecord () {
      this.$store.dispatch('userManagement/removeUser', this.user_data.uuid)
        .then((response) => {
          this.$router.push({name:'admin-users'})
          this.$vs.notify({
            color: 'success',
            text: response.data.message
          })
        })
        .catch(err => {
          this.$vs.notify({
            color: 'danger',
            text: err.response.data.message
          })
        })
    },
    setUser (user) {
      this.user_data = _.clone(user)
      this.user_data.role = this.user_data.roles[0] || {name: ''}
    }
  },
  created () {
    // Register Module UserManagement Module
    if (!moduleUserManagement.isRegistered) {
      this.$store.registerModule('userManagement', moduleUserManagement)
      moduleUserManagement.isRegistered = true
    }

    const userUUID = this.$route.params.userUUID
    this.$store.dispatch('userManagement/fetchUser', userUUID)
      .then(res => { this.setUser(res.data) })
      .catch(err => {
        if (err.response.status === 404) {
          this.user_not_found = true
          return
        }
        console.error(err)
      })
  }
}
</script>

<style lang="scss">
#avatar-col {
  width: 10rem;
}

#page-user-view {
  table {
    td {
      vertical-align: top;
      min-width: 140px;
      padding-bottom: .8rem;
      word-break: break-all;
    }

    &:not(.permissions-table) {
      td {
        @media screen and (max-width:370px) {
          display: block;
        }
      }
    }
  }
}

@media screen and (min-width:1201px) and (max-width:1211px), only screen and (min-width:636px) and (max-width:991px) {
  #account-info-col-1 {
    width: calc(100% - 12rem) !important;
  }
}
</style>
