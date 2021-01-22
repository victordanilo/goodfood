<!-- =========================================================================================
  File Name: OrderList.vue
  Description: Order List page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div id="order-list" class="container">
    <div class="vx-card p-6">
      <div class="flex flex-wrap items-center">
        <!-- ITEMS PER PAGE -->
        <div class="flex-grow">
          <vs-dropdown vs-trigger-click class="cursor-pointer">
            <div class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
              <span class="mr-2">{{ currentPage * paginationPageSize - (paginationPageSize - 1) }} - {{ ordersData.length - currentPage * paginationPageSize > 0 ? currentPage * paginationPageSize : ordersData.length }} {{$t('of')}} {{ ordersData.length }}</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>
            <vs-dropdown-menu>
              <vs-dropdown-item @click="gridApi.paginationSetPageSize(10)">
                <span>10</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="gridApi.paginationSetPageSize(20)">
                <span>20</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="gridApi.paginationSetPageSize(25)">
                <span>25</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click="gridApi.paginationSetPageSize(30)">
                <span>30</span>
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </div>
      </div>

      <!-- AgGrid Table -->
      <ag-grid-vue
        ref="agGridTable"
        :components="components"
        :gridOptions="gridOptions"
        class="ag-theme-material w-100 my-4 ag-grid-table"
        :columnDefs="columnDefs"
        :defaultColDef="defaultColDef"
        :rowData="ordersData"
        rowSelection="multiple"
        colResizeDefault="shift"
        :animateRows="true"
        :floatingFilter="false"
        :pagination="true"
        :paginationPageSize="paginationPageSize"
        :suppressPaginationPanel="true"
        :enableRtl="$vs.rtl">
      </ag-grid-vue>

      <vs-pagination
        :total="totalPages"
        :max="7"
        v-model="currentPage" />
    </div>
  </div>
</template>

<script>
import vSelect from 'vue-select'
import { AgGridVue } from 'ag-grid-vue'
import '@sass/vuexy/extraComponents/agGridStyleOverride.scss'

// Cell Renderer
import CellRendererActions from './cell-renderer/CellRendererActions.vue'
import CellRendererStatus from './cell-renderer/CellRendererStatus.vue'

// Store Module
import moduleOrderManagement from '@/store/customer/order-management/moduleOrderManagement'

export default {
  components: {
    AgGridVue,
    vSelect,

    // Cell Renderer
    CellRendererActions,
    CellRendererStatus
  },
  data () {
    return {
      searchQuery: '',

      // AgGrid
      gridApi: null,
      gridOptions: {},
      defaultColDef: {
        sortable: true,
        resizable: true,
        suppressMenu: true,
        lockPosition: true
      },
      columnDefs: [
        {
          headerName: null,
          field: 'uuid',
          width: 60,
          filter: true,
          resizable: false,
          checkboxSelection: true,
          headerCheckboxSelectionFilteredOnly: true,
          headerCheckboxSelection: true
        },
        {
          headerName: this.$i18n.t('customer'),
          field: 'customer.name',
          filter: true,
          width: 300
        },
        {
          headerName: this.$i18n.t('company'),
          field: 'company.name',
          filter: true,
          width: 300
        },
        {
          headerName: this.$i18n.t('amount'),
          field: 'amount',
          filter: true,
          width: 200,
          valueFormatter: params => this.$options.filters.BRL(params.value)
        },
        {
          headerName: this.$i18n.t('status'),
          field: 'status.name',
          filter: true,
          width: 140,
          cellRendererFramework: 'CellRendererStatus'
        },
        {
          headerName: this.$i18n.t('actions'),
          field: 'transactions',
          width: 140,
          cellRendererFramework: 'CellRendererActions'
        }
      ],

      // Cell Renderer Components
      components: {
        CellRendererActions
      }
    }
  },
  computed: {
    ordersData () {
      return this.$store.state.orderManagement.orders
    },
    paginationPageSize () {
      if (this.gridApi) return this.gridApi.paginationGetPageSize()
      else return 10
    },
    totalPages () {
      if (this.gridApi) return this.gridApi.paginationGetTotalPages()
      else return 0
    },
    currentPage: {
      get () {
        if (this.gridApi) return this.gridApi.paginationGetCurrentPage() + 1
        else return 1
      },
      set (val) {
        this.gridApi.paginationGoToPage(val - 1)
      }
    }
  },
  methods: {
    setColumnFilter (column, val) {
      const filter = this.gridApi.getFilterInstance(column)
      let modelObj = null

      if (val !== 'all') {
        modelObj = { type: 'equals', filter: val }
      }

      filter.setModel(modelObj)
      this.gridApi.onFilterChanged()
    },
    resetColFilters () {
      // Reset Grid Filter
      this.gridApi.setFilterModel(null)
      this.gridApi.onFilterChanged()

      this.$refs.filterCard.removeRefreshAnimation()
    },
    updateSearchQuery (val) {
      this.gridApi.setQuickFilter(val)
    }
  },
  mounted () {
    this.gridApi = this.gridOptions.api
    if (window.innerWidth >= 1300) setTimeout(() => this.gridApi.sizeColumnsToFit(), 450)

    /* =================================================================
      NOTE:
      Header is not aligned properly in RTL version of agGrid table.
      However, we given fix to this issue. If you want more robust solution please contact them at gitHub
    ================================================================= */
    if (this.$vs.rtl) {
      const header = this.$refs.agGridTable.$el.querySelector('.ag-header-container')
      header.style.left = `-${  String(Number(header.style.transform.slice(11, -3)) + 9)  }px`
    }
  },
  created () {
    // Register Module OrderManagement
    if (!moduleOrderManagement.isRegistered) {
      this.$store.registerModule('orderManagement', moduleOrderManagement)
      moduleOrderManagement.isRegistered = true
    }

    this.$store.dispatch('orderManagement/fetchOrders').catch(err => { console.error(err) })
  }
}
</script>

<style lang="scss">
#order-list {
  padding-top: 80px !important;
  padding-bottom: 50px !important;
}
</style>
