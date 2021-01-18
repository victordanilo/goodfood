/*=========================================================================================
  File Name: moduleCustomerManagementActions.js
  Description: Customer Management Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import axios from '@/axios.js'

export default {
  fetchCustomers ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/admin/customer')
        .then((response) => {
          commit('SET_CUSTOMERS', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  fetchCustomer ({ commit }, customerUUID) {
    return new Promise((resolve, reject) => {
      axios.get(`/api/admin/customer/${customerUUID}`)
        .then((response) => {
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  addCustomer ({ commit }, customerData) {
    return new Promise((resolve, reject) => {
      axios.post('/api/admin/customer', customerData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then((response) => {
          commit('ADD_CUSTOMER', response.data.customer)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  updateCustomer ({ commit }, {customerUUID, customerData}) {
    return new Promise((resolve, reject) => {
      customerData.append('_method', 'PUT')
      axios.post(`/api/admin/customer/${customerUUID}`, customerData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then((response) => {
          customerData = response.data.updates
          commit('UPDATE_CUSTOMER', {customerUUID, customerData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  removeCustomer ({ commit }, customerUUID) {
    return new Promise((resolve, reject) => {
      axios.delete(`/api/admin/customer/${customerUUID}`)
        .then((response) => {
          commit('REMOVE_CUSTOMER', customerUUID)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  addAddress ({ commit }, {customerUUID, addressData}) {
    return new Promise((resolve, reject) => {
      axios.post(`/api/admin/customer/${customerUUID}/address`, addressData)
        .then((response) => {
          const addressData = response.data.address
          commit('ADD_ADDRESS', {customerUUID, addressData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  updateAddress ({ commit }, {customerUUID, addressData}) {
    return new Promise((resolve, reject) => {
      axios.put(`/api/admin/customer/${customerUUID}/address/${addressData.uuid}`, addressData)
        .then((response) => {
          commit('UPDATE_ADDRESS', {customerUUID, addressData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  removeAddress ({ commit }, {customerUUID, addressUUID}) {
    return new Promise((resolve, reject) => {
      axios.delete(`/api/admin/customer/${customerUUID}/address/${addressUUID}`)
        .then((response) => {
          commit('REMOVE_ADDRESS', {customerUUID, addressUUID})
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  }
}
