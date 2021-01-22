<template>
  <vx-card class="list-view-item overflow-hidden" v-on="$listeners">
    <template slot="no-body">
      <div class="vx-row item-details no-gutter">

        <!-- IMG COL -->
        <div
          class="vx-col sm:w-1/4 item-img-container bg-white flex items-center justify-start cursor-pointer">
          <img :src="productImg" :alt="item.description" class="grid-view-img p-2" height="150">
        </div>

        <!-- ITEM NAME & DESC COL -->
        <div class="vx-col sm:w-1/2 flex items-center">
          <div class="p-4 pt-6">
            <slot name="item-meta">
              <h6 class="item-name font-semibold mb-1 hover:text-primary cursor-pointer">{{ item.description }}</h6>
              <!--<p class="text-sm mb-4">By <span class="font-semibold cursor-pointer">{{ item.brand }}</span></p>-->
              <p class="item-description text-sm">{{ item.description }}</p>
            </slot>
          </div>
        </div>

        <!-- PURCHASE COL -->
        <div class="vx-col sm:w-1/4 flex items-center sm:border border-0 border-solid d-theme-border-grey-light border-r-0 border-t-0 border-b-0">
          <div class="p-4 flex flex-col w-full">
            <!--
            <div class="text-warning flex self-end border border-solid border-warning py-1 px-2 rounded">
                <span class="text-sm mr-1">{{ item.rating }}</span>
                <feather-icon icon="StarIcon" svgClasses="h-4 w-4" />
            </div>
            -->

            <div class="my-6">
              <h5 class="font-bold text-center">R$ {{ item.price | BRL }}</h5>
              <!--
              <span class="text-grey flex items-start justify-center mt-1" v-if="item.free_shipping">
                  <feather-icon icon="ShoppingCartIcon" svgClasses="w-4 h-4"/>
                  <span class="text-sm ml-2">Free Shipping</span>
              </span>
              -->
            </div>

            <!-- SLOT: ACTION BUTTONS -->
            <slot name="action-buttons"/>
          </div>
        </div>
      </div>
    </template>
  </vx-card>
</template>

<script>
import helpers from '@/helpers'

export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  computed: {
    productImg () {
      let img = require('@assets/images/default_food.jpg')

      if (this.item.image) {
        img = helpers.assets(`storage/product/${this.item.image}`)
      }

      return img
    },
    isInCart () {
      return (itemId) => this.$store.getters['shop/isInCart'](itemId)
    }
  },
  methods: {
    addItemInCart (item) {
      this.$store.dispatch('shop/addItemInCart', item)
    },
    cartButtonClicked (item) {
      this.isInCart(item.objectID) ? this.$router.push('/checkout').catch(() => {}) : this.addItemInCart(item)
    },
    navigate_to_detail_view () {
      //this.$router.push({name: 'ecommerce-item-detail-view', params: {item_id: this.item.objectID }}).catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.list-view-item {
  .item-name, .item-description {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .item-name {
    -webkit-line-clamp: 2;
  }

  .item-description {
    -webkit-line-clamp: 5;
  }

  .grid-view-img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    transition: .35s;
  }

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0px 4px 25px 0px rgba(0, 0, 0, .25);

    .grid-view-img {
      opacity: 0.9;
    }
  }
}
</style>
