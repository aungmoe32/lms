<template>
    <v-row v-if="instructor" class="my-10 mx-10">
        <v-col cols="12" sm="7"  class="pr-4">
            <h2 class="mb-3">{{ instructor.first_name + ' ' + instructor.last_name }}</h2>
            <div v-for="(info, i) in infos" :key="i" class="mb-2">
                <i :class="'my_icon fa ' + info.icon"></i>
                <span>{{ instructor[info.name] }}</span>
            </div>

            <v-divider></v-divider>
            <h4 class="my-3">Biography</h4>
            <span class="my-3" style="white-space: pre-line;">{{ instructor.biography }}</span>
            <v-divider class="my-3"></v-divider>
        </v-col>
        <v-col cols="12" sm="5" >
            <v-card>
                <v-img
                :aspect-ratio="16/9"
                :src="InstructorImagePath(instructor)"></v-img>

                <v-card-title>
                    <v-row>
                        <v-col cols="12 d-flex justify-space-around">
                            <a :href="'//' + instructor[social.name]" target="_blank" v-for="(social, i) in socials">
                                <i style="font-size: 30px;" :class="'fab ' + social.icon"></i>
                            </a>
                        </v-col>
                        <v-col cols="12">
                            <v-divider class="mb-4"></v-divider>
                            <span>
                                <i class="fa fa-chalkboard my_icon"></i>
                                <span class="mx-2" style="font-size: medium;">
                                    {{ instructor.courses_count }}
                                    Course(s)
                                </span>

                            </span>
                        </v-col>
                    </v-row>
                </v-card-title>

                <!-- <v-card-subtitle>
                    <i class="fa fa-chalkboard-teacher my_icon"></i>
                    Created by {{ instructor.instructor.first_name + ' ' + instructor.instructor.last_name }}
                </v-card-subtitle> -->
            </v-card>
        </v-col>

        <v-col cols="12" class="my-5">
            <h2 class="mt-4">Courses</h2>
            <Courses :courses="instructor.latest_courses" :instructor="instructor"></Courses>
        </v-col>
    </v-row>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import instructor from "../../store/modules/instructor";

import Courses from '../../components/Courses.vue'


export default {
    data: () => ({
        instructor: null,
        infos: [
            { icon: 'fa-phone-volume', name: 'telephone' },
            { icon: 'fa-mobile-alt', name: 'mobile' },
            { icon: 'fa-envelope', name: 'contact_email' },
        ],
        socials: [
            { icon: 'fa-facebook-f', name: 'link_facebook' },
            { icon: 'fa-linkedin-in', name: 'link_linkedin' },
            { icon: 'fa-twitter', name: 'link_twitter' },
            { icon: 'fa-google-plus-g', name: 'link_googleplus' },
        ],
    }),

    created() {
        this.GetInstructor(this.$route.params.id)
            .then(res => {
                this.instructor = res.data
            })
            .catch(error => { })
    },

    computed: {
        ...mapGetters(['InstructorImagePath']),
    },

    components: {
        Courses
    },

    methods: {
        ...mapActions(["GetInstructor"]),



    }

};
</script>

