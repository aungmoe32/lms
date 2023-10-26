import Vuex from "vuex";
import Vue from "vue";
import createPersistedState from "vuex-persistedstate";
import auth from "./modules/auth";
import category from "./modules/category";
import instructor from "./modules/instructor";
import course from "./modules/course";
import admin from "./modules/admin";
import comment from "./modules/comment";
import rating from "./modules/rating";
import curriculums from "./modules/curriculums";

// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
  modules: {
    auth,
    category,
    instructor,
    course,
    admin,
    comment,
    rating,
    curriculums
  },
  plugins: [createPersistedState({
    paths : [
        'auth',
        // 'category',
        // 'instructor'
    ]
  })],
});
