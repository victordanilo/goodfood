<!-- =========================================================================================
  File Name: CustomerEdit.vue
  Description: Customer Edit Page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div id="page-user-edit">
    <vs-alert color="danger" :title="title_customer_not_found" :active.sync="customer_not_found">
      <span>{{ $t('notify_customer_not_found', {prop:'uuid', value: $route.params.customerUUID}) }}</span>
      <span>
        <span>{{ $t('check') }} </span><router-link :to="{name:'admin-customers'}" class="text-inherit underline">{{ $t('all_customers') }}</router-link>
      </span>
    </vs-alert>

    <vx-card v-if="customer_data">
      <div slot="no-body" class="tabs-container px-6 pt-6">
        <vs-tabs class="tab-action-btn-fill-conatiner">
          <vs-tab :label="$t('account')" icon-pack="feather" icon="icon-users">
            <div class="tab-text">
              <customer-edit-tab-account class="mt-4" :data="customer_data" :go_view="this.go_view" />
            </div>
          </vs-tab>

          <vs-tab :label="$t('address')" icon-pack="feather" icon="icon-map">
            <div class="tab-text">
              <customer-edit-tab-address class="mt-4 z-50" :data="customer_data" :go_view="this.go_view" />
            </div>
          </vs-tab>
        </vs-tabs>
      </div>
    </vx-card>
  </div>
</template>

<script>
import CustomerEditTabAccount from './CustomerEditTabAccount.vue'
import CustomerEditTabAddress from './CustomerEditTabAddress.vue'

// Store Module
import moduleCustomerManagement from '@/store/admin/customer-management/moduleCustomerManagement'

export default {
  components: {
    CustomerEditTabAccount,
    CustomerEditTabAddress
  },
  data () {
    return {
      title_customer_not_found: this.$i18n.t('customer_not_found'),
      customer_data: null,
      customer_not_found: false
    }
  },
  methods: {
    fetch_customer_data (customerUUID) {
      this.$store.dispatch('customerManagement/fetchCustomer', customerUUID)
        .then(res => { this.setCustomer(res.data) })
        .catch(err => {
          if (err.response.status === 404) {
            this.customer_not_found = true
            return
          }
          console.error(err)
        })
    },
    setCustomer (customer) {
      this.customer_data = customer
    },
    go_view () {
      this.$router.push({name:'admin-customer-view', params: {customerUUID: this.$route.params.customerUUID}})
    }
  },
  created () {
    // Register Module CustomerManagement
    if (!moduleCustomerManagement.isRegistered) {
      this.$store.registerModule('customerManagement', moduleCustomerManagement)
      moduleCustomerManagement.isRegistered = true
    }

    this.fetch_customer_data(this.$route.params.customerUUID)
  }
}
</script>
