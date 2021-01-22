<template>
  <vs-tabs :position="isSmallerScreen ? 'top' : 'left'" class="tabs-shadow-none" id="profile-tabs" :key="isSmallerScreen">

    <vs-tab icon-pack="feather" icon="icon-mail" :label="!isSmallerScreen ? 'E-mail' : ''">
      <div class="tab-general md:ml-4 md:mt-0 mt-4 ml-0">
        <settings-email />
      </div>
    </vs-tab>

    <vs-tab icon-pack="feather" icon="icon-heart" :label="!isSmallerScreen ? 'Algolia' : ''">
      <div class="tab-general md:ml-4 md:mt-0 mt-4 ml-0">
        <settings-algolia />
      </div>
    </vs-tab>

    <vs-tab icon-pack="feather" icon="icon-heart" :label="!isSmallerScreen ? 'Google' : ''">
      <div class="tab-general md:ml-4 md:mt-0 mt-4 ml-0">
        <settings-google />
      </div>
    </vs-tab>

    <vs-tab icon-pack="feather" icon="icon-dollar-sign" :label="!isSmallerScreen ? 'MercadoPago' : ''">
      <div class="tab-general md:ml-4 md:mt-0 mt-4 ml-0">
        <settings-mercado-pago />
      </div>
    </vs-tab>

  </vs-tabs>
</template>

<script>
import SettingsEmail from './SettingsEmail'
import SettingsAlgolia from './SettingsAlgolia'
import SettingsGoogle from './SettingsGoogle'
import SettingsMercadoPago from './SettingsMercadoPago'


export default {
  components: {
    SettingsEmail,
    SettingsGoogle,
    SettingsAlgolia,
    SettingsMercadoPago
  },
  computed: {
    isSmallerScreen () {
      return this.$store.state.windowWidth < 768
    }
  }
}
</script>

<style lang="scss">
  #profile-tabs {
    .vs-tabs--content {
      padding: 0;
    }
  }
</style>
