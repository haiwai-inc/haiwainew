<template>
  <div class="article-page">
    <div class="container">
      <div>
        <main-menu ></main-menu>
      </div>
      <div class="row">
        <div class="col-sm-8 col-12">
          <h4>{{articleDetail.title}}</h4>
          <div class="d-flex justify-content-between">
            <span class="blogger-box">
              <bloger-list-item :data="authorInfor" type="small"></bloger-list-item>
            </span>
          
          <span class="media-icons">
            <button type="button" class="btn btn-icon btn-round btn-neutral" title="喜欢">
              <i class="now-ui-icons ui-2_favourite-28"></i>
            </button>
            <button type="button" class="btn btn-icon btn-round btn-neutral" title="收藏">
              <i class="now-ui-icons location_bookmark"></i>
            </button>
            <button type="button" class="btn btn-icon btn-round btn-neutral" title="分享">
              <i class="now-ui-icons arrows-1_share-66"></i>
            </button>
          </span>
          </div>
          <div class="content" v-html="articleDetail.content">
            <!-- blog 正文 -->
          </div>
        </div>
        <div class="col-sm-4 d-none d-sm-block">
         <!-- r1 -->
            <div class="box my-3">
              <span class="blogger-box">
              <bloger-list-item :data="authorInfor" type="small"></bloger-list-item>
            </span>
               <!-- 左边相同样式 -->
               <div class="r3 row no-gutters">
                 <div class="align-self-center right col-10 pr-2">
                    <div class="d-flex align-self-center">
                       <div class="name">硅谷传奇——硅的到来：第一家硅谷晶体管创业公司</div>
                    </div>
                    <div class="d-flex justify-content-between my-2">
                       <div class="descrip">阅读 . 10853</div>
                       <div class="descrip">2020.08.07</div>
                    </div>
                 </div>
                 <div class="col-2">
                    <img alt="" src="/img/bg3.jpg" class="rounded">
                 </div>
              </div>
            </div>
          <!-- r2 -->
            <div class="box">
               <div class="title  d-flex justify-content-between">
                  <h5>文集-芳草渡 (56) </h5>
               </div>
               <div class="row r2 no-gutters">
                  <div class="align-self-center right col-12">
                     <div class="name">《芳草渡》下部 《二十七》终归大海作波涛 </div>
                  </div>
               </div>
               <div class="justify-content-right border-top d-flex text-right ">
                  <button type="button" class="btn btn-link btn-default f-right" style="padding-right: 0px;">
                     <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>more</title>
                        <g id="more" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <path d="M11.5740443,6.24199451 L11.5740443,20.2419945 M5,12 L11.5,6 L18,12" id="Arrow" stroke="#6D7278" stroke-width="2" transform="translate(11.500000, 13.120997) rotate(46.000000) translate(-11.500000, -13.120997) "></path>
                           <rect id="Rectangle" stroke="#6D7278" stroke-width="2" x="3" y="3" width="18" height="18" rx="1"></rect>
                        </g>
                     </svg>
                     更多
                  </button>
               </div>
            </div>
          <!-- r3 -->
            <div class="box my-3">
               <div class="title  d-flex justify-content-between">
                  <h5>相关推荐</h5>
                  <button type="button" class="btn btn-link btn-default" style="padding-right: 0px;"><i class="now-ui-icons arrows-1_refresh-69"></i> 换一批</button>
               </div>
               <div class="r3 row no-gutters">
                 <div class="align-self-center right col-10 pr-2">
                    <div class="d-flex align-self-center">
                       <div class="name">老妈的摄影作品 - 麻雀也是鸟</div>
                    </div>
                    <div class="d-flex justify-content-between my-2">
                       <div class="descrip">阅读 . 10853</div>
                       <div class="descrip">2020.08.07</div>
                    </div>
                 </div>
                 <div class="col-2">
                    <img alt="" src="/img/bg3.jpg" class="rounded">
                 </div>
              </div>
            </div>
          <!-- r3 end-->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import MainMenu from '../components/Main/MainMenu';
