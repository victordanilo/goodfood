/*=========================================================================================
  File Name: i18n.js
  Description: i18n configuration file. Imports i18n data.
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import Vue from 'vue'
import VueI18n from 'vue-i18n'
import VueI18nFilter from 'vue-i18n-filter'
import i18nData from './i18nData'

Vue.use(VueI18n)
Vue.use(VueI18nFilter)

export default new VueI18n({
  locale: 'pt', // set default locale
  messages: i18nData
})
