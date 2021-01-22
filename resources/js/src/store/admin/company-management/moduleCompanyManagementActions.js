/*=========================================================================================
  File Name: moduleCompanyManagementActions.js
  Description: Company Management Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import axios from '@/axios.js'

export default {
  fetchCompanies ({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('/api/admin/company')
        .then((response) => {
          commit('SET_COMPANIES', response.data)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  fetchCompany ({ commit }, companyUUID) {
    return new Promise((resolve, reject) => {
      axios.get(`/api/admin/company/${companyUUID}`)
        .then((response) => {
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  addCompany ({ commit }, companyData) {
    return new Promise((resolve, reject) => {
      axios.post('/api/admin/company', companyData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then((response) => {
          commit('ADD_COMPANY', response.data.company)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  updateCompany ({ commit }, {companyUUID, companyData}) {
    return new Promise((resolve, reject) => {
      companyData.append('_method', 'PUT')
      axios.post(`/api/admin/company/${companyUUID}`, companyData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then((response) => {
          companyData = response.data.updates
          commit('UPDATE_COMPANY', {companyUUID, companyData})
          resolve(response)
        }).catch((error) => { reject(error) })
    })
  },
  removeCompany ({ commit }, companyUUID) {
    return new Promise((resolve, reject) => {
      axios.delete(`/api/admin/company/${companyUUID}`)
        .then((response) => {
          commit('REMOVE_COMPANY', companyUUID)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  }
}
