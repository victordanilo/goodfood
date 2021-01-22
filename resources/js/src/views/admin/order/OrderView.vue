<!-- =========================================================================================
  File Name: OrderView.vue
  Description: Order View page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div id="page-user-view">
    <vs-alert color="danger" :title="title_order_not_found" :active.sync="order_not_found">
      <span>{{ $t('notify_order_not_found', {prop:'uuid', value: $route.params.orderUUID}) }}</span>
      <span>
        <span>{{ $t('check') }} </span><router-link :to="{name:'admin-orders'}" class="text-inherit underline">{{ $t('all_orders') }}</router-link>
      </span>
    </vs-alert>

    <div class="vx-row" v-if="order_data">
      <div class="vx-col w-full md:w-5/6 h-64 mx-auto">
        <vx-card :title="title_order" class="mb-base">
          <template slot="actions">
            <div class="vx-card__code-toggler sm:block hidden">
              <vs-button color="dark" type="flat" :to="{name: 'admin-orders'}">{{$t('back')}}</vs-button>
            </div>
          </template>
          <div class="vx-row mb-2">
            <div class="vx-col w-full md:w-1/2">
              <span class="font-semibold">{{ $t('company') }} {{ $t('name') }}:</span>
              <span>{{ order_data.company.name }}</span>
            </div>
            <div class="vx-col w-full md:w-1/2">
              <span class="font-semibold">{{ $t('company') }} CNPJ/CPF:</span>
              <span>{{ order_data.company.cpf_cnpj }}</span>
            </div>
          </div>
          <div class="vx-row mb-2">
            <div class="vx-col w-full md:w-1/2">
              <span class="font-semibold">{{ $t('customer') }} {{ $t('name') }}:</span>
              <span>{{ order_data.customer.name }}</span>
            </div>
            <div class="vx-col w-full md:w-1/2">
              <span class="font-semibold">{{ $t('customer') }} CNPJ/CPF:</span>
              <span>{{ order_data.customer.cpf_cnpj }}</span>
            </div>
          </div>
          <div class="vx-row mb-2">
            <div class="vx-col w-full md:w-1/2">
              <span class="font-semibold">{{ $t('amount') }}:</span>
              <span>{{ order_data.amount }}</span>
            </div>
            <div class="vx-col w-full md:w-1/2">
              <span class="font-semibold">{{ $t('delivery_price') }}</span>
              <span>{{ order_data.delivery_price }}</span>
            </div>
          </div>
          <div class="vx-row mb-2">
            <div class="vx-col w-full">
              <span class="font-semibold">{{ $t('delivery_address') }}:</span>
              <span>{{ order_data.delivery_address }}</span>
            </div>
          </div>
          <div class="vx-row mb-2">
            <div class="vx-col w-full">
              <span class="font-semibold">{{ $t('status') }}:</span>
              <span>{{ order_data.status.name }}</span>
            </div>
          </div>
          <div class="vx-row mb-2">
            <div class="vx-col w-full">
              <span class="font-semibold">{{ $t('obs') }}:</span>
              <span>{{ order_data.obs }}</span>
            </div>
          </div>
          <vs-divider />
          <div class="vx-row mt-6 mb-2">
            <div class="vx-col w-full" v-if="order_items">
              <vs-table :data="order_items">
                <template slot="thead">
                  <vs-th>{{ $t('product_description') }}</vs-th>
                  <vs-th>{{ $t('qty') }}</vs-th>
                  <vs-th>{{ $t('price') }}</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                    <vs-td :data="data[indextr].products.description">
                      {{ data[indextr].products.description }}
                    </vs-td>

                    <vs-td :data="data[indextr].qty">
                      {{ data[indextr].qty }}
                    </vs-td>

                    <vs-td :data="data[indextr].price">
                      {{ data[indextr].price }}
                    </vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>
          </div>
        </vx-card>
      </div>
    </div>
  </div>
</template>

<script>
import moduleOrderManagement from '@/store/admin/order-management/moduleOrderManagement'

export default {
  data () {
    return {
      title_order_not_found: this.$i18n.t('order_not_found'),
      title_order: this.$i18n.t('order'),
      order_not_found: false,
      order_data: {
        amount: '',
        delivery_price: '',
        delivery_address: '',
        company: {
          name: '',
          cpf_cnpj: ''
        },
        customer: {
          name: '',
          cpf_cnpj: ''
        },
        status: {
          name: ''
        }
      },
      order_items: []
    }
  },
  methods: {
    confirmDeleteRecord () {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: this.$i18n.t('delete_confirm.title'),
        text: this.$i18n.t('delete_confirm.text', {username: this.order_data.uuid}),
        accept: this.deleteRecord,
        acceptText: 'Delete'
      })
    },
    deleteRecord () {
      this.$store.dispatch('orderManagement/removeOrder', this.order_data.uuid)
        .then((response) => {
          this.$router.push({name:'admin-orders'})
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
    setOrder (order) {
      this.order_data = order
      this.order_items = order.items
    }
  },
  created () {
    // Register Module OrderManagement
    if (!moduleOrderManagement.isRegistered) {
      this.$store.registerModule('orderManagement', moduleOrderManagement)
      moduleOrderManagement.isRegistered = true
    }

    const orderUUID = this.$route.params.orderUUID
    this.$store.dispatch('orderManagement/fetchOrder', orderUUID)
      .then(res => {
        this.setOrder(res.data)
      })
      .catch(err => {
        if (err.response.status === 404) {
          this.customer_not_found = true
          return
        }
        this.$vs.notify({
          color: 'danger',
          text: err.response.data.message
        })
      })
  }
}
</script>

<style>
.font-semibold {
  padding-right: 10px;
}
</style>