import BlogerListItem from '../components/Main/BlogerListItem';
export default {
  name: 'article-page',
  bodyClass: 'index-page',
  components: {
    MainMenu,
    BlogerListItem
  },
  data() {
    return {
      authorInfor : {
        avatarUrl:'/img/julie.jpg',
        isHot:true,
        name:'用户名',
        firstLetter:'用',
        description:'简介简介简介简介',
        isFollowed:true,
      },
      articleDetail: {
          articleID:'3456789',
          articleUrl:'',
          title:'标题可以有多长，一部《红楼梦》放得下吗？',
          description:'这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介这里是简介',
          content:'<p><!---->张晓峰第一次被自己感动，第一次感动了这么多人，也第一次被男人们这么 “ 揉捏 ” ，心里有说不出欢畅。这是从刁兵变为乖兵的 “ 处女作 ” 。这乖兵表现也不知能坚持多久，就因为何政委的几句话就能让一个长期捣蛋的人脱胎换骨吗？ 或者这只是暂时的心动，暂时的转变而己？ </p> <p></p> <p>战友们散去了，张晓峰独自坐在树下让自己平静下来。心里总有些不踏实，总有什么东西硌着。 他觉得自己有些表里不一， 因为今天的表现还是有点强装，不是完全自内向外的自然驱动，有表演成份。虽然受何叔一阵痛心的训斥后，他有了痛改前非的决心，但是他自己都不知道自己能守住多久，还会不会再次 “ 出轨 ” 。 </p> <p><img alt="Visualize the Pandemic with R #COVID-19 | by Xinhan Qian | Towards Data  Science" src="https://miro.medium.com/max/3024/1*38qLMmHpLCXIq_s9bdM_Gw.png" ></p> <p></p> <p></p> <p>张晓峰能在全连取得好成绩不是偶然的。还在家的时候他就常跟战士一起玩， 细观战士军事技能训练，有时会忍不住自己动一动模一模。理论加实践再加上时间长了就成了不穿军装的兵了。张晓峰特别爱玩枪，不管长枪短枪还是机枪都爱摆弄， 做起分解组合来就像庄子笔下的庖丁解牛一般流利。每次战士打靶，张晓峰都跟着，求别人赏几发过过瘾， 打完别提有多舒畅。他从小胆子大，敢偷老爸的手枪，还带到学校玩 --- 当然，里面是空的，没子弹。 </p> <p></p> <p>每年的秋天，张晓峰最爱跟父亲一起上山打猎。父亲有三支手枪，一支德国造的佐轮手枪，一支美国造盒子枪，另一支是国产五四式手枪。秋天时父亲带上二三警卫，加上司机和晓峰一同上山打猎， 带上所有家伙开战。父亲总是一马当先， 也给张晓峰佩一支空枪壮胆。警卫们常逗晓峰 : 全副武装，可惜全是空气袋！气得晓峰哇哇叫。父亲会手把手教晓峰怎样打静止物和动态目标。张晓峰学得很认真刻苦。用心学总会有收获。在他父亲指导下，每次他都能打到些野鸡、野兔、野猪回家 --- 当然，那时候还没有野生动物保护法。 </p> <p></p> <p>“打猎大军”回来的时候，大院里的妈妈们早就在操场上支起几口大锅等着改善伙食。别忘了，那可是什么都凭票供应的年代。大军一到， 笑声吵声欢呼声打破了寂寞的夜空。灯火一起旺起来，肉香飘进每人的鼻孔里，馋涎的口水便止不住。酒来了，肉上桌了，一场大会餐开始了。男人们醉得把灯叫星星，女人们笑的蹲地捂腹直不起腰，大孩子小孩子在操场草上摸爬滚打。这时候的张晓峰会被陈勇、何坤山、何蓉蓉等团团围住，像个战斗英雄似的跟他们讲打猎的故事：从点到面，先是夸张、虚拟、生动的把场景再现，然后以真实填补空洞，讲得大伙傻傻的，都成了晓峰的佐餐料。张晓峰吹牛从来不打草稿，只要有点事实基础，添盐加醋是他的拿手好戏。 </p> <p></p> <p></p> <p></p> <p>想到在家的快活时光，张晓峰忍不住笑了。 </p> <p></p> <p>“ 真是孩子，傻呼呼的笑，你笑比哭还难看。 ” 连长人没到话已入耳了。张晓峰站起来，还笑着，调皮地敬了个礼！ </p> <p></p> <p>“ 唉！ 真还舍不得你走。你走了我会寂寞的，没有了对手，没有了 ...... 也罢，去吧！ ”</p> <p></p> <p>张晓峰被搞晕了，这怎么回事啊？ </p> <p></p> <p>“ 刚接到师部调令 : 调张晓峰同志到师警卫连警卫排服役，立即前往报到，不准滞留。 ” 指导员懒洋洋的传达命令。 </p> <p></p> <p></p> <p></p> <p>张晓峰不敢相信这是真的。这，怎么可能，不会是在拿我开心吧两位首长？ 他做梦也没想到，太突然了，没思想准备，一点都没有！ </p> <p></p> <p>“ 你小子走运了。这命令绝对是师长亲自下的，要不然也不会这么急，他总是这样出人预料，不奇怪，我们早就习惯了。 ” 连长说。 </p> <p></p> <p>“ 也是我连的光荣啊！我肯定师长看上你了，要你当警卫员。今后可别忘了我们哟。 ” 指导员深情满满地说着他的结论。 </p> <p></p> <p>张晓峰还是不大敢面对这现实，呆呆的一动不动。 </p> <p></p> <p>“ 别跟木头似的，去准备吧，一会儿跟师长一起走，这是命令。 ” 连长说完后跟指导员一使眼色便转身离去。 </p> <p></p> <p>人在军营，身不由己，聚散离合只在朝夕。张晓峰愣神看着连长指导员的背影，突然神经质般地带着哭声喊了声 : 连长，指导员，敬礼！ </p> <p></p> <p>二位首长默默还了礼，指导员轻声道 :“ 一路平安， 后会有期。我们会记住你的，刁兵 ......”<br><!----><br><!----><!----><br><!---->上集：<a href="http://www.wenxuecity.com/blogview.php?date=201112&amp;postID=28956"><font color="#000099"><font color="#0000cc">刁兵张晓峰 22 抛向空中！</font> </font></a><!----><!----><!----><!----><br><!---->下集：<a href="http://www.wenxuecity.com/blogview.php?date=201201&amp;postID=1782"><font color="#000099">刁兵张晓峰 24 一千个不愿意 </font></a><br><!----><!----><!----><br><!----></p> <table border="0" cellspacing="0" cellpadding="2" width="100%"><tbody> <tr><td valign="top"><li> <a href="http://www.wenxuecity.com/blogview.php?date=201112&amp;postID=27996"><font size="4"><font color="#0033cc">圣诞期间重访生活过的墨区 <!----></font></font></a><br><!----><a href="http://www.wenxuecity.com/blogview.php?date=201112&amp;postID=30025"><font color="#0033cc" size="4">这个芭芭拉，硬是没过圣诞节 </font></a><!----> </li></td></tr> <tr><td valign="top"><li><a href="http://www.wenxuecity.com/blogview.php?date=201112&amp;postID=27026"><font color="#0033cc" size="4">我被十二月的星空迷住 </font></a></li></td></tr> <tr><td valign="top"><li><a href="http://www.wenxuecity.com/blogview.php?date=201112&amp;postID=25254"><font color="#0033cc" size="4">圣诞广州年轻救世军 </font></a></li></td></tr> <tr><td valign="top"><li><a href="http://www.wenxuecity.com/blogview.php?date=201112&amp;postID=22415"><font color="#0033cc" size="4">让这些“人”被地狱永火烧一千年！ </font></a></li></td></tr> <tr><td valign="top"><li><a href="http://www.wenxuecity.com/blogview.php?date=201112&amp;postID=20801"><font color="#0033cc" size="4">倒行逆施一口气长了五磅 </font></a></li></td></tr> </tbody></table> <br><!----><p></p>',
          author:'这里是作者',
          authorID:'123456789',
          isHot:true,
          read:'3456',
          commont:'12',
          likes:'23',
          image:'',
        }
    };
  },
};
</script>
<style>

/* r1 r2 r3*/
.article-page .box {
        background-color: aliceblue;
        border-radius: 16px;
        padding: 10px 0
}
.article-page .r1,.article-page .r2,.article-page .r3 {
        padding: 12px 18px;
        border-top: 1px solid #ddd
}
.article-page .name {
        font-size: 1rem;
        font-weight: 600
}
.article-page .title
{
padding: 0 18px;
}
.article-page h5{
  margin-top: 15px;
  font-weight:400
}
.article-page .descrip{
        font-size:small;
        color: #9A9A9A;
        font-weight: 500
        }
/* r2 */

.article-page h4{
  margin-bottom: 0;
}
.article-page .content{
  padding-top:1rem;
}
.article-page .content img{
  width:85%;
  margin: 0 auto ;
}
.article-page .blogger-box{
  width:300px
}
.article-page .media-icons .btn{
  font-size: 1.5rem !important;
  margin:10px 0 0 0;
}

@media (max-width: 575.98px){
  .article-page .blogger-box{
    width:220px
  }
  .article-page .content img{
  width:100%;
}
}
</style>
