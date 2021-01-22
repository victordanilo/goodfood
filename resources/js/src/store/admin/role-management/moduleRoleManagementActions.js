/*=========================================================================================
  File Name: moduleRoleManagementActions.js
  Description: Role Management Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import axios from '@/axios.js'

export default {
  fetchRoles ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/admin/role')
        .then(response => {
          commit('SET_ROLES', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  addRole ({ commit }, roleData) {
    return new Promise((resolve, reject) => {
      axios.post('/api/admin/role', roleData)
        .then(response => {
          commit('ADD_ROLE', response.data.role)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  updateRole ({ commit }, {roleID, roleData}) {
    return new Promise((resolve, reject) => {
      axios.put(`/api/admin/role/${roleID}`, roleData)
        .then(response => {
          commit('UPDATE_ROLE', {roleID, roleData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  removeRole ({ commit }, roleID) {
    return new Promise((resolve, reject) => {
      axios.delete(`/api/admin/role/${roleID}`)
        .then(response => {
          commit('REMOVE_ROLE', roleID)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  fetchPermissionsFromRole ({ commit }, roleID) {
    return new Promise((resolve, reject) => {
      axios.get(`/api/admin/role/${roleID}/permissions`)
        .then(response => {
          const permissions = response.data
          commit('SYNC_PERMISSION', {roleID, permissions})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  fetchPermissions ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/admin/permission')
        .then(response => {
          commit('SET_PERMISSION', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  addPermission ({ commit }, permissionData) {
    return new Promise((resolve, reject) => {
      axios.post('/api/admin/permission', permissionData)
        .then(response => {
          commit('ADD_PERMISSION', response.data.permission)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  updatePermission ({ commit }, {permissionID, permissionData}) {
    return new Promise((resolve, reject) => {
      axios.put(`/api/admin/permission/${permissionID}`, permissionData)
        .then(response => {
          commit('UPDATE_PERMISSION', {permissionID, permissionData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  removePermission ({ commit }, permissionID) {
    return new Promise((resolve, reject) => {
      axios.delete(`/api/admin/permission/${permissionID}`)
        .then(response => {
          commit('REMOVE_PERMISSION', permissionID)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  syncPermissions ({ commit }, {roleID, permissions}) {
    return new Promise((resolve, reject) => {
      axios.post(`/api/admin/role/${roleID}/permissions`, {permissions})
        .then(response => {
          commit('SYNC_PERMISSION', {roleID, permissions})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  }
}
