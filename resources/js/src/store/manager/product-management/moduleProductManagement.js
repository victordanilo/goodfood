/*=========================================================================================
  File Name: moduleProductManagement.js
  Description: Product Management Module
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import state from './moduleProductManagementState'
import mutations from './moduleProductManagementMutations'
import actions from './moduleProductManagementActions'
import getters from './moduleProductManagementGetters'

export default {
  isRegistered: false,
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}
