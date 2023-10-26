import axios from "axios";

const state = {
};

const getters = {
};

const actions = {


    GetCourseRating({ state, commit }, course_id) {
        return axios.get('/user-ratings/' + course_id)

    },
    SaveRating({ state, commit }, form) {
        return axios.post('rating-save', form)

    },

};

const mutations = {


};

export default {
    state,
    getters,
    actions,
    mutations,
};
