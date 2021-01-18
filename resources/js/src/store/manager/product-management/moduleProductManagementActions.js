/*=========================================================================================
  File Name: moduleProductManagementActions.js
  Description: Product Management Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import axios from '@/axios.js'

export default {
  fetchProducts ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/manager/product')
        .then((response) => {
          commit('SET_PRODUCTS', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  addProduct ({ commit }, productData) {
    return new Promise((resolve, reject) => {
      axios.post('/api/manager/product', productData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then((response) => {
          commit('ADD_PRODUCT', response.data.product)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  updateProduct ({ commit }, {productUUID, productData}) {
    return new Promise((resolve, reject) => {
      productData.append('_method', 'PUT')
      axios.post(`/api/manager/product/${productUUID}`, productData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then((response) => {
          commit('UPDATE_PRODUCT', {productUUID, productData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  removeProduct ({ commit }, productUUID) {
    return new Promise((resolve, reject) => {
      axios.delete(`/api/manager/product/${productUUID}`)
        .then((response) => {
          commit('REMOVE_PRODUCT', productUUID)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  fetchCategories ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/manager/product/category')
        .then((response) => {
          commit('SET_CATEGORIES', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  }
}
