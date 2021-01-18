<!-- =========================================================================================
  File Name: DashboardAnalytics.vue
  Description: Dashboard Analytics
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div id="dashboard-analytics">
    <div class="vx-row">
      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
        <statistics-card-line
          icon="SmileIcon"
          v-if="customers.show"
          :statistic="customers.subscribers | k_formatter"
          :statisticTitle="customers.title"
          :chartData="customers.series"
          color="warning"
          type="area" />
      </div>

      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
        <statistics-card-line
          icon="UsersIcon"
          v-if="companies.show"
          :statistic="companies.subscribers | k_formatter"
          :statisticTitle="companies.title"
          :chartData="companies.series"
          color="secondary"
          type="area" />
      </div>

      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
        <statistics-card-line
          icon="ShoppingBagIcon"
          v-if="products.show"
          :statistic="products.subscribers | k_formatter"
          :statisticTitle="products.title"
          :chartData="products.series"
          color="danger"
          type="area" />
      </div>

      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
        <statistics-card-line
          icon="ShoppingCartIcon"
          v-if="orders.show"
          :statistic="orders.subscribers | k_formatter"
          :statisticTitle="orders.title"
          :chartData="orders.series"
          color="success"
          type="area" />
      </div>
    </div>
    <div class="vx-row">
      <div class="vx-col w-full md:w-2/3 mb-base">
        <vx-card :title="revenuesSetting.title">
          <div slot="no-body" class="p-6 pb-0">
            <div class="flex" v-if="revenuesSetting.show">
              <div class="mr-6">
                <p class="mb-1 font-semibold">{{ $t('this_month') }}</p>
                <p class="text-3xl text-success"><sup class="text-base mr-1">R$</sup>{{ (revenues.analyticsData.thisMonth).toFixed(2) }}</p>
              </div>
              <div>
                <p class="mb-1 font-semibold">{{ $t('last_month') }}</p>
                <p class="text-3xl"><sup class="text-base mr-1">R$</sup>{{ (revenues.analyticsData.lastMonth).toFixed(2) }}</p>
              </div>
            </div>
            <vue-apex-charts
              type="line"
              height="266"
              :options="revenuesSetting.chartOptions"
              :series="revenues.series" />
          </div>
        </vx-card>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import moment from 'moment'
import VueApexCharts from 'vue-apexcharts'
import StatisticsCardLine from '@/components/statistics-cards/StatisticsCardLine.vue'
import moduleCustomerManagement from '@/store/admin/customer-management/moduleCustomerManagement'
import moduleCompanyManagement from '@/store/admin/company-management/moduleCompanyManagement'
import moduleProductManagement from '@/store/admin/product-management/moduleProductManagement'
import moduleOrderManagement from '@/store/admin/order-management/moduleOrderManagement'

