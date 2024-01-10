import { defineStore } from 'pinia'
import axios from 'axios'
import Cookies from 'js-cookie'
import { url } from '@/config/http'
import { http } from '@/config'

// You can name the return value of `defineStore()` anything you want,
// but it's best to use the name of the store and surround it with `use`
// and `Store` (e.g. `useUserStore`, `useCartStore`, `useProductStore`)
// the first argument is a unique id of the store across your application
export const useAuthStore = defineStore('auth', {
    state: () => ({
        cookies: Cookies.get('user'),
        loggedIn: '',
        token: '',
        user: '',
        form: {
            nama: '',
            username: '',
            email: '',
            role: '',
            password: '',
        },
        validate: '',
        returnUrl: '',
    }),
    actions: {
        async login(request) {
            const user = await axios.post(url + '/login/', {
                username: request.username,
                password: request.password,
                device_name: 'web',
            })

            return user
        },

        logout() {
            this.user = null
            this.token = null

            return http.post('/logout')
        },

        removeCookies() {
            return Cookies.remove('user')
        },

        resetValidation() {
            this.validate = ''
        },

        setValidation(validation) {
            this.validate = validation
        },

        resetForm() {
            this.form.email = ''
            this.form.password = ''
        },

        getLoggedIn() {
            this.loggedIn =
                this.cookies == undefined
                    ? false
                    : JSON.parse(this.cookies)?.loggedIn

            return this.loggedIn
        },

        getToken() {
            this.token =
                this.cookies == undefined ? '' : JSON.parse(this.cookies)?.token

            return this.token
        },

        getUser() {
            this.user =
                this.cookies == undefined ? {} : JSON.parse(this.cookies)?.data

            return this.user
        },
    },
})
