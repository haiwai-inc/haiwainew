import API from "../../service/api";

class Search extends API{
    tag={data:{data:[]}};
    article={data:{data:[]}};
    blogger={data:{data:[]}};
    categories={data:{data:[]}};
    tag_articles={data:{data:[]}};
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
    async search_articles(kw,ls){
        return await this.sendget("search/page/articles/?keyword="+kw+"&last_score="+ls)
    }

    /**
     * 用户名博客名搜索
     * @param string keyword        搜索关键词
     * @param number last_score     翻页用，之前页面分数最低的
     * @param string type           内容，可以为blogger, user, all
     * @param number with_article   是否(1/0) 在article页调用
     * */ 
    async search_bloggers(kw,ls,type,wa){
        return await this.sendget("search/page/bloggers/?keyword="+kw+"&last_score="+ls+"&type="+type+"&with_article="+wa)
    }

    /**
     * 文集搜索
     * @param string keyword        搜索关键词
     * @param number last_score     翻页用，之前页面分数最低的
     * */ 
    async search_categories(kw,ls){
        return await this.sendget("search/page/categories/?keyword="+kw+"&last_score="+ls)
    }

    /**
     * 根据标签搜索文章
     * @param string tags        搜索关键词
     * @param number last_id     翻页用，之前结果的最后一个id
     * */ 
    async search_tag_articles(tags,id){
        return await this.sendget("search/page/tag_articles/?tags="+tags+"&last_id="+id)
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