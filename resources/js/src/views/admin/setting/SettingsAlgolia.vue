<template>
  <vx-card no-shadow>
    <div class="vx-row">
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" label-placeholder="Algolia APP_ID" v-model="data.id" />
      </div>
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" label-placeholder="Algolia SECRET" v-model="data.secret" />
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
        id: '',
        secret: ''
      },
      data: {
        id: '',
        secret: ''
      }
    }
  },
  computed: {
    dataUpdated () {
      const data = {
        id: this.data.id,
        secret: this.data.secret
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
        return {key: `algolia.${input}`, value}
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
        if (response.data.hasOwnProperty('algolia')) {
          this.setData(response.data.algolia)
        }
      })
      .catch(err => console.error(err))
  }
}
</script>
