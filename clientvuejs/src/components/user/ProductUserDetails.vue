<template>
    <div id="administrator">
        <div id="big">
            <div id="top">
                <div id="buy">
                    <div id="left">
                        <div class="top_mini">
                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel"
                                v-if="images[0]">
                                <div class="carousel-inner">
                                    <div class="carousel-item active imgproduct">
                                        <!-- nhằm đảm bảo luôn có ít nhất 1 ảnh để có slider nên có active cho cái đầu tiên -->
                                        <img class="d-block w-100" :src="domain + '/' + images[0].image_path">
                                    </div>
                                    <div class="carousel-item imgproduct" v-for="(img, index) in images" :key="index">
                                        <img class="d-block w-100" :src="domain + '/' + img.image_path">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                                    data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                                    data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div id="category_name" class="ifontawesome">
                            <div class="alert alert-primary" role="alert"><i class="fa-solid fa-list"></i> Category :
                                {{ detailsProduct.category_name }}</div>
                        </div>
                    </div>
                    <div id="right">
                        <div class="ifontawesome" id="name_product" role="alert"><i class="fa-brands fa-dropbox"></i>
                            {{ detailsProduct.product_name }}</div>
                        <div id="price" style="margin-top:10px">$ {{ new
                            Intl.NumberFormat().format(detailsProduct.price)}}</div>
                        <div class="row infor_product">
                            <div class="col-3">Dimension</div>
                            <div class="col-9">{{ detailsProduct.dimension }}</div>
                        </div>
                        <div class="row infor_product">
                            <div class="col-3">Material</div>
                            <div class="col-9">{{ detailsProduct.material }}</div>
                        </div>
                        <div class="row infor_product">
                            <div class="col-3">Quantity</div>
                            <div class="col-9">{{ detailsProduct.quantity }}</div>
                        </div>
                        <div class="row infor_product">
                            <div class="col-3">Warranty period</div>
                            <div class="col-3"><input disabled :value="detailsProduct.warranty_period" type="date"
                                    format="YYYY MM DD" class="form-control"></div>
                        </div>

                        <div v-if="(detailsProduct.quantity > 0)" class="row infor_product"
                            style="margin-top:40px;color:green;">
                            <div class="col-3">
                                <div class="row">
                                    <!-- chỉ thêm nếu nhỏ hơn số lượng => server đỡ phải sử lý -->
                                    <button type="button"
                                        @click="if (buy_number < detailsProduct.quantity) buy_number++;"
                                        class="b_number col-4"><i class="fa-solid fa-square-plus"></i></button>
                                    <input type="number" disabled v-model="buy_number" min="0"
                                        class="form-control col-4">
                                    <button type="button" @click="if (buy_number > 0) buy_number--;"
                                        class="b_number col-4"><i class="fa-solid fa-square-minus"></i></button>
                                    <!-- chỉ giảm nếu lớn hơn 0 -->
                                </div>
                            </div>
                            <div class="col-2"><button @click="addToCart" type="button"
                                    class="btn btn-outline-primary"><i class="fa-solid fa-cart-plus"></i> Add to
                                    Cart</button></div>
                            <div class="col-2 ml-8"><button type="button" class="btn btn-success"><i
                                        class="fa-solid fa-cart-shopping"></i> Buy Now</button></div>
                        </div>

                        <div v-if="(detailsProduct.quantity == 0)" class="row infor_product"
                            style="margin-top:40px;margin-left:4px">
                            <div class="alert alert-danger" role="alert"><i class="fa-solid fa-flag"></i> This product
                                has been sold out !!!</div>
                            <!-- kiểm tra ngay tại client nếu như hết hàng (quan) thì ẩn mấy nút thêm đi -->
                        </div>
                    </div>
                </div>
                <div id="other">
                    <div id="accordion">
                        <div id="tab_content">
                            <div id="headingOne" :class="{ 'top-border': topborder }" @click="topborder = true">
                                <div data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                    aria-controls="collapseOne">Description</div>
                            </div>
                            <div id="headingTwo" :class="{ 'top-border': topborder == false }" @click="topborder = false">
                                <div data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">Evaluate(0)</div>
                            </div>
                        </div>
                        <div class="card">
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordion">
                                <div class="card-body">
                                    <i class="fa-solid fa-quote-left"></i> {{ detailsProduct.description }} <i
                                        class="fa-solid fa-quote-right"></i>
                                </div>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                data-parent="#accordion">
                                <div class="card-body">
                                    <i class="fa-brands fa-envira"></i> There are no reviews yet !
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="recommend">
                    <div id="label_recommend"><span><i class="fa-solid fa-circle-question"></i></span> List of suggested products, maybe it is suitable for you !</div>
                    <div id="list_product">
                        <div class="item_product" v-for="(product, index) in products" :key="index">
                            <div class="top_mini">
                                <div :id="'carouselExampleControls' + index" class="carousel slide"
                                    data-ride="carousel" v-if="product.images[0]">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active imgproduct">
                                            <!-- nhằm đảm bảo luôn có ít nhất 1 ảnh để có slider nên có active cho cái đầu tiên -->
                                            <img class="d-block w-100" :src="domain + '/' + product.images[0].image_path">
                                        </div>
                                        <div class="carousel-item imgproduct" v-for="(img, index) in product.images"
                                            :key="index">
                                            <img class="d-block w-100" :src="domain + '/' + img.image_path">
                                        </div>
                                    </div>
                                    <a class="carousel-control-prev" :href="'#carouselExampleControls' + index"
                                        role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" :href="'#carouselExampleControls' + index"
                                        role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                                <div>
                                    <div class="name_product_recommend" @click="viewDetail(product.uri, product.id)">{{ product.name }}</div>
                                    <div class="price_product_recommend">$ {{new Intl.NumberFormat().format(product.price)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bottom">

            </div>
            <Notification></Notification>
        </div>
    </div>
</template>

<script>

import Notification from './Notification'
import config from '../../config.js'
// import FilePicker from './FilePicker.vue';
import BaseRequest from '../../restful/user/core/BaseRequest';
import useEventBus from '../../composables/useEventBus'
// import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name: "ProductUserDetails",
    components: {
        Notification,
        // FilePicker,
        // ParticleVue32
    },
    setup() {
        document.title = "Meta Shop - Product";
    },
    data() {
        return {

            // buy 
            buy_number: 0,
            topborder: true,

            url_img: '',
            pageN: 1,
            product: null,
            admin: {
                id: null,
                fullname: '',
                username: '',
                email: '',
                phone: '',
                date_of_birth: null,
                url_img: null,
                gender: null,
                address: '',
                role: '',
                access_token: '',
                refreshToken: '',
                created_at: null,
                updated_at: null,
                email_verified_at: null,
            },
            domain: '',
            searchad: '',
            categorys: '',
            uriProduct: '',
            detailsProduct: {
                id: null,
                product_name: '',
                category_name: '',
                warranty_period: '',
                description: '',
                category_id: null,
                price: null,
                quantity: null,
                material: '',
                dimension: '',
                uri: '',
                status: false, // phục vụ cho checkbox ở component UserOrder
            },
            // images:null,
            images: [], // lỗi hay gặp , nếu là array của array thì khai báo null cũng được 
            // còn array của object thì khai báo mảng images:[]
            // nó giống biến detailsProduct ở phía trên vậy 

            imgRemove: [],
            category_id2: '',
            err: {
                name: [],
                warranty_period: [],
                description: [],
                category_id: [],
                price: [],
                material: [],
                dimension: []
            },
            fixclick: 0,
            products: null,
        }
    },
    mounted() {
        this.domain = config.API_URL;
        this.uriProduct = location.pathname.substring(14);
        BaseRequest.get('api/dashboard-customer/product-detail/' + this.uriProduct)
            .then((data) => {
                this.detailsProduct = data.product;
                this.images = data.images;
                document.title = "Meta Shop - Product - " + this.detailsProduct.product_name;
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess', 'View Detail Product Success !');
            })
            .catch(error => {
                const { emitEvent } = useEventBus();
                emitEvent('eventError', error.message);
            })

        // get recommend product 
        let user = JSON.parse(window.localStorage.getItem('user'));
        BaseRequest.get('api/recommend/product?id_user=' + user.id)
            .then((data) => {
                console.log(data);
                this.products = data.products;
            })
            .catch(error => {
                console.log(error);
            })
    },

    methods: {
        viewDetail:function(uri, product_id){
            let user = JSON.parse(window.localStorage.getItem('user'));
            var data_tracking = {
                action: "click",
                id_user: user.id,
                product_ids: [product_id],
            }
            console.log(data_tracking);
            BaseRequest.post('api/recommend/tracking', data_tracking)
                .then((data) => {
                    console.log(data);
                    window.location.href = window.location.origin + '/main/product/' + uri;
                })
                .catch(error => {console.log(error);})
        },
        updateTracking:function(action="add", product_ids=[]) {
            let user = JSON.parse(window.localStorage.getItem('user'));
            var data_tracking = {
                action: action,
                id_user: user.id,
                product_ids: product_ids,
            }
            console.log(data_tracking);
            BaseRequest.post('api/recommend/tracking', data_tracking)
                .then((data) => {
                    console.log(data);
                })
                .catch(error => {console.log(error);})
        },
        addToCart: function () {

            if (this.buy_number == 0) return; // cộng vào cái cũ thì không chi nhưng nếu là thêm mới mà 
            // số lượng 0 thì tất nhiên là không cho 

            if (JSON.parse(window.localStorage.getItem('user_order')) == null) {
                let user_order = [];
                let detailsProduct = this.detailsProduct;
                detailsProduct.buy_number = this.buy_number; // thêm mới và gán giá trị cho thuộc tính 
                detailsProduct.image_path = this.images[0].image_path;
                user_order.push(detailsProduct);
                window.localStorage.setItem('user_order', JSON.stringify(user_order));

                // const { emitEvent } = useEventBus();
                // emitEvent('eventUserOrder');
            }
            else {
                let detailsProduct = this.detailsProduct; // nếu khai báo var detailsProduct và var user_order 
                // sẽ lỗi vì ở trên if có rồi 
                detailsProduct.buy_number = this.buy_number;
                detailsProduct.image_path = this.images[0].image_path;

                let user_order = JSON.parse(window.localStorage.getItem('user_order'));
                var _check = false;
                user_order.forEach(product => {
                    if (detailsProduct.product_id == product.product_id) {
                        product.buy_number = product.buy_number + detailsProduct.buy_number;
                        window.localStorage.setItem('user_order', JSON.stringify(user_order));
                        _check = true;
                    }
                });
                if (_check == false) {
                    user_order.push(detailsProduct);
                    window.localStorage.setItem('user_order', JSON.stringify(user_order));
                }
            }

            // tracking 
            let user_order = JSON.parse(window.localStorage.getItem('user_order'));
            var trackingProductIds = [];
            user_order.forEach(order => {
                trackingProductIds.push(order.product_id);
            })
            this.updateTracking("add", trackingProductIds);
            // tracking 

            const { emitEvent } = useEventBus();
            emitEvent('eventUserOrder');
            
            return;
        }
    },
    watch: {

    }
}
</script>

<style scoped>
/* recommend */
#recommend {
    margin-top: 10px;
    padding: 20px;
    background-color: white;
}

