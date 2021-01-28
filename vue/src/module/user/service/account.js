import API from '../../../service/api'

class Account extends API{


    async login_status(){
        return await this.sendget("account/passport/login_status/");
    }
    
    async signup(form){
        let email = form.email;
        let password = form.password;
        return await this.sendget("account/passport/user_register/?email=" + email + "&password=" + password);
    };

    async checkpassword(password){
        return await this.sendget("account/passport/user_password_check/?password="+password)
    }

    async login(form){
        let email = form.email;
        let password = form.password;
        return await this.sendget("account/passport/user_login/?email=" + email + "&password=" + password);
    };

    async wxc_sign_in(form){
        let email = form.email;
        let password = form.password;
        return await this.sendget("account/passport/user_login_wxc?email="+email+"&password="+password)
    }

    async google_sign_in(token){
        return await this.sendget("account/passport/user_login_google/?login_token="+token);
    }

    async facebook_sign_in(token){
        return await this.sendget("search/user/facebookLogin/?token="+token);
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