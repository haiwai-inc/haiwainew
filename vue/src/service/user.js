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
     * 激活博客
     */
    async blog_register(){
        return await this.sendget("blog/passport/blog_register/");
    }

    /**
     * 获取博客设置项
     *  
     */
    async blogger_profile(){
        return await this.sendget("blog/user/blogger_profile/")
    }
    
    /**
     * 更新博客设置项
     *  
     */
    async blogger_profile_update(name,description){
        return await this.sendget("blog/user/blogger_profile_update/?name=" + name + "&description=" + description)
    }
    
    /**
     * 获取黑名单
     * @param {翻页时需要的参数，为当前列表的最后一个id；初始值为0} lastID 
     */
    async blacklist_list(lastID){
        return await this.sendget("account/user/blacklist_list/?lastID="+lastID)
    }

    /**
     * 添加黑名单
     * @param {要拉黑的用户id} blockID 
     */
    async blacklist_add(blockID){
        return await this.sendget("account/user/blacklist_add/?blockID="+blockID)
    }

    /**
     * 从黑名单中移除
     * @param {要移除的用户id} blockID 
     */
    async blacklist_delete(blockID){
        return await this.sendget("account/user/blacklist_delete/?blockID="+blockID)
    }

    /**
     * 获取我的收藏
     * @param {翻页时需要的参数，为当前列表的最后一个id；初始值为0} lastID 
     */
    async bookmark_list(lastID){
        return await this.sendget("account/user/bookmark_list/?lastID="+lastID)
    }
    
    /**
     * 收藏、加书签
     * @param postID $postID
     */ 
    async bookmark_add(postID){
      return await this.sendget("account/user/bookmark_add/?postID="+postID)
    }
  
    /**
     * 取消收藏、取消书签
     * @param postID $postID
     */ 
    async bookmark_delete(postID){
      return await this.sendget("account/user/bookmark_delete/?postID="+postID)
    }
    /**
     * 获取notice-全部消息
     * @param {翻页时需要的参数，为当前列表的最后一个id；初始值为0} lastID 
     */
    async notification_list(lastID){
        return await this.sendget("account/user/notification_list/?lastID="+lastID)
    }

    /**
     * 获取我收到的评论
     * @param {翻页时需要的参数，为当前列表的最后一个id；初始值为0} lastID 
     */
    async my_comment_list(lastID){
        return await this.sendget("account/user/my_comment_list/?lastID="+lastID)
    }

    /**
     * 获取消息未读数
     *  
     */
    async notification_unread_count(){
        return await this.sendget("account/user/notification_unread_count/")
    }

    /**
     * 消息未读数清零
     * @param {消息类型} type 
     */
    async notification_unread_clear(type){
        return await this.sendget("account/user/notification_unread_clear/?type="+type)
    }

     /**
     * 获取我的粉丝列表
     * @param {翻页时需要的参数，为当前列表的最后一个id；初始值为0} lastID 
     */
    async my_follower_list(lastID){
        return await this.sendget("account/user/my_follower_list/?lastID="+lastID)
    }

    /**
     * 获取我的粉丝列表
     * @param {翻页时需要的参数，为当前列表的最后一个id；初始值为0} lastID 
     */
    async my_buzz_article_list(lastID){
        return await this.sendget("account/user/my_buzz_article_list/?lastID="+lastID)
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
     * 获取我关注的人
     * @param {分页参数} lastID 
     */
    async my_followering_list(lastID){
        return await this.sendget("blog/user/my_followering_list/?lastID="+lastID)
    }

    /**
     * 获取我关注的人的文章列表
     * @param {被我关注人的id，如果为0返回所有被关注者新文章} followingID 
     */
    async following_article_list(followingID){
        return await this.sendget("blog/user/following_article_list/?followingID="+followingID)
    }

    /**
     * 返回正文页内容
     * @param number id 
     */
    async article_view(id){
    return await this.sendget("article/user/article_view/?id="+id)
    }

    /**
     * 返回草稿内容
     * @param number id 
     */
    async draft_view(id){
    return await this.sendget("article/user/draft_view/?id="+id)
    }

    // 管理员相关函数
    /**
     * 获取文章列表
     * @param lastID {列表最后一条id，用于分页；缺省为0}
     * */ 
    async admin_article_list(lastID){
        return await this.sendget("blog/admin/article_list/?lastID="+lastID);
    }

    /**
     * 管理员推荐文章
     * @param postID {推荐文章的postID}
     * */ 
    async article_recommand_add(postID){
        return await this.sendget("blog/admin/article_recommand_add/?lastID="+postID);
    }

    /**
     * 管理员取消推荐文章
     * @param postID {推荐文章的postID}
     * */ 
    async article_recommand_delete(postID){
        return await this.sendget("blog/admin/article_recommand_delete/?postID="+postID);
    }

    /**
     * 管理员修改文章标题
     * @param postID {推荐文章的postID}
     * @param title {标题文本}
     * */ 
    async article_recommand_update(postID,title){
        return await this.sendget("blog/admin/article_recommand_update/?postID="+postID+"&title="+title);
    }

// 公用函数
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

export default User