/*=========================================================================================
  File Name: moduleAuthActions.js
  Description: Auth Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import jwt from '../../http/requests/auth/jwt'
import router from '@/router'

const localStorageKey = 'loggedIn'
const tokenExpiryKey = 'tokenExpiry'

export default {
  // JWT
  loginJWT ({commit}, payload) {
    return new Promise((resolve, reject) => {
      jwt.login(payload.path, payload.email, payload.password, payload.remember_me)
        .then(response => {
          // If there's user data in response
          if (response.status === 200) {

            // token expirykey
            localStorage.setItem(tokenExpiryKey, new Date(response.data.expires_at))
            localStorage.setItem(localStorageKey, 'true')

            // set path from logout
            const redirecLogin = router.currentRoute.matched[0].meta.redirectLogin
            localStorage.setItem('redirectLogin', redirecLogin)

            // Update user details
            commit('UPDATE_USER_INFO', response.data.user, {root: true})

            // Set bearer token in axios
            commit('SET_BEARER', response.data.access_token)

            resolve(response)
          } else {
            reject(response)
          }
        })
        .catch(error => {
          reject(error.response.data)
        })
    })
  },
  registerUserJWT ({commit}, payload) {
    return new Promise((resolve, reject) => {

      // Check confirm password
      if (payload.password !== payload.confirmPassword) {
        reject({message: this.$i18n.t('notify_password_no_match')})
      }

      jwt.registerUser(payload.path, payload.name, payload.email, payload.password, payload.confirmPassword)
        .then(response => {
          resolve(response)
        })
        .catch(error => {
          reject(error.response.data)
        })
    })
  },
  fetchAccessToken (path) {
    return new Promise((resolve) => {
      jwt.refreshToken(path).then(response => {
        resolve(response)
      })
    })
  }
}
