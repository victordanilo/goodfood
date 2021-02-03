<template>
  <div id="shop-checkout" class="container">
    <form-wizard
      ref="checkoutWizard"
      color="rgba(var(--vs-primary), 1)"
      :title="null"
      :subtitle="null"
      :hide-buttons="true">

      <!-- tab 1 content -->
      <tab-content :title="$t('delivery_address')" icon="feather icon-home" class="mb-5">
        <div class="vx-row">
          <!-- LEFT COL: NEW ADDRESS -->
          <div class="vx-col lg:w-2/3 w-full mx-auto">
            <vx-card :title="$t('address')" class="mb-base">
              <form data-vv-scope="form-address">
                <div class="vx-row">
                  <div class="vx-col w-full md:w-4/5">
                    <vs-input class="w-full mt-4" :label="$t('street')" v-model="formAddress.street" v-validate="'required|min:4|max:255'" name="street" />
                    <span class="text-danger text-sm"  v-show="errors.has('form-address.street')">{{ errors.first('form-address.street') }}</span>
                  </div>

                  <div class="vx-col w-full md:w-1/5">
                    <vs-input class="w-full mt-4" label="Nº" v-model="formAddress.n" v-validate="'required'" name="n" />
                    <span class="text-danger text-sm"  v-show="errors.has('form-address.n')">{{ errors.first('form-address.n') }}</span>
                  </div>

                  <div class="vx-col w-full">
                    <vs-input class="w-full mt-4" :label="$t('complement')" v-model="formAddress.complement" name="complement" />
                    <span class="text-danger text-sm"  v-show="errors.has('form-address.complement')">{{ errors.first('form-address.complement') }}</span>
                  </div>

                  <div class="vx-col w-full md:w-2/3">
                    <vs-input class="w-full mt-4" :label="$t('district')" v-model="formAddress.district" v-validate="'required|min:3|max:255'" name="district" />
                    <span class="text-danger text-sm"  v-show="errors.has('form-address.district')">{{ errors.first('form-address.district') }}</span>
                  </div>

                  <div class="vx-col w-full md:w-1/3">
                    <vs-input class="w-full mt-4" label="CEP" v-model="formAddress.zip_code" v-mask="'#####-###'" v-validate="'required'" name="zip_code" />
                    <span class="text-danger text-sm"  v-show="errors.has('form-address.zip_code')">{{ errors.first('form-address.zip_code') }}</span>
                  </div>

                  <div class="vx-col w-full md:w-4/5">
                    <vs-input class="w-full mt-4" :label="$t('city')" v-model="formAddress.city" v-validate="'required|min:3|max:255'" name="city" />
                    <span class="text-danger text-sm"  v-show="errors.has('form-address.city')">{{ errors.first('form-address.city') }}</span>
                  </div>

                  <div class="vx-col w-full md:w-1/5 mt-4" style="position: relative !important;">
                    <label class="vs-input--label">UF</label>
                    <v-select v-model="ufFormAddress" append-to-body :calculate-position="calculatePosition" :clearable="false" :options="ufOptions" v-validate="'required'" name="uf" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
                    <span class="text-danger text-sm"  v-show="errors.has('form-address.uf')">{{ errors.first('form-address.uf') }}</span>
                  </div>
                </div>

                <vs-button class="mt-6 ml-auto flex" @click.prevent="confirmDeliveryAddress()" :disabled="!validateFormAdress">{{ $t('confirm') }}</vs-button>
              </form>
            </vx-card>
          </div>
        </div>
      </tab-content>

      <!-- tab 2 content -->
      <tab-content :title="$t('cart')" icon="feather icon-shopping-cart" class="mb-5">
        <!-- IF CART HAVE ITEMS -->
        <div class="vx-row" v-if="cartItems.length">
          <!-- LEFT COL -->
          <div class="vx-col lg:w-2/3 w-full relative">
            <div class="vx-row flex justify-center">
              <div class="vx-col lg:w-10/12">
                <div class="items-list-view" v-for="(item, index) in cartItems" :key="item.objectID">
                  <item-list-view :item="item" class="mb-base">

                    <!-- SLOT: ITEM META -->
                    <template slot="item-meta">
                      <h6 class="item-name font-semibold mb-1 cursor-pointer hover:text-primary">{{ item.description }}</h6>
                      <!--<p class="text-sm mb-2">By <span class="font-semibold cursor-pointer">{{ item.brand }}</span></p>-->
                      <p class="text-success text-sm">{{ $t('in_stock') }}</p>

                      <p class="mt-4 font-bold text-sm">{{ $t('quantity') }}</p>
                      <vs-input-number min="1" :value="item.quantity" @input="updateItemQuantity($event, index)" class="inline-flex" />

                      <!--<p class="font-medium text-grey mt-4">Delivery by, {{ item.delivery_date }}</p>
                      <p class="text-success font-medium">{{ item.discount_in_percentage }}% off {{ item.offers_count }} offers Available</p>-->
                    </template>

                    <!-- SLOT: ACTION BUTTONS -->
                    <template slot="action-buttons">
                      <!-- PRIMARY BUTTON: REMOVE -->
                      <div class="item-view-primary-action-btn p-3 rounded-lg flex flex-grow items-center justify-center cursor-pointer mb-3" @click="removeItemFromCart(item)">
                        <feather-icon icon="XIcon" svgClasses="h-4 w-4" />
                        <span class="text-sm font-semibold ml-2">{{ $t('remove') }}</span>
                      </div>
                    </template>
                  </item-list-view>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT COL -->
          <div class="vx-col lg:w-1/3 w-full">
            <vx-card>
              <p class="font-semibold mb-3">{{ $t('price_details') }}</p>
              <div class="flex justify-between mb-2">
                <span class="text-grey">Subtotal</span>
                <span>{{ subTotal | BRL }}</span>
              </div>
              <div class="flex justify-between mb-2">
                <span class="text-grey">{{ $t('delivery_price') }}</span>
                <span class="">{{ deliveryPrice | BRL }}</span>
              </div>

              <vs-divider />

              <div class="flex justify-between font-semibold mb-3">
                <span>Total</span>
                <span>R$ {{ total | BRL }}</span>
              </div>

              <vs-button class="w-full" @click="$refs.checkoutWizard.nextTab()"><span class="uppercase">{{ $t('place_order') }}</span></vs-button>
            </vx-card>
          </div>
        </div>

        <!-- IF NO ITEMS IN CART -->
        <vx-card :title="$t('notiy_cart_empty')" v-else>
          <vs-button @click="$router.push('/').catch(() => {})">{{ $t('browse_shop') }}</vs-button>
        </vx-card>

      </tab-content>

      <!-- tab 3 content -->
      <tab-content :title="$t('payment')" icon="feather icon-credit-card" class="mb-5">
        <div class="vx-row">
          <!-- LEFT COL: PAYMENT OPTIONS -->
          <div class="vx-col lg:w-2/3 w-full">
            <div class="vx-row flex justify-center">
              <div class="vx-col lg:w-10/12">
                <vx-card :title="$t('payment')" class="mb-base">
                  <form data-vv-scope="form-payment" method="post" id="formPayment" name="formPayment" v-on:submit.prevent="validateCard" >
                    <input type="hidden" name="description" value="GoodFood">
                    <input type="hidden" name="transaction_amount" :value="100">
                    <input type="hidden" name="paymentMethodId" id="paymentMethodId" />

                    <div class="vx-row">
                      <div class="vx-col w-full md:w-1/3 mt-4">
                        <vs-select :label="$t('card_doc_type')" id="docType" data-checkout="docType">
                          <vs-select-item value="CPF" text="CPF"/>
                          <vs-select-item value="CNPJ" text="CNPJ"/>
                        </vs-select>
                      </div>
                      <div class="vx-col w-full md:w-2/3">
                        <div class="vs-component vs-con-input-label vs-input w-full mt-4 vs-input-primary">
                          <label for="docNumber" class="vs-input--label">{{ $t('card_doc_number') }}</label>
                          <div class="vs-con-input">
                            <input type="text" id="docNumber" data-checkout="docNumber" class="vs-inputx vs-input--input normal">
                          </div>
                        </div>
                      </div>
                      <div class="vx-col w-full">
                        <!--<vs-input class="w-full mt-4" :label="$t('card_holder_name')" data-checkout="cardholderName" />-->
                        <div class="vs-component vs-con-input-label vs-input w-full mt-4 vs-input-primary">
                          <label for="cardholderName" class="vs-input--label">{{ $t('card_holder_name') }}</label>
                          <div class="vs-con-input">
                            <input type="text" id="cardholderName" data-checkout="cardholderName" class="vs-inputx vs-input--input normal">
                          </div>
                        </div>
                      </div>
                      <div class="vx-col w-full">
                        <!--<vs-input class="w-full mt-4" :label="$t('card_number')" data-checkout="cardNumber" />-->
                        <div class="vs-component vs-con-input-label vs-input w-full mt-4 vs-input-primary">
                          <label for="cardNumber" class="vs-input--label">{{ $t('card_number') }}</label>
                          <div class="vs-con-input">
                            <input type="text" name="cardNumber" id="cardNumber" data-checkout="cardNumber" @input="setPaymentMethod" v-validate="'required|size:16'" v-mask="['################']" class="vs-inputx vs-input--input normal">
                          </div>
                        </div>
                        <span class="text-danger text-sm" v-show="errors.has('cardNumber')">{{ errors.first('cardNumber') }}</span>
                      </div>
                      <div class="vx-col w-full md:w-1/2">
                        <div class="vx-row">
                          <div class="vx-col w-full md:w-1/2">
                            <!--<vs-input class="w-full mt-4" :label="$t('month')" placeholder="MM" data-checkout="cardExpirationMonth" />-->
                            <div class="vs-component vs-con-input-label vs-input w-full mt-4 vs-input-primary">
                              <label for="cardExpirationMonth" class="vs-input--label">{{ $t('month') }}</label>
                              <div class="vs-con-input">
                                <input type="text" placeholder="MM" id="cardExpirationMonth" data-checkout="cardExpirationMonth" v-mask="['##']" class="vs-inputx vs-input--input normal">
                              </div>
                            </div>
                          </div>
                          <div class="vx-col w-full md:w-1/2">
                            <!--<vs-input class="w-full mt-4" :label="$t('year')" placeholder="YY" data-checkout="cardExpirationYear" />-->
                            <div class="vs-component vs-con-input-label vs-input w-full mt-4 vs-input-primary">
                              <label for="cardExpirationYear" class="vs-input--label">{{ $t('year') }}</label>
                              <div class="vs-con-input">
                                <input type="text" placeholder="YY" id="cardExpirationYear" data-checkout="cardExpirationYear" v-mask="['##']" class="vs-inputx vs-input--input normal">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="vx-col w-full md:w-1/2">
                        <!--<vs-input class="w-full mt-4" :label="$t('card_cvv')" data-checkout="securityCode" name="securityCode" />-->
                        <div class="vs-component vs-con-input-label vs-input w-full mt-4 vs-input-primary">
                          <label for="securityCode" class="vs-input--label">{{ $t('card_cvv') }}</label>
                          <div class="vs-con-input">
                            <input type="text" id="securityCode" data-checkout="securityCode" v-mask="['###']" class="vs-inputx vs-input--input normal">
                          </div>
                        </div>

                      </div>
                    </div>
                    <input type="submit" value="pay" class="invisible" ref="submitPayment">
                  </form>
                </vx-card>
              </div>
            </div>
          </div>

          <!-- RIGHT COL: PRICE -->
          <div class="vx-col lg:w-1/3 w-full">
            <vx-card>
              <p class="font-semibold mb-3">{{ $t('price_details') }}</p>
              <div class="flex justify-between mb-2">
                <span class="text-grey">Subtotal</span>
                <span>{{ subTotal | BRL }}</span>
              </div>
              <div class="flex justify-between mb-2">
                <span class="text-grey">{{ $t('delivery_price') }}</span>
                <span class="">{{ deliveryPrice | BRL }}</span>
              </div>

              <vs-divider />

              <div class="flex justify-between font-semibold mb-3">
                <span>Total</span>
                <span>R$ {{ total | BRL }}</span>
              </div>

              <vs-button class="w-full" @click="$refs.submitPayment.click()"><span class="uppercase">{{ $t('place_order') }}</span></vs-button>
            </vx-card>
          </div>
        </div>
      </tab-content>

    </form-wizard>
  </div>
