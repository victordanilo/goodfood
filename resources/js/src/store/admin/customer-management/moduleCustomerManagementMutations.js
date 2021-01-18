/*=========================================================================================
  File Name: moduleCustomerManagementMutations.js
  Description: Customer Management Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default {
  SET_CUSTOMERS (state, customers) {
    state.customers = customers
  },
  ADD_CUSTOMER (state, customer) {
    state.customers.push(customer)
  },
  UPDATE_CUSTOMER (state, {customerUUID, customerData}) {
    state.customers = state.customers.map((c) => {
      if (c.uuid === customerUUID) c = Object.assign({}, c, customerData)
      return c
    })
  },
  REMOVE_CUSTOMER (state, customerUUID) {
    const customerIndex = state.customers.findIndex((c) => c.uuid === customerUUID)
    state.customers.splice(customerIndex, 1)
  },
  ADD_ADDRESS (state, {customerUUID, addressData}) {
    state.customers = state.customers.map((c) => {
      if (c.uuid === customerUUID) c.addresses = [addressData]
      return c
    })
  },
  UPDATE_ADDRESS (state, {customerUUID, addressData}) {
    state.customers = state.customers.map((c) => {
      if (c.uuid === customerUUID) {
        c.addresses = c.addresses.map((a) => {
          if (a.uuid === addressData.uuid) a = Object.assign({}, a, addressData)
          return a
        })
      }

      return c
    })
  },
  REMOVE_ADDRESS (state, {customerUUID, addressUUID}) {
    state.customers = state.customers.map((c) => {
      if (c.uuid === customerUUID) {
        const addressIndex = c.addresses.findIndex((a) => a.uuid === addressUUID)
        c.addresses.splice(addressIndex, 1)
      }

      return c
    })
  }
}
