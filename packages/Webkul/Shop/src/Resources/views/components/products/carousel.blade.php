<v-products-carousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
>
    <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false" />
</v-products-carousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-products-carousel-template"
    >
        <div
            class="container mt-20 max-lg:px-8 max-sm:mt-8"
            v-if="! isLoading && products.length"
        >
            <div class="flex justify-between">
                <h2
                    class="text-3xl font-poppins max-sm:text-2xl"
                    v-text="title"
                >
                </h2>

                <div class="flex gap-8 justify-between items-center">
                    <span
                        class="mdi mdi-chevron-left text-navyBlue inline-block text-2xl cursor-pointer"
                        role="button"
                        aria-label="@lang('shop::app.components.products.carousel.previous')"
                        tabindex="0"
                        @click="swipeLeft"
                    >
                    </span>

                    <span
                        class="mdi mdi-chevron-right text-navyBlue inline-block text-2xl cursor-pointer"
                        role="button"
                        aria-label="@lang('shop::app.components.products.carousel.next')"
                        tabindex="0"
                        @click="swipeRight"
                    >
                    </span>
                </div>
            </div>

            <div
                ref="swiperContainer"
                class="flex gap-8 [&>*]:flex-[0] mt-10 overflow-auto scroll-smooth scrollbar-hide max-sm:mt-5 p-1"
            >
                <x-shop::products.card
                    class="min-w-[291px] rounded-md p-4"
                    v-for="product in products"
                    style="box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;"
                />
            </div>

            <a
                :href="navigationLink"
                class="secondary-button block w-max mt-14 mx-auto py-3 px-11 rounded-2xl text-base text-center"
                v-if="navigationLink"
            >
                @lang('shop::app.components.products.carousel.view-all')
            </a>
        </div>

        <!-- Product Card Listing -->
        <template v-if="isLoading">
            <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false" />
        </template>
    </script>

    <script type="module">
        app.component('v-products-carousel', {
            template: '#v-products-carousel-template',

            props: [
                'src',
                'title',
                'navigationLink',
            ],

            data() {
                return {
                    isLoading: true,

                    products: [],

                    offset: 323,
                };
            },

            mounted() {
                this.getProducts();
            },

            methods: {
                getProducts() {
                    this.$axios.get(this.src)
                        .then(response => {
                            this.isLoading = false;

                            this.products = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                swipeLeft() {
                    const container = this.$refs.swiperContainer;

                    container.scrollLeft -= this.offset;
                },

                swipeRight() {
                    const container = this.$refs.swiperContainer;

                    // Check if scroll reaches the end
                    if (container.scrollLeft + container.clientWidth >= container.scrollWidth) {
                        // Reset scroll to the beginning
                        container.scrollLeft = 0;
                    } else {
                        // Scroll to the right
                        container.scrollLeft += this.offset;
                    }
                },
            },
        });
    </script>
@endPushOnce
