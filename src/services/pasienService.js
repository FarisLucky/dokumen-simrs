import { http } from "../config/http"

const get = async (url) => {
    const response = await http.get(url)

    return response.data
}

export default {
    get
}