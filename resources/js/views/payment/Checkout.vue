<template>
    <v-container fill-height>
        <v-row class=" px-5" v-if="course">
            <v-col cols="12">
                <h3>Confirm Purchase</h3>
            </v-col>
            <v-col cols="6">
                <v-img
                :aspect-ratio="16/9"
                :src="CourseImagePath(course)"></v-img>

            </v-col>
            <v-col cols="6" class="">
                <h3>{{ course.course_title }}</h3>

                <div class="mb-2">
                    price : {{ course.price }} $
                </div>

                <span>
                    <i class="my_icon fas fa-user-friends mr-2"></i>
                    {{ course.course_takens_count }} students
                </span>

                <v-divider class="my-5"></v-divider>
                <div v-if="!payment_status">
                    <div id="paypalBtn" class="text-center"></div>
                    <v-btn v-if="isFree" color="success" @click="checkout()">Checkout</v-btn>
                </div>


                <span v-else color="success">Purchase Success</span>

            </v-col>
            <!-- <v-col class="col-12">

            </v-col> -->







        </v-row>

    </v-container>
</template>

<script>
import axios from "axios";
import { mapGetters, mapActions } from "vuex";



export default {
    name: 'Checkout',
    data: () => ({
        course: null,
        isFree: true,
        payment_status: false
    }),

    created() {
        this.GetCourseViewInfo(this.$route.params.course_id)
            .then(response => {
                this.course = response.data
                this.isFree = parseFloat(this.course.price) ? false : true;
                this.$nextTick(() => {
                    if (!this.isFree) this.paypalInit()

                })


                // this.paypalInit()
            })
            .catch(error => { })
    },

    computed: {
        ...mapGetters(['CourseImagePath']),
    },

    methods: {
        ...mapActions(["GetCourseViewInfo"]),
        checkout() {
            axios.post('/free-enroll', {
                course_id: this.$route.params.course_id
            })
                .then(res => {
                    if (res.data.status) {
                        this.payment_status = true
                    }
                })
        },

        paypalInit() {
            let course_id = this.$route.params.course_id;
            const vm = this;
            paypal
                .Buttons({
                    // style: {
                    //     layout: 'horizontal',
                    //     color: 'blue',
                    //     tagline: false
                    // },


                    createOrder: function () {

                        return axios.post('/checkout', {
                            course_id: course_id
                        })
                            .then(res => {
                                // console.log(res);
                                if (res.data.id) {
                                    return res.data.id
                                }
                                return Promise.reject(res)
                            })
                            .catch(e => {
                                console.error(e)
                            })
                    },
                    onApprove: function (data, actions) {
                        return actions.order.capture().then((details) => {
                            axios.get('/payment-success').then(res => {
                                if (res.data.status) {
                                    vm.payment_status = true;
                                }
                            })
                                .catch(err => {
                                    console.log(err);
                                })
                            // alert('payment success')
                        })
                    },
                })
                .render("#paypalBtn")

        }


    }

};
</script>