.name_product_recommend {
    width: 100%;
    background-color: #0085FF;
    display: flex;
    justify-content: center;
    font-size: 18px;
    padding: 6px 0px;
    color: white;
    font-weight: bold;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}
.name_product_recommend:hover {
    text-decoration: underline;
    cursor: pointer;
}

.price_product_recommend {
    width: 100%;
    background-color: white;
    display: flex;
    justify-content: center;
    font-size: 18px;
    padding: 6px 0px;
    color: red;
    font-weight: bold;
    border: 3px solid #0085FF;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
}


#label_recommend {
    font-weight: bold;
    margin-bottom: 10px;
    font-size: 20px;
    color: green;
}
#label_recommend span {
    margin: 10px;
}

#list_product {
    width: 100%;
    overflow: hidden;
    overflow-x: scroll;
    display: flex;
    justify-content: space-around;
}
#list_product::-webkit-scrollbar {
    height: 15px; 
    width: 15px;
}

.item_product {
    padding: 10px;
    margin: 6px;
    width: 20%;
    border: 1px solid silver;
    border-radius: 10px;
}

/* recommend */

#tab_content {
    display: flex;
    justify-content: flex-end;
}

#tab_content>div {
    font-weight: bold;
    font-size: 18px;
    display: flex;
    align-content: center;
    margin-right: 10px;
    margin-bottom: 6px;
    cursor: pointer;
}

