import API from "./api"

/**
 * Temp user class
 */
class User extends API{
    username = "username"

    printUsername(){
        console.log(this.username)
    }

    /**
     * Login function for example
     * @param {*} username 
     * @param {*} password 
     */
    async login(username, password){

        try{
            let rs = await this.post("login", {
                username: username,
                password: password
            });
            return rs;
        }
        catch(e){
            console.log(e)
            return false;
        }
    }
}

export default User