<template>
    <v-container >

        <v-toolbar elevation="0">
            <h1>Courses</h1>

            <v-spacer></v-spacer>


            <v-btn @click="filterDialog = true" color="primary">Filter</v-btn>
            <v-btn class="ml-2" text @click="clearFilters()">clear filters</v-btn>
        </v-toolbar>

        <v-dialog v-model="filterDialog" max-width="500px" persistent>
            <v-card>
                <!-- <v-toolbar dark color="primary" class="elevation-0">
                    <v-btn icon dark @click="filterDialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Course Filter</v-toolbar-title>
                </v-toolbar> -->

                <v-card-title>
                    <span class="text-h5">Course Filters</span>
                </v-card-title>

                <v-card-text>

                    <v-row class="">
                        <v-col cols="12">
                            <v-divider></v-divider>
                        </v-col>
                        <v-col cols="6">
                            <h3>Categories</h3>
                            <v-checkbox v-for="(category, i) in ActiveCategories" :key="i" :label="category.name"
                                hide-details :value="category.id" v-model="selectedCategories">
                            </v-checkbox>
                        </v-col>
                        <v-col cols="6">
                            <h3>Price</h3>
                            <v-checkbox v-for="(price, i) in prices" :key="i" :label="price.text" hide-details
                                :value="price.id" v-model="selectedPrices">
                            </v-checkbox>
                        </v-col>

                        <v-col cols="12">
                            <v-divider></v-divider>
                        </v-col>

                        <v-col cols="6">
                            <h3>Level</h3>
                            <v-checkbox v-for="(level, i) in levels" :key="i" :label="level.name" hide-details
                                :value="level.id" v-model="selectedLevels">
                            </v-checkbox>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <!-- <v-btn color="primary" text @click="clearAllFilters()">
                        Clear All Filters
                    </v-btn> -->
                    <v-btn color="primary"  @click="applyFilter()">
                        Apply
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>


        <!-- <v-row>
            <v-col class="text-center" cols="12">
                <v-toolbar dense floating>
                    <v-text-field v-model="search" placeholder="Search Course Title" single-line></v-text-field>

                    <v-btn icon @click="searchCourse()">
                        <v-icon>mdi-magnify</v-icon>
                    </v-btn>
                    <v-btn icon @click="searchCourse()">
                        <v-icon>mdi-magnify</v-icon>
                    </v-btn>

                </v-toolbar>

            </v-col>
        </v-row> -->



        <!-- <v-row class="mt-3">


            <v-col xs="6" sm="6" md="3" v-for="(course, i) in courses" :key="i">
                <v-card @click="goToCourseView(course.id)">
                    <v-img :src="CourseImagePath(course)"></v-img>

                    <v-card-title>
                        {{ course.course_title }}
                    </v-card-title>

                    <v-card-subtitle>
                        <i class="fa fa-chalkboard-teacher my_icon"></i>
                        Created by {{ course.instructor.first_name + ' ' + course.instructor.last_name }}
                    </v-card-subtitle>

                    <v-divider class="mx-4"></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text>{{ course.price ? course.price + ' $' : 'Free' }}</v-btn>
                    </v-card-actions>

                </v-card>
            </v-col>
        </v-row> -->


        <Courses :courses="courses"></Courses>


    </v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import Courses from "../components/Courses.vue"

export default {
    // name: 'Courses',

    data() {
        return {
            menu: false,
            filterDialog: false,
            search: '',
            courses: [],
            items: [
                { text: 'Real-Time', icon: 'mdi-clock' },
                { text: 'Audience', icon: 'mdi-account' },
                { text: 'Conversions', icon: 'mdi-flag' },
            ],

            prices: [
                { id: '0-0', text: 'Free' },
                { id: '0-50', text: 'Less than USD 50' },
                { id: '50-99', text: 'USD 50 - USD 99' },
                { id: '100-199', text: 'USD 100 - USD 199' },
                { id: '200-299', text: 'USD 200 - USD 299' },
                { id: '300-399', text: 'USD 300 - USD 399' },
                { id: '400-499', text: 'USD 400 - USD 499' },
                { id: '500', text: 'More than USD 500' },
            ],
            levels: [
                { id: '1', name: 'Introductory' },
                { id: '2', name: 'Intermediate' },
                { id: '3', name: 'Advanced' },
                { id: '4', name: 'Comprehensive' },
            ],

            selectedCategories: [],
            selectedPrices: [],
            selectedLevels: [],

            // tempCategories
        }
    },

    computed: {
        ...mapGetters(['ActiveCategories', 'CourseImagePath']),
    },

    components: {
        Courses
    },

    created() {
        // this.GetLastestCourses()
        this.GetActiveCategories()
        this.getFilteredCourses(this.$route.query)
    },


    methods: {
        ...mapActions(["GetFilteredCourses", "GetActiveCategories"]),
        getFilteredCourses(query = {}) {
            let temp = '';
            if (query.search) {
                temp = 'search=' + query.search
            }

            this.GetFilteredCourses(temp)
                .then(res => {
                    this.courses = res.data
                })
                .catch()
        },
        // searchCourse() {
        //     if (this.search) {
        //         this.$router.push('/courses?search=' + this.search)

        //     }


        // }

        applyFilter() {
            this.GetFilteredCourses(this.getQuery())
                .then(res => {
                    this.courses = res.data
                })
                .catch()
            this.filterDialog = false
        },
        // clearAllFilters() {
        //     this.GetFilteredCourses('')
        //         .then(res => {
        //             this.courses = res.data
        //         })
        //         .catch()
        //     this.filterDialog = false
        // },

        sortedPrices(){
            let sorted = []
            for(var price of this.prices){
                if(this.selectedPrices.includes(price.id)){
                    sorted.push(price.id)
                }
            }
            return sorted
        },

        getQuery(selected, query_key) {
            let query = ''

            // let sortedPrices = []

            let selecteds = {
                'category_id': this.selectedCategories,
                'price_id': this.sortedPrices(),
                'instruction_level_id': this.selectedLevels,
            }

            for (var selected in selecteds) {
                selecteds[selected].forEach(id => {
                    query += selected + '[]=' + id + '&'
                })
            }
            return query
        },

        clearFilters() {
            this.selectedCategories = []
            this.selectedLevels = []
            this.selectedPrices = []
            this.applyFilter()
        }
    },


    // beforeRouteUpdate(to, from, next) {
    //     // console.log(to.query);
    //     this.getFilteredCourses(to.query)
    //     this.selectedCategories = []
    //     next()
    // }
};
</script>


<style scoped>
.v-label {
    font-size: 11px;
}
</style>
