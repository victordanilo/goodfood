<!-- =========================================================================================
  File Name: CustomerEditTabInformation.vue
  Description: Customer Edit Information Tab content
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div class="vx-row">
    <!-- Avatar Row -->
    <div class="vx-col w-full lg:w-3/12 pr-0">
      <input ref="profileImg" type="file" @change="change_avatar" hidden>
      <img :src="srcAvatar" class="rounded mx-auto w-48 h-48 lg:mx-0 xl:mx-auto"/>

      <div class="btn-group flex justify-center lg:justify-start xl:justify-center mt-4">
        <vs-button size="small" @click="choose_avatar">{{ $t('change_avatar') }}</vs-button>
        <vs-button size="small" type="border" color="danger" @click.prevent="remove_avatar">{{ $t('remove_avatar') }}</vs-button>
      </div>
    </div>

    <div class="vx-col w-full lg:w-9/12">
      <!-- Content Row -->
      <div class="vx-row">
        <div class="vx-col w-full md:w-1/2">
          <div class="container mx-auto md:p-0 lg:pl-4 xl:pl-0">
            <vs-input class="w-full mt-4" :label="$t('name')" v-model="data_local.name" v-validate="'required|min:3|max:255'" name="name"/>
            <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first('name') }}</span>

            <vs-input class="w-full mt-4" label="CNPJ/CPF" v-model="data_local.cpf_cnpj" v-mask="['###.###.###-##', '##.###.###/####-##']" name="cpf_cnpj"/>
            <span class="text-danger text-sm" v-show="errors.has('cpf_cnpj')">{{ errors.first('cpf_cnpj') }}</span>

            <the-mask class="w-full mt-4" :label="$t('phone')" v-model="data_local.phone" :mask="['(##) ####-####', '(##) #####-####']" name="phone" />
            <span class="text-danger text-sm" v-show="errors.has('phone')">{{ errors.first('phone') }}</span>
          </div>
        </div>

        <div class="vx-col w-full md:w-1/2">
          <div class="container mx-auto md:p-0">
            <vs-input class="w-full mt-4" label="Email" v-model="data_local.email" type="email"
                      v-validate="'required|email'" name="email"/>
            <span class="text-danger text-sm" v-show="errors.has('email')">{{ errors.first('email') }}</span>

            <vs-input class="w-full mt-4" :label="$t('password')" v-model="password" type="password"
                      v-validate="'min:6|max:20'" ref="password" name="password"/>
            <span class="text-danger text-sm" v-show="errors.has('password')">{{ errors.first('password') }}</span>

            <vs-input class="w-full mt-4" :label="$t('password_confirm')" v-model="confirm_password" type="password"
                      v-validate="'min:6|max:20|confirmed:password'" data-vv-as="password"
                      name="confirm_password"/>
            <span class="text-danger text-sm" v-show="errors.has('confirm_password')">{{ errors.first('confirm_password') }}</span>
          </div>
        </div>
      </div>

      <!-- Save & Reset Button -->
      <div class="vx-row">
        <div class="vx-col w-full">
          <div class="mt-8 flex justify-center md:justify-end">
            <vs-button class="md:ml-auto mt-2" type="border" color="primary" @click="go_view">{{ $t('back') }}
            </vs-button>
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

export default {
  props: {
    data: {
      type: Object,
      required: true
    },
    go_view: {
      required: true
    }
  },
  data () {
    return {
      data_local: _.pick(this.data, ['uuid', 'name', 'cpf_cnpj', 'email', 'phone', 'avatar']),
      avatar_preview: null,
      password: null,
      confirm_password: null
    }
  },
  computed: {
    validateForm () {
      const customerData = this.get_updates()

      return this.errors.any() || _.isEmpty(customerData)
    },
    srcAvatar () {
      let img = require('@assets/images/profile/default-user.jpg')

      if (!_.isEmpty(this.data_local.avatar) && _.isEmpty(this.avatar_preview)) {
        img = helpers.assets(`storage/customer/avatar/${this.data_local.avatar}`)
      }

      if (!_.isEmpty(this.avatar_preview)) {
        img = this.avatar_preview
      }

      return img
    }
  },
  methods: {
    get_updates () {
      const customerData = helpers.diff_JSON(this.data, this.data_local)

      // Add password à lista de updates
      if ((!_.isEmpty(this.password) && !_.isEmpty(this.confirm_password)) && this.password === this.confirm_password) {
        customerData.password = this.password
      }

      return customerData
    },
    get_formdatas () {
      const formDatas = new FormData()
      const updateDatas = this.get_updates()

      for (const key in updateDatas) {
        formDatas.append(key, updateDatas[key])
      }

      return formDatas
    },
    save_changes () {
      if (this.validateForm) return

      this.$validator.validateAll().then(statusValidator => {
        if (!statusValidator) return

        const customerUUID = this.data_local.uuid
        const customerData = this.get_formdatas()
        const save = () => {
          this.$store.dispatch('customerManagement/updateCustomer', {customerUUID, customerData})
            .then(() => this.go_view())
            .catch(err => {
              this.$vs.notify({
                color: 'danger',
                text: err.response.data.message
              })
            })
        }

        // verifica se há atualizações
        if (customerData.entries().next().done) return

        if (_.isEmpty(this.$store.state.customerManagement.customers)) {
          this.$store.dispatch('customerManagement/fetchCustomers')
            .then(() => save())
            .catch(err => {
              this.$vs.notify({
                color: 'danger',
                text: err.response.data.message
              })
            })
        } else {
          save()
        }
      })
    },
    choose_avatar () {
      this.$refs.profileImg.click()
    },
    change_avatar (e) {
      const file = e.target.files[0]
      this.data_local.avatar = file
      this.avatar_preview = URL.createObjectURL(file)
    },
    remove_avatar () {
      this.data_local.avatar = ''
      this.avatar_preview = null
      this.$refs.profileImg.value = null
    }
  }
}
</script>
