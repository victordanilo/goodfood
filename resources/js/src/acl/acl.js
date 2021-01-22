import Vue from 'vue'
import { AclInstaller, AclCreate, AclRule } from 'vue-acl'
import router from '@/router'

Vue.use(AclInstaller)

let initialRole = 'public'

const userInfo = JSON.parse(localStorage.getItem('userInfo'))
if (userInfo && userInfo.userRole) initialRole = userInfo.userRole

export default new AclCreate({
  initial  : initialRole,
  notfound : '/not-authorized',
  router,
  acceptLocalRules : true,
  globalRules: {
    admin: new AclRule('admin').generate(),
    company: new AclRule('company').generate(),
    customer: new AclRule('customer').generate(),
    public: new AclRule('public')
      .or('customer')
      .or('company')
      .or('admin')
      .generate()
  }
})
