const getCart = () => {
  return JSON.parse(localStorage.getItem('cartLocalStorage')) || []
}

export default {
  cartItems: getCart(),
  delivery_price: '0.00'
}
