/*=========================================================================================
  File Name: moduleUserManagementMutations.js
  Description: User Management Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import _ from 'lodash'

export default {
  SET_USERS (state, users) {
    // remover usuário logado da lista
    const userLoggedIn = JSON.parse(localStorage.getItem('userInfo')) || {}
    users = _.remove(users, (u) => {
      return u.uuid !== userLoggedIn.uuid
    })

    state.users = users
  },
  ADD_USER (state, user) {
    state.users.push(user)
  },
  UPDATE_USER (state, {userUUID, userData}) {
    state.users = state.users.map((u) => {
      if (u.uuid === userUUID) u = Object.assign({}, u, userData)
      return u
    })
  },
  REMOVE_USER (state, userUUID) {
    const userIndex = state.users.findIndex((u) => u.uuid === userUUID)
    state.users.splice(userIndex, 1)
  }
}
