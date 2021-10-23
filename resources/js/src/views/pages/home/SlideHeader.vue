<template>
  <swiper :options="swiperOption" :dir="$vs.rtl ? 'rtl' : 'ltr'" :key="$vs.rtl">
    <swiper-slide>
      <img src="@assets/images/pages/carousel/slider-01.jpg" alt="banner" data-swiper-parallax="-23%">
      <div class="swiper-slide-content">
        <div class="title" data-swiper-parallax="-100">Du'Cheff Pizzaria</div>
        <!--<div class="subtitle font-semibold" data-swiper-parallax="-200">Subtitle</div>
        <div class="text" data-swiper-parallax="-300">
          <p class="font-medium">Jelly chocolate cupcake chocolate bar caramels chupa chups chupa chups ice cream tiramisu. Oat cake muffin pastry marzipan sweet jujubes powder cupcake carrot cake. Caramels candy pastry marzipan pudding. Sugar plum carrot cake topping cookie.</p>
        </div>-->
      </div>
    </swiper-slide>
    <swiper-slide>
      <img class="responsive" src="@assets/images/pages/carousel/slider-02.jpg" alt="banner" data-swiper-parallax="-23%">
      <div class="swiper-slide-content">
        <div class="title" data-swiper-parallax="-100">Suri Ceviche Bar</div>
      </div>
    </swiper-slide>
    <div class="swiper-pagination" slot="pagination"></div>
  </swiper>
</template>

<script>
import 'swiper/dist/css/swiper.min.css'
import { swiper, swiperSlide } from 'vue-awesome-swiper'

export default {
  name: 'SlideHeader',
  components: {
    swiper,
    swiperSlide
  },
  data () {
    return {
      swiperOption: {
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
          delay: 2500,
          disableOnInteraction: false
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        }
      }
    }
  }
}
</script>

<style scoped lang="scss">
.swiper-slide img {
  filter: brightness(0.4);
  width: auto !important;
  height: 260px !important;

  @media screen and (min-width:480px) {
    width: 100% !important;
    height: auto !important;
  }
}
.swiper-slide-content {
  top: auto;
  left: 5%;
  right: auto;
  bottom: 5%;
  transform: translate(-5%, -5%);
  position: absolute;

  font-size: 18px;
  color: #fff;

  .title {
    font-size: 41px;
    font-weight: 300;
  }
  .subtitle {
    font-size: 21px;
  }
  .text {
    max-width: 400px;
    font-size: 14px;
    line-height: 1.3;
  }
}
</style>
