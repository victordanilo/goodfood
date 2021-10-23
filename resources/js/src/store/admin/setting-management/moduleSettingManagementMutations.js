/*=========================================================================================
  File Name: moduleSettingManagementMutations.js
  Description: Setting Management Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import _ from 'lodash'

export default {
  LOAD_SETTINGS (state, settings) {
    state.settings = settings
  },
  SET_SETTING (state, settings) {
    state.settings = _.merge(state.settings, settings)
  }
}
