/*=========================================================================================
  File Name: moduleSettingManagement.js
  Description: Setting Management Module
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import state from './moduleSettingManagementState'
import mutations from './moduleSettingManagementMutations'
import actions from './moduleSettingManagementActions'
import getters from './moduleSettingManagementGetters'

export default {
  isRegistered: false,
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}
