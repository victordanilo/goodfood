/*=========================================================================================
  File Name: moduleRoleManagement.js
  Description: Role Management Module
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import state from './moduleRoleManagementState'
import mutations from './moduleRoleManagementMutations'
import actions from './moduleRoleManagementActions'
import getters from './moduleRoleManagementGetters'

export default {
  isRegistered: false,
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}
