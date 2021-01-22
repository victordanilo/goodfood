import axios from '../../../axios/index.js'

export default {
  init () {
    axios.interceptors.response.use(function (response) {
      return response
    }, function (error) {
      return Promise.reject(error)
    })
  },
  login (path, email, password, remember_me) {
    return axios.post(path, {
      email,
      password,
      remember_me
    })
  },
  registerUser (path, name, email, password, password_confirmation) {
    return axios.post(path, {
      name,
      email,
      password,
      password_confirmation
    })
  },
  refreshToken (path) {
    return axios.post(path, {accessToken: localStorage.getItem('accessToKen')})
  }
}