#tab_content>div:hover {
    color: #0085FF;
}

.top-border {
    border-bottom: 5px solid red;
}

.b_number {
    outline: none;
    color: gray;
}

.b_number:hover {
    color: #0085FF;
}

#administrator {
    background-color: #F0F2F5;
    padding: 20px;
}

#buy {
    background-color: white;
    padding: 30px;
    margin-bottom: 20px;
    padding-left: 60px;
    display: flex;
}

#right {
    margin-left: 60px;
    width: calc(100% - 400px);
}

#name_product {
    font-weight: bold;
    color: white;
    background-color: #0085FF;
    font-size: 20px;
    height: 50px;
    display: flex;
    align-items: center;
    padding-left: 60px;
    border-radius: 3px;

    /* thêm 3 chấm đằng sau nếu như dài */
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    /* thêm 3 chấm đằng sau nếu như dài */
}

#price {
    font-weight: bold;
    color: white;
    font-size: 30px;
    height: 50px;
    display: flex;
    align-items: center;
    padding-left: 60px;
    border-radius: 3px;
    color: red;
}

#other {
    background-color: white;
    width: 100%;
    padding: 20px;
}

.infor_product {
    display: flex;
    align-content: center;
    align-items: center;
    font-weight: 600;
    font-size: 18px;
    margin-top: 10px;
}

#category_name {
    margin: 0px;
    width: 300px;
    color: white;
    font-weight: bold;
}

.ifontawesome i {
    margin-right: 20px;
}


/* Img product */

.top_mini {
    width: 300px;
}

.imgproduct {
    width: 300px;
    height: 200px;
}

.imgproduct img {
    width: 100%;
    /* or any custom size */
    height: 100%;
    object-fit: contain;
}

.carousel-control-prev:hover .carousel-control-prev-icon {
    background-color: #0085FF;
    display: block;
    border-radius: 10px;
    width: 20px;
    height: 40px;
}

.carousel-control-next:hover .carousel-control-next-icon {
    background-color: #0085FF;
    display: block;
    border-radius: 10px;
    width: 20px;
    height: 40px;
}

.top_mini:hover .carousel-control-prev-icon {
    background-color: #0085FF;
    display: block;
    border-radius: 10px;
    width: 20px;
    height: 40px;
    transition: all 0.5s ease;
}

.top_mini:hover .carousel-control-next-icon {
    background-color: #0085FF;
    display: block;
    border-radius: 10px;
    width: 20px;
    height: 40px;
    transition: all 0.5s ease;
}
</style>
