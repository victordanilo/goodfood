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
  ADD_PRODUCT (states, product) {
    states.products.push(product)
  },
  UPDATE_PRODUCT (states, {productUUID, productData}) {
    states.products = states.products.map((p) => {
      if (p.uuid === productUUID) p = Object.assign({}, p, productData)
      return p
    })
  },
  REMOVE_PRODUCT (states, productUUID) {
    const productIndex = states.products.findIndex((p) => p.uuid === productUUID)
    states.products.splice(productIndex, 1)
  },
  SET_CATEGORIES (states, categories) {
    states.categories = categories
  }
}
