/*=========================================================================================
  File Name: moduleOrderManagement.js
  Description: Order Management Module
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import state from './moduleOrderManagementState'
import mutations from './moduleOrderManagementMutations'
import actions from './moduleOrderManagementActions'
import getters from './moduleOrderManagementGetters'

export default {
  isRegistered: false,
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}
