import axios from 'axios'

class API {

    apiPrefix = "/api/v1/"
    get(url){
        return new Promise((resolve, reject) =>{
            axios({
                method: "get",
                url: this.apiPrefix+url, 
              })
                .then(response => {
                    resolve(response.data)       
                })
                .catch(error => reject(error));
        });
    }

    post(url, data){
        return new Promise((resolve, reject) =>{
            axios({
                method: "post",
                url: this.apiPrefix+url, 
                data:data
              })
                .then(response => {
                    resolve(response.data)
                })
                .catch(error => reject(error));
        });
    }
}

export default API