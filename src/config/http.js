import axios from "axios";
import Cookies from "js-cookie";
import { useAuthStore } from "@/store/auth";

// const url = 'http://192.168.3.11/dokumen-simrs/backend/api' //WEB APACHE
// const httpWeb = 'http://192.168.3.11/dokumen-simrs/backend' //WEB APACHE
const url = 'http://192.168.3.9/dokumen-simrs/backend/api' //WEB APACHE
const httpWeb = 'http://192.168.3.9/dokumen-simrs/backend' //WEB APACHE
// const baseUrl = "http://localhost:8000/api"; // PHP LIVE SERVER

const token =
    Cookies.get("user") != undefined ? JSON.parse(Cookies.get("user")).token : "";

const authorization = "Bearer " + token;

const http = axios.create({
    baseURL: url,
    headers: {
        Authorization: authorization,
        Accept: "application/json",
    },
    withCredentials: true,
});

http.interceptors.request.use(
    (x) => {
        // to avoid overwriting if another interceptor
        // already defined the same object (meta)
        // console.log(x);

        return x;
    },
    (error) => {
        return Promise.reject(error.message);
    }
);

http.interceptors.response.use(
    (x) => {
        return x;
    },
    (error) => {
        console.log('http-error: ' + error)
        if (error.response.status === 401) {
            useAuthStore().removeCookies();
            window.location.href = "/login";
        }

        return Promise.reject(error);
    }
);

export { http, httpWeb, url };