</template>

<script>
import _ from 'lodash'
import vSelect from 'vue-select'
import { createPopper } from '@popperjs/core'
import { FormWizard, TabContent } from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'

export default {
  components: {
    ItemListView: () => import('./components/ItemListView.vue'),
    vSelect,
    FormWizard,
    TabContent
  },
  data () {
    return {
      formAddress: {
        street: '',
        n: '',
        complement: '',
        district: '',
        zip_code: '',
        city: '',
        uf: ''
      },
      ufOptions: [
        { label: 'AC', value: 'AC' },
        { label: 'AL', value: 'AL' },
        { label: 'AP', value: 'AP' },
        { label: 'AM', value: 'AM' },
        { label: 'BA', value: 'BA' },
        { label: 'CE', value: 'CE' },
        { label: 'DF', value: 'DF' },
        { label: 'ES', value: 'ES' },
        { label: 'GO', value: 'GO' },
        { label: 'MA', value: 'MA' },
        { label: 'MT', value: 'MT' },
        { label: 'MS', value: 'MS' },
        { label: 'MG', value: 'MG' },
        { label: 'PA', value: 'PA' },
        { label: 'PB', value: 'PB' },
        { label: 'PR', value: 'PR' },
        { label: 'PE', value: 'PE' },
        { label: 'PI', value: 'PI' },
        { label: 'RJ', value: 'RJ' },
        { label: 'RN', value: 'RN' },
        { label: 'RS', value: 'RS' },
        { label: 'RO', value: 'RO' },
        { label: 'RR', value: 'RR' },
        { label: 'SC', value: 'SC' },
        { label: 'SP', value: 'SP' },
        { label: 'SE', value: 'SE' },
        { label: 'TO', value: 'TO' }
      ],
      localDeliveryPrice: '0.00',
      paymentMethod: ''
    }
  },
  computed: {
    validateFormAdress () {
      return !this.errors.any()
    },
    ufFormAddress: {
      get () {
        return {label: _.toUpper(this.formAddress.uf), value: this.formAddress.uf}
      },
      set (uf) {
        this.formAddress.uf = uf.value
      }
    },
    cartItems () {
      return this.$store.state.shop.cartItems.slice().reverse()
    },
    cartDatas () {
      return {
        products: this.cartItems.map(item => { return {uuid: item.uuid, qty: item.quantity} }),
        delivery_address: this.deliveryAdrress
      }
    },
    deliveryAdrress () {
      return [
        this.formAddress.street,
        this.formAddress.n,
        this.formAddress.district,
        this.formAddress.city,
        this.formAddress.uf,
        this.formAddress.zip_code
      ].filter(Boolean).join(', ')
    },
    deliveryPrice () {
      return this.localDeliveryPrice
    },
    subTotal () {
      return this.cartItems.reduce((amount, current) => {
        return amount + (current.quantity * parseFloat(current.price))
      }, 0)
    },
    total () {
      return parseFloat(this.deliveryPrice) + parseFloat(this.subTotal)
    }
  },
  methods: {
    calculatePosition  (dropdownList, component, {width}) {
      /**
       * We need to explicitly define the dropdown width since
       * it is usually inherited from the parent with CSS.
       */
      dropdownList.style.width = width

      /**
       * Here we position the dropdownList relative to the $refs.toggle Element.
       *
       * The 'offset' modifier aligns the dropdown so that the $refs.toggle and
       * the dropdownList overlap by 1 pixel.
       *
       * The 'toggleClass' modifier adds a 'drop-up' class to the Vue Select
       * wrapper so that we can set some styles for when the dropdown is placed
       * above.
       */
      const popper = createPopper(component.$refs.toggle, dropdownList, {
        placement: 'top',
        modifiers: [
          {
            name: 'offset',
            options: {
              offset: [0, -1]
            }
          },
          {
            name: 'toggleClass',
            enabled: true,
            phase: 'write',
            fn ({state}) {
              component.$el.classList.toggle('drop-up', state.placement === 'top')
            }
          }
        ]
      })

      /**
       * To prevent memory leaks Popper needs to be destroyed.
       * If you return function, it will be called just before dropdown is removed from DOM.
       */
      return () => popper.destroy()
    },

    // TAB 1
    confirmDeliveryAddress () {
      return new Promise(() => {
        this.$validator.validateAll('form-address').then(statusValidator => {
          if (!statusValidator) return

          this.$vs.loading()
          this.$store.dispatch('shop/calculateDelivery', this.cartDatas)
            .then(response => {
              this.localDeliveryPrice = response.data.delivery_price
              this.$refs.checkoutWizard.nextTab()
              this.$vs.loading.close()
            })
            .catch(err => {
              this.$vs.loading.close()
              this.$vs.notify({
                color: 'danger',
                text: err.response.data.message
              })
            })
        })
      })
    },

    // TAB 2
    removeItemFromCart (item) {
      this.$store.dispatch('shop/removeItemFromCart', item)
    },
    updateItemQuantity (event, index) {
      const itemIndex = Math.abs(index + 1 - this.cartItems.length)
      this.$store.dispatch('shop/updateItemQuantity', { quantity: event, index: itemIndex })
    },

    // TAB 3
    loadApiMercadoPago () {
      const mercadoPagoJs = document.createElement('script')
      mercadoPagoJs.setAttribute('src', 'https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js')
      document.head.appendChild(mercadoPagoJs)
    },
    setPublishableKey () {
      window.Mercadopago.setPublishableKey(window.mp_public_key)
    },
    setPaymentMethod (event) {
      if (event.target.value.length < 6) {
        document.getElementById('cardNumber').style.backgroundImage = 'none'
        return
      }

      const cardNumber = event.target.value
      const bin = cardNumber.substring(0, 6)
      this.setPublishableKey()
      window.Mercadopago.getPaymentMethod({bin}, (status, response) => {
        if (status !== 200) return

        const paymentMethod = response[0]
        this.paymentMethod = paymentMethod.id

        document.getElementById('cardNumber').style.backgroundImage = `url(${paymentMethod.thumbnail})`
      })
    },
    validateCard () {
      this.setPublishableKey()
      const $form = document.querySelector('#formPayment')

      this.$vs.loading()
      window.Mercadopago.createToken($form, (status, response) => {
        if (status === 200 || status === 201) {
          this.payment(response.id)
        } else {
          this.$vs.loading.close()
          this.$vs.notify({
            color: 'danger',
            text: this.$t('notify_fail_validate_card')
          })
        }
      })

      return false
    },
    payment (cardToken) {
      const paymentData = this.cartDatas
      paymentData.paymentMethodId = this.paymentMethod
      paymentData.token = cardToken

      this.$store.dispatch('shop/payment', paymentData)
        .then(response => {
          this.$vs.loading.close()
          this.$router.push({name:'home'})
          this.$vs.notify({
            color: 'success',
            text: response.data.message
          })
        })
        .catch(err => {
          this.$vs.loading.close()
          this.$vs.notify({
            color: 'danger',
            text: err.response.data.message
          })
        })
    }
  },
  created () {
    if (!this.$store.state.auth.isUserLoggedIn('customer')) {
      this.$vs.notify({
        title: this.$i18n.t('notify_checkout_auth_required.title'),
        text: this.$i18n.t('notify_checkout_auth_required.text'),
        iconPack: 'feather',
        icon: 'icon-alert-circle',
        color: 'danger',
        time: 4000
      })
      this.$router.push({name:'login'})
    } else {
      // load address
      this.$store.dispatch('loadUserAddress').then(response => {
        const address = [...response.data].shift()
        if (!_.isEmpty(address)) {
          this.formAddress = _.pick(address, 'street', 'n', 'complement', 'district', 'zip_code', 'city', 'uf')
        }
      })
    }

    this.loadApiMercadoPago()
  }
}
</script>

<style lang="scss" scoped>
#shop-checkout {
  padding-top: 50px !important;

  .item-view-primary-action-btn {
    color: #2c2c2c !important;
    background-color: #f6f6f6;
  }

  .item-name {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
    -webkit-line-clamp: 2;
  }

  .vue-form-wizard {
    padding-bottom: 0;

    ::v-deep .wizard-header {
      padding: 0;
    }

    ::v-deep .wizard-tab-content {
      padding-right: 0;
      padding-left: 0;
      padding-bottom: 0;

      .wizard-tab-container{
        margin-bottom: 0 !important;
      }
    }
  }

  #cardNumber {
    background-position: 98% 50%;
    background-repeat: no-repeat;
    background-color: #fff;
  }
}
</style>
