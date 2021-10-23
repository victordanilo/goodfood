<template>
  <ais-instant-search
      :search-client="searchClient"
      index-name="products" id="algolia-instant-search-demo" class="mb-8">

      <!-- AIS CONFIG -->
      <ais-configure :hits-per-page.camel="9" />

      <!-- Contents -->
      <div id="algolia-content-container" class="relative clearfix">
        <!-- Filters -->
        <vs-sidebar
          class="items-no-padding vs-sidebar-rounded background-absolute"
          parent="#algolia-content-container"
          :click-not-close="clickNotClose"
          :hidden-background="clickNotClose"
          v-model="isFilterSidebarActive">

          <div class="p-6 filter-container">
            <!-- CATEGORIES -->
            <h6 class="font-bold mb-4">{{ $t('category') }}</h6>
            <ais-menu-select attribute="category.name">
              <div slot-scope="{ items, canRefine, refine }">
                <vs-select v-model="category" @input="filterCategory(refine)" :disabled="!canRefine">
                  <vs-select-item text="Todas" />
                  <vs-select-item v-for="item in items" :key="item.value" :value="item.value" :selected="item.isRefined" :text="item.label"  />
                </vs-select>
              </div>
            </ais-menu-select>

            <vs-divider />

            <ais-clear-refinements class="flex justify-center">
              <vs-button class="w-full" slot-scope="{ canRefine, refine }" @click.prevent="resetCategory(refine)" :disabled="!canRefine">{{ $t('reset_filters') }}</vs-button>
            </ais-clear-refinements>
          </div>
        </vs-sidebar>
        <!-- Filters / -->

        <!-- CONTENT -->
        <div :class="{'sidebar-spacer-with-margin': clickNotClose}">

          <!-- SEARCH BAR -->
          <ais-search-box>
            <div slot-scope="{ currentRefinement, isSearchStalled, refine }">
              <div class="relative mb-8">

                <!-- SEARCH INPUT -->
                <vs-input class="w-full vs-input-shadow-drop vs-input-no-border d-theme-input-dark-bg" :placeholder="$t('search')" v-model="currentRefinement" @input="refine($event)" @keyup.esc="refine('')" size="large" />

                <!-- SEARCH LOADING -->
                <p :hidden="!isSearchStalled" class="mt-4 text-grey">
                  <feather-icon icon="ClockIcon" svgClasses="w-4 h-4" class="mr-2 align-middle" />
                  <span>{{ $t('loading') }}...</span>
                </p>

                <!-- SEARCH ICON -->
                <div slot="submit-icon" class="absolute top-0 right-0 py-4 px-6" v-show="!currentRefinement">
                  <feather-icon icon="SearchIcon" svgClasses="h-6 w-6" />
                </div>

                <!-- CLEAR INPUT ICON -->
                <div slot="reset-icon" class="absolute top-0 right-0 py-4 px-6" v-show="currentRefinement">
                  <feather-icon icon="XIcon" svgClasses="h-6 w-6 cursor-pointer" @click="refine('')" />
                </div>
              </div>
            </div>
          </ais-search-box>
          <!-- SEARCH BAR / -->

          <!-- SEARCH RESULT -->
          <ais-hits>
            <div slot-scope="{ items }">

              <!-- GRID VIEW -->
              <template v-if="currentItemView == 'item-grid-view'">
                <div class="items-grid-view vx-row match-height">
                  <div class="vx-col lg:w-1/3 sm:w-1/2 w-full" v-for="item in items" :key="item.objectID">

                    <item-grid-view :item="item">

                      <!-- SLOT: ACTION BUTTONS -->
                      <template slot="action-buttons">
                        <div class="flex flex-wrap">

                          <!-- PRIMARY BUTTON: ADD TO WISH LIST -->
                          <div
                            class="item-view-secondary-action-btn bg-primary p-3 flex flex-grow items-center justify-center text-white cursor-pointer"
                            @click="cartButtonClicked(item)">
                            <feather-icon icon="ShoppingBagIcon" svgClasses="h-4 w-4" />

                            <span class="text-sm font-semibold ml-2" v-if="isInCart(item.objectID)">{{ $t('view_in_cart') }}</span>
                            <span class="text-sm font-semibold ml-2" v-else>{{ $t('add_to_cart') }}</span>
                          </div>
                        </div>
                      </template>
                    </item-grid-view>

                  </div>
                </div>
              </template>

              <!-- LIST VIEW -->
              <template v-else>
                <div class="items-list-view mb-base" v-for="item in items" :key="item.objectID">

                  <item-list-view :item="item">

                    <!-- SLOT: ACTION BUTTONS -->
                    <template slot="action-buttons">
                      <div
                        class="item-view-secondary-action-btn bg-primary p-3 rounded-lg flex flex-grow items-center justify-center text-white cursor-pointer"
                        @click="cartButtonClicked(item)">
                        <feather-icon icon="ShoppingBagIcon" svgClasses="h-4 w-4" />

                        <span class="text-sm font-semibold ml-2" v-if="isInCart(item.objectID)">{{ $t('view_in_cart') }}</span>
                        <span class="text-sm font-semibold ml-2" v-else>{{ $t('add_to_cart') }}</span>
                      </div>
                    </template>
                  </item-list-view>

                </div>
              </template>
            </div>
          </ais-hits>
          <!-- SEARCH RESULT / -->

          <!-- PAGINATION -->
          <ais-pagination>
            <div slot-scope="{
                                currentRefinement,
                                nbPages,
                                pages,
                                isFirstPage,
                                isLastPage,
                                refine,
                                createURL
                            }"
            >

              <vs-pagination

                :total="nbPages"
                :max="7"
                :value="currentRefinement + 1"
                @input="(val) => { refine(val - 1) }" />
            </div>
          </ais-pagination>
          <!-- PAGINATION / -->
        </div>
        <!-- CONTENT / -->
      </div>
    </ais-instant-search>
