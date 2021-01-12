import API from '../../../service/api'

class Account extends API{


    async login_status(){
        return await this.sendget("account/passport/login_status/")
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
  



    signal(message){
        console.log(message)
    }

}

export default new Account();