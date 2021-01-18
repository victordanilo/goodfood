<!-- =========================================================================================
File Name: RegisterJWT.vue
Description: Register Page for JWT
----------------------------------------------------------------------------------------
Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div class="clearfix">
    <vs-input
      v-validate="'required|min:3'"
      data-vv-validate-on="blur"
      label-placeholder="Name"
      name="name"
      placeholder="Name"
      v-model="name"
      class="w-full" />
    <span class="text-danger text-sm">{{ errors.first('name') }}</span>

    <vs-input
      v-validate="'required|email'"
      data-vv-validate-on="blur"
      name="email"
      type="email"
      label-placeholder="Email"
      placeholder="Email"
      v-model="email"
      class="w-full mt-6" />
    <span class="text-danger text-sm">{{ errors.first('email') }}</span>

    <vs-input
      ref="password"
      type="password"
      data-vv-validate-on="blur"
      v-validate="'required|min:6|max:10'"
      name="password"
      label-placeholder="Password"
      placeholder="Password"
      v-model="password"
      class="w-full mt-6" />
    <span class="text-danger text-sm">{{ errors.first('password') }}</span>

    <vs-input
      type="password"
      v-validate="'min:6|max:10|confirmed:password'"
      data-vv-validate-on="blur"
      data-vv-as="password"
      name="confirm_password"
      label-placeholder="Confirm Password"
      placeholder="Confirm Password"
      v-model="confirm_password"
      class="w-full mt-6" />
    <span class="text-danger text-sm">{{ errors.first('confirm_password') }}</span>

    <div class="flex flex-wrap justify-center my-6">
      <vs-checkbox v-model="isTermsConditionAccepted">{{ $t('auth.register.terms_conditions') }}</vs-checkbox>
    </div>

    <google-re-captcha-v3 v-model="reCaptcha.token" ref="captcha" :site-key="reCaptcha.public_key" action="register" />

    <div class="flex flex-wrap justify-between">
      <vs-button :disabled="!validateForm" @click="registerUserJWt" color="success" class="w-full" >{{ $t('register') }}</vs-button>
    </div>

  </div>
</template>

<script>
import recaptchaService from '@/services/recaptchaService'
import GoogleReCaptchaV3 from '@/components/googlerecaptchav3/GoogleReCaptchaV3'

export default {
  components: {
    GoogleReCaptchaV3
  },
  data () {
    return {
      name: '',
      email: '',
      password: '',
      confirm_password: '',
      isTermsConditionAccepted: true,
      reCaptcha: {
        public_key: window.reCaptcha_public_key,
        token: '',
        status: false
      }
    }
  },
  computed: {
    validateForm () {
      return !this.errors.any()
        && this.displayName !== ''
        && this.email !== ''
        && this.password !== ''
        && this.confirm_password !== ''
        && this.isTermsConditionAccepted === true
        && this.reCaptcha.status
    }
  },
  watch: {
    'reCaptcha.token' (tokenRecaptcha) {
      this.checkReCaptcha(tokenRecaptcha)
    }
  },
  methods: {
    checkLogin () {
      // If user is already logged in notify
      if (this.$store.state.auth.isUserLoggedIn('customer')) {

        // Close animation if passed as payload
        // this.$vs.loading.close()

        this.$vs.notify({
          text: this.$i18n.t('auth.login.notify.is_logged'),
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'success'
        })

        return true
      }

      return false
    },
    registerUserJWt () {
      // If form is not validated or user is already login return
      if (!this.validateForm) return

      this.$vs.loading()

      const payload = {
        path: '/api/manager/auth/signup',
        name: this.name,
        email: this.email,
        password: this.password,
        confirmPassword: this.confirm_password
      }
      this.$store.dispatch('auth/registerUserJWT', payload)
        .then(response => {
          this.$vs.loading.close()

          this.$router.push({name: 'manager-login'})

          this.$vs.notify({
            text: response.data.message,
            iconPack: 'feather',
            icon: 'icon-alert-circle',
            color: 'success'
          })
        })
        .catch(error => {
          this.$vs.loading.close()
          for (const input in error.errors) {
            this.$vs.notify({
              title: error.message,
              text: error.errors[input].join(', '),
              iconPack: 'feather',
              icon: 'icon-alert-circle',
              color: 'danger'
            })
          }
        })
    },
    checkReCaptcha (tokenRecaptcha) {
      recaptchaService
        .validate('register', tokenRecaptcha)
        .then(() => { this.reCaptcha.status = true })
        .catch(err => {
          this.$vs.notify({
            text: this.$i18n.t('notify_validate_recaptcha'),
            iconPack: 'feather',
            icon: 'icon-alert-circle',
            color: 'danger'
          })
          console.log(err)
        })
    }
  },
  created () {
    if (this.checkLogin()) {
      // Navigate User to homepage
      this.$router.push('/')
    }
  }
}
</script>
