<!-- =========================================================================================
  File Name: RoleView.vue
  Description: Role View page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div>
    <vs-alert color="danger" :title="title_role_not_found" :active.sync="role_not_found">
      <span>{{ $t('notify_register_not_found', {record: this.$i18n.t('role'), prop: 'id', value: $route.params.roleID}) }}</span>
      <span>
        <span>{{ $t('check') }} </span><router-link :to="{name:'admin-roles'}" class="text-inherit underline">{{ $t('all_roles') }}</router-link>
      </span>
    </vs-alert>

    <div v-if="role">
      <vx-card>
        <div class="vx-row">
          <div class="vx-col w-full">
            <div class="flex items-end px-3">
              <feather-icon svgClasses="w-6 h-6" icon="LockIcon" class="mr-2" />
              <span class="font-medium text-lg leading-none">{{ $t('permissions') }}</span>
            </div>

            <vs-divider />

            <div class="block overflow-x-auto">
              <table class="w-full permissions-table">
                <tr>
                  <th class="font-semibold text-base text-left px-3 py-2" v-for="heading in ['module', 'read', 'create', 'edit', 'delete']" :key="heading">{{ $t(heading) }}</th>
                </tr>
                <tr v-for="(groupPermission, scope_name) in permissions" :key="scope_name">
                  <td class="px-3 py-2">{{ $t(scope_name) }}</td>
                  <td v-for="(permission, key) in groupPermission" class="px-3 py-2" v-if="permissionsActions[key] === permission.action" :key="permission.id">
                    <vs-checkbox v-model="role.permissions" :vs-value="permission.id" :class="[role.name === 'admin' ? 'pointer-events-none' : '']" />
                  </td>
                </tr>
              </table>
            </div>
          </div>

          <div class="vx-col w-full" style="margin-top: 50px;">
            <div class="flex items-end px-3">
              <feather-icon svgClasses="w-6 h-6" icon="LockIcon" class="mr-2" />
              <span class="font-medium text-lg leading-none">{{ $t('permissions') }} {{ $t('special') }}</span>
            </div>

            <vs-divider />

            <div class="block overflow-x-auto">
              <table class="w-full permissions-table">
                <tr>
                  <th class="font-semibold text-base text-left px-3 py-2" v-for="heading in ['name', 'active']" :key="heading">{{ $t(heading) }}</th>
                </tr>

                <tr v-for="permission in permissionsSpecial" :key="permission.id">
                  <td class="px-3 py-2" style="width: 366px">{{ $t(permission.action) }}</td>
                  <td class="px-3 py-2">
                    <vs-checkbox v-model="role.permissions" :vs-value="permission.id"  :class="[role.name === 'admin' ? 'pointer-events-none' : '']" />
                  </td>
                </tr>
              </table>
            </div>
          </div>

          <div class="vx-col w-full">
            <div class="mt-8 flex justify-center md:justify-end">
              <vs-button class="ml-4 mt-2" @click="save" :disabled="!hasUpdates">{{ $t('save') }}</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import MainBackAction from './MainBackAction.vue'
import moduleRoleManagement from '@/store/admin/role-management/moduleRoleManagement'

export default {
  data () {
    return {
      title_role_not_found: this.$i18n.t('record_not_found', {record: this.$i18n.t('role')}),
      roleOrigin: null,
      role: null,
      permissions: [],
      permissionsSpecial: [],
      permissionsActions: ['read', 'create', 'update', 'delete'],
      role_not_found: false
    }
  },
  computed: {
    hasUpdates () {
      return !_.isEmpty(_.difference(this.roleOrigin.permissions, this.role.permissions))
        || !_.isEmpty(_.difference(this.role.permissions, this.roleOrigin.permissions))
    }
  },
  methods: {
    setPermissions (permissions) {
      permissions = _.chain(permissions)
        .filter({guard_name: 'admin'})
        .map(permission => {
          const meta = permission.name.split('.')
          permission.scope = meta[0] || ''
          permission.action = meta[1] || ''
          permission.special = !this.permissionsActions.includes(permission.action)
          return permission
        })
        .groupBy('scope')
        .value()
      this.permissions = permissions
      this.permissionsSpecial = _.chain(permissions).flatMap().filter({special: true}).value()
    },
    setRole (role) {
      role.permissions = _.map(role.permissions, 'id')
      this.role = _.clone(role)
      this.roleOrigin = _.clone(role)
    },
    save () {
      const roleID = this.role.id
      const permissions = this.role.permissions
      this.$store.dispatch('roleManagement/syncPermissions', {roleID, permissions})
        .then(response => {
          this.roleOrigin.permissions = this.role.permissions
          this.$vs.notify({
            color: 'success',
            text: response.data.message
          })
        })
        .catch(err => {
          this.$vs.notify({
            color: 'danger',
            text: err.response.data.message
          })
        })
    }
  },
  created () {
    // add main-actions
    this.$parent.mainActions = MainBackAction

    // Register Module RoleManagement Module
    if (!moduleRoleManagement.isRegistered) {
      this.$store.registerModule('roleManagement', moduleRoleManagement)
      moduleRoleManagement.isRegistered = true
    }

    this.$store.dispatch('roleManagement/fetchPermissions')
      .then(response =>  this.setPermissions(response.data))
      .catch(err => console.error(err))

    const roleID = parseInt(this.$route.params.roleID)
    this.$store.dispatch('roleManagement/fetchRoles')
      .then(response =>  {
        const role = _.find(response.data, {'id': roleID})
        if (_.isEmpty(role)) throw  'role_not_found'
        this.setRole(_.clone(role))
      })
      .catch(err => {
        if (err === 'role_not_found' || err.response.status === 404) {
          this.role_not_found = true
        }
        console.error(err)
      })
  }
}
</script>

<style lang="scss">
</style>
