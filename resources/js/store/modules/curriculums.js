import axios from "axios";
import Vue from "vue";

const state = {
    curriculums : null,
};

const getters = {
    Curriculums: (state) => state.curriculums,
};

const actions = {

    GetCourseCurriculums({ state, commit }, course_id) {
        let path = '/instructor/course-curriculum/' + course_id

        return axios.get(path)
            .then((response) => {
                commit('setCirculums', response.data)
                // console.log(response.data);
                return response
            })
            .catch(error => {

            })
    },

    SaveLecture({ state, commit }, form) {
        let path = '/instructor/courses/lecture/save'

        return axios.post(path, form)


    },


    SaveSection({ state, commit }, form) {
        let path = '/instructor/courses/section/save'

        return axios.post(path, form)

    },

    DeleteSection({ state, commit }, form) {
        let path = '/instructor/courses/section/delete'

        return axios.post(path, form)

    },

    DeleteLecture({ state, commit }, form) {
        let path = '/instructor/courses/lecture/delete'

        return axios.post(path, form)

    },
};

const mutations = {

    setCirculums(state, curriculums) {
        state.curriculums = curriculums;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
