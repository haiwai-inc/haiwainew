<template>
    <div class="article-menu">
        <div class="article-menub">
            <a class="text-black" href="javascript:void(0)" @click="go(data.article_previous_next.previous.postID)" v-if="data.article_previous_next.previous.postID">
                <i>
                    <svg viewBox="64 64 896 896" focusable="false"  width="1em" height="1em">
                        <path d="M724 218.3V141c0-6.7-7.7-10.4-12.9-6.3L260.3 486.8a31.86 31.86 0 0 0 0 50.3l450.8 352.1c5.3 4.1 12.9.4 12.9-6.3v-77.3c0-4.9-2.3-9.6-6.1-12.6l-360-281 360-281.1c3.8-3 6.1-7.7 6.1-12.6z"></path>
                    </svg>
                </i>
                <span>上一篇</span>
            </a>
        </div>
        <div class="article-menub article-menut " role="button" @click="gocategory(data.article_previous_next.category.id)">{{data.article_previous_next.category.name}}</div>
        <div class="article-menub">
            <a class="text-black" href="javascript:void(0)" @click="go(data.article_previous_next.next.postID)" v-if="data.article_previous_next.next.postID">
                <span>下一篇</span>
                <i>
                    <svg viewBox="64 64 896 896" width="1em" height="1em" >
                        <path d="M765.7 486.8L314.9 134.7A7.97 7.97 0 0 0 302 141v77.3c0 4.9 2.3 9.6 6.1 12.6l360 281.1-360 281.1c-3.9 3-6.1 7.7-6.1 12.6V883c0 6.7 7.7 10.4 12.9 6.3l450.8-352.1a31.96 31.96 0 0 0 0-50.4z"></path>
                    </svg>
                </i>
            </a>
        </div>
    </div>
</template>
<script>
export default {
    name:'previous-next-bar',
    props:{
        data:{}
    },
    created:()=>{
        // console.log(this.data)
    },
    methods:{
        go(id){
            let url='/blog/p/'+id;
            this.$router.push(url);
            this.$router.go(url);
        },
        gocategory(id){
            let url = '/blog/c/'+this.data.bloggerID+"/"+id;
            this.$router.push(url);
        }
    }
}
</script>
<style>

/* menu */
.article-menu {
        display: flex;
        align-items: center;
        margin: 30px 0;
        line-height: 20px;
        border-radius: 30px;
        background-color: #f2f2f2;
}
.article-menub {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 33.33%;
        height: 60px;
        font-size: 14px;
        font-weight: bold;
}
.article-menut {
        height: 20px;
        color: #8c8c8c;
        font-weight: normal;
        border-left: 1px solid #eee;
        border-right: 1px solid #eee;
        cursor: pointer;
}

</style>