/*=========================================================================================
  File Name: moduleCompanyManagementMutations.js
  Description: Company Management Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default {
  SET_COMPANIES (state, companies) {
    state.companies = companies
  },
  ADD_COMPANY (state, company) {
    state.companies.push(company)
  },
  UPDATE_COMPANY (state, {companyUUID, companyData}) {
    state.companies = state.companies.map((c) => {
      if (c.uuid === companyUUID) c = Object.assign({}, c, companyData)
      return c
    })
  },
  REMOVE_COMPANY (state, companyUUID) {
    const companyIndex = state.companies.findIndex((c) => c.uuid === companyUUID)
    state.companies.splice(companyIndex, 1)
  }
}
