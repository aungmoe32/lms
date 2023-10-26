import axios from "axios";

const state = {
    users: [],
    dashboard : null
};

const getters = {
    Users: (state) => state.users,
    Dashboard: (state) => state.dashboard,
};

const actions = {

    GetDashboard({ state, commit }) {
        return axios.get('admin/dashboard')
            .then((response) => {
                state.dashboard = response.data
            })
            .catch()
    },


    GetUsers({ state, commit }) {
        return axios.get('admin/users')
            .then((response) => {
                state.users = response.data
            })
            .catch()
    },

    SaveUser({ state, commit }, form) {
        return axios.post('admin/save-user', form)
    },
    DeleteUser({ state, commit }, form) {
        return axios.post('admin/delete-user', form)
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
