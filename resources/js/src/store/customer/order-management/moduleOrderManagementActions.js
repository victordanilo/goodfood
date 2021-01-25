/*=========================================================================================
  File Name: moduleOrderManagementActions.js
  Description: Order Management Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import axios from '@/axios.js'

export default {
  fetchOrders ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/order')
        .then((response) => {
          commit('SET_ORDERS', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  fetchOrder ({ commit }, orderUUID) {
    return new Promise((resolve, reject) => {
      axios.get(`/api/order/${orderUUID}`)
        .then((response) => {
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  }
}
