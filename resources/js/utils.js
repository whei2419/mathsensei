const baseUrl =  import.meta.env.VITE_API_ENDPOINT
const token = localStorage.getItem("token");

const logout = function(){
    axios({
        method: "post",
        url: `${baseUrl}/api/logout`,
        headers: {
            Authorization: `Bearer ${token}`,
        },
    }).then((res) => {
        localStorage.removeItem("token");
        this.$router.push('/');
    });
}


export default {
    baseUrl,
    token,
    logout
  };