</template>

<script>
import {
  AisClearRefinements,
  AisConfigure,
  AisHierarchicalMenu,
  AisMenuSelect,
  AisHits,
  AisInstantSearch,
  AisNumericMenu,
  AisPagination,
  AisRangeInput,
  AisRatingMenu,
  AisRefinementList,
  AisSearchBox,
  AisSortBy,
  AisStats
} from 'vue-instantsearch'
import algoliasearch from 'algoliasearch/lite'

export default {
  components: {
    ItemGridView: () => import('./components/ItemGridView.vue'),
    ItemListView: () => import('./components/ItemListView.vue'),
    AisClearRefinements,
    AisConfigure,
    AisHierarchicalMenu,
    AisMenuSelect,
    AisHits,
    AisInstantSearch,
    AisNumericMenu,
    AisPagination,
    AisRangeInput,
    AisRatingMenu,
    AisRefinementList,
    AisSearchBox,
    AisSortBy,
    AisStats
  },
  data () {
    return {
      searchClient: algoliasearch(
        window.algolia_id,
        window.algolia_key
      ),
      // Filter Sidebar
      isFilterSidebarActive: true,
      clickNotClose: true,
      currentItemView: 'item-grid-view',
      category: null
    }
  },
  computed: {
    toValue () {
      return (value, range) => [
        value.min !== null ? value.min : range.min,
        value.max !== null ? value.max : range.max
      ]
    },

    // GRID VIEW
    isInCart () {
      return (itemId) => this.$store.getters['shop/isInCart'](itemId)
    },
    windowWidth () { return this.$store.state.windowWidth }
  },
  watch: {
    windowWidth () {
      this.setSidebarWidth()
    }
  },
  methods: {
    setSidebarWidth () {
      if (this.windowWidth < 992) {
        this.isFilterSidebarActive = this.clickNotClose = false
      } else {
        this.isFilterSidebarActive = this.clickNotClose = true
      }
    },
    filterCategory (refine) {
      refine(this.category)
    },
    resetCategory (refine) {
      this.category = null
      refine(null)
    },

    // GRID VIEW - ACTIONS
    addItemInCart (item) {
      this.$store.dispatch('shop/addItemInCart', item)
    },
    cartButtonClicked (item) {
      this.isInCart(item.objectID) ? this.$router.push('/checkout').catch(() => {}) : this.addItemInCart(item)
    }
  },
  created () {
    this.setSidebarWidth()
  }
}
</script>


<style lang="scss">
  #algolia-instant-search-demo {
    .algolia-header {
      .algolia-filters-label {
        width: calc(260px + 2.4rem);
      }
    }

    #algolia-content-container {

      .vs-sidebar {
        position: relative;
        float: left;
      }
    }

    .algolia-search-input-right-aligned-icon {
      padding: 1rem 1.5rem;
    }

    .algolia-price-slider {
      min-width: unset;
    }

    .item-view-primary-action-btn {
      color: #2c2c2c !important;
      background-color: #f6f6f6;
      min-width: 50%;
    }

    .item-view-secondary-action-btn {
      min-width: 50%;
    }
  }

  .theme-dark {
    #algolia-instant-search-demo {
      #algolia-content-container {
        .vs-sidebar {
          background-color: #10163a;
        }
      }
    }
  }

  @media (min-width: 992px) {
    .vs-sidebar-rounded {
      .vs-sidebar {
        border-radius: .5rem;
      }

      .vs-sidebar--items {
        border-radius: .5rem;
      }
    }
  }

  @media (max-width: 992px) {
    #algolia-content-container {
      .vs-sidebar {
        position: absolute !important;
        float: none !important;
      }
    }
  }

</style>
