import API from "./api"

/**
 * Temp user class
 */
class User extends API{
    userinfo = {};
    init = this.getUserStatus().then(res=>{
        this.userinfo= res.data;
    });
    published = {};
    notice={};
    myEditor={};
    editorObj=[];
    editorTabStatus=[];
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
     * 获取登录用户profile
     * 
     */
    async user_profile(){
        try{
            let res = await this.get("account/user/user_profile/");
            return res
        }
        catch(e){
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
        let res = await this.sendget("blog/passport/blog_register/");
        if (res.status){
            this.getUserInfo(this.userinfo.id).then(r=>{
                if(r.status){
                    return res
                }
            })
        }
    }

    /**
     * 文学城用户sso 
     * @param {文学城token} haiwai_token
     */
    async user_login_wxc_to_haiwai(token){
        return await this.sendget("account/passport/user_login_wxc_to_haiwai/?haiwai_token="+token);
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
     * 更新用户设置项
     *  
     */
     async user_profile_update(username,description){
        return await this.sendget("account/user/user_profile_update/?username=" + username + "&description=" + description)
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
     * 举报
     * @param {要举报的用户id} userID 
     * @param {举报内容} msgbody
     */
     async report_add(userID,msgbody){
        return await this.sendget("account/user/report_add/?userID="+userID+"&msgbody="+msgbody)
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
        let res = await this.sendget("account/user/notification_unread_count/")
        if(res.status) this.notice=res.data
        return res
    }

    /**
     * 消息未读数清零
     * @param {消息类型} type 
     */
    async notification_unread_clear(type){
        let res = await this.sendget("account/user/notification_unread_clear/?type="+type)
        if(res.status) await this.notification_unread_count()
        return res
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
     * 删除 qqh  
     * @param qqhID
     */
    async qqh_delete(qqhID){
        try{
            let res = await this.get("account/user/qqh_delete/?qqhID="+qqhID);
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
     * @param lastID
     */
    async following_article_list(followingID,lastID){
        return await this.sendget("blog/user/following_article_list/?followingID="+followingID+"&lastID="+lastID)
    }

    /**
     * 返回正文页内容
     * @param number id 
     */
    async article_view(id){
    return await this.sendget("article/user/article_view/?id="+id)
    }

    /**
     * 发布新博客文章
     * @param {title:'文章标题',msgbody:'文章内容',tagname:[tag1,tag2],typeID:1(bolg模块为1)} article_data 
     * @param {add:true,bloggerID:#,categoryID:#} module_data 
     */
    async article_add(data){
        return await this.sendpost('article/user/article_add/',data)
    }
    /**
     * 编辑已发布的文章（已发布文章转草稿）
     * @param {*} id 
     */
    async article_to_draft_by_postID(id){
        return await this.sendget('article/user/article_to_draft_by_postID/?id='+id)
    }
    // article/user/article_update
        
    /**
     * 发布编辑的已发布文章（已发布文章草稿转成发布状态）
     * @param {title:'文章标题',msgbody:'文章内容',tagname:[tag1,tag2],postID:0,typeID:1(bolg模块为1),draftID:0} article_data 
   * @param {edit:true,bloggerID:#,categoryID:#} module_data 
     */
     async article_update(data){
        return await this.sendpost('article/user/article_update/',data)
    }

    /**
     * 返回是否有草稿
     * @param number id - postID
     */
     async draft_check(postID){
        return await this.sendget("article/user/draft_check/?id="+postID)
    }
    /**
     * 返回草稿内容
     * @param number id - postID
     */
     async draft_view(id){
        return await this.sendget("article/user/draft_view/?id="+id)
    }
        
    /**
     * 删除目录中的草稿
     * 
     * @param {postID}
     *          postID
     */
    async draft_delete(postID){
        return await this.sendget('article/user/draft_delete/?id='+postID)
    }

    /**
     * 草稿自动保存
     * @param {title:'文章标题',msgbody:'文章内容',tagname:[tag1,tag2],typeID:1(bolg模块为1),draftID:0} article_data 
   * @param {edit:true,bloggerID:#,categoryID:#} module_data 
     */
     async draft_update(data){
        return await this.sendpost('article/user/draft_update/',data)
    }

    /**
     * 创建草稿
     * @param {title:'文章标题',msgbody:'文章内容',tagname:[tag1,tag2],typeID:1(bolg模块为1),"is_comment":1,} article_data 
   * @param {add:true,bloggerID:#,categoryID:#} module_data 
     */
     async draft_add(data){
        return await this.sendpost('article/user/draft_add/',data)
    }
    
    /**
     * 快速发布草稿（快速发布已发布的文章草稿）
     * @param {*} draftID 
     */
     async draft_to_article_by_draftID(draftID){
        return await this.sendget('article/user/draft_to_article_by_draftID/?draftID='+draftID)
    }

    /**
     * 文章设为私密/公开
     * @param {*} postID
     * @param {val=1 公开; 0 私密} is_publish 
     */
     async article_publish(postID,val){
        return await this.sendget('article/user/article_publish/?postID='+postID+'&is_publish='+val)
    }

    /**
     * 文章禁止/允许评论
     * @param {*} postID
     * @param {val=1 公开; 0 私密} is_comment 
     */
     async article_comment(postID,val){
        return await this.sendget('article/user/article_comment/?postID='+postID+'&is_comment='+val)
    }

    /**
     * 文章禁止/允许转载
     * @param {*} postID
     * @param {val=1 公开; 0 私密} is_share 
     */
     async article_share(postID,val){
        return await this.sendget('article/user/article_share/?postID='+postID+'&is_share='+val)
    }

  /**
	 * 返目录列表(作者)
	 * 
	 * @param number
	 *          $bloggerID
	 */
   async category_list(bloggerID){
    return await this.sendget("blog/user/category_list/?bloggerID="+bloggerID)
  }
    /**
     * 定时发布
     * @param {*} draftID
     * @param {val=1 公开; 0 私密} is_timer
     * @param {time 与现在时间的时间差秒数} time 
     */
     async article_timer(draftID,val){
        return await this.sendget('article/user/article_timer/?draftID='+draftID+'&is_timer='+is_timer+'&time='+time)
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