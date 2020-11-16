import API from "./api"

/**
 * Article class
 */
class Article extends API{

    /**
     * Get recommend article list
     */
    async recommend_article(){

        try{
            let res = await this.get("/api/v1/blog/page/recommend_article/");
            return res
        }
        catch(e){
            // console.log(e);
            return false;
        }
    }
    
}

export default Article