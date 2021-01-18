import axios from '@/axios.js'

export default {
  validate (action, token) {
    return new Promise((resolve, reject) => {
      axios.post('/api/validate-recaptcha', {action, token})
        .then((response) => {
          resolve(response)
        })
        .catch((error) => { reject(error) })
    })
  }
}
