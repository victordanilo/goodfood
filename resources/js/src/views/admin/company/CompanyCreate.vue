<!-- =========================================================================================
  File Name: CompanyCreate.vue
  Description: Company Create page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div class="vx-row">
    <div class="vx-col w-full md:w-2/3 h-64 mx-auto">
      <vx-card>
        <div class="vx-row">
          <!-- Avatar Row -->
          <div class="vx-col w-full">
            <input ref="avatar" type="file" @change="change_avatar" hidden>
            <img :src="srcAvatar" class="rounded mx-auto w-48 h-48 lg:mx-0 xl:mx-auto"/>

            <div class="btn-group flex justify-center mt-4">
              <vs-button size="small" @click="choose_avatar">{{ $t('upload_avatar') }}</vs-button>
              <vs-button size="small" type="border" color="danger" @click.prevent="remove_avatar">{{ $t('remove_avatar') }}</vs-button>
            </div>
          </div>

          <!-- Content Row -->
          <div class="vx-col w-full md:w-1/2">
            <div class="container mx-auto md:p-0 lg:pl-4 xl:pl-0">
              <vs-input class="w-full mt-4" :label="$t('name')" v-model="company_data.name" v-validate="'required|min:3|max:255'" name="name"/>
              <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first('name') }}</span>

              <vs-input class="w-full mt-4" :label="$t('trade')" v-model="company_data.trade" v-validate="'min:3|max:255'" name="trade"/>
              <span class="text-danger text-sm" v-show="errors.has('trade')">{{ errors.first('trade') }}</span>

              <vs-input class="w-full mt-4" label="CNPJ/CPF" v-model="company_data.cpf_cnpj" v-mask="['###.###.###-##', '##.###.###/####-##']" name="cpf_cnpj"/>
              <span class="text-danger text-sm" v-show="errors.has('cpf_cnpj')">{{ errors.first('cpf_cnpj') }}</span>

              <vs-input class="w-full mt-4" :label="$t('owner')" v-model="company_data.owner" v-validate="'min:3|max:255'" name="owner"/>
              <span class="text-danger text-sm" v-show="errors.has('owner')">{{ errors.first('owner') }}</span>

              <vs-input class="w-full mt-4" :label="$t('delivery_cost')" :value="delivery_cost_local" v-money="mask.money" @focus="startPrice" @change="changePrice" name="delivery_cost"/>
              <span class="text-danger text-sm" v-show="errors.has('delivery_cost')">{{ errors.first('delivery_cost') }}</span>
            </div>
          </div>
          <div class="vx-col w-full md:w-1/2">
            <div class="container mx-auto md:p-0">
              <vs-input class="w-full mt-4" label="Email" v-model="company_data.email" type="email"
                        v-validate="'required|email'" name="email"/>
              <span class="text-danger text-sm" v-show="errors.has('email')">{{ errors.first('email') }}</span>

              <the-mask class="w-full mt-4" :label="$t('phone')" v-model="company_data.phone" :mask="['(##) ####-####', '(##) #####-####']" name="phone" />
              <span class="text-danger text-sm" v-show="errors.has('phone')">{{ errors.first('phone') }}</span>

              <vs-input class="w-full mt-4" :label="$t('password')" v-model="company_data.password" type="password"
                        v-validate="'required|min:6|max:20'" ref="password" name="password"/>
              <span class="text-danger text-sm" v-show="errors.has('password')">{{ errors.first('password') }}</span>

              <vs-input class="w-full mt-4" :label="$t('password_confirm')" v-model="company_data.confirm_password" type="password"
                        v-validate="'required|min:6|max:20|confirmed:password'" data-vv-as="password"
                        name="confirm_password"/>
              <span class="text-danger text-sm" v-show="errors.has('confirm_password')">{{ errors.first('confirm_password') }}</span>
            </div>
          </div>

          <div class="vx-col w-full mt-12">
            <div class="flex items-end px-3">
              <feather-icon svgClasses="w-6 h-6" icon="MapIcon" class="mr-2" />
              <span class="font-medium text-lg leading-none">{{ $t('address') }}</span>
            </div>
            <vs-divider />
          </div>
          <div class="vx-col w-full md:w-4/5">
            <vs-input class="w-full mt-4" :label="$t('street')" v-model="company_data.street" name="street" />
            <span class="text-danger text-sm"  v-show="errors.has('street')">{{ errors.first('street') }}</span>
          </div>

          <div class="vx-col w-full md:w-1/5">
            <vs-input class="w-full mt-4" label="Nº" v-model="company_data.n" name="n" />
            <span class="text-danger text-sm"  v-show="errors.has('n')">{{ errors.first('n') }}</span>
          </div>

          <div class="vx-col w-full">
            <vs-input class="w-full mt-4" :label="$t('complement')" v-model="company_data.complement" name="complement" />
            <span class="text-danger text-sm"  v-show="errors.has('complement')">{{ errors.first('complement') }}</span>
          </div>

          <div class="vx-col w-full md:w-2/3">
            <vs-input class="w-full mt-4" :label="$t('district')" v-model="company_data.district" name="district" />
            <span class="text-danger text-sm"  v-show="errors.has('district')">{{ errors.first('district') }}</span>
          </div>

          <div class="vx-col w-full md:w-1/3">
            <vs-input class="w-full mt-4" label="CEP" v-model="company_data.zip_code" v-mask="'#####-###'" name="zip_code" />
            <span class="text-danger text-sm"  v-show="errors.has('zip_code')">{{ errors.first('zip_code') }}</span>
          </div>

          <div class="vx-col w-full md:w-4/5">
            <vs-input class="w-full mt-4" :label="$t('city')" v-model="company_data.city" name="city" />
            <span class="text-danger text-sm"  v-show="errors.has('city')">{{ errors.first('city') }}</span>
          </div>

          <div class="vx-col w-full md:w-1/5 mt-4" style="position: relative !important">
            <label class="vs-input--label">UF</label>
            <v-select v-model="uf_local" append-to-body :calculate-position="calculatePosition" :clearable="false" :options="ufOptions" name="uf" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
            <span class="text-danger text-sm"  v-show="errors.has('uf')">{{ errors.first('uf') }}</span>
          </div>

          <!-- Save & Reset Button -->
          <div class="vx-col w-full">
            <div class="mt-8 flex justify-center md:justify-end">
              <vs-button class="md:ml-auto mt-2" type="border" color="primary" :to="{name: 'admin-companies'}">{{ $t('back') }}</vs-button>
              <vs-button class="ml-4 mt-2" @click.prevent="save" :disabled="!validateForm">{{ $t('save') }}</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import vSelect from 'vue-select'
