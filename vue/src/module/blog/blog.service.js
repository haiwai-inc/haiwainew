import API from "../../service/api"

/**
 * Blog content class
 */
class Blog extends API{

  /**
   * 返回推荐博文列表
   */
  async recommend_article(lastid){
    return await this.sendget("blog/page/recommend_article/?lastID="+lastid);
  }

  /**
   * 返回推荐博主列表
   */
  async recommand_blogger(){
    return await this.sendget("blog/page/recommand_blogger/");
  }
  
  /** 
   * 返回热榜标签（分类）
   */
  async hot_tag(){
    return await this.sendget("blog/page/hot_tag/");
  }

  /**
   * 返回热榜博文列表
   */
  async hot_article_list(tagID,lastid){
    return await this.sendget("blog/page/article_list_tag/?tagID="+tagID+"&lastID="+lastid);
  }

  /**
   * 返回博主首页最新列表
   */
  async article_list_recent(bloggerID,lastid){
    return await this.sendget("blog/page/article_list_recent/?bloggerID="+bloggerID+"&lastID="+lastid);
  }

  /**
   * 返回博主首页最热列表
   */
  async article_list_hot(bloggerID,lastid){
    return await this.sendget("blog/page/article_list_hot/?bloggerID="+bloggerID+"&lastID="+lastid);
  }
  
  /**
   * 返回博主首页新评列表
   */
  async article_list_comment(bloggerID,lastid){
    return await this.sendget("blog/page/article_list_comment/?bloggerID="+bloggerID+"&lastID="+lastid);
  }

  /**
   * 返回博主信息
   * @param number bloggerID
   */
  async blogger_info(bloggerID){
    return await this.sendget("blog/page/blogger_info/?bloggerID="+bloggerID);
  }
  
  /**
   * 返回博客文章列表
   * @param number bloger 
   */
  async getArticleList(bloger){
      return await this.sendget("blog/dashboard/getArticleList/?bloger="+bloger)
  }

  /**
   * 返回正文页内容
   * @param number id 
   */
  async article_view(id){
    return await this.sendget("blog/page/article_view/?id="+id)
  }

  /**
   * 返回一个第一级的评论
   * @param number id
   */
  async article_view_comment_one(id){
    return await this.sendget("blog/page/article_view_comment_one/?id="+id)
  }

  /**
   * 返回正文页评论
   * @param number id 
   */
  async article_view_comment(id,lastID){
    return await this.sendget("blog/page/article_view_comment/?id="+id+"&lastID="+lastID)
  }

  /**
   * 发表/回复评论
   * @param obj article_data | post 对象{'msgbody'=>"回复内容",'postID'=>144819,"typeID"=>1,}
   */
  async reply_add(obj){
    return await this.sendpost("article/user/reply_add/",obj)
  }

  /**
   * 点赞
   * @param number postID 
   */
  async buzz_add(postID){
    return await this.sendget("account/user/buzz_add/?postID="+postID)
  }
  
