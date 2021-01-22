<template>
  <vx-card no-shadow>
    <div class="vx-row">
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" :label-placeholder="$t('statement_descriptor')" v-model="mp.statement_descriptor" />
      </div>
      <div class="vx-col w-full md:w-1/2">
        <vs-input class="w-full mb-base" :label-placeholder="$t('application_fee')" v-model="mp.application_fee" type="number" />
      </div>
      <div class="vx-col w-full md:w-1/2">
        <vs-input class="w-full mb-base" :label-placeholder="$t('money_release_days')" v-model="mp.money_release_days" type="number" v-mask="'###'" />
      </div>
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" label-placeholder="Public Key" v-model="mp.public_key" />
      </div>
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" label-placeholder="Access Token" v-model="mp.access_token" />
      </div>
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" label-placeholder="URL Redirect" v-model="mp.redirect_url" />
      </div>
    </div>

    <!-- Save & Reset Button -->
    <div class="flex flex-wrap items-center justify-end">
      <vs-button class="ml-auto mt-2" @click="save" :disabled="!hasUpdates">{{ $t('save') }}</vs-button>
    </div>
  </vx-card>
</template>

<script>
import _ from 'lodash'
import helpers from '@/helpers'
import moduleSettingManagement from '@/store/admin/setting-management/moduleSettingManagement'

export default {
  data () {
    return {
      origin: {
        statement_descriptor: '',
        application_fee: '',
        money_release_days: '',
        public_key: '',
        access_token: '',
        redirect_url: ''
      },
      mp: {
        statement_descriptor: '',
        application_fee: '',
        money_release_days: '',
        public_key: '',
        access_token: '',
        redirect_url: ''
      }
    }
  },
  computed: {
    dataUpdated () {
      const mp = {
        statement_descriptor: this.mp.statement_descriptor,
        application_fee: this.mp.application_fee,
        money_release_days: this.mp.money_release_days,
        public_key: this.mp.public_key,
        access_token: this.mp.access_token,
        redirect_url: this.mp.redirect_url
      }

      return helpers.diff_JSON(this.origin, mp)
    },
    hasUpdates () {
      return !_.isEmpty(this.dataUpdated)
    }
  },
  methods: {
    setData (mp) {
      _.map(mp, (value, input) => {
        this.mp[input] = value
        this.origin[input] = value
      })
    },
    save () {
      const dataUpdated = this.dataUpdated
      const settings = _.map(dataUpdated, (value, input) => {
        return {key: `mp.${input}`, value}
      })

      this.$store.dispatch('settingManagement/setSetting', settings)
        .then(response => {
          this.setData(dataUpdated)
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
    }
  },
  created () {
    // Register Module RoleManagement Module
    if (!moduleSettingManagement.isRegistered) {
      this.$store.registerModule('settingManagement', moduleSettingManagement)
      moduleSettingManagement.isRegistered = true
    }

    this.$store.dispatch('settingManagement/fetchSettings')
      .then(response => {
        if (response.data.hasOwnProperty('mp')) {
          this.setData(response.data.mp)
        }
      })
      .catch(err => console.error(err))
  }
}
</script>
