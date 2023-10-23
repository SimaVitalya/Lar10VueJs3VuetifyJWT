import axios from "axios";
import router from "@/router/router.js";

const api = axios.create();
//старт запроса
api.interceptors.request.use(config => {

    if (localStorage.getItem('access_token')) {

        config.headers.authorization = `Bearer ${localStorage.getItem('access_token')}`

    }
    return config
}, error => {
    console.log()
});
//конец запроса
//начало ответа
api.interceptors.response.use(config => {
    if (localStorage.getItem('access_token')) {

        config.headers.authorization = `Bearer ${localStorage.getItem('access_token')}`

    }
    return config
}, error => {
    if (error.response.data.message === 'Token has expired') {
        return axios.post('/auth/refresh', {}, {
            headers: {
                'authorization': `Bearer ${localStorage.getItem('access_token')}`
            }
        }).then(res => {
            localStorage.setItem('access_token',res.data.access_token)
            error.confirm.headers.authorization = `Bearer ${res.data.access_token}`
            return api.request(error.confirm)
        })

    }
    if (error.response.status === 401) {
      router.push({name: 'user.login'})
      }


});
export default api;
