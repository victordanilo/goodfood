import axios from '@/axios.js'

export default {
  toggleItemInCart ({ getters, commit, dispatch }, item) {
    getters.isInCart(item.objectID) ? commit('REMOVE_ITEM_FROM_CART', item) : dispatch('addItemInCart', item)
  },
  addItemInCart ({ commit }, item) {
    item['quantity'] = 1
    commit('ADD_ITEM_IN_CART', item)
  },
  removeItemFromCart ({ commit }, item) {
    commit('REMOVE_ITEM_FROM_CART', item)
  },
  updateItemQuantity ({ commit }, payload) {
    commit('UPDATE_ITEM_QUANTITY', payload)
  },
  toCleanCart ({ commit }) {
    commit('CLEAN_CART')
  },
  calculateDelivery ({ commit }, cartData) {
    return new Promise((resolve, reject) => {
      axios.post('/api/calculate-delivery', cartData)
        .then((response) => {
          commit('SET_DELIVERY_PRICE', response.data.delivery_price)
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  },
  payment ({ commit }, paymentData) {
    return new Promise((resolve, reject) => {
      axios.post('/api/order', paymentData)
        .then((response) => {
          commit('CLEAN_CART')
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  }
}
