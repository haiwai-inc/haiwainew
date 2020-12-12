import API from '../api'

class Search extends API{
    /**
     * 获取autocomplete列表
     * @param string keyword 
     */
    async getautocomplete(kw){
        return await this.sendget("search/page/tags/?keyword="+kw)
    }

    /**
     * 文章搜索
     * @param string keyword        搜索关键词
     * @param number last_score     翻页用，之前页面分数最低的
     * */ 
    async search_articles(kw){
        return await this.sendget("search/page/articles/?keyword="+kw)
    }

   /**
    * 公用函数 
    */
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

export default Search