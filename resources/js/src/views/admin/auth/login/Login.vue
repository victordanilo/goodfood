<!-- =========================================================================================
    File Name: Login.vue
    Description: Login Page
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div class="h-screen flex w-full bg-img vx-row no-gutter items-center justify-center" id="page-login">
    <div class="vx-col sm:w-1/1 md:w-1/2 lg:w-1/3 sm:m-0 m-4">
      <vx-card>
        <div slot="no-body" class="full-page-bg-color">
          <div class="vx-row no-gutter">

            <div class="vx-col sm:w-full md:w-full d-theme-dark-bg">
              <div class="px-8 pt-8">
                <div class="vx-card__title mb-4">
                  <img src="/images/logo/logo.png" class="w-10 mx-auto mb-4" alt="">
                  <p class="description">{{ $t('auth.login.description') }}</p>
                </div>
                <login-jwt class="pt-2 pb-4"></login-jwt>
              </div>
            </div>

          </div>
        </div>
      </vx-card>
    </div>
  </div>
</template>


<script>
import LoginJwt from './LoginJWT.vue'

export default {
  components: {
    LoginJwt
  }
}
</script>

<style lang="scss">
p.description {
  font-family: "visby-round";
  font-size: 1rem;
  text-align: center;
}
</style>
