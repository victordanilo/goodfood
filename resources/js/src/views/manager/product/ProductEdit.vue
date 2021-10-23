<!-- =========================================================================================
  File Name: ProductView.vue
  Description: Product View page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div>
    <div class="vx-row">
      <div class="vx-col w-full lg:w-2/3 mx-auto">
        <vx-card>
          <div class="vx-row">
            <!-- product img -->
            <div class="vx-col w-full md:w-1/3">
              <input ref="productImg" type="file" @change="change_img" hidden>
              <img :src="productImg" class="rounded mx-auto w-48 h-48 lg:mx-0 xl:mx-auto"/>

              <div class="btn-group flex justify-center mt-4">
                <vs-button size="small" @click="choose_img" style="padding-top: 10px !important; padding-bottom: 10px !important;">{{ $t('upload_img') }}</vs-button>
                <vs-button size="small" type="border" color="danger" @click.prevent="remove_img" style="padding-top: 10px !important; padding-bottom: 10px !important;">{{ $t('remove_img') }}</vs-button>
              </div>
            </div>
            <!-- product img -->

            <!-- product detail -->
            <div class="vx-col w-full md:w-2/3 mt-4 md:mt-0">
              <div class="vx-row">
                <div class="vx-col w-full">
                  <vs-input class="w-full" :label="$t('description')" v-model="product_data.description" v-validate="'required|min:3|max:255'" name="description"/>
                  <span class="text-danger text-sm" v-show="errors.has('description')">{{ errors.first('description') }}</span>
                </div>
                <div class="vx-col w-full mt-4">
                  <label class="vs-input--label">{{ $t('categories') }}</label>
                  <v-select v-model="categories_local" append-to-body :calculate-position="calculatePosition" :clearable="false" :options="categoriesOptions" v-validate="'required'" name="category" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
                  <span class="text-danger text-sm"  v-show="errors.has('category')">{{ errors.first('category') }}</span>
                </div>
                <div class="vx-col w-full md:w-1/2 mt-4">
                  <vs-input class="w-full" :label="$t('price')" :value="price_local" v-money="mask.money" @focus="startPrice" @change="changePrice" name="price"/>
                  <span class="text-danger text-sm" v-show="errors.has('price')">{{ errors.first('price') }}</span>
                </div>
                <div class="vx-col w-full md:w-1/2 mt-4">
                  <vs-input class="w-full" :label="$t('stock')" v-model="product_data.stock" type="number" min="0" name="stock"/>
                  <span class="text-danger text-sm" v-show="errors.has('stock')">{{ errors.first('stock') }}</span>
                </div>
              </div>
            </div>
            <!-- product detail -->
          </div>
        </vx-card>
      </div>
    </div>
    <div class="vx-row mt-6">
      <div class="vx-col w-full lg:w-2/3 mx-auto">
        <div class="vx-row">
          <div class="vx-col w-full flex justify-end">
            <vs-button icon-pack="feather" icon="icon-save" color="success" @click.prevent="save" :disabled="validateForm">{{ $t('save') }}</vs-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import helpers from '@/helpers'
import { VMoney } from 'v-money'
import vSelect from 'vue-select'
import { createPopper } from '@popperjs/core'
import MainBackAction from './MainBackAction.vue'
import moduleProductManagement from '@/store/manager/product-management/moduleProductManagement'
import { format, unformat } from '@/utils/vMoney'

