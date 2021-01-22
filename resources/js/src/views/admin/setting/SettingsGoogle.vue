<template>
  <vx-card no-shadow>
    <div class="vx-row">
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" label-placeholder="Maps API_KEY" v-model="data.maps_api_key" />
      </div>

      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" label-placeholder="Recaptcha PUBLIC_KEY" v-model="data.recaptcha_public_key" />
      </div>

      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" label-placeholder="Recaptcha PRIVATE_KEY" v-model="data.recaptcha_private_key" />
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
        maps_api_key: '',
        recaptcha_public_key: '',
        recaptcha_private_key: ''
      },
      data: {
        maps_api_key: '',
        recaptcha_public_key: '',
        recaptcha_private_key: ''
      }
    }
  },
  computed: {
    dataUpdated () {
      const data = {
        maps_api_key: this.data.maps_api_key,
        recaptcha_public_key: this.data.recaptcha_public_key,
        recaptcha_private_key: this.data.recaptcha_private_key
      }

      return helpers.diff_JSON(this.origin, data)
    },
    hasUpdates () {
      return !_.isEmpty(this.dataUpdated)
    }
  },
  methods: {
    setData (data) {
      _.map(data, (value, input) => {
        this.data[input] = value
        this.origin[input] = value
      })
    },
    save () {
      const dataUpdated = this.dataUpdated
      const settings = _.map(dataUpdated, (value, input) => {
        return {key: `google.${input}`, value}
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
        if (response.data.hasOwnProperty('google')) {
          this.setData(response.data.google)
        }
      })
      .catch(err => console.error(err))
  }
}
</script>
