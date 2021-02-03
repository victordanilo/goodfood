<template>
  <div class="flex">
    <div class="search-full-container w-full h-full absolute left-0 top-0" :class="{'flex': showFullSearch}" v-show="showFullSearch">
      <vx-auto-suggest
        ref="navbarSearch"
        :autoFocus="showFullSearch"
        :data="navbarSearchAndPinList"
        search_key="title"
        background-overlay
        class="w-full"
        inputClassses="w-full vs-input-no-border vs-input-no-shdow-focus"
        icon="SearchIcon"
        placeholder="Pesquisar..."
        @input="search_query_update"
        @selected="selected"
        @closeSearchbar="showFullSearch = false">

        <template v-slot:group="{ group_name }">
          <p class="font-semibold text-primary">{{ group_name | t | title }}</p>
        </template>

        <!-- Pages Suggestion -->
        <template v-slot:pages="{ suggestion }">
          <div class="flex items-end leading-none py-1">
            <feather-icon :icon="suggestion.icon" svgClasses="h-5 w-5" class="mr-4" />
            <span class="mt-1">{{ suggestion.title }}</span>
          </div>
        </template>

        <!-- Contacts Suggestion -->
        <template v-slot:products="{ suggestion }">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <img :src="load_product_img(suggestion.image)" :alt="suggestion.description" class="w-8 h-8 mr-3 rounded-full">
              <div class="leading-none mt-1">
                <p>{{ suggestion.description }}</p>
                <small>{{ suggestion.category.name }}</small>
              </div>
            </div>
          </div>
        </template>
      </vx-auto-suggest>

      <div class="absolute right-0 h-full z-50">
        <feather-icon
          icon="XIcon"
          class="px-4 cursor-pointer h-full close-search-icon"
          @click="showFullSearch = false" />
      </div>
    </div>
    <feather-icon icon="SearchIcon" @click="showFullSearch = true" class="cursor-pointer navbar-fuzzy-search ml-4" />
  </div>
</template>

<script>
import _ from 'lodash'
import helpers from '@/helpers'
import VxAutoSuggest from '@/components/vx-auto-suggest/VxAutoSuggest.vue'
import moduleProductManagement from '@/store/manager/product-management/moduleProductManagement'

export default {
  components: {
    VxAutoSuggest
  },
  data () {
    return {
      currentScope: 'public',
      searchQuery: '',
      showFullSearch: false,
      pinList: {
        manager: [
          {title: this.$t('dashboard'),  icon: 'HomeIcon',         url:'/manager'},
          {title: this.$t('product'),    icon: 'ShoppingBagIcon',  url:'/manager/product'},
          {title: this.$t('order'),      icon: 'FileTextIcon',     url:'/manager/order'},
          {title: this.$t('profile'),    icon: 'UserIcon',         url:'/manager/profile'}
        ],
        admin: [
          {title: this.$t('dashboard'),  icon: 'HomeIcon',         url:'/admin'},
          {title: this.$t('order'),      icon: 'FileTextIcon',     url:'/admin/order'},
          {title: this.$t('categories'), icon: 'TagIcon',          url:'/admin/product/category'},
          {title: this.$t('customer'),   icon: 'SmileIcon',        url:'/admin/customer'},
          {title: this.$t('company'),    icon: 'UsersIcon',        url:'/admin/company'},
          {title: this.$t('user'),       icon: 'UsersIcon',        url:'/admin/company'},
          {title: this.$t('role'),       icon: 'ShieldIcon',       url:'/admin/role'},
          {title: this.$t('settings'),   icon: 'SettingsIcon',     url:'/admin/settings'},
          {title: this.$t('profile'),    icon: 'UserIcon',         url:'/admin/profile'}
        ]
      }
    }
  },
  computed: {
    products () {
      if (_.isEmpty(this.$store.state.productManagement.products)) {
        return []
      }

      return  _.clone(this.$store.state.productManagement.products)
    },
    navbarSearchAndPinList () {
      let results = {}

      if (!_.isEmpty(this.searchQuery)) {
        results = _.assign(results, this.load_pin_list())
      }
      if (!_.isEmpty(this.searchQuery) && this.currentScope !== 'admin') {
        results = _.assign(results, {
          products: {
            key: 'description',
            data: this.products
          }
        })
      }
      if (_.isEmpty(results)) {
        results = {
          results: {
            key: 'title',
            data: []
          }
        }
      }

      return results
    }
  },
  methods: {
    selected (item) {
      if (item.pages) this.$router.push(item.pages.url).catch(() => {})
      if (item.products) this.$router.push({name:'manager-product-view', params:{productUUID: item.products.uuid}}).catch(() => {})
      this.showFullSearch = false
    },
    search_query_update (query) {
      // update query search
      this.searchQuery = query

      // Show overlay if any character is entered
      this.$store.commit('TOGGLE_CONTENT_OVERLAY', !!query)
    },
    get_session_scope () {
      let scope = 'public'

      if (this.$acl.check('admin')) scope = 'admin'
      else if (this.$acl.check('company')) scope = 'manager'

      return scope
    },
    load_pin_list () {
      let pinList = {}

      if (this.currentScope !== 'public') {
        pinList = {
          pages: {
            key: 'title',
            data: this.pinList[this.currentScope]
          }
        }
      }

      return pinList
    },
    load_product_img (path_img) {
      let img = require('@assets/images/default_food.jpg')

      if (!_.isEmpty(path_img)) {
        img = helpers.assets(`storage/product/${path_img}`)
      }

      return img
    }
  },
  created () {
    this.currentScope = this.get_session_scope()

    // Register Module CompanyManagement
    if (!moduleProductManagement.isRegistered) {
      this.$store.registerModule('productManagement', moduleProductManagement)
      moduleProductManagement.isRegistered = true
      this.$store.dispatch('productManagement/fetchProducts').catch(err => console.error(err))
    }
  }
}
</script>
