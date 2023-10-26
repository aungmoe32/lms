
function isAuth(callback) {
    axios.get('/api/authenticated', {
    })
        .then((response) => {
            callback(response.data === 1)
        })

    // user(user => {
    //     callback('id' in user, user)

    // })
}
function user(callback) {
    axios.get('/api/user/info', {
    })
        .then((response) => {
            if (isUnauthenticated(response)) {
                router.push('/login')

            }
            else{
                callback(response.data)
            }

        })
}

// only authenticated
function isMe(id, callback) {

    user(user => {
        callback(user.id == id, user)
    })
}

function login(form, callback) {
    axios.get('/sanctum/csrf-cookie').then(response => {


        axios.post('/login', form)
            .then((response) => {
                // console.log(response.data);
                let data = response.data
                callback(data.status == 'success', data)

            })

    });
}

function register(form, callback) {
    axios.get('/sanctum/csrf-cookie').then(response => {


        axios.post('/register', form)
            .then((response) => {
                // console.log(response.data);
                let data = response.data
                callback(data.status == 'success', data)

            })

    });
}

function logout(router) {
    axios.get('/sanctum/csrf-cookie').then(response => {
        axios.post('/logout').then((response) => {
            // console.log(response.data);
            if (response.data == 1) {
                router.push('/login')

            }
        })
    })


}


function getToken(callback) {
    axios.get('/sanctum/csrf-cookie').then(response => {
        callback()
    })
}
// function userInfo(callback) {
//     axios.get('/api/user/info', {
//     })
//         .then((response) => {
//             callback(response.data)
//         })
// }

function isRouteRequireAuth(to) {
    return to.matched.some(record => record.meta.requiresAuth)
}

function isLoginOrRegister(to) {
    return to.name == 'register' || to.name == 'login'
}

function isUnauthenticated(response) {
    return response.data.status == 'unauthenticated'
}

function createPost(form, callback) {
    getToken(() => {
        axios.post('/api/posts/create', form)
            .then((response) => {
                if (isUnauthenticated(response)) {
                    router.push('/login')
                }
                else {
                    let success = response.data.status == 1
                    callback(success, response.data)
                }

            })
    })

}

function getMyPosts(callback) {
    axios.get('/api/posts').then((response) => {
        // console.log(response.data);
        if (isUnauthenticated(response)) {
            router.push('/login')

        }
        else {
            callback(response.data)
        }
    })
}

function joinOn(post) {
    return moment(post.created_at).fromNow();
}

function getPost(id, callback) {
    axios.get("/api/posts/" + id).then((response) => {

        if (isUnauthenticated(response)) {
            router.push('/login')

        }
        else {
            callback(response.data)
        }
    });
}

function updatePost(id, form, callback) {
    axios.post("/api/posts/update/" + id, form).then((response) => {

        if (isUnauthenticated(response)) {
            router.push('/login')

        }
        else {
            let success = false

            if (response.data == 1) {
                success = true
            }

            callback(success, response.data)
        }

    });
}

function deletePost(id, callback) {
    axios.post("/api/posts/delete/" + id).then((response) => {

        if (isUnauthenticated(response)) {
            router.push('/login')

        }
        else {
            callback(response.data)
        }
    });
}

export default {
    isAuth,
    user,
    isMe,
    logout,
    login,
    register,
    isRouteRequireAuth,
    isLoginOrRegister,
    getMyPosts,
    createPost,
    updatePost,
    joinOn,
    getPost,
    deletePost
}
