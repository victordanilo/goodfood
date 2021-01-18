<!-- =========================================================================================
  File Name: CompanyView.vue
  Description: Company View page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div id="page-user-view">
    <vs-alert color="danger" :title="title_company_not_found" :active.sync="company_not_found">
      <span>{{ $t('notify_company_not_found', {prop:'uuid', value: $route.params.companyUUID}) }}</span>
      <span>
        <span>{{ $t('check') }} </span><router-link :to="{name:'admin-companies'}" class="text-inherit underline">{{ $t('all_companies') }}</router-link>
      </span>
    </vs-alert>

    <div id="user-data" v-if="company_data">
      <vx-card :title="title_account" class="mb-base">
        <template slot="actions">
          <div class="vx-card__code-toggler sm:block hidden">
            <vs-button color="dark" type="flat" :to="{name: 'admin-companies'}">{{$t('back')}}</vs-button>
          </div>
        </template>

        <div class="vx-row">
          <!-- Avatar Col -->
          <div class="vx-col" id="avatar-col">
            <div class="img-container mb-4">
              <img :src="srcAvatar" class="rounded w-full" />
            </div>
          </div>
          <!-- /Avatar Col -->

          <!-- Information - Col 1 -->
          <div class="vx-col flex-1" id="account-info-col-1">
            <table>
              <tr>
                <td class="font-semibold">{{ $t('name') }}</td>
                <td>{{ company_data.name }}</td>
              </tr>
              <tr>
                <td class="font-semibold">{{ $t('trade') }}</td>
                <td>{{ company_data.trade }}</td>
              </tr>
              <tr>
                <td class="font-semibold">CNPJ/CPF</td>
                <td>{{ company_data.cpf_cnpj }}</td>
              </tr>
            </table>
          </div>
          <!-- /Information - Col 1 -->

          <!-- Information - Col 2 -->
          <div class="vx-col flex-1" id="account-info-col-2">
            <table>
              <tr>
                <td class="font-semibold">{{ $t('owner') }}</td>
                <td>{{ company_data.owner }}</td>
              </tr>
              <tr>
                <td class="font-semibold">{{ $t('phone') }}</td>
                <td>{{ this.masker(company_data.phone, ['(##) ####-####', '(##) #####-####']) }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Email</td>
                <td>{{ company_data.email }}</td>
              </tr>
            </table>
          </div>
          <!-- /Information - Col 2 -->

          <div class="vx-col w-full flex" id="account-manage-buttons">
            <vs-button icon-pack="feather" icon="icon-edit" class="mr-4" :to="{name: 'admin-company-edit', params: {companyUUID: $route.params.companyUUID}}">{{ $t('edit') }}</vs-button>
            <vs-button type="border" color="danger" icon-pack="feather" icon="icon-trash" @click="confirmDeleteRecord">{{ $t('delete') }}</vs-button>
          </div>
        </div>
      </vx-card>

      <div class="vx-row">
        <div class="vx-col lg:w-1/2 w-full">
          <vx-card :title="title_address" class="mb-base">
            <table>
              <tr>
                <td class="font-semibold">{{ $t('street') }}</td>
                <td>{{ company_data.street }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Nº</td>
                <td>{{ company_data.n }}</td>
              </tr>
              <tr>
                <td class="font-semibold">{{ $t('complement') }}</td>
                <td>{{ company_data.complement }}</td>
              </tr>
              <tr>
                <td class="font-semibold">{{ $t('district') }}</td>
                <td>{{ company_data.district }}</td>
              </tr>
              <tr>
                <td class="font-semibold">{{ $t('zip_code') }}</td>
                <td>{{ company_data.zip_code }}</td>
              </tr>
              <tr>
                <td class="font-semibold">{{ $t('city') }}</td>
                <td>{{ company_data.city }}</td>
              </tr>
              <tr>
                <td class="font-semibold">{{ $t('uf') }}</td>
                <td>{{ company_data.uf }}</td>
              </tr>
            </table>
          </vx-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import masker from 'vue-the-mask/src/masker'
import tokens from 'vue-the-mask/src/tokens'
import helpers from '@/helpers'
import moduleCompanyManagement from '@/store/admin/company-management/moduleCompanyManagement'

export default {
  data () {
    return {
      title_company_not_found: this.$i18n.t('company_not_found'),
      title_account: this.$i18n.t('account'),
      title_address: this.$i18n.t('address'),
      company_data: null,
      company_not_found: false
    }
  },
  computed: {
    srcAvatar () {
      let img = require('@assets/images/profile/default-user.jpg')

      if (this.company_data.avatar) {
        img = helpers.assets(`storage/company/avatar/${this.company_data.avatar}`)
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
        text: this.$i18n.t('delete_confirm.text', {username: this.company_data.name}),
        accept: this.deleteRecord,
        acceptText: 'Delete'
      })
    },
    deleteRecord () {
      this.$store.dispatch('companyManagement/removeCompany', this.company_data.uuid)
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
    },
    setCompany (company) {
      this.company_data = company
    },
    masker (value, mask) {
      return masker(value, mask, true, tokens)
    }
  },
  created () {
    // Register Module CompanyManagement
    if (!moduleCompanyManagement.isRegistered) {
      this.$store.registerModule('companyManagement', moduleCompanyManagement)
      moduleCompanyManagement.isRegistered = true
    }

    const companyUUID = this.$route.params.companyUUID
    const company = _.find(this.$store.state.companyManagement.companies, {'uuid': companyUUID})
    if (!_.isEmpty(company)) {
      this.setCompany(company)
    } else {
      this.$store.dispatch('companyManagement/fetchCompany', companyUUID)
        .then(res => this.setCompany(res.data))
        .catch(err => {
          if (err.response.status === 404) {
            this.company_not_found = true
            return
          }
          console.error(err)
        })
    }
  }
}
</script>

<style lang="scss">
#avatar-col {
  width: 10rem;
}

#page-user-view {
  table {
    td {
      vertical-align: top;
      min-width: 140px;
      padding-bottom: .8rem;
      word-break: break-all;
    }

    &:not(.permissions-table) {
      td {
        @media screen and (max-width:370px) {
          display: block;
        }
      }
    }
  }
}

@media screen and (min-width:1201px) and (max-width:1211px), only screen and (min-width:636px) and (max-width:991px) {
  #account-info-col-1 {
    width: calc(100% - 12rem) !important;
  }
}
</style>
