<!-- =========================================================================================
  File Name: CustomerEditTabAddress.vue
  Description: Customer Edit Address Tab content
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div id="customer-edit-tab-address">
    <div class="vx-row">
      <div class="vx-col w-full md:w-4/5">
        <vs-input class="w-full mt-4" :label="$t('street')" v-model="data_local.street" name="street" />
        <span class="text-danger text-sm"  v-show="errors.has('street')">{{ errors.first('street') }}</span>
      </div>

      <div class="vx-col w-full md:w-1/5">
        <vs-input class="w-full mt-4" label="Nº" v-model="data_local.n" name="n" />
        <span class="text-danger text-sm"  v-show="errors.has('n')">{{ errors.first('n') }}</span>
      </div>

      <div class="vx-col w-full">
        <vs-input class="w-full mt-4" :label="$t('complement')" v-model="data_local.complement" name="complement" />
        <span class="text-danger text-sm"  v-show="errors.has('complement')">{{ errors.first('complement') }}</span>
      </div>

      <div class="vx-col w-full md:w-2/3">
        <vs-input class="w-full mt-4" :label="$t('district')" v-model="data_local.district" name="district" />
        <span class="text-danger text-sm"  v-show="errors.has('district')">{{ errors.first('district') }}</span>
      </div>

      <div class="vx-col w-full md:w-1/3">
        <vs-input class="w-full mt-4" label="CEP" v-model="data_local.zip_code" v-mask="'#####-###'" name="zip_code" />
        <span class="text-danger text-sm"  v-show="errors.has('zip_code')">{{ errors.first('zip_code') }}</span>
      </div>

      <div class="vx-col w-full md:w-4/5">
        <vs-input class="w-full mt-4" :label="$t('city')" v-model="data_local.city" name="city" />
        <span class="text-danger text-sm"  v-show="errors.has('city')">{{ errors.first('city') }}</span>
      </div>

      <div class="vx-col w-full md:w-1/5 mt-4" style="position: relative !important;">
        <label class="vs-input--label">UF</label>
        <v-select v-model="uf_local" append-to-body :calculate-position="calculatePosition" :clearable="false" :options="ufOptions" name="uf" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
        <span class="text-danger text-sm"  v-show="errors.has('uf')">{{ errors.first('uf') }}</span>
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
</template>

<script>
import _ from 'lodash'
import helpers from '@/helpers'
import vSelect from 'vue-select'
import { createPopper } from '@popperjs/core'

export default {
  components: {
    vSelect
  },
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
      data_origin: {
        uuid: '',
        street: '',
        n: '',
        complement: '',
        district: '',
        zip_code: '',
        uf: ''
      },
      data_local: {
        uuid: '',
        street: '',
        n: '',
        complement: '',
        district: '',
        zip_code: '',
        uf: ''
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
      const customerData = this.get_updates()

      return this.errors.any() || _.isEmpty(customerData)
    },
    uf_local: {
      get () {
        return {label: _.toUpper(this.data_local.uf), value: this.data_local.uf}
      },
      set (uf) {
        this.data_local.uf = uf.value
      }
    }
  },
  methods: {
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
    get_updates () {
      const addressData = helpers.diff_JSON(this.data_origin, this.data_local)
      addressData.uuid = this.data_origin.uuid

      return addressData
    },
    save_changes () {
      if (this.validateForm) return

      this.$validator.validateAll().then(statusValidator => {
        if (!statusValidator) return

        const customerUUID = this.data.uuid
        const addressData = this.get_updates()
        const save = () => {
          if (_.isEmpty(this.data_local.uuid)) {
            this.$store.dispatch('customerManagement/addAddress', {customerUUID, addressData})
              .then((response) => {
                this.data_origin = response.data.address
                this.data_local = response.data.address
                this.go_view()
              })
              .catch(err => {
                this.$vs.notify({
                  color: 'danger',
                  text: err.response.data.message
                })
              })
          } else {
            this.$store.dispatch('customerManagement/updateAddress', {customerUUID, addressData})
              .then(() => this.go_view())
              .catch(err => {
                this.$vs.notify({
                  color: 'danger',
                  text: err.response.data.message
                })
              })
          }
        }

        // checa se há atualização
        if (_.isEmpty(addressData)) return

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
    }
  },
  created () {
    const address = this.data.addresses
    if (!_.isEmpty(address)) {
      this.data_origin = address.shift()
    }

    this.data_local = _.pick(this.data_origin, ['uuid', 'street', 'n', 'complement', 'district', 'zip_code', 'city', 'uf'])
  }
}
</script>

<style>
.v-select.drop-up.vs--open .vs__dropdown-toggle {
  border-radius: 0 0 4px 4px;
  border-top-color: transparent;
  border-bottom-color: rgba(60, 60, 60, 0.26);
}

[data-popper-placement='top'] {
  border-radius: 4px 4px 0 0;
  border-top-style: solid;
  border-bottom-style: none;
  box-shadow: 0 -3px 6px rgba(0, 0, 0, 0.15)
}
</style>
