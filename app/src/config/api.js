import axios from "axios/index"

export default axios.create({
    baseURL: process.env.API_URL,
    withCredentials: true
})
