const baseUrl =  import.meta.env.VITE_API_ENDPOINT
const token = localStorage.getItem("token");

export default {
    baseUrl,
    token,
  };
