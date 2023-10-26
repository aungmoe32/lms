import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store'


Vue.use(VueRouter)


const routes = [

    {
        path: '/',
        name: 'home',
        components: {
            default: () => import('./views/Home.vue'),
            nav: () => import('./components/Navbars/HomeNav.vue'),

        }

    },
    {
        path: '/course-view/:course_id',
        components: {
            default: () => import('./views/course/CourseView.vue'),
            nav: () => import('./components/Navbars/HomeNav.vue'),

        }

    },
    {
        path: '/profile',
        meta: { requiresAuth: true },
        components: {
            default: () => import('./views/Profile.vue'),
            nav: () => import('./components/Navbars/HomeNav.vue'),

        },

    },
    {
        path: '/admin',
        name: 'admin',
        redirect: { name: 'admin-dashboard' },
        meta: { requiresAuth: true, roles: ['admin'] },
        components: {
            default: () => import('./views/admin/Admin.vue'),
            nav: () => import('./components/Navbars/AdminNav.vue'),

        },
        children: [
            {

                path: 'dashboard',
                name: 'admin-dashboard',

                components: {
                    default: () => import('./views/admin/Dashboard.vue'),

                }
            },
            {

                path: '/admin/users',

                components: {
                    default: () => import('./views/admin/UserManage.vue'),

                }
            },
            {

                path: 'categories',
                name: 'admin-categories',

                components: {
                    default: () => import('./views/admin/Category.vue'),

                }
            },
        ]
    },

    {
        path: '/instructor-list',
        components: {
            default: () => import('./views/instructor/InstructorList.vue'),
            nav: () => import('./components/Navbars/HomeNav.vue'),


        },
    },
    {
        path: '/instructors/:id',
        components: {
            default: () => import('./views/instructor/InstructorView.vue'),
            nav: () => import('./components/Navbars/HomeNav.vue'),


        },
    },

    {
        path: '/instructor',
        name: 'instructor',
        redirect: { name: 'instructor-dashboard' },
        meta: { requiresAuth: true, roles: ['instructor'] },
        components: {
            default: () => import('./views/instructor/instructor.vue'),
            nav: () => import('./components/Navbars/InstructorNav.vue'),


        },
        children: [
            {

                path: 'dashboard',
                name: 'instructor-dashboard',

                components: {
                    default: () => import('./views/instructor/Dashboard.vue'),

                }
            },
            {

                path: 'profile',
                components: {
                    default: () => import('./views/instructor/InstrProfile.vue'),

                }
            },
            {

                path: 'course-list',
                name: 'instructor-course-list',

                components: {
                    default: () => import('./views/instructor/CourseList.vue'),

                }
            },
            {

                path: 'course-info',
                // path: 'course-info/:course_id',
                name: 'instructor-course-info',

                components: {
                    default: () => import('./views/instructor/CourseInfo.vue'),

                }
            },
            {

                path: 'course-info/:course_id',

                components: {
                    default: () => import('./views/instructor/CourseInfo.vue'),

                }
            },
            {

                path: 'course-image/:course_id',

                components: {
                    default: () => import('./views/instructor/CourseImage.vue'),

                }
            },
            {

                path: 'course-curriculum/:course_id',

                components: {
                    default: () => import('./views/instructor/CourseCurriculum.vue'),

                }
            },
        ]
    },


    {
        path: '/login',
        name: 'login',
        meta: { guest: true },
        components: {
            default: () => import('./views/auth/Login.vue'),
            nav: () => import('./components/Navbars/LoginNav.vue'),

        }
    },
    {
        path: '/register',
        name: 'register',
        meta: { guest: true },
        components: {
            default: () => import('./views/auth/Register.vue'),
            nav: () => import('./components/Navbars/LoginNav.vue'),

        }
    },

    {
        path: '/courses',
        name: 'courses',
        components: {
            default: () => import('./views/CourseList.vue'),
            nav: () => import('./components/Navbars/HomeNav.vue'),

        }
    },

    {
        path: '/checkout/:course_id',
        meta: { requiresAuth: true, roles: ['student'] },
        components: {
            default: () => import('./views/payment/Checkout.vue'),
            nav: () => import('./components/Navbars/HomeNav.vue'),

        }
    },
    {
        path: '/my-courses',
        meta: { requiresAuth: true, roles: ['student'] },
        components: {
            default: () => import('./views/course/MyCourses.vue'),
            nav: () => import('./components/Navbars/HomeNav.vue'),

        }
    },
    {
        path: '/course-learn/:course_id',
        meta: { requiresAuth: true, roles: ['student'] },
        components: {
            default: () => import('./views/course/CourseLearn.vue'),
            nav: () => import('./components/Navbars/HomeNav.vue'),

        }
    },
    {
        path: '/course-enroll/:course_id/:lecture_id',
        meta: { requiresAuth: true, roles: ['student'] },
        components: {
            default: () => import('./views/course/CourseEnroll.vue'),
            nav: () => import('./components/Navbars/CourseEnrollNav.vue'),

        }
    },

    {
        path: '/about',
        name: 'about',
        component: () => import('./views/About.vue'),
    },

    {
        path: '/page-expired',
        name: 'page-expired',
        component: () => import('./views/PageExpired.vue'),
    },

    // {
    // 	path: '/projects',
    // 	name: 'projects',
    // 	component: Projects
    // 	// route level code-splitting
    // 	// this generates a separate chunk (about.[hash].js) for this route
    // 	// which is lazy-loaded when the route is visited.
    // 	//component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
    // },
    // {
    // 	path: '/team',
    // 	name: 'team',
    // 	component: Team
    // }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

router.beforeEach((to, from, next) => {
    // console.log(from.path, to.path);
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (store.getters.isAuthenticated) {
            next();

            return;
        }
        next("/login");
    } else {
        next();
    }
});

router.beforeEach((to, from, next) => {
    // console.log('rolecheck');
    let roles = null
    if (to.matched.some((record) => {
        roles = record.meta.roles
        return record.meta.roles
    })) {
        // console.log(store.getters.HasAnyRole(roles));
        if (store.getters.HasAnyRole(roles)) {
            // console.log('hasrole');
            next()

            return
        }
        next('/')
    }
    else {
        next()
    }
});

router.beforeEach((to, from, next) => {
    // console.log('guest');

    if (to.matched.some((record) => record.meta.guest)) {
        if (store.getters.isAuthenticated) {
            next("/");
            return;
        }
        next();
    } else {
        next();
    }
});

export default router
