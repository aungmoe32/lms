import axios from "axios";

const state = {
    categories: [],
    active_categories: [],
};

const getters = {
    Categories: (state) => state.categories,
    ActiveCategories: (state) => state.active_categories,
};

const actions = {

    GetCategories({ state, commit }) {
        return axios.get('categories')
            .then((response) => {
                commit("setCategories", response.data);
            })
    },
    GetActiveCategories({ state, commit }) {
        return axios.get('active-categories')
            .then((response) => {
                commit("setActiveCategories", response.data);
                return response
            })
    },

    SaveCategories({ state, commit }, form) {
        return axios.post('admin/save-category', form)
        .then((response) => {
            commit("setCategories", response.data.categories);
            return response
        })
    },
    DeleteCategory({ state, commit }, form) {
        return axios.post('admin/delete-category', form)
        .then((response) => {
            commit("setCategories", response.data.categories);
            return response
        })
    },
};

const mutations = {
    setCategories(state, categories) {
        state.categories = categories;
    },


    setActiveCategories(state, categories) {
        state.active_categories = categories;
    },



};

export default {
    state,
    getters,
    actions,
    mutations,
};
