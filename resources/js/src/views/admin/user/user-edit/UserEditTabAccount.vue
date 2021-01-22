<!-- =========================================================================================
  File Name: UserEditTabInformation.vue
  Description: User Edit Information Tab content
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div class="vx-row">
    <!-- Avatar Row -->
    <div class="vx-col w-full lg:w-3/12 pr-0">
      <input ref="avatar" type="file" @change="change_avatar" hidden>
      <img :src="srcAvatar" class="rounded mx-auto w-48 h-48 lg:mx-0 xl:mx-auto"/>

      <div class="btn-group flex justify-center lg:justify-start xl:justify-center mt-4">
        <vs-button size="small" @click="choose_avatar">{{ $t('change_avatar') }}</vs-button>
        <vs-button size="small" type="border" color="danger" @click.prevent="remove_avatar">{{ $t('remove_avatar') }}</vs-button>
      </div>
    </div>

    <!-- Content Row -->
    <div class="vx-col w-full lg:w-9/12">
      <div class="vx-row">
        <div class="vx-col w-full md:w-1/2">
          <div class="container mx-auto md:p-0 lg:pl-4 xl:pl-0">
            <vs-input class="w-full mt-4" :label="$t('name')" v-model="data_local.name" v-validate="'required|min:3|max:255'" name="name" />
            <span class="text-danger text-sm"  v-show="errors.has('name')">{{ errors.first('name') }}</span>

            <vs-input class="w-full mt-4" label="Email" v-model="data_local.email" type="email" v-validate="'required|email'" name="email" />
            <span class="text-danger text-sm"  v-show="errors.has('email')">{{ errors.first('email') }}</span>

            <div class="mt-4">
              <label class="vs-input--label">{{ $t('role') }}</label>
              <v-select v-model="role_local" :clearable="false" :options="roleOptions" v-validate="'required'" name="role" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
              <span class="text-danger text-sm"  v-show="errors.has('role')">{{ errors.first('role') }}</span>
            </div>
          </div>
        </div>

        <div class="vx-col w-full md:w-1/2">
          <div class="container mx-auto md:p-0">
            <vs-input class="w-full mt-4" :label="$t('password')" v-model="password" type="password" v-validate="'min:6|max:20'" ref="password" name="password" />
            <span class="text-danger text-sm"  v-show="errors.has('password')">{{ errors.first('password') }}</span>

            <vs-input  class="w-full mt-4" :label="$t('password_confirm')" v-model="confirm_password" type="password" v-validate="'min:6|max:20|confirmed:password'" data-vv-as="password" name="confirm_password" />
            <span class="text-danger text-sm" v-show="errors.has('confirm_password')">{{ errors.first('confirm_password') }}</span>
          </div>
        </div>
      </div>

      <!-- Save & Reset Button -->
      <div class="vx-row">
        <div class="vx-col w-full">
          <div class="mt-8 flex justify-center md:justify-end">
            <vs-button class="md:ml-auto mt-2" type="border" color="primary" @click="go_view">{{ $t('back') }}</vs-button>
            <vs-button class="ml-4 mt-2" @click="save_changes" :disabled="validateForm">{{ $t('save') }}</vs-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import helpers from '@/helpers'
import vSelect from 'vue-select'

export default {
  components: {
    vSelect
  },
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      data_local: _.pick(this.data, ['uuid', 'name', 'cpf_cnpj', 'email', 'phone', 'avatar', 'role', 'roles']),
      avatar_preview: null,
      password: null,
      confirm_password: null,
      status_update: false,

      roleOptions: [
        { label: 'Admin', value: 1 },
        { label: 'User', value: 2 }
      ]
    }
  },
  computed: {
    role_local: {
      get () {
        return _.find(this.roleOptions, (r) => r.value === this.data_local.role)
      },
      set (obj) {
        this.data_local.role = obj.value
      }
    },
    validateForm () {
      const userData = this.get_updates()

      return this.errors.any() || _.isEmpty(userData)
    },
    srcAvatar () {
      let img = require('@assets/images/profile/default-user.jpg')

      if (!_.isEmpty(this.data_local.avatar) && _.isEmpty(this.avatar_preview)) {
        img = helpers.assets(`storage/user/avatar/${this.data_local.avatar}`)
      }
      if (!_.isEmpty(this.avatar_preview)) {
        img = this.avatar_preview
      }

      return img
    }
  },
  methods: {
    get_updates () {
      const userData = helpers.diff_JSON(this.data, this.data_local)

      // Add password à lista de updates
      if ((!_.isEmpty(this.password) && !_.isEmpty(this.confirm_password)) && this.password === this.confirm_password) {
        userData.password = this.password
      }

      return userData
    },
    get_formdatas () {
      const formDatas = new FormData()
      const updateDatas = this.get_updates()

      for (const key in updateDatas) {
        formDatas.append(key, updateDatas[key])
      }

      return formDatas
    },
    save_account (userUUID, userData) {
      return this.$store.dispatch('userManagement/updateUser', {userUUID, userData})
        .then(() => this.go_view())
        .catch(err => Promise.reject(err.response.data.message))
    },
    save_role (userUUID, userData) {
      const roleData = {role: this.data_local.role }
      return this.$store.dispatch('userManagement/setRole', {userUUID, roleData})
        .then(() => {
          userData.delete('role')
          return userData
        })
        .catch(err => Promise.reject(err.response.data.message))
    },
    save_changes () {
      if (this.validateForm) return

      this.$validator.validateAll().then(statusValidator => {
        if (!statusValidator) return

        const userUUID = this.data_local.uuid
        const userData = this.get_formdatas()
        const save = () => new Promise((resolve) => { resolve(userData) })

        save()
          // salvar atualização de função
          .then(userData => {
            if (!userData.has('role')) return userData
            return this.save_role(userUUID, userData)
          })
          // verifica se há atualizações
          .then(userData => {
            if (userData.entries().next().done) {
              this.go_view()
              return Promise.reject('finish')
            }

            return userData
          })
          // carrega o storage do vue
          .then(userData => {
            if (!_.isEmpty(this.$store.state.userManagement.users)) return userData

            return this.$store.dispatch('userManagement/fetchUsers')
              .then(() => userData)
              .catch(err => Promise.reject(err.response.data.message))
          })
          // salvar atualização de conta
          .then(userData => this.save_account(userUUID, userData))
          .catch(err => {
            if (err === 'finish') return

            this.$vs.notify({
              color: 'danger',
              text: err
            })
          })
      })
    },
    choose_avatar () {
      this.$refs.avatar.click()
    },
    change_avatar (e) {
      const file = e.target.files[0]
      this.data_local.avatar = file
      this.avatar_preview = URL.createObjectURL(file)
    },
    remove_avatar () {
      this.data_local.avatar = ''
      this.avatar_preview = null
      this.$refs.avatar.value = null
    },
    go_view () {
      this.$router.push({name:'admin-user-view', params: {userUUID: this.$route.params.userUUID}})
    }
  }
}
</script>
