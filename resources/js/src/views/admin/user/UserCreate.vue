<!-- =========================================================================================
  File Name: UserCreate.vue
  Description: User Create page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <vx-card>
    <div class="vx-row">
      <!-- Avatar Row -->
      <div class="vx-col w-full lg:w-3/12 pr-0">
        <input ref="avatar" type="file" @change="change_avatar" hidden>
        <img :src="srcAvatar" class="rounded mx-auto w-48 h-48 lg:mx-0 xl:mx-auto"/>

        <div class="btn-group flex justify-center lg:justify-start xl:justify-center mt-4">
          <vs-button size="small" @click="choose_avatar">{{ $t('upload_avatar') }}</vs-button>
          <vs-button size="small" type="border" color="danger" @click.prevent="remove_avatar">{{ $t('remove_avatar') }}</vs-button>
        </div>
      </div>
      <div class="vx-col w-full lg:w-9/12">
        <!-- Content Row -->
        <div class="vx-row">
          <div class="vx-col w-full md:w-1/2">
            <div class="container mx-auto md:p-0 lg:pl-4 xl:pl-0">
              <vs-input class="w-full mt-4" :label="$t('name')" v-model="user_data.name" v-validate="'required'"
                        name="name"/>
              <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first('name') }}</span>

              <vs-input class="w-full mt-4" label="Email" v-model="user_data.email" type="email"
                        v-validate="'required|email'" name="email"/>
              <span class="text-danger text-sm" v-show="errors.has('email')">{{ errors.first('email') }}</span>

              <div class="mt-4">
                <label class="vs-input--label">{{ $t('role') }}</label>
                <v-select v-model="role_local" :clearable="false" :options="roleOptions" v-validate="'required'"
                          name="role" :dir="$vs.rtl ? 'rtl' : 'ltr'"/>
                <span class="text-danger text-sm" v-show="errors.has('role')">{{ errors.first('role') }}</span>
              </div>
            </div>
          </div>

          <div class="vx-col w-full md:w-1/2">
            <div class="container mx-auto md:p-0">
              <vs-input class="w-full mt-4" :label="$t('password')" v-model="user_data.password" type="password"
                        v-validate="'required|min:6|max:20'" ref="password" name="password"/>
              <span class="text-danger text-sm" v-show="errors.has('password')">{{ errors.first('password') }}</span>

              <vs-input class="w-full mt-4" :label="$t('password_confirm')" v-model="user_data.confirm_password"
                        type="password" v-validate="'required|min:6|max:20|confirmed:password'" data-vv-as="password"
                        name="confirm_password"/>
              <span class="text-danger text-sm" v-show="errors.has('confirm_password')">{{ errors.first('confirm_password') }}</span>
            </div>
          </div>
        </div>

        <!-- Save & Reset Button -->
        <div class="vx-row">
          <div class="vx-col w-full">
            <div class="mt-8 flex justify-center md:justify-end">
              <vs-button class="md:ml-auto mt-2" type="border" color="primary" :to="{name: 'admin-users'}">{{ $t('back') }}</vs-button>
              <vs-button class="ml-4 mt-2" @click.prevent="save" :disabled="!validateForm">{{ $t('save') }}</vs-button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </vx-card>
</template>

<script>
import _ from 'lodash'
import vSelect from 'vue-select'
import moduleUserManagement from '@/store/admin/user-management/moduleUserManagement.js'

export default {
  components: {
    vSelect
  },
  data () {
    return {
      user_data: {
        name: null,
        email: null,
        avatar: null,
        role: null,
        password: null,
        confirm_password: null
      },
      avatar_preview: null,

      roleOptions: [
        { label: 'Admin', value: 1},
        { label: 'User', value: 2 }
      ]
    }
  },
  computed: {
    role_local: {
      get () {
        return _.find(this.roleOptions, (r) => r.value === this.user_data.role)
      },
      set (obj) {
        this.user_data.role = obj.value
      }
    },
    validateForm () {
      return !this.errors.any()
    },
    srcAvatar () {
      let img = require('@assets/images/profile/default-user.jpg')

      if (this.avatar_preview) {
        img = this.avatar_preview
      }

      return img
    }
  },
  methods: {
    choose_avatar () {
      this.$refs.avatar.click()
    },
    change_avatar (e) {
      const file = e.target.files[0]
      this.user_data.avatar = file
      this.avatar_preview = URL.createObjectURL(file)
    },
    remove_avatar () {
      this.user_data.avatar = null
      this.avatar_preview = null
      this.$refs.avatar.value = null
    },
    get_formdatas () {
      const data = new FormData()
      data.append('name', this.user_data.name)
      data.append('email', this.user_data.email)
      data.append('password', this.user_data.password)
      data.append('confirm_password', this.user_data.confirm_password)

      if (this.user_data.avatar) {
        data.append('avatar', this.user_data.avatar)
      }

      return data
    },
    save_role (userUUID) {
      const roleData = {role: this.user_data.role }
      this.$store.dispatch('userManagement/setRole', {userUUID, roleData})
        .then((response) => {
          this.$router.push({name: 'admin-users'})
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
    save () {
      if (!this.validateForm) return
      this.$validator.validateAll().then(statusValidator => {
        if (!statusValidator) return

        this.$store.dispatch('userManagement/addUser', this.get_formdatas())
          .then((response) => this.save_role(response.data.user.uuid))
          .catch(err => {
            this.$vs.notify({
              color: 'danger',
              text: err.response.data.message
            })
          })
      })
    }
  },
  created () {
    // Register Module UserManagement Module
    if (!moduleUserManagement.isRegistered) {
      this.$store.registerModule('userManagement', moduleUserManagement)
      moduleUserManagement.isRegistered = true
    }
  }
}
</script>
