/*=========================================================================================
  File Name: moduleCustomerManagement.js
  Description: Customer Management Module
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import state from './moduleCustomerManagementState.js'
import mutations from './moduleCustomerManagementMutations.js'
import actions from './moduleCustomerManagementActions.js'
import getters from './moduleCustomerManagementGetters.js'

export default {
  isRegistered: false,
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}
