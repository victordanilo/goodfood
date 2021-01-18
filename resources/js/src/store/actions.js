/*=========================================================================================
  File Name: actions.js
  Description: Vuex Store - actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import axios from '@/axios.js'

const actions = {

  // /////////////////////////////////////////////
  // COMPONENTS
  // /////////////////////////////////////////////

  // Vertical NavMenu
  updateVerticalNavMenuWidth ({ commit }, width) {
    commit('UPDATE_VERTICAL_NAV_MENU_WIDTH', width)
  },

  // VxAutoSuggest
  updateStarredPage ({ commit }, payload) {
    commit('UPDATE_STARRED_PAGE', payload)
  },

  // The Navbar
  arrangeStarredPagesLimited ({ commit }, list) {
    commit('ARRANGE_STARRED_PAGES_LIMITED', list)
  },
  arrangeStarredPagesMore ({ commit }, list) {
    commit('ARRANGE_STARRED_PAGES_MORE', list)
  },

  // /////////////////////////////////////////////
  // UI
  // /////////////////////////////////////////////

  toggleContentOverlay ({ commit }) {
    commit('TOGGLE_CONTENT_OVERLAY')
  },
  updateTheme ({ commit }, val) {
    commit('UPDATE_THEME', val)
  },

  // /////////////////////////////////////////////
  // User/Account
  // /////////////////////////////////////////////

  loadUserInfo ({ commit }, payload) {
    commit('UPDATE_USER_INFO', payload)
  },
  loadUserRole ({ commit }, payload) {
    // Change client side
    payload.aclChangeRole(payload.userRole)
    commit('UPDATE_USER_INFO',  {userRole: payload.userRole})
  },
  loadUserAddress ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/profile/address')
        .then((response) => {
          commit('ADD_USER_ADDRESS', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  editUser ({ commit }, payload) {
    return new Promise((resolve, reject) => {
      payload.formData.append('_method', 'PUT')
      axios.post(payload.path, payload.formData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then((response) => {
          const updatedData = response.data.updates
          commit('UPDATE_USER_INFO', updatedData)
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  addUserAddress ({ commit }, addressData) {
    return new Promise((resolve, reject) => {
      axios.post('/api/profile/address', addressData)
        .then((response) => {
          const addressData = [response.data.address]
          commit('ADD_USER_ADDRESS', addressData)
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  editUserAddress ({ commit }, {addressUUID, addressData}) {
    return new Promise((resolve, reject) => {
      axios.put(`/api/profile/address/${addressUUID}`, addressData)
        .then((response) => {
          commit('UPDATE_USER_ADDRESS', {addressUUID, addressData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  resetUser ({ commit }) {
    commit('UPDATE_USER_INFO', {
      uuid        : null,
      name        : '',
      email       : '',
      avatar      : '',
      userRole    : 'public'
    })
    commit('UPDATE_USER_ADDRESS', [])
  }
}

export default actions
