<!-- =========================================================================================
    File Name: ForgotPassword.vue
    Description: FOrgot Password Page
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div class="h-screen flex w-full bg-img">
    <div class="vx-col w-4/5 sm:w-4/5 md:w-3/5 lg:w-3/4 xl:w-1/3 mx-auto self-center">
      <vx-card>
        <div slot="no-body" class="full-page-bg-color">
          <div class="vx-row">
            <div class="vx-col sm:w-full md:w-full mx-auto self-center d-theme-dark-bg">
              <div class="p-8">
                <div class="vx-card__title mb-8">
                  <h4 class="mb-4">{{ $t('auth.forget_password.title') }}</h4>
                  <p>{{ $t('auth.forget_password.description') }}</p>
                </div>

                <vs-input type="email" label-placeholder="Email" v-model="value1" class="w-full mb-8"/>
                <vs-button type="border" to="/admin/auth/login" class="px-4 w-full md:w-auto">
                  {{ $t('auth.forget_password.btn_back_to_login') }}
                </vs-button>
                <vs-button class="float-right px-4 w-full md:w-auto mt-3 mb-8 md:mt-0 md:mb-0">
                  {{ $t('auth.forget_password.btn_recover_password') }}
                </vs-button>
              </div>
            </div>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      value1: ''
    }
  }
}
</script>