  /**
   * 取消点赞
   * @param postID $postID 
   */
  async buzz_delete(postID){
    return await this.sendget("account/user/buzz_delete/?postID="+postID)
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
   * 返回单个博客文章
   * @param number blogid 
   */
  async getArticle(blogid){
      console.log('calling getArticle here!');
      return await this.sendget("blog/dashboard/getArticle/?id="+blogid)
  }

  /**
   * 
   * @param String  $name:文集名称 
   */
  async category_add(name){
    return await this.sendget("/blog/user/category_add/?name="+name)
  }

  /**
   * 
   * @param String $name:文集名称
   * @param Number id ：文集id
   */
  async category_update(name,id){
    return await this.sendget("/blog/user/category_update/?name="+name+"&id="+id)
  }

  /**
   * 
   * @param Number $id :文集id
   */
  async category_delete(id){
    return await this.sendget("/blog/user/category_delete/?id="+id)
  }

  /**
   * 
   * @param String $name:文集名称 
   */
  async category_name_check(name){
    return await this.sendget("/blog/user/category_name_check/?name="+name)
  }

  /**
   * 返回文集列表
   * @param number $bloggerID 
   */
  async category_list(bloggerID){
    return await this.sendget("blog/page/category_list/?bloggerID="+bloggerID)
  }

  /**
   * 返回某文集的文章列表
   * @param number $id 
   * @param number $lastID
   */
  async category_article_list(id,lastID){
    return await this.sendget("blog/user/article_list/?id="+id+"&lastID="+lastID)
  }

  /**
   * 新建博客文章
   * @param {title:'文章标题',msgbody:'文章内容',tagname:[tag1,tag2],typeID:1(bolg模块为1)} article_data 
   * @param {add:true,bloggerID:#,categoryID:#} module_data 
   */
  async article_add(data){
    return await this.sendpost('article/user/article_add/',data)
  }

  /**
   * 删除文集中的已发布文章
   * @param {文章的postID} postID 
   */
  async article_delete(postID){
    return await this.sendget('article/user/article_delete/?postID='+postID)
  }

  /**
   * 新建文集中的草稿
   * @param {title:'文章标题',msgbody:'文章内容',tagname:[tag1,tag2],typeID:1(bolg模块为1)} article_data 
   * @param {add:true,bloggerID:#,categoryID:#} module_data 
   */
  async draft_add(data){
    return await this.sendpost('article/user/draft_add/?id=',data)
  }

  /**
   * 删除文集中的草稿
   * @param {草稿的id} id 
   */
  async draft_delete(id){
    return await this.sendget('article/user/draft_delete/?id='+id)
  }

  /**
   * 新建博客文章
   * @param number bloger 
   * @param object data
   */
  async add(bloger,data){
      return await this.sendpost("blog/dashboard/add/?bloger="+bloger,data);
  }

  /**
   * 文章置顶
   * @param {文章编号} postID
   * @param {1开 0关} is_sticky 
   */
  async article_sticky(postID,is_sticky){
    return await this.sendget("article/user/article_sticky/?postID="+postID+"&is_sticky="+is_sticky)
  }

  /**
   * 文章移动到某文集
   * @param {文章编号} postID
   * @param {移动到的文集编号} categoryID 
   */
  async article_shift_category(postID,categoryID){
    return await this.sendget("article/user/article_shift_category/?postID="+postID+"&categoryID="+categoryID)
  }

  /**
   * 上传图片
   * @param FormData data 
   * @return url
   * @error false
   */
  async uploadImage(data){

    return await this.sendpost("blog/page/uploadImage/",data);
}

  /**
   * 新建博客自动保存
   * @param number bloger 
   * @param object data
   */
  async autosave(bloger,data){
      return await this.sendpost("blog/dashboard/autosave/?bloger="+bloger,data);
  }

  /**
   * 修改博客文章
   * @param number blogid 
   * @param object data
   */
  async update(blogid,data){
      return await this.sendpost("blog/dashboard/update/?id="+blogid,data);
  }

  /**
   * 删除博客文章
   * @param number blogid 
   */
  async delete(blogid){
      return await this.sendget("blog/dashboard/delete/?id="+blogid)
  }

  /**
   * 上传文件
   * @param string file | base64 of the file
   * @param string type | 文件类型
   */
  async uploadFile(file, type){
    let data = {
      file:file
    }
    return await this.sendpost("search/user/upload_file/?type="+type, data)
  }

  /**
   * 上传图片
   * @param string data | base64 of the file
   */
  async uploadImage(data){
    return await this.uploadFile(data, "pic");
  }

  /**
   * 上传多媒体
   * @param {string} data | base64 of the file
   */
  async uploadMedia(data){
    return await this.uploadFile(data, "media");
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

  testname = 'hello blog'
  i=1
  message(){
      console.log(this.testname)
      console.log(this.i)
      this.i++
  }

}

export default new Blog();

/**
//导出工具类
export default Blog;

//使用vuex进行状态管理的方式调用, 适用于需要在多组件间共享数据的情况

//导入
import Blog from '../../../../service/blog'
let blogModule ={
  state: {
    object: new Blog()
  },
  mutations: {
    increment (state) {
      state.count++
    }
  },
  actions: {

  }
}

  ...
  ,
  beforeCreate(){//注册
    this.$store.registerModule('blog', blogModule)
  },
  methods:{
    test(){//使用
      console.log('hello edit vue');
      this.$store.state.blog.object.message()
    },
  ...

 */