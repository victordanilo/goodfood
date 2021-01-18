/*=========================================================================================
  File Name: moduleUserManagementActions.js
  Description: User Management Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import axios from '@/axios.js'

export default {
  fetchUsers ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/admin/user')
        .then((response) => {
          commit('SET_USERS', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  fetchUser ({ commit }, userUUID) {
    return new Promise((resolve, reject) => {
      axios.get(`/api/admin/user/${userUUID}`)
        .then((response) => {
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  addUser ({ commit }, userData) {
    return new Promise((resolve, reject) => {
      axios.post('/api/admin/user', userData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then((response) => {
          commit('ADD_USER', response.data.user)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  updateUser ({ commit }, {userUUID, userData}) {
    return new Promise((resolve, reject) => {
      userData.append('_method', 'PUT')
      axios.post(`/api/admin/user/${userUUID}`, userData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then((response) => {
          userData = response.data.updates
          commit('UPDATE_USER', {userUUID, userData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  removeUser ({ commit }, userUUID) {
    return new Promise((resolve, reject) => {
      axios.delete(`/api/admin/user/${userUUID}`)
        .then((response) => {
          commit('REMOVE_USER', userUUID)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  setRole ({ commit }, {userUUID, roleData}) {
    return new Promise((resolve, reject) => {
      axios.post(`/api/admin/user/${userUUID}/set-role`, roleData)
        .then((response) => {
          const userData = roleData
          commit('UPDATE_USER', {userUUID, userData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  }
}