export default {
  components: {
    VueApexCharts,
    StatisticsCardLine
  },
  data () {
    return {
      revenuesSetting: {
        show: true,
        title: this.$i18n.t('revenues'),
        chartOptions: {
          chart: {
            toolbar: {show: false},
            dropShadow: {
              enabled: true,
              top: 5,
              left: 0,
              blur: 4,
              opacity: 0.10
            }
          },
          stroke: {
            curve: 'smooth',
            dashArray: [0, 8],
            width: [4, 2]
          },
          grid: {
            borderColor: '#e7e7e7'
          },
          legend: {
            show: false
          },
          colors: ['#F97794', '#b8c2cc'],
          fill: {
            type: 'gradient',
            gradient: {
              shade: 'dark',
              inverseColors: false,
              gradientToColors: ['#7367F0', '#b8c2cc'],
              shadeIntensity: 1,
              type: 'horizontal',
              opacityFrom: 1,
              opacityTo: 1,
              stops: [0, 100, 100, 100]
            }
          },
          markers: {
            size: 0,
            hover: {
              size: 5
            }
          },
          xaxis: {
            labels: {
              style: {
                cssClass: 'text-grey fill-current'
              }
            },
            axisTicks: {
              show: false
            },
            categories: ['01', '05', '09', '13', '17', '21', '26', '31'],
            axisBorder: {
              show: false
            }
          },
          yaxis: {
            tickAmount: 5,
            labels: {
              style: {
                cssClass: 'text-grey fill-current'
              },
              formatter (val) {
                return val > 999 ? `${(val / 1000).toFixed(1)}k` : val
              }
            }
          },
          tooltip: {
            x: { show: false }
          }
        }
      }
    }
  },
  computed: {
    customers () {
      let subscribers = 0
      let series_data = [0, 0]

      if (moduleCustomerManagement.isRegistered) {
        subscribers = this.$store.state.customerManagement.customers.length
        series_data = this.toSeries(this.$store.state.customerManagement.customers)
      }

      return  {
        show: true,
        title: `${this.$i18n.t('customers')} ${this.$i18n.t('subscribers')}`,
        subscribers,
        series: [
          {
            name: this.$i18n.t('customers'),
            data: series_data
          }
        ]
      }
    },
    companies () {
      let subscribers = 0
      let series_data = [0, 0]

      if (moduleCompanyManagement.isRegistered) {
        subscribers = this.$store.state.companyManagement.companies.length
        series_data = this.toSeries(this.$store.state.companyManagement.companies)
      }

      return {
        show: true,
        title: `${this.$i18n.t('companies')} ${this.$i18n.t('subscribers')}`,
        subscribers,
        series: [
          {
            name: this.$i18n.t('companies'),
            data: series_data
          }
        ]
      }
    },
    products () {
      let subscribers = 0
      let series_data = [0, 0]

      if (moduleProductManagement.isRegistered) {
        subscribers = this.$store.state.productManagement.products.length
        series_data = this.toSeries(this.$store.state.productManagement.products)
      }

      return {
        show: true,
        title: `${this.$i18n.t('products')} ${this.$i18n.t('available')}`,
        subscribers,
        series: [
          {
            name: this.$i18n.t('products'),
            data: series_data
          }
        ]
      }
    },
    orders () {
      let subscribers = 0
      let series_data = [0, 0]

      if (moduleOrderManagement.isRegistered) {
        subscribers = this.$store.state.orderManagement.orders.length
        series_data = this.toSeries(this.$store.state.orderManagement.orders)
      }

      return {
        show: true,
        title: `${this.$i18n.t('orders')} ${this.$i18n.t('received')}`,
        subscribers,
        series: [
          {
            name: this.$i18n.t('orders'),
            data: series_data
          }
        ]
      }
    },
    revenues () {
      let analyticsData = {
        thisMonth: 0,
        lastMonth: 0
      }
      let series = {
        thisMonth: [0, 0],
        lastMonth: [0, 0]
      }

      if (moduleOrderManagement.isRegistered) {
        analyticsData = this.revenueAnalyticsData(this.$store.state.orderManagement.orders)
        series = this.revenueSeries(this.$store.state.orderManagement.orders)
      }

      return {
        analyticsData,
        series: [
          {
            name: this.$i18n.t('this_month'),
            data: series.thisMonth
          },
          {
            name: this.$i18n.t('last_month'),
            data: series.lastMonth
          }
        ]
      }
    }
  },
  methods: {
    toSeries (datas) {
      if (_.isEmpty(datas)) return [0, 0]

      let series = _.groupBy(datas, (data) => {
        return moment(data.created_at).startOf('day').format()
      })
      series = _.map(series, (data) => {
        return data.length
      })
      if (series.length === 1) series.unshift(0)

      return series
    },
    revenueAnalyticsData (datas) {
      const analyticsData = {
        thisMonth: 0,
        lastMonth: 0
      }

      if (_.isEmpty(datas)) return analyticsData

      analyticsData.lastMonth = _.reduce(datas, (amount, current) => {
        return amount + parseFloat(current.amount)
      }, 0)

      const datasThisMonth = _.filter(datas, (data) => {
        return moment(data.created_at).isSame(new Date(), 'month')
      })
      analyticsData.thisMonth = _.reduce(datasThisMonth, (amount, current) => {
        return amount + parseFloat(current.amount)
      }, 0)

      return analyticsData
    },
    revenueSeries (datas) {
      const series = {
        thisMonth: [0, 0],
        lastMonth: [0, 0]
      }

      if (_.isEmpty(datas)) return series

      series.lastMonth = this.toSeries(datas)

      const datasThisMonth = _.filter(datas, (data) => {
        return moment(data.created_at).isSame(new Date(), 'month')
      })
      series.thisMonth = this.toSeries(datasThisMonth)

      return  series
    }
  },
  created () {
    // Register Module CustomerManagement
    if (!moduleCustomerManagement.isRegistered) {
      this.$store.registerModule('customerManagement', moduleCustomerManagement)
      moduleCustomerManagement.isRegistered = true
    }
    // Register Module CompanyManagement
    if (!moduleCompanyManagement.isRegistered) {
      this.$store.registerModule('companyManagement', moduleCompanyManagement)
      moduleCompanyManagement.isRegistered = true
    }
    // Register Module ProductManagement Module
    if (!moduleProductManagement.isRegistered) {
      this.$store.registerModule('productManagement', moduleProductManagement)
      moduleProductManagement.isRegistered = true
    }
    // Register Module OrderManagement
    if (!moduleOrderManagement.isRegistered) {
      this.$store.registerModule('orderManagement', moduleOrderManagement)
      moduleOrderManagement.isRegistered = true
    }

    // load customers
    if (_.isEmpty(this.$store.state.customerManagement.customers)) {
      this.$store.dispatch('customerManagement/fetchCustomers').catch(err => { console.error(err) })
    }
    // load companies
    if (_.isEmpty(this.$store.state.companyManagement.companies)) {
      this.$store.dispatch('companyManagement/fetchCompanies').catch(err => { console.error(err) })
    }
    // load products
    if (_.isEmpty(this.$store.state.productManagement.products)) {
      this.$store.dispatch('productManagement/fetchProducts').catch(err => { console.error(err) })
    }
    // load orders
    if (_.isEmpty(this.$store.state.orderManagement.isRegistered)) {
      this.$store.dispatch('orderManagement/fetchOrders').catch(err => { console.error(err) })
    }
  }
}
</script>

<style lang="scss">
.chat-card-log {
  height: 400px;

  .chat-sent-msg {
    background-color: #f2f4f7 !important;
  }
}
</style>
