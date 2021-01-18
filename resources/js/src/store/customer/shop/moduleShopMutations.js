export default {
  REMOVE_ITEM_FROM_CART (state, item) {
    const index = state.cartItems.findIndex(i => i.objectID === item.objectID)
    if (index > -1) { state.cartItems.splice(index, 1) }
    localStorage.setItem('cartLocalStorage', JSON.stringify([...state.cartItems]))
  },
  ADD_ITEM_IN_CART (state, item) {
    state.cartItems.push(Object.assign({}, item))
    localStorage.setItem('cartLocalStorage', JSON.stringify([...state.cartItems]))
  },
  UPDATE_ITEM_QUANTITY (state, payload) {
    state.cartItems[payload.index].quantity = payload.quantity
    localStorage.setItem('cartLocalStorage', JSON.stringify([...state.cartItems]))
  },
  CLEAN_CART (state) {
    state.cartItems = []
    localStorage.setItem('cartLocalStorage', JSON.stringify([]))
  },
  SET_DELIVERY_PRICE (state, price) {
    state.delivery_price = price
  }
}