export default {
  directives: {
    money: VMoney
  },
  components: {
    vSelect
  },
  data () {
    return {
      data_origin: {
        description: '',
        category_uuid: null,
        price: '0.00',
        stock: 0,
        image: ''
      },
      product_data: {
        description: '',
        category_uuid: null,
        price: '0.00',
        stock: 0,
        image: ''
      },
      price_local: '0.00',
      image_preview: null,
      mask: {
        money: {
          prefix: 'R$ ',
          suffix: '',
          decimal: ',',
          thousands: '.',
          precision: 2,
          masked: false
        },
        _money_start: false
      },

      categoriesOptions:[]
    }
  },
  computed: {
    validateForm () {
      const productData = this.get_updates()

      return this.errors.any() || _.isEmpty(productData)
    },
    categories_local: {
      get () {
        return _.find(this.categoriesOptions, (c) => c.value === this.product_data.category_uuid)
      },
      set (obj) {
        this.product_data.category_uuid = obj.value
      }
    },
    productImg () {
      let img = require('@assets/images/default_food.jpg')

      if (!_.isEmpty(this.product_data.image) && _.isEmpty(this.image_preview)) {
        img = helpers.assets(`storage/product/${this.product_data.image}`)
      }

      if (!_.isEmpty(this.image_preview)) {
        img = this.image_preview
      }

      return img
    }
  },
  watch: {
    'product_data.price': {
      immediate: true,
      handler (newValue, oldValue) {
        const formatted = format(newValue, this.mask.money)
        if (formatted !== this.price_local) {
          this.price_local = formatted
        }
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
    startPrice () {
      this.mask._money_start = true
    },
    changePrice (evt) {
      if (this.mask._money_start) {
        this.product_data.price = unformat(evt.target.value, 2)
      }
    },
    setDataOrigin (product) {
      this.data_origin = product
    },
    setProduct (product) {
      this.product_data = product
    },
    setCategories (categories) {
      this.categoriesOptions = _.map(categories, (category) => {
        return {label: category.name, value: category.uuid}
      })
    },
    get_updates () {
      const productData = helpers.diff_JSON(this.data_origin, this.product_data)

      return productData
    },
    choose_img () {
      this.$refs.productImg.click()
    },
    change_img (e) {
      const file = e.target.files[0]
      this.product_data.image = file
      this.image_preview = URL.createObjectURL(file)
    },
    remove_img () {
      this.product_data.image = ''
      this.image_preview = ''
      this.$refs.productImg.value = null
    },
    get_formdatas () {
      const formDatas = new FormData()
      const updateDatas = this.get_updates()

      for (const key in updateDatas) {
        /*
        if (key === 'price') {
          updateDatas[key] = unformat(this.product_data.price, 2)
        }
         */

        formDatas.append(key, updateDatas[key])
      }

      return formDatas
    },
    save () {
      if (this.validateForm) return

      this.$validator.validateAll().then(statusValidator => {
        if (!statusValidator) return

        const productUUID = this.data_origin.uuid
        const productData = this.get_formdatas()

        // verifica se há atualizações
        if (productData.entries().next().done) return
        this.$store.dispatch('productManagement/updateProduct', {productUUID, productData})
          .then((response) => {
            this.$router.push({name:'manager-products'})
            this.$vs.notify({
              color: 'success',
              text: response.data.message
            })
          })
          .catch((err) => {
            this.$vs.notify({
              color: 'danger',
              text: err.response.data.message
            })
          })
      })
    }
  },
  created () {
    // add main-actions
    this.$parent.mainActions = MainBackAction

    // Register Module CompanyManagement
    if (!moduleProductManagement.isRegistered) {
      this.$store.registerModule('productManagement', moduleProductManagement)
      moduleProductManagement.isRegistered = true
    }

    const productUUID = this.$route.params.productUUID
    this.$store.dispatch('productManagement/fetchProducts')
      .then((res) => {
        const product = _.find(res.data, {'uuid': productUUID})
        if (_.isEmpty(product)) throw 'product_not_found'
        this.setDataOrigin(_.clone(product))
        this.setProduct(_.clone(product))
      })
      .catch(err => {
        if (err === 'product_not_found' || err.response.status === 404) {
          this.product_not_found = true
          return
        }
        console.error(err)
      })

    this.$store.dispatch('productManagement/fetchCategories')
      .then((res) => {
        this.setCategories(res.data)
      })
      .catch(err => console.error(err))
  },
  destroyed () {
    this.$parent.mainActions = null
  }
}
</script>

<style>
label.vs-input--label {
  bottom: 4px;
  position: relative;
}
</style>
