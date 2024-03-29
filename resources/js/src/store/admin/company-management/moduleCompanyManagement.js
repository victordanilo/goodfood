/*=========================================================================================
  File Name: moduleCompanyManagement.js
  Description: Company Management Module
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import state from './moduleCompanyManagementState.js'
import mutations from './moduleCompanyManagementMutations.js'
import actions from './moduleCompanyManagementActions.js'
import getters from './moduleCompanyManagementGetters.js'

export default {
  isRegistered: false,
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}
