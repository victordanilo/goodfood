/*=========================================================================================
  File Name: moduleSettingManagementActions.js
  Description: Setting Management Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import axios from '@/axios.js'

export default {
  fetchSettings ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/admin/setting')
        .then((response) => {
          commit('LOAD_SETTINGS', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  setSetting ({ commit }, settings) {
    return new Promise((resolve, reject) => {
      axios.post('/api/admin/setting', { settings })
        .then((response) => {
          commit('SET_SETTING', settings)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  }
}
