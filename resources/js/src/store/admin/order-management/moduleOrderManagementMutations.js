/*=========================================================================================
  File Name: moduleOrderManagement.js
  Description: Order Management Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default {
  SET_ORDERS (state, orders) {
    state.orders = orders
  },
  ADD_ORDER (state, order) {
    state.orders.push(order)
  },
  UPDATE_ORDER (state, {orderUUID, orderData}) {
    state.orders = state.orders.map((o) => {
      if (o.uuid === orderUUID) o = Object.assign({}, o, orderData)
      return o
    })
  },
  REMOVE_ORDER (state, orderUUID) {
    const orderIndex = state.orders.findIndex((o) => o.uuid === orderUUID)
    state.orders.splice(orderIndex, 1)
  }
}
