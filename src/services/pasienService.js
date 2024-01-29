import { http } from "../config/http"

const get = async (url) => {
    const response = await http.get(url)

    return response.data
}
const find = async (url) => {
    return await http.get(url)
}

export default {
    get, find
}