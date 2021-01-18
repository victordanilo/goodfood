// axios
import axios from 'axios'

const baseURL = window.baseUrl

export default axios.create({
  baseURL
  // You can add your headers here
})
