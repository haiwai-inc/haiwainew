import API from '../../../service/api'

class Account extends API{


    async login_status(){
        return await this.sendget("account/passport/login_status/");
    }

    async logout(){
        return await this.sendget("account/passport/user_logout/");
    }

    async follower_add(followerID){
        return await this.sendget("account/user/follower_add/?followerID="+followerID);
    }

    async follower_delete(followerID){
        return await this.sendget("account/user/follower_delete/?followerID="+followerID);
    }

    async google_sign_in(token){
        return await this.sendget("account/passport/user_login_google/?login_token="+token);
    }

    signal(message){
        console.log(message)
    }

    async facebook_sign_in(token){
        return await this.sendget("search/user/facebookLogin/?token="+token);
    }




    async sendget(url){
        try{
            let rs = await this.get(url);
            return rs;
        }
        catch(e){
            console.log(e)
            return false;
        }
    }
  
    async sendpost(url,data){
        try{
            let rs = await this.post(url,data);
            return rs;
        }
        catch(e){
            console.log(e)
            return false;
        }
    }
  
}

export default new Account();