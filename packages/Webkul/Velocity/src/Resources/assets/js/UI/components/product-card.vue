<template>
    
    <div class="popular-item" >
        <div class="popular-img">
            <a :href="`${baseUrl}/${product.slug}`" tabindex="0">
                <img loading="lazy"
                :alt="product.name"
                :src="product.image || product.product_image"
                :data-src="product.image || product.product_image"
                class="card-img-top lzy_img"
                :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" /></a>
        </div>
        <div class="popular-item-title">
            <a :href="`${baseUrl}/${product.slug}`" tabindex="0">{{ product.name }}</a>
            <a href="" tabindex="0"><i class="fal fa-heart"></i></a>
        </div>
        <span class="title-tag">Lowest List</span>
        <p class="price" v-html="product.priceHTML"></p>
    </div>


</template>

<script type="text/javascript">
    
    export default {
        props: [
            'list',
            'product',
        ],

        data: function () {
            return {
                'addToCart': 0,
                'addToCartHtml': '',
            }
        },

        methods: {
            'isMobile': function () {
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
</script>