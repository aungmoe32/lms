import axios from "axios";

const state = {
    user: null,
    roles: null,
    posts: null,
    // profile_user: null
};

const getters = {
    isAuthenticated: (state) => !!state.user,
    StatePosts: (state) => state.posts,
    StateUser: (state) => state.user,
    // ProfileUser: (state) => state.profile_user,
    UserRoles: (state) => state.roles,
    HasAnyRole: (state) => (roles) => {
        if (state.roles == null) return false
        const matched = state.roles.filter(role => roles.includes(role.name));
        // console.log(matched, 'matched');
        // console.log(matched.length != 0);
        return matched.length != 0;

    },
    HasRole: (state) => (role) => {
        if (state.roles == null) return false

        for (const r of state.roles) {
            if (r.name == role) {
                return true
            }
        }
        return false
    }
};

const actions = {

    // SanctumCsrf(context, form) {
    //     axios.get('sanctum/csrf-cookie')
    //         .then((response) => {
    //             // console.log(response.data);
    //             // console.log('setting user');
    //         })
    // },

    Profile({ state, commit }) {
        return axios.get('profile')
    },
    InstrProfile({ state, commit }) {
        return axios.get('/instructor/profile')
    },
    SaveProfile({ state, commit }, form) {
        return axios.post('profile-save', form)

    },

    Register({ state, commit }, form) {
        return axios.post('register', form)
            .then((response) => {
                // console.log(response.data);
                // console.log('setting user');
                commit("setUser", response.data.user);
            })
    },

    Login({ commit }, form) {
        return axios.post('login', form).then((response) => {
            // console.log(response.data);
            commit("setUser", response.data.user);
        })

        // .catch((response)=>{
        //     console.error(response.data);
        // })
},



    Logout({ commit }) {
        let user = null;
        axios.post('logout')
        commit("logout", user);
    },





    // async Register({ dispatch }, form) {
    //     await axios.post('register', form)
    //     let UserForm = new FormData()
    //     UserForm.append('username', form.username)
    //     UserForm.append('password', form.password)
    //     await dispatch('LogIn', UserForm)
    // },
    // async LogIn({ commit }, user) {
    //     await axios.post("login", user);
    //     await commit("setUser", user.get("username"));
    // },

    // async CreatePost({ dispatch }, post) {
    //     await axios.post("post", post);
    //     return await dispatch("GetPosts");
    // },

    // async GetPosts({ commit }) {
    //     let response = await axios.get("posts");
    //     commit("setPosts", response.data);
    // },

    // async LogOut({ commit }) {
    //     let user = null;
    //     commit("logout", user);
    // },
};

const mutations = {
    setUser(state, user) {
        state.user = user;
        state.roles = user.roles;
    },

    setPosts(state, posts) {
        state.posts = posts;
    },
    logout(state, user) {
        state.user = user;
        state.roles = null;
    },


};

export default {
    state,
    getters,
    actions,
    mutations,
};
