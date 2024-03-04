import { http } from "@/config/http"

class HistoryService {

    http
    
    constructor(http) {
        this.http = http
    }

    async all(page, perPage) {
        try {
            
            const {data} = await this.http.get(`/history-dokumens/by-mr?page=${page}&per_page=${perPage}`)

            return [null, data] 
        } catch (error) {
            return [error]
        }
    }
    
}

export const historyService = new HistoryService(http)