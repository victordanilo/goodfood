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
      axios.get('/api/admin/product')
        .then((response) => {
          commit('SET_PRODUCTS', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  fetchCategories ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/admin/product/category')
        .then((response) => {
          commit('SET_CATEGORIES', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  addCategory ({ commit }, categoryData) {
    return new Promise((resolve, reject) => {
      axios.post('/api/admin/product/category', categoryData)
        .then((response) => {
          commit('ADD_CATEGORY', response.data.category)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  updateCategory ({ commit }, {categoryUUID, categoryData}) {
    return new Promise((resolve, reject) => {
      axios.put(`/api/admin/product/category/${categoryUUID}`, categoryData)
        .then((response) => {
          commit('UPDATE_CATEGORY', {categoryUUID, categoryData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  removeCategory ({ commit }, categoryUUID) {
    return new Promise((resolve, reject) => {
      axios.delete(`/api/admin/product/category/${categoryUUID}`)
        .then((response) => {
          commit('REMOVE_CATEGORY', categoryUUID)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  }
}
