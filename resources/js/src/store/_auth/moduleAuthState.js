/*=========================================================================================
  File Name: moduleAuthState.js
  Description: Auth Module State
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import auth from '@/services/authService'

export default {
  accessToken: null,
  isUserLoggedIn: (guard) => {
    let isAuthenticated = false
    const userInfo = JSON.parse(localStorage.getItem('userInfo'))
    const userRole = userInfo ? userInfo.userRole : null

    if (auth.isAuthenticated()) isAuthenticated = true
    else isAuthenticated = false

    return userRole === guard && isAuthenticated
  }
}
