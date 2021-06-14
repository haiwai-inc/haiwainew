import API from '../../../service/api'

class Account extends API{

// login/singup api
    async login_status(){
        return await this.sendget("account/passport/login_status/");
    }
    
    async signup(form){
        let email = form.email;
        let password = form.password;
        return await this.sendget("account/passport/user_register/?email=" + email + "&password=" + password);
    };
    
    async checkemail(email){
        return await this.sendget('account/passport/user_email_check/?email='+email)
    }

    async checkpassword(password){
        return await this.sendget("account/passport/user_password_check/?password="+password)
    }

    async sendverifymail(id){
        return await this.sendget("account/passport/user_send_verification/?id="+id)
    }

    async login(form){
        let email = form.email;
        let password = form.password;
        return await this.sendget("account/passport/user_login/?login_data=" + email + "&login_token=" + password);
    };

    async wxc_sign_in(form){
        let username = form.username;
        let password = form.password;
        return await this.sendget("account/passport/user_login_wxc/?login_data="+username+"&login_token="+password)
    }

    async google_sign_in(token){
        return await this.sendget("account/passport/user_login_google/?login_token="+token);
    }

    async facebook_sign_in(token){
        return await this.sendget("search/user/facebookLogin/?token="+token);
    }

    async line_sign_in(token){
        return await this.sendget("search/user/lineLogin/?token="+token);
    }

    async apple_sign_in(token){
        return await this.sendget("search/user/appleLogin/?token="+token);
    }
    async logout(){
        return await this.sendget("account/passport/user_logout/");
    }
    async user_password_send_request(email){
        return await this.sendget("account/passport/user_password_send_request/?email="+email)
    }
    async user_password_reset(password,token){
        return await this.sendget("account/passport/user_password_reset/?password="+password+"&token="+token)
    }
// user profile api
    async get_user_profile(){
        return await this.sendget("account/user/user_profile/");
    }

    async user_password_update(password){
        return await this.sendget("account/user/user_password_update/?password="+password)
    }

// user functions api
    async following_add(followingID){
        return await this.sendget("account/user/following_add/?followingID="+followingID);
    }

    async following_delete(followingID){
        return await this.sendget("account/user/following_delete/?followingID="+followingID);
    }

    async upload_avatar(avatar){
        return await this.sendpost("account/user/user_avatar_update/", avatar)
    }

    async upload_background(background){
        return await this.sendpost("blog/user/blogger_background_update/", background);
    }

// base function 
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