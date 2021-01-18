/*=========================================================================================
  File Name: sidebarItems.js
  Description: Sidebar Items list. Add / Remove menu items from here.
  Strucutre:
          url     => router path
          name    => name to display in sidebar
          slug    => router path name
          icon    => Feather Icon component/icon name
          tag     => text to display on badge
          tagColor  => class to apply on badge element
          i18n    => Internationalization
          submenu   => submenu of current item (current item will become dropdown )
                NOTE: Submenu don't have any icon(you can add icon if u want to display)
          isDisabled  => disable sidebar item/group
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default [
  // admin
  {
    url: '/admin',
    name: 'dashboard',
    icon: 'HomeIcon',
    i18n: 'dashboard'
  },
  {
    url: '/admin/order',
    name: 'order',
    icon: 'FileTextIcon',
    slug: 'admin-orders',
    i18n: 'order'
  },
  {
    url: '/admin/product/category',
    name: 'product-category',
    icon: 'TagIcon',
    slug: 'admin-product-categories',
    i18n: 'categories'
  },
  {
    url: '/admin/customer',
    name: 'customer',
    icon: 'SmileIcon',
    slug: 'admin-customers',
    i18n: 'customer'
  },
  {
    url: '/admin/company',
    name: 'company',
    icon: 'UsersIcon',
    slug: 'admin-companies',
    i18n: 'company'
  },
  {
    url: '/admin/user',
    name: 'user',
    icon: 'UserIcon',
    slug: 'admin-users',
    i18n: 'user'
  },
  {
    url: '/admin/role',
    name: 'role',
    icon: 'ShieldIcon',
    slug: 'admin-roles',
    i18n: 'role'
  },
  {
    url: '/admin/settings',
    name: 'settings',
    icon: 'SettingsIcon',
    slug: 'admin-settings',
    i18n: 'settings'
  }
]