import { VMoney } from 'v-money'
import {createPopper} from '@popperjs/core'
import { format, unformat } from '@/utils/vMoney'
import moduleCompanyManagement from '@/store/admin/company-management/moduleCompanyManagement'

export default {
  directives: {
    money: VMoney
  },
  components: {
    vSelect
  },
  data () {
    return {
      company_data: {
        name: '',
        trade: '',
        cpf_cnpj: '',
        owner: '',
        delivery_cost: '0.00',
        email: '',
        phone: '',
        avatar: '',
        password: '',
        confirm_password: '',
        street: '',
        n: '',
        complement: '',
        district: '',
        zip_code: '',
        city: '',
        uf: ''
      },
      avatar_preview: null,
      delivery_cost_local: '0.00',
      mask: {
        money: {
          prefix: 'R$ ',
          suffix: '',
          decimal: ',',
          thousands: '.',
          precision: 2,
          masked: false
        },
        money_start: false
      },

      ufOptions: [
        { label: 'AC', value: 'AC' },
        { label: 'AL', value: 'AL' },
        { label: 'AP', value: 'AP' },
        { label: 'AM', value: 'AM' },
        { label: 'BA', value: 'BA' },
        { label: 'CE', value: 'CE' },
        { label: 'DF', value: 'DF' },
        { label: 'ES', value: 'ES' },
        { label: 'GO', value: 'GO' },
        { label: 'MA', value: 'MA' },
        { label: 'MT', value: 'MT' },
        { label: 'MS', value: 'MS' },
        { label: 'MG', value: 'MG' },
        { label: 'PA', value: 'PA' },
        { label: 'PB', value: 'PB' },
        { label: 'PR', value: 'PR' },
        { label: 'PE', value: 'PE' },
        { label: 'PI', value: 'PI' },
        { label: 'RJ', value: 'RJ' },
        { label: 'RN', value: 'RN' },
        { label: 'RS', value: 'RS' },
        { label: 'RO', value: 'RO' },
        { label: 'RR', value: 'RR' },
        { label: 'SC', value: 'SC' },
        { label: 'SP', value: 'SP' },
        { label: 'SE', value: 'SE' },
        { label: 'TO', value: 'TO' }
      ]
    }
  },
  computed: {
    validateForm () {
      return !this.errors.any()
    },
    srcAvatar () {
      let img = require('@assets/images/profile/default-user.jpg')

      if (this.avatar_preview) {
        img = this.avatar_preview
      }

      return img
    },
    uf_local: {
      get () {
        return {label: _.toUpper(this.company_data.uf), value: this.company_data.uf}
      },
      set (uf) {
        this.company_data.uf = uf.value
      }
    }
  },
  watch: {
    'company_data.delivery_cost': {
      immediate: true,
      handler (newValue, oldValue) {
        const formatted = format(newValue, this.mask.money)
        if (formatted !== this.delivery_cost_local) {
          this.delivery_cost_local = formatted
        }
      }
    }
  },
  methods: {
    startPrice () {
      this.mask.money_start = true
    },
    changePrice (evt) {
      if (this.mask.money_start) {
        this.company_data.delivery_cost = (unformat(evt.target.value, 2)).toFixed(2)
      }
    },
    calculatePosition  (dropdownList, component, {width}) {
      /**
       * We need to explicitly define the dropdown width since
       * it is usually inherited from the parent with CSS.
       */
      dropdownList.style.width = width

      /**
       * Here we position the dropdownList relative to the $refs.toggle Element.
       *
       * The 'offset' modifier aligns the dropdown so that the $refs.toggle and
       * the dropdownList overlap by 1 pixel.
       *
       * The 'toggleClass' modifier adds a 'drop-up' class to the Vue Select
       * wrapper so that we can set some styles for when the dropdown is placed
       * above.
       */
      const popper = createPopper(component.$refs.toggle, dropdownList, {
        placement: 'top',
        modifiers: [
          {
            name: 'offset',
            options: {
              offset: [0, -1]
            }
          },
          {
            name: 'toggleClass',
            enabled: true,
            phase: 'write',
            fn ({state}) {
              component.$el.classList.toggle('drop-up', state.placement === 'top')
            }
          }
        ]
      })

      /**
       * To prevent memory leaks Popper needs to be destroyed.
       * If you return function, it will be called just before dropdown is removed from DOM.
       */
      return () => popper.destroy()
    },
    choose_avatar () {
      this.$refs.avatar.click()
    },
    change_avatar (e) {
      const file = e.target.files[0]
      this.company_data.avatar = file
      this.avatar_preview = URL.createObjectURL(file)
    },
    remove_avatar () {
      this.company_data.avatar = null
      this.avatar_preview = null
      this.$refs.avatar.value = null
    },
    get_formdatas () {
      const formDatas = new FormData()
      const companyDatas = this.company_data

      for (const key in companyDatas) {
        formDatas.append(key, companyDatas[key])
      }

      return formDatas
    },
    save () {
      if (!this.validateForm) return

      this.$validator.validateAll().then(statusValidator => {
        if (!statusValidator) return

        this.$store.dispatch('companyManagement/addCompany', this.get_formdatas())
          .then((response) => {
            this.$router.push({name:'admin-companies'})
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
      })
    }
  },
  created () {
    // Register Module UserManagement Module
    if (!moduleCompanyManagement.isRegistered) {
      this.$store.registerModule('companyManagement', moduleCompanyManagement)
      moduleCompanyManagement.isRegistered = true
    }
  }
}
</script>
