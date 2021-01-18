/*=========================================================================================
  File Name: moduleRoleManagementMutations.js
  Description: Role Management Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default {
  SET_ROLES (state, roles) {
    state.roles = roles
  },
  ADD_ROLE (state, role) {
    state.roles.push(role)
  },
  UPDATE_ROLE (state, {roleID, roleData}) {
    state.roles = state.roles.map(r => {
      if (r.id === roleID) r = Object.assign({}, r, roleData)
      return r
    })
  },
  REMOVE_ROLE (state, roleID) {
    const roleIndex = state.roles.findIndex((r) => r.id === roleID)
    state.roles.splice(roleIndex, 1)
  },
  SET_PERMISSION (state, permissions) {
    state.permissions = permissions
  },
  ADD_PERMISSION (state, permission) {
    state.permissions.push(permission)
  },
  UPDATE_PERMISSION (state, {permissionID, permissionData}) {
    state.permissions = state.permissions.map(p => {
      if (p.id === permissionID) p = Object.assign({}, p, permissionData)
      return p
    })
  },
  REMOVE_PERMISSION (state, permissionID) {
    const permissionIndex = state.permissions.findIndex((p) => p.id === permissionID)
    state.permissions.splice(permissionIndex, 1)
  },
  SYNC_PERMISSION (state, {roleID, permissions}) {
    state.roles = state.roles.map(r => {
      if (r.id === roleID) r.permissions = permissions
      return r
    })
  }
}
