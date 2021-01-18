/*=========================================================================================
  File Name: router.js
  Description: Routes for vue-router. Lazy loading is enabled.
  Object Strucutre:
                    path => router path
                    name => router name
                    component(lazy loading) => component to load
                    meta : {
                      rule => which user can have access (ACL)
                      breadcrumb => Add breadcrumb to specific page
                      pageTitle => Display title besides breadcrumb
                    }
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  base: '/',
  scrollBehavior () {
    return { x: 0, y: 0 }
  },
  routes: [
    // =============================================================================
    // CUSTOMER
    // =============================================================================
    {
      path: '',
      component: () => import('@/views/pages/home/Main.vue'),
      children: [
        {
          path: '/',
          name: 'home',
          component: () => import('@/views/pages/home/Home.vue'),
          meta: {
            rule: 'public'
          }
        }
      ]
    },

    // =============================================================================
    // ADMIN
    // =============================================================================
    {
      path: '',
      component: () => import('@/layouts/full-page/FullPage.vue'),
      meta:{
        redirectLogin: 'admin-login'
      },
      children: [
        {
          path: '/admin/auth/login',
          name: 'admin-login',
          component: () => import('@/views/admin/auth/login/Login.vue'),
          meta: {
            rule: 'public'
          }
        },
        {
          path: '/admin/auth/forgot-password',
          name: 'admin-forgot-password',
          component: () => import('@/views/admin/auth/ForgotPassword.vue'),
          meta: {
            rule: 'public'
          }
        }
      ]
    },
    {
      path: '',
      component: () => import('@/layouts/main/Main.vue'),
      meta:{
        redirectLogin: 'admin-login'
      },
      children: [
        {
          path: '/admin',
          name: 'admin-dashboard',
          component: () => import('@/views/admin/Dashboard.vue'),
          meta: {
            rule: 'admin'
          }
        },

        // customer
        {
          path: '/admin/customer',
          name: 'admin-customers',
          component: () => import('@/views/admin/customer/customer-list/CustomerList.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', active: true }
            ],
            pageTitle: 'customers',
            rule: 'admin'
          }
        },
        {
          path: '/admin/customer/customer-create',
          name: 'admin-customer-create',
          component: () => import('@/views/admin/customer/CustomerCreate.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', url: '/admin/customer'},
              { title: 'create_company', active: true }
            ],
            parent: 'admin-customers',
            pageTitle: 'customers',
            rule: 'admin'
          }
        },
        {
          path: '/admin/customer/customer-view/:customerUUID',
          name: 'admin-customer-view',
          component: () => import('@/views/admin/customer/CustomerView.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', url: '/admin/customer'},
              { title: 'view', active: true }
            ],
            parent: 'admin-customers',
            pageTitle: 'customers',
            rule: 'admin'
          }
        },
        {
          path: '/admin/customer/customer-edit/:customerUUID',
          name: 'admin-customer-edit',
          component: () => import('@/views/admin/customer/customer-edit/CustomerEdit.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', url: '/admin/customer'},
              { title: 'edit', active: true }
            ],
            parent: 'admin-customers',
            pageTitle: 'customers',
            rule: 'admin'
          }
        },

        // companies
        {
          path: '/admin/company',
          name: 'admin-companies',
          component: () => import('@/views/admin/company/company-list/CompanyList.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', active: true }
            ],
            pageTitle: 'companies',
            rule: 'admin'
          }
        },
        {
          path: '/admin/companies/company-create',
          name: 'admin-company-create',
          component: () => import('@/views/admin/company/CompanyCreate.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', url: '/admin/company'},
              { title: 'create_company', active: true }
            ],
            parent: 'admin-companies',
            pageTitle: 'companies',
            rule: 'admin'
          }
        },
        {
          path: '/admin/company/company-view/:companyUUID',
          name: 'admin-company-view',
          component: () => import('@/views/admin/company/CompanyView.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', url: '/admin/company'},
              { title: 'view', active: true }
            ],
            parent: 'admin-companies',
            pageTitle: 'companies',
            rule: 'admin'
          }
        },
        {
          path: '/admin/company/company-edit/:companyUUID',
          name: 'admin-company-edit',
          component: () => import('@/views/admin/company/company-edit/CompanyEdit.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', url: '/admin/company'},
              { title: 'edit', active: true }
            ],
            parent: 'admin-companies',
            pageTitle: 'companies',
            rule: 'admin'
          }
        },

        // users
        {
          path: '/admin/user',
          name: 'admin-users',
          component: () => import('@/views/admin/user/user-list/UserList.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', active: true }
            ],
            pageTitle: 'users',
            rule: 'admin'
          }
        },
        {
          path: '/admin/user/user-create',
          name: 'admin-user-create',
          component: () => import('@/views/admin/user/UserCreate.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', url: '/admin/user'},
              { title: 'create_user', active: true }
            ],
            parent: 'admin-users',
            pageTitle: 'users',
            rule: 'admin'
          }
        },
        {
          path: '/admin/user/user-view/:userUUID',
          name: 'admin-user-view',
          component: () => import('@/views/admin/user/UserView.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', url: '/admin/user'},
              { title: 'view', active: true }
            ],
            parent: 'admin-users',
            pageTitle: 'users',
            rule: 'admin'
          }
        },
        {
          path: '/admin/user/user-edit/:userUUID',
          name: 'admin-user-edit',
          component: () => import('@/views/admin/user/user-edit/UserEdit.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', url: '/admin/user'},
              { title: 'edit', active: true }
            ],
            parent: 'admin-users',
            pageTitle: 'users',
            rule: 'admin'
          }
        },

        // orders
        {
          path: '/admin/order',
          name: 'admin-orders',
          component: () => import('@/views/admin/order/order-list/OrderList.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', active: true }
            ],
            pageTitle: 'orders',
            rule: 'admin'
          }
        },
        {
          path: '/admin/order/order-view/:orderUUID',
          name: 'admin-order-view',
          component: () => import('@/views/admin/order/OrderView.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', url: '/admin/order'},
              { title: 'view', active: true }
            ],
            parent: 'admin-orders',
            pageTitle: 'orders',
            rule: 'admin'
          }
        },

        // product categories
        {
          path: '/admin/product/category',
          name: 'admin-product-categories',
          component: () => import('@/views/admin/product-category/ProductCategoryList.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/' },
              { title: 'list', active: true }
            ],
            pageTitle: 'categories',
            rule: 'admin'
          }
        },

        // roles
        {
          path: '/admin/role',
          name: 'admin-roles',
          component: () => import('@/views/admin/role/RoleList.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/admin' },
              { title: 'list', active: true }
            ],
            pageTitle: 'roles',
            rule: 'admin'
          }
        },
        {
          path: '/admin/role/:roleID',
          name: 'admin-role-view',
          component: () => import('@/views/admin/role/RoleView.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/admin' },
              { title: 'view', active: true }
            ],
            pageTitle: 'roles',
            rule: 'admin'
          }
        },

        // settings
        {
          path: '/admin/settings',
          name: 'admin-settings',
          component: () => import('@/views/admin/setting/Settings.vue'),
          meta: {
            breadcrumb: [
              { title: 'admin', url: '/admin' },
              { title: 'settings', active: true }
            ],
            pageTitle: 'setting',
            rule: 'admin'
          }
        },

        // profile
        {
          path: '/admin/profile',
          name: 'admin-profile',
          component: () => import('@/views/admin/profile/ProfileEdit.vue'),
          meta: {
            rule: 'admin'
          }
        }
      ]
    },

    // =============================================================================
    // ERRORS
    // =============================================================================
    {
      path: '',
      component: () => import('@/layouts/full-page/FullPage.vue'),
      children: [
        {
          path: '/404',
          name: 'error-404',
          component: () => import('@/views/pages/Error404.vue'),
          meta: {
            rule: 'public'
          }
        },
        {
          path: '/500',
          name: 'error-500',
          component: () => import('@/views/pages/Error500.vue'),
          meta: {
            rule: 'public'
          }
        },
        {
          path: '/not-authorized',
          name: 'page-not-authorized',
          component: () => import('@/views/pages/NotAuthorized.vue'),
          meta: {
            rule: 'public'
          }
        }
      ]
    },
    // Redirect to 404 page, if no match found
    {
      path: '*',
      redirect: '404'
    }
  ]
})

export default router
