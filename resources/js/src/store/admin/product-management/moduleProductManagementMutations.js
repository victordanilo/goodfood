/*=========================================================================================
  File Name: moduleProductManagement.js
  Description: Product Management Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default {
  SET_PRODUCTS (states, products) {
    states.products = products
  },
  SET_CATEGORIES (states, categories) {
    states.categories = categories
  },
  ADD_CATEGORY (states, category) {
    states.categories.push(category)
  },
  UPDATE_CATEGORY (states, {categoryUUID, categoryData}) {
    states.categories = states.categories.map((c) => {
      if (c.uuid === categoryUUID) c = Object.assign({}, c, categoryData)
      return c
    })
  },
  REMOVE_CATEGORY (states, categoryUUID) {
    const categoryIndex = states.categories.findIndex((c) => c.uuid === categoryUUID)
    states.categories.splice(categoryIndex, 1)
  }
}
