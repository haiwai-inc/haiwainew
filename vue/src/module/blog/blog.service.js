import API from "../../service/api"

/**
 * Blog content class
 */
class Blog extends API{
    /**
     * 返回推荐博文列表
     */
    async recommend_article(lastid){
      return await this.sendget("blog/page/recommend_article/?lastid="+lastid);
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
      return await this.sendget("blog/page/hot_article_list/?tagID="+tagID+"&lastid="+lastid);
    }

    /**
     * 返回博主首页最新列表
     */
    async article_list_recent(bloggerID,lastid){
      return await this.sendget("blog/page/article_list_recent/?bloggerID="+bloggerID+"&lastid="+lastid);
    }

    /**
     * 返回博主首页最热列表
     */
    async article_list_hot(bloggerID,lastid){
      return await this.sendget("blog/page/article_list_hot/?bloggerID="+bloggerID+"&lastid="+lastid);
    }
    
    /**
     * 返回博主首页新评列表
     */
    async article_list_comment(bloggerID,lastid){
      return await this.sendget("blog/page/article_list_comment/?bloggerID="+bloggerID+"&lastid="+lastid);
    }

    /**
     * 返回博主文集列表
     * @param number bloggerID
     */
    async category_list(bloggerID){
      return await this.sendget("blog/page/category_list/?bloggerID="+bloggerID);
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
     * 返回正文页评论
     * @param number id 
     */
    async article_view_comment(id){
      return await this.sendget("blog/page/article_view_comment/?id="+id)
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
     * 新建博客文章
     * @param number bloger 
     * @param object data
     */
    async add(bloger,data){
        return await this.sendpost("blog/dashboard/add/?bloger="+bloger,data);
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