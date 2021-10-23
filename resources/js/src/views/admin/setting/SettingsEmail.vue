<template>
  <vx-card no-shadow>
    <div class="vx-row">
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" :label-placeholder="$t('mail_host')" v-model="mail.host" />
      </div>
      <div class="vx-col w-full md:w-1/2">
        <vs-input class="w-full mb-base" :label-placeholder="$t('mail_encryption')" v-model="mail.encryption" />
      </div>
      <div class="vx-col w-full md:w-1/2">
        <vs-input class="w-full mb-base" :label-placeholder="$t('mail_port')" type="number" v-model="mail.port" />
      </div>
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" :label-placeholder="$t('mail_username')" v-model="mail.username" />
      </div>
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" :label-placeholder="$t('mail_password')" type="password" v-model="mail.password" />
      </div>
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" :label-placeholder="$t('mail_from_name')" v-model="mail.from_name" />
      </div>
      <div class="vx-col w-full">
        <vs-input class="w-full mb-base" :label-placeholder="$t('mail_from_address')" type="email" v-model="mail.from_address" />
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
        host: '',
        encryption: '',
        port: '',
        username: '',
        password: '',
        from_name: '',
        from_address: ''
      },
      mail: {
        host: '',
        encryption: '',
        port: '',
        username: '',
        password: '',
        from_name: '',
        from_address: ''
      }
    }
  },
  computed: {
    dataUpdated () {
      const mail = {
        host: this.mail.host,
        encryption: this.mail.encryption,
        port: this.mail.port,
        username: this.mail.username,
        password: this.mail.password,
        from_name: this.mail.from_name,
        from_address: this.mail.from_address
      }

      return helpers.diff_JSON(this.origin, mail)
    },
    hasUpdates () {
      return !_.isEmpty(this.dataUpdated)
    }
  },
  methods: {
    setData (mail) {
      _.map(mail, (value, input) => {
        this.mail[input] = value
        this.origin[input] = value
      })
    },
    save () {
      const dataUpdated = this.dataUpdated
      const settings = _.map(dataUpdated, (value, input) => {
        return {key: `mail.${input}`, value}
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
        if (response.data.hasOwnProperty('mail')) {
          this.setData(response.data.mail)
        }
      })
      .catch(err => console.error(err))
  }
}
</script>
