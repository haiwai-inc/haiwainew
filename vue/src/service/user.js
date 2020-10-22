import API from "./api"

/**
 * Temp user class
 */
class User extends API{
    username = "testUser"
    avatar = ""
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
    /**
     * get user infor
     * @param userID
     */
    async getUserInfo(userID){
        try{
            let res = await this.get("account/passport/login_status/?userID="+userID);
            this.avatar = res.data.data.avatar
            return res
        }
        catch(e){
            console.log(e);
            return false;
        }
    }
}

export default User