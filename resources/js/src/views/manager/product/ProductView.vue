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
    <vs-alert color="danger" :title="$t('record_not_found', {record: $t('product')})" :active.sync="product_not_found">
      <span>{{ $t('notify_register_not_found', {record:  $t('product'), prop:'uuid', value: $route.params.productUUID}) }}</span>
      <span>
        <span>{{ $t('check') }} </span><router-link :to="{name:'manager-products'}" class="text-inherit underline">{{ $t('all_products') }}</router-link>
      </span>
    </vs-alert>

    <div v-if="product_data">
      <div class="vx-row">
        <div class="vx-col w-full lg:w-2/3 mx-auto">
          <vx-card>
            <div class="vx-row">
              <!-- product img -->
              <div class="vx-col w-full md:w-1/3">
                <img :src="productImg" class="rounded mx-auto w-48 h-48 lg:mx-0 xl:mx-auto"/>
              </div>
              <!-- product img -->

              <!-- product detail -->
              <div class="vx-col w-full md:w-2/3 mt-4 md:mt-0">
                <div class="vx-row">
                  <div class="vx-col w-full">
                    <h5 class="font-semibold">{{ $t('description') }}</h5>
                    <span>{{ product_data.description }}</span>
                  </div>
                  <div class="vx-col w-full mt-4">
                    <h5 class="font-semibold">{{ $t('category') }}</h5>
                    <span>{{ product_data.category.name }}</span>
                  </div>
                  <div class="vx-col w-full md:w-1/2 mt-4">
                    <h5 class="font-semibold">{{ $t('price') }}</h5>
                    <span>R$ {{ product_data.price | BRL }}</span>
                  </div>
                  <div class="vx-col w-full md:w-1/2 mt-4">
                    <h5 class="font-semibold">{{ $t('stock') }}</h5>
                    <span>{{ product_data.stock }}</span>
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
              <vs-button type="border" color="danger" icon-pack="feather" icon="icon-trash" class="mr-2" @click="confirmDeleteRecord">{{ $t('delete') }}</vs-button>
              <vs-button icon-pack="feather" icon="icon-edit" :to="{name: 'manager-product-edit', params: {productUUID: $route.params.productUUID}}">{{ $t('edit') }}</vs-button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import helpers from '@/helpers'
import MainBackAction from './MainBackAction.vue'
import moduleProductManagement from '@/store/manager/product-management/moduleProductManagement'

export default {
  data () {
    return {
      product_data: null,
      product_not_found: false
    }
  },
  computed: {
    productImg () {
      let img = require('@assets/images/default_food.jpg')

      if (this.product_data.image) {
        img = helpers.assets(`storage/product/${this.product_data.image}`)
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
        text: this.$i18n.t('delete_confirm.text', {username: this.product_data.description}),
        accept: this.deleteRecord,
        acceptText: 'Delete'
      })
    },
    deleteRecord () {
      this.$store.dispatch('productManagement/removeProduct', this.product_data.uuid)
        .then((response) => {
          this.$router.push({name:'manager-products'})
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
    setProduct (product) {
      this.product_data = product
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
        this.setProduct(product)
      })
      .catch(err => {
        if (err === 'product_not_found' || err.response.status === 404) {
          this.product_not_found = true
          return
        }
        console.error(err)
      })
  },
  destroyed () {
    this.$parent.mainActions = null
  }
}
</script>

<style>
.font-semibold {
  color: #626262;
  padding-bottom: 4px;
}
</style>
