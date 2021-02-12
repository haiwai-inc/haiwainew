import API from "./api"

/**
 * Temp user class
 */
class User extends API{
    userinfo = {};
    init = this.getUserStatus().then(res=>{
        this.userinfo= res.data;
    });

    /**
     * Login function for example
     * @param {*} email 
     * @param {*} password 
     */
    async signup(form){
        let email = form.email;
        let password = form.password;
        try{
            // let rs = await this.post("login", {
            //     username: username,
            //     password: password
            // });
            let res = await this.get("account/passport/user_register/?email=" + email + "&password=" + password);
            return res;
        }
        catch(e){
            // console.log(e);
            return false;
        }
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
            // console.log(e);
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
            this.userinfo= res.data;
            return res
        }
        catch(e){
            // console.log(e);
            return false;
        }
    }

    /**
     * 
     * 获取用户状态
     */
    async getUserStatus(){
        try{
            let res = await this.get("account/passport/login_status/");
            return res
        }
        catch(e){
            return e;
        }
    }

    /**
     * send qqh 
     * @param userID
     * @param touserID
     * @param msgbody
     */
    async sendQqh(userID,touserID,msgbody){
        try{
            let res = await this.get("account/user/qqh_add/?userID="+userID+"&touserID="+touserID+"&msgbody="+msgbody);
            this.avatar = res.avatar
            return res
        }
        catch(e){
            // console.log(e);
            return false;
        }
    } 
    /**
     * get qqh list 
     */
    async qqh_list(){
        try{
            let res = await this.get("account/user/qqh_list/");
            return res
        }
        catch(e){
            return false;
        }
    }
    /**
     * get qqh view 
     * @param qqhID
     */
    async qqh_view(qqhID){
        try{
            let res = await this.get("account/user/qqh_view/?qqhID="+qqhID);
            return res
        }
        catch(e){
            return false;
        }
    }
    /**
     * 获取小铃铛未读数
     */
    async notification_unread_count(){
        try{
            let res = await this.get("api/v1/account/user/notification_unread_count/");
            return res;
        }
        catch(e){
            return false;
        }
    }
}

export default User