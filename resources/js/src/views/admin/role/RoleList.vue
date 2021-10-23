<template>
  <div class="vx-row">
    <div class="vx-col w-full md:w-2/3 h-64 mx-auto">
      <vs-table ref="table" v-model="selected" pagination :max-items="itemsPerPage" :data="roles">
        <div slot="header" class="flex flex-wrap items-center flex-grow justify-between">

          <!-- ADD NEW -->
          <add-role />

          <!-- SEARCH BAR -->
          <vs-input icon-pack="feather" icon="icon-search" class="table-search mb-6 vs-input-shadow-drop vs-input-no-border d-theme-input-dark-bg" size="large" :placeholder="$t('search')" v-model="searchx" />

          <!-- ITEMS PER PAGE -->
          <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 items-per-page-handler">
            <div class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
              <span class="mr-2">{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} - {{ roles.length - currentPage * itemsPerPage > 0 ? currentPage * itemsPerPage : roles.length }} {{ $t('of') }} {{ queriedItems }}</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>
            <vs-dropdown-menu>
              <vs-dropdown-item @click="itemsPerPage=4">
                <span>4</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="itemsPerPage=10">
                <span>10</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="itemsPerPage=15">
                <span>15</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="itemsPerPage=20">
                <span>20</span>
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </div>

        <template slot="thead">
          <vs-th sort-key="name">{{ $t('name') }}</vs-th>
          <vs-th style="width: 100px">{{ $t('actions') }}</vs-th>
        </template>

        <template slot-scope="{data}">
          <tbody>
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td>
              <p class="font-medium truncate w-full" @click="openRole(tr.id)">{{ tr.name }}</p>
            </vs-td>

            <vs-td class="whitespace-no-wrap">
              <feather-icon icon="ToggleLeftIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="openRole(tr.id)" />
              <feather-icon icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="editRole(tr)" v-if="disableAction(tr)" />
              <feather-icon icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2" @click.stop="confirmDeleteRecord(tr)" v-if="disableAction(tr)" />
            </vs-td>
          </vs-tr>
          </tbody>
        </template>
      </vs-table>
    </div>
    <edit-role v-if="promptEdit.show" :show="promptEdit.show" :data="promptEdit.data" @hidePromptEdit="promptEdit.show = false"/>
  </div>
</template>

<script>
import _ from 'lodash'
import AddRole from './AddRole'
import EditRole from './EditRole.vue'
import moduleRoleManagement from '@/store/admin/role-management/moduleRoleManagement'

export default {
  components: {
    AddRole,
    EditRole
  },
  data () {
    return {
      search: '',
      itemsPerPage: 6,
      allCheck: false,
      selected: [],
      promptEdit: {
        show: false,
        data: {}
      },
      isMounted: false
    }
  },
  computed: {
    roles () {
      return this.$store.state.roleManagement.roles
    },
    currentPage () {
      if (this.isMounted) {
        return this.$refs.table.currentx
      }

      return 0
    },
    queriedItems () {
      return this.$refs.table ? this.$refs.table.queriedResults.length : this.roles.length
    },
    searchx: {
      get () {
        return this.search
      },
      set (search) {
        this.$refs.table.searchx = this.search = search
      }
    }
  },
  methods: {
    openRole (roleID) {
      console.log(roleID)
      this.$router.push({name: 'admin-role-view', params: {roleID}}).catch(err => { console.log(err) })
    },
    editRole (roleData) {
      this.promptEdit.data = _.clone(roleData)
      this.promptEdit.show = true
    },
    disableAction (roleData) {
      return roleData.name !== 'admin'
    },
    confirmDeleteRecord (roleData) {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: this.$i18n.t('delete_confirm.title'),
        text: this.$i18n.t('delete_confirm.text', {username: roleData.name}),
        accept: () => {
          return this.deleteRecord(roleData.id)
        },
        acceptText: this.$i18n.t('delete')
      })
    },
    deleteRecord (roleID) {
      this.$store.dispatch('roleManagement/removeRole', roleID)
        .then((response) => {
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
    // Register Module RoleManagement Module
    if (!moduleRoleManagement.isRegistered) {
      this.$store.registerModule('roleManagement', moduleRoleManagement)
      moduleRoleManagement.isRegistered = true
    }

    this.$store.dispatch('roleManagement/fetchRoles').catch(err => console.error(err))
  },
  mounted () {
    this.isMounted = true
  }
}
</script>

<style lang="scss">
.vs-con-table {
  .vs-table--thead{
    tr{
      background: none;
      box-shadow: none;
    }
    tr th.td-check {
      width: 70px !important;
    }
    tr th {
      padding-top: 0;
      padding-bottom: 0;

      .vs-table-text{
        text-transform: uppercase;
        font-weight: 600;
      }
    }
  }
  .vs-table {
    border-collapse: separate;
    border-spacing: 0 .6rem;
    padding: 0 1rem;

    tbody tr{
      box-shadow: 0 4px 20px 0 rgba(0,0,0,.05);
      td{
        padding: 20px;
        &:first-child{
          border-top-left-radius: .5rem;
          border-bottom-left-radius: .5rem;
        }
        &:last-child{
          border-top-right-radius: .5rem;
          border-bottom-right-radius: .5rem;
        }
      }
    }
  }

  @media (max-width: 767px) {
    .btn-add-new {
      width: 100%;
    }
    .table-search {
      width: 100%;
    }
    .items-per-page-handler {
      display: table;
      margin-left: auto;
    }
  }

  @media (min-width: 768px) {
    .table-search {
      width: calc(100% - 110px - 106px - 120px);
    }
  }
}
</style>
