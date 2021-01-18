<!-- =========================================================================================
  File Name: CompanyEdit.vue
  Description: Company Edit Page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div id="page-user-edit">
    <vs-alert color="danger" :title="title_company_not_found" :active.sync="company_not_found">
      <span>{{ $t('notify_company_not_found', {prop:'uuid', value: $route.params.companyUUID}) }}</span>
      <span>
        <span>{{ $t('check') }} </span><router-link :to="{name:'admin-companies'}" class="text-inherit underline">{{ $t('all_companies') }}</router-link>
      </span>
    </vs-alert>

    <vx-card v-if="company_data">
      <div slot="no-body" class="tabs-container px-6 pt-6">
        <vs-tabs class="tab-action-btn-fill-conatiner">
          <vs-tab :label="$t('account')" icon-pack="feather" icon="icon-users">
            <div class="tab-text">
              <company-edit-tab-account class="mt-4" :data="company_data" :go_view="this.go_view" />
            </div>
          </vs-tab>

          <vs-tab :label="$t('address')" icon-pack="feather" icon="icon-map">
            <div class="tab-text">
              <company-edit-tab-address class="mt-4 z-50" :data="company_data" :go_view="this.go_view" />
            </div>
          </vs-tab>
        </vs-tabs>
      </div>
    </vx-card>
  </div>
</template>

<script>
import _ from 'lodash'
import CompanyEditTabAccount from './CompanyEditTabAccount.vue'
import CompanyEditTabAddress from './CompanyEditTabAddress.vue'

// Store Module
import moduleCompanyManagement from '@/store/admin/company-management/moduleCompanyManagement'

export default {
  components: {
    CompanyEditTabAccount,
    CompanyEditTabAddress
  },
  data () {
    return {
      title_company_not_found: this.$i18n.t('company_not_found'),
      company_data: null,
      company_default_data: {},
      company_not_found: false
    }
  },
  methods: {
    fetch_company_data (companyUUID) {
      this.$store.dispatch('companyManagement/fetchCompany', companyUUID)
        .then(res => this.setCompany(res.data))
        .catch(err => {
          if (err.response.status === 404) {
            this.company_not_found = true
            return
          }
          console.error(err)
        })
    },
    setCompany (company) {
      company = _.merge(company, this.company_default_data)
      this.company_data = company
    },
    go_view () {
      this.$router.push({name:'admin-company-view', params: {companyUUID: this.$route.params.companyUUID}})
    }
  },
  created () {
    // Register Module UserManagement Module
    if (!moduleCompanyManagement.isRegistered) {
      this.$store.registerModule('companyManagement', moduleCompanyManagement)
      moduleCompanyManagement.isRegistered = true
    }

    this.fetch_company_data(this.$route.params.companyUUID)
  }
}
</script>
