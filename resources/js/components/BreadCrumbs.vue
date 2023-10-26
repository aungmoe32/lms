<template>
    <v-breadcrumbs :items="breadcrumbs" divider="|">
        <template v-slot:item="{ item }">
            <v-breadcrumbs-item :to="item.href" :disabled="item.disabled">
                {{ item.text }}
            </v-breadcrumbs-item>
        </template>
    </v-breadcrumbs>
</template>

<script>



export default {
    props: ['course_id'],
    data: () => ({
        breadcrumbs: [
            {
                text: 'Course Info',
                href: '/instructor/course-info/',
            },
            {
                text: 'Course Image',
                href: '/instructor/course-image/',
            },
            // {
            //     text: 'Promo Video',
            //     href: '/instructor/course-video/',
            // },
            {
                text: 'Curriculum',
                href: '/instructor/course-curriculum/',
            },
        ],
    }),

    created() {
        // this.breadcrumbs[0].href += this.course_id
        // this.breadcrumbs[1].href += this.course_id
        // this.breadcrumbs[3].href += this.course_id

        for(const breadcrumb of this.breadcrumbs){
            breadcrumb.href += this.course_id
        }
    },



};
</script>
