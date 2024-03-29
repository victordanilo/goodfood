<template>
  <div>
    <vs-input
      v-validate="'required|email|min:3'"
      data-vv-validate-on="blur"
      name="email"
      icon-no-border
      icon="icon icon-user"
      icon-pack="feather"
      label-placeholder="Email"
      v-model="email"
      class="w-full"/>
    <span class="text-danger text-sm">{{ errors.first('email') }}</span>

    <vs-input
      data-vv-validate-on="blur"
      v-validate="'required|min:6|max:20'"
      type="password"
      name="password"
      icon-no-border
      icon="icon icon-lock"
      icon-pack="feather"
      label-placeholder="Password"
      v-model="password"
      class="w-full mt-6"/>
    <span class="text-danger text-sm">{{ errors.first('password') }}</span>

    <div class="flex flex-wrap justify-between my-5">
      <vs-checkbox v-model="checkbox_remember_me" class="mb-3">{{ $t('auth.btn_remember_me') }}</vs-checkbox>
      <router-link to="/admin/auth/forgot-password" v-if="false">{{ $t('auth.btn_forget_password') }}</router-link>
    </div>

    <google-re-captcha-v3 v-model="reCaptcha.token" ref="captcha" :site-key="reCaptcha.public_key" action="login" v-if="this.reCaptcha.show" />

    <div class="flex flex-wrap justify-between mb-3">
      <vs-button :disabled="!validateForm" @click="loginJWT" class="w-full">{{ $t('auth.btn_login') }}</vs-button>
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
      email: '',
      password: '',
      checkbox_remember_me: false,
      reCaptcha: {
        public_key: window.reCaptcha_public_key,
        token: '',
        status: false,
        show: false
      }
    }
  },
  computed: {
    validateForm () {
      return !this.errors.any() && this.email !== '' && this.password !== '' && this.reCaptcha.status
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
      if (this.$store.state.auth.isUserLoggedIn('admin')) {

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
    loginJWT () {
      // Loading
      this.$vs.loading()

      const payload = {
        path: '/api/admin/auth/login',
        email: this.email,
        password: this.password,
        remember_me: this.checkbox_remember_me
      }

      this.$store.dispatch('auth/loginJWT', payload)
        .then(() => {
          this.$vs.loading.close()

          // Update roles
          this.$store.dispatch('loadUserRole', {aclChangeRole: this.$acl.change, userRole: 'admin'})

          // Navigate User to homepage
          this.$router.push('/admin')
        })
        .catch(error => {
          this.$vs.loading.close()

          this.$vs.notify({
            title: 'Error',
            text: error.message,
            iconPack: 'feather',
            icon: 'icon-alert-circle',
            color: 'danger'
          })
        })
    },
    checkReCaptcha (tokenRecaptcha) {
      recaptchaService
        .validate('login', tokenRecaptcha)
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
      this.$router.push('/admin')
    } else {
      this.reCaptcha.show = true
    }
  }
}
</script>
