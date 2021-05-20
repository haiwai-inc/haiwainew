<template>
  <div class="article-page">
    <div class="container">
      <div>
        <main-menu ></main-menu>
      </div>
      <div class="row">
        <div v-if="!articleDetail.status" class="mt-4 pt-4 mx-auto text-center  mb-4 ">
        <h4>{{articleDetail.error}}</h4></div>
        <div class="col-sm-8 col-12" v-if="articleDetail.status">
          
          <div>
            <h4 class="py-3">{{articleDetail.data.postInfo_postID.title}}<span v-if="articleDetail.data.is_publish===0" class="text-muted">（隐）</span></h4>
            <div class="d-flex justify-content-between align-items-center">
              <span class="blogger-box">
                <bloger-list-item :data="articleDetail.data" type="small" @opendialog="$refs.dialog.isLogin()"></bloger-list-item>
              </span>
               
              <div class="media-icons">
                <button type="button" class="btn btn-icon btn-round btn-neutral" title="喜欢" v-if="false">
                  
                </button>
                <button type="button" class="btn btn-icon btn-round btn-neutral" title="喜欢" @click="like()">
                  <span v-if="articleDetail.data.postInfo_postID.is_buzz" style="fill:#39B8EB" v-html="icons.like"></span>
                  <span v-if="!articleDetail.data.postInfo_postID.is_buzz" style=" stroke:#39B8EB" v-html="icons.like_outline"></span>
                </button>
                <button type="button" class="btn btn-icon btn-round btn-neutral" title="收藏" style="fill:#39B8EB" @click="bookmark()">
                  <span v-if="articleDetail.data.postInfo_postID.is_bookmark" v-html="icons.star"></span>
                  <span v-if="!articleDetail.data.postInfo_postID.is_bookmark" v-html="icons.star_outline"></span>
                </button>
                
                <el-popover
                placement="top-end"
                trigger="click"
                v-model="share.showShareBar">
                  <div class="shareIcons">
                    <ShareNetwork
                    v-for="item in shareNetworks"
                    :key="item.network"
                    :network="item.network"
                    :url="shareItem.url"
                    :title="shareItem.title"
                    :description="shareItem.description"
                    quote=""
                    hashtags=""
                    >
                      <span class="shareIcon mr-1" v-html="item.icon" @click="share.showShareBar=false"></span>  
                    </ShareNetwork>
                    <el-popover style="display:inline-block"
                    placement="bottom-end"
                    width="400" 
                    trigger="click"
                    visible-arrow="false"
                    v-model="share.wechatQR">
                      <div class="float-right" @click="share.wechatQR=false">关闭</div>
                      <div class="mt-5">打开微信扫一扫[Scan QR Code]，打开网页后点击屏幕右上角分享按钮</div>
                      <img style="margin: 0 95px;" :src="shareItem.QRcode" alt="">
                      <a href="#" @click="share.showShareBar=false" slot="reference"><span class="shareIcon d-none d-sm-block" v-html="icons.wechat"></span></a>
                    </el-popover>
                  </div>
                    
                  <button type="button" class="btn btn-icon btn-round btn-neutral" title="分享" slot="reference">
                    <span style=" fill:#39B8EB;" v-html="icons.share"></span>
                  </button>
                </el-popover>
              
              </div>
            </div>
            <div class="d-flex align-items-center">
              <div style="color:gray;">{{articleDetail.data.create_date*1000 | formatDate}}</div>
              <div style="color:gray;" class="ml-3">阅读: {{articleDetail.data.countinfo_postID.count_read}}</div>
              <el-button class="ml-3" type="text" icon="el-icon-edit" v-if="articleDetail.data.userID==$store.state.user.userinfo.UserID" style="color:#39b8eb" @click="gotoEditor(articleDetail.data)">编辑</el-button>
              <el-popconfirm  v-if="articleDetail.data.userID==$store.state.user.userinfo.UserID"
                placement="top-end"
                confirm-button-text='删除'
                cancel-button-text='取消'
                title="确定删除这篇文章吗？"
                :hide-icon="true"
                @confirm="article_delete(articleDetail.data)"
              >
                <el-button type="text" icon="el-icon-delete" slot="reference" class="ml-3" style="color:#39b8eb">删除</el-button>
              </el-popconfirm>
            </div>
            <div class="content" v-html="articleDetail.data.postInfo_postID.msgbody">
              <!-- blog 正文 -->
            </div>
            
            <previous-next-bar
              v-if="articleDetail.data.article_previous_next.next ||articleDetail.data.article_previous_next.previous"
             :data="articleDetail.data"></previous-next-bar>
            
          </div>
          <div class="comment" v-if="articleDetail.data.is_comment">
            <textarea type="textarea" v-model="replymsgbody" rows="3" class="w-100 mt-2 p-2" placeholder="写下您的评论..." @keyup="checkstatus"></textarea>
            <n-button 
              type="primary"
              round 
              simple 
              :disabled="replybtndisable" 
              @click="reply_add">发表评论</n-button>
            <h5 class="commentlable">评论（{{articleDetail.data.countinfo_postID.count_comment}}）</h5>
            
          </div>
          <div v-if="!showcomment" class="text-center">评论数据获取失败</div>
          <div v-infinite-scroll="getComment"
          infinite-scroll-disabled="disabled"
          infinite-scroll-distance="50" v-if="comment.length>0">
              <comment 
              v-for="item in comment"
              :key="item.postID"
              :data="item"
              :author="articleDetail.data.userID"
              @regetcomment="rewrite"
              @opendialog="$refs.dialog.isLogin()"
              ></comment>
          </div>
          <div class="text-center py-5" v-if="loading.comment"><!-- loader -->
              <i class="now-ui-icons loader_refresh spin"></i>
          </div>
          <p class="text-center py-4" style="cursor:pointer" v-if="!noMore" @click="getComment">加载更多评论</p>
          <p class="text-center py-4" v-if="noMore">没有更多了</p>
        </div>
        <div class="col-sm-4 d-none d-sm-block" v-if="articleDetail.status">
         <!-- r1 -->
            <div class="box my-3">
              <span class="blogger-box">
                <bloger-list-item :data="articleDetail.data" type="small" @opendialog="$refs.dialog.isLogin()"></bloger-list-item>
              </span>
               <!-- 左边相同样式 -->
               <span v-for="(item,index) in recommend.authorArticle" :key="index">
                 <recommend-list-item :data="item" v-if="index<5"></recommend-list-item>
               </span>
               <div class="justify-content-right border-top d-flex text-right ">
                 <router-link  :to="'/blog/user/'+articleDetail.data.bloggerID">
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
                 </router-link>
               </div>
            </div>
        <!-- help -->
            <div class="box my-3 pl-3">
              <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
              width="28" height="28"
              viewBox="0 0 172 172"
              style=" fill:#000000;"><g transform="translate(4.988,4.988) scale(0.942,0.942)"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="none" stroke-linecap="butt" stroke-linejoin="none" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g id="original-icon 1" fill="#000000" stroke="#cccccc" stroke-width="10" stroke-linejoin="round"><path d="M88.6875,0c33.18433,0 60.33228,26.28711 61.73901,59.125h2.76099c7.41162,0 13.4375,6.02588 13.4375,13.4375v37.625c0,7.40112 -6.02588,13.4375 -13.4375,13.4375v5.375c0,16.29297 -13.25903,29.5625 -29.5625,29.5625h-16.50293c-1.20728,4.61914 -5.375,8.0625 -10.37207,8.0625h-5.375c-5.9314,0 -10.75,-4.8291 -10.75,-10.75c0,-5.9314 4.8186,-10.75 10.75,-10.75h5.375c4.99707,0 9.16479,3.43286 10.37207,8.0625h16.50293c13.34302,0 24.1875,-10.85498 24.1875,-24.1875v-5.375h-3.19141c-1.10229,3.11792 -4.06274,5.375 -7.55859,5.375c-4.45117,0 -8.0625,-3.62183 -8.0625,-8.0625v-59.125c0,-4.45117 3.61133,-8.0625 8.0625,-8.0625c3.49585,0 6.4563,2.24658 7.55859,5.375h0.36743c-0.57739,-12.10425 -4.94458,-23.20068 -11.98877,-32.14502l-12.11474,11.97827c-3.94727,3.89478 -10.09912,4.19922 -14.31934,0.70337c-5.76343,-4.77661 -13.07007,-7.41162 -20.56567,-7.41162c-7.5271,0 -14.85474,2.64551 -20.61816,7.45361c-1.97363,1.6377 -4.36719,2.44604 -6.76074,2.44604c-2.71899,0 -5.44849,-1.0498 -7.5481,-3.10742l-12.16723,-11.94678c-6.9917,8.92334 -11.3169,19.97778 -11.89429,32.02954h0.35693c1.11279,-3.11792 4.07324,-5.375 7.56909,-5.375c4.44067,0 8.0625,3.62183 8.0625,8.0625v59.125c0,4.44067 -3.62183,8.0625 -8.0625,8.0625c-3.49585,0 -6.4563,-2.25708 -7.56909,-5.375h-8.55591c-7.41162,0 -13.4375,-6.02588 -13.4375,-13.4375v-37.625c0,-7.41162 6.02588,-13.4375 13.4375,-13.4375h2.75049c1.41723,-32.83789 28.56518,-59.125 61.74951,-59.125zM42.42261,23.02222l12.41919,12.17773c1.97363,1.94214 5.02857,2.08911 7.09668,0.36743c6.73975,-5.60596 15.28516,-8.69238 24.06152,-8.69238c8.75537,0 17.26929,3.07593 23.98804,8.65039c2.08911,1.71118 5.14404,1.56421 7.11768,-0.38843l12.3667,-12.23022c-10.27759,-10.771 -24.7439,-17.53174 -40.78491,-17.53174h-5.375c-16.09351,0 -30.60181,6.80273 -40.88989,17.64722zM88.6875,13.4375v5.375c0,1.49072 -1.19678,2.6875 -2.6875,2.6875c-1.49072,0 -2.6875,-1.19678 -2.6875,-2.6875v-5.375c0,-1.49072 1.19678,-2.6875 2.6875,-2.6875c1.49072,0 2.6875,1.19678 2.6875,2.6875zM101.65259,12.80762c1.42773,0.38843 2.28857,1.85815 1.90015,3.29639l-1.38574,5.18603c-0.32544,1.20728 -1.41724,1.99463 -2.60352,1.99463c-0.23096,0 -0.46191,-0.03149 -0.70337,-0.08398c-1.42773,-0.38843 -2.27808,-1.86865 -1.90015,-3.29639l1.39624,-5.19653c0.29395,-1.0813 1.19678,-1.82666 2.22559,-1.96313c0.34644,-0.05249 0.71387,-0.03149 1.0708,0.06299zM73.6438,14.71826l1.39624,5.18603c0.37793,1.43823 -0.47241,2.90796 -1.90015,3.29639c-0.24146,0.05249 -0.47241,0.08398 -0.70337,0.08398c-1.19678,0 -2.27808,-0.78735 -2.60352,-1.99463l-1.38574,-5.18603c-0.38843,-1.43823 0.47241,-2.90796 1.90015,-3.29639c0.35693,-0.09448 0.72437,-0.11548 1.0708,-0.06299c1.02881,0.14697 1.93164,0.89233 2.22559,1.97363zM59.42944,19.84131l2.6875,4.64014c0.74536,1.29126 0.31494,2.93945 -0.97632,3.68482c-0.41992,0.24146 -0.88184,0.34643 -1.34375,0.34643c-0.92383,0 -1.82666,-0.47241 -2.32007,-1.34375l-2.6875,-4.64014c-0.74536,-1.29126 -0.31494,-2.92896 0.97632,-3.67432c0.31494,-0.18896 0.66138,-0.30444 1.00781,-0.34643c1.03931,-0.12598 2.11011,0.36743 2.65601,1.33325zM116.23438,18.85449c1.28076,0.74536 1.72168,2.38306 0.97632,3.67432l-2.6875,4.65063c-0.49341,0.86084 -1.39624,1.34375 -2.32007,1.34375c-0.46191,0 -0.92383,-0.11548 -1.34375,-0.35693c-1.29126,-0.74536 -1.72168,-2.39355 -0.97632,-3.67432l2.6875,-4.65063c0.5459,-0.96582 1.6062,-1.45923 2.65601,-1.32276c0.34643,0.04199 0.68237,0.14697 1.00781,0.33594zM32.25,61.8125v59.125c0,1.48022 1.20728,2.6875 2.6875,2.6875c1.48022,0 2.6875,-1.20728 2.6875,-2.6875v-59.125c0,-1.48022 -1.20728,-2.6875 -2.6875,-2.6875c-1.48022,0 -2.6875,1.20728 -2.6875,2.6875zM134.375,61.8125v59.125c0,1.48022 1.20728,2.6875 2.6875,2.6875c1.48022,0 2.6875,-1.20728 2.6875,-2.6875v-59.125c0,-1.48022 -1.20728,-2.6875 -2.6875,-2.6875c-1.48022,0 -2.6875,1.20728 -2.6875,2.6875zM10.75,72.5625v37.625c0,4.44067 3.62183,8.0625 8.0625,8.0625h8.0625v-53.75h-8.0625c-4.44067,0 -8.0625,3.62183 -8.0625,8.0625zM145.125,118.25h8.0625c4.44067,0 8.0625,-3.62183 8.0625,-8.0625v-37.625c0,-4.44067 -3.62183,-8.0625 -8.0625,-8.0625h-8.0625zM86,155.875c0,2.96045 2.41455,5.375 5.375,5.375h5.375c2.96045,0 5.375,-2.41455 5.375,-5.375c0,-2.96045 -2.41455,-5.375 -5.375,-5.375h-5.375c-2.96045,0 -5.375,2.41455 -5.375,5.375z"></path></g><path d="M0,172v-172h172v172z" fill="none" stroke="none" stroke-width="1" stroke-linejoin="miter"></path><g id="original-icon" fill="#000000" stroke="none" stroke-width="1" stroke-linejoin="miter"><path d="M83.3125,0c-33.18433,0 -60.33228,26.28711 -61.74951,59.125h-2.75049c-7.41162,0 -13.4375,6.02588 -13.4375,13.4375v37.625c0,7.41162 6.02588,13.4375 13.4375,13.4375h8.55591c1.11279,3.11792 4.07324,5.375 7.56909,5.375c4.44067,0 8.0625,-3.62183 8.0625,-8.0625v-59.125c0,-4.44067 -3.62183,-8.0625 -8.0625,-8.0625c-3.49585,0 -6.4563,2.25708 -7.56909,5.375h-0.35693c0.57739,-12.05176 4.90259,-23.1062 11.89429,-32.02954l12.16723,11.94678c2.09961,2.05762 4.8291,3.10742 7.5481,3.10742c2.39355,0 4.78711,-0.80835 6.76074,-2.44604c5.76343,-4.80811 13.09107,-7.45361 20.61816,-7.45361c7.49561,0 14.80225,2.63501 20.56567,7.41162c4.22021,3.49585 10.37207,3.19141 14.31934,-0.70337l12.11474,-11.97827c7.04419,8.94434 11.41138,20.04077 11.98877,32.14502h-0.36743c-1.10229,-3.12842 -4.06274,-5.375 -7.55859,-5.375c-4.45117,0 -8.0625,3.61133 -8.0625,8.0625v59.125c0,4.44067 3.61133,8.0625 8.0625,8.0625c3.49585,0 6.4563,-2.25708 7.55859,-5.375h3.19141v5.375c0,13.33252 -10.84448,24.1875 -24.1875,24.1875h-16.50293c-1.20728,-4.62964 -5.375,-8.0625 -10.37207,-8.0625h-5.375c-5.9314,0 -10.75,4.8186 -10.75,10.75c0,5.9209 4.8186,10.75 10.75,10.75h5.375c4.99707,0 9.16479,-3.44336 10.37207,-8.0625h16.50293c16.30347,0 29.5625,-13.26953 29.5625,-29.5625v-5.375c7.41162,0 13.4375,-6.03638 13.4375,-13.4375v-37.625c0,-7.41162 -6.02588,-13.4375 -13.4375,-13.4375h-2.76099c-1.40674,-32.83789 -28.55469,-59.125 -61.73901,-59.125zM83.3125,5.375h5.375c16.04102,0 30.50733,6.76074 40.78491,17.53174l-12.3667,12.23022c-1.97363,1.95264 -5.02857,2.09961 -7.11768,0.38843c-6.71875,-5.57446 -15.23266,-8.65039 -23.98804,-8.65039c-8.77637,0 -17.32178,3.08643 -24.06152,8.69238c-2.06811,1.72168 -5.12305,1.57471 -7.09668,-0.36743l-12.41919,-12.17773c10.28809,-10.84448 24.79639,-17.64722 40.88989,-17.64722zM86,10.75c-1.49072,0 -2.6875,1.19678 -2.6875,2.6875v5.375c0,1.49072 1.19678,2.6875 2.6875,2.6875c1.49072,0 2.6875,-1.19678 2.6875,-2.6875v-5.375c0,-1.49072 -1.19678,-2.6875 -2.6875,-2.6875zM100.58179,12.74463c-1.02881,0.13647 -1.93164,0.88184 -2.22559,1.96313l-1.39624,5.19653c-0.37793,1.42773 0.47241,2.90796 1.90015,3.29639c0.24146,0.05249 0.47241,0.08398 0.70337,0.08398c1.18628,0 2.27808,-0.78735 2.60352,-1.99463l1.38574,-5.18603c0.38843,-1.43823 -0.47241,-2.90796 -1.90015,-3.29639c-0.35693,-0.09448 -0.72436,-0.11548 -1.0708,-0.06299zM71.41821,12.74463c-0.34643,-0.05249 -0.71387,-0.03149 -1.0708,0.06299c-1.42773,0.38843 -2.28857,1.85815 -1.90015,3.29639l1.38574,5.18603c0.32544,1.20728 1.40674,1.99463 2.60352,1.99463c0.23096,0 0.46191,-0.03149 0.70337,-0.08398c1.42773,-0.38843 2.27808,-1.85816 1.90015,-3.29639l-1.39624,-5.18603c-0.29395,-1.0813 -1.19678,-1.82666 -2.22559,-1.97363zM56.77344,18.50806c-0.34643,0.04199 -0.69287,0.15747 -1.00781,0.34643c-1.29126,0.74536 -1.72168,2.38306 -0.97632,3.67432l2.6875,4.64014c0.49341,0.87134 1.39624,1.34375 2.32007,1.34375c0.46191,0 0.92383,-0.10498 1.34375,-0.34643c1.29126,-0.74536 1.72168,-2.39355 0.97632,-3.68482l-2.6875,-4.64014c-0.5459,-0.96582 -1.6167,-1.45923 -2.65601,-1.33325zM115.22656,18.51855c-1.0498,-0.13647 -2.11011,0.35693 -2.65601,1.32276l-2.6875,4.65063c-0.74536,1.28076 -0.31494,2.92896 0.97632,3.67432c0.41992,0.24146 0.88184,0.35693 1.34375,0.35693c0.92383,0 1.82666,-0.48291 2.32007,-1.34375l2.6875,-4.65063c0.74536,-1.29126 0.30445,-2.92896 -0.97632,-3.67432c-0.32544,-0.18896 -0.66138,-0.29395 -1.00781,-0.33594zM34.9375,59.125c1.48022,0 2.6875,1.20728 2.6875,2.6875v59.125c0,1.48022 -1.20728,2.6875 -2.6875,2.6875c-1.48022,0 -2.6875,-1.20728 -2.6875,-2.6875v-59.125c0,-1.48022 1.20728,-2.6875 2.6875,-2.6875zM137.0625,59.125c1.48022,0 2.6875,1.20728 2.6875,2.6875v59.125c0,1.48022 -1.20728,2.6875 -2.6875,2.6875c-1.48022,0 -2.6875,-1.20728 -2.6875,-2.6875v-59.125c0,-1.48022 1.20728,-2.6875 2.6875,-2.6875zM18.8125,64.5h8.0625v53.75h-8.0625c-4.44067,0 -8.0625,-3.62183 -8.0625,-8.0625v-37.625c0,-4.44067 3.62183,-8.0625 8.0625,-8.0625zM145.125,64.5h8.0625c4.44067,0 8.0625,3.62183 8.0625,8.0625v37.625c0,4.44067 -3.62183,8.0625 -8.0625,8.0625h-8.0625zM91.375,150.5h5.375c2.96045,0 5.375,2.41455 5.375,5.375c0,2.96045 -2.41455,5.375 -5.375,5.375h-5.375c-2.96045,0 -5.375,-2.41455 -5.375,-5.375c0,-2.96045 2.41455,-5.375 5.375,-5.375z"></path></g><path d="" fill="none" stroke="none" stroke-width="1" stroke-linejoin="miter"></path><path d="" fill="none" stroke="none" stroke-width="1" stroke-linejoin="miter"></path></g></g></svg>
                <span class="ml-3 mb-3 h6">帮助中心</span>
                <div class=row>
                  <div class="col-6 mt-3 text-secondary">如何上传图片</div>
                  <div class="col-6 mt-3  text-secondary">怎样发视频</div>
                  <div class="col-12 mt-3 text-secondary">如何贴音乐</div>
                </div>
            </div>
            <!-- help -->
          <!-- r2 -->
            <div class="box" v-if="false">
               <div class="title  d-flex justify-content-between">
                  <h5>文集-芳草渡 (56) </h5>
               </div>
               <!-- <span v-for="(item,index) in recommend.collections" :key="index" >
                 <recommend-list-item type="small" :data="item"></recommend-list-item>
               </span> -->
               

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
            <div class="box my-3" v-if="recommend.articles.length>0">
               <div class="title  d-flex justify-content-between">
                  <h5>相关推荐</h5>
                  <!-- <button type="button" class="btn btn-link btn-default" style="padding-right: 0px;" @click="getRecommend()"><i class="now-ui-icons arrows-1_refresh-69"></i> 换一批</button> -->
               </div>
               <span v-for="(item,index) in recommend.articles" :key="index">
                 <recommend-list-item :data="item" v-if="index<10"></recommend-list-item>
               </span>
            </div>
          <!-- r3 end-->
        </div>
      </div>
    </div>
    
      <login-dialog ref="dialog"></login-dialog>
    
  </div>
</template>
<script>
import {formatDate} from '@/directives/formatDate.js';
import MainMenu from '../components/Main/MainMenu';
import BlogerListItem from '../components/Main/BlogerListItem';
import RecommendListItem from '../components/Main/RecommendListItem';
import { Button } from '@/components';
import icons from "@/components/Icons/Icons";
import Comment from './Comment';
import PreviousNextBar from './PreviousNextBar';
import blog from '../../blog.service';
import account from '../../../user/service/account';
import { Popover } from 'element-ui';
import LoginDialog from '../../../user/login/LoginDialog.vue';

export default {
  name: 'article-page',
  components: {
    MainMenu,
    BlogerListItem,
    RecommendListItem,
    Comment,
    PreviousNextBar,
    [Button.name]: Button,
    [Popover.name]:Popover,
    LoginDialog
  },
  mounted: function () {
    this.article_view();
    this.user_login_wxc_to_haiwai(this.token);
  },
  computed:{
    disabled () {
      return this.loading.comment || this.noMore
    }
  },
  methods:{
    article_view(){
      this.showcomment = false;
      let postid = this.$route.params.id ;
      blog.article_view(postid).then(res=>{
        this.articleDetail = res;console.log(res);
        if(res.status){
          let descrip = res.data.postInfo_postID.msgbody
          this.shareItem.title = res.data.postInfo_postID.title;
          this.shareItem.description = descrip.replace(/<[^>]+>/g,"").substr(0,100);
          this.initRecommendProp(res);
          this.getRecentArticle(res,0);
          this.getComment();
        }
      });
    },
    initRecommendProp(res){
      var arr = [];
      res.data.postInfo_postID.tags.forEach(r=>{
        arr.push(r.id)
      });
      this.recommend.props.tags = arr.length>0?arr.toString():''
      this.recommend.props.lastID = 0;
      this.getRecommend()
    },
    getRecommend(){
      let postID = this.$route.params.id;
      blog.article_list_tag(this.recommend.props.tags,postID).then(res=>{
        console.log(res);
        if(res.status){
          let arr = res.data
          this.recommend.articles = arr;
          this.recommend.props.lastID = arr.length>10?arr[9].postID:arr.length!=0?arr[arr.length-1].postid:0;
        }
      })
    },
    getRecentArticle(res,lastID){
      let postID = this.$route.params.id;
      var bloggerID = res.data.bloggerID;
      blog.article_list_recent(bloggerID,lastID,postID).then(res=>{
        console.log(res);
        if(res.status){
          this.recommend.authorArticle = res.data
        }
      })
    },
    reply_add(){
      if(this.$refs.dialog.isLogin()){ //权限判断
        let obj = {
          article_data:{msgbody:this.replymsgbody,
          postID:this.articleDetail.data.postID,
          typeID:1}
        }
        this.replybtndisable = true;
        blog.reply_add(obj).then(res=>{
          this.replybtndisable = false;
          this.getFirst20();
          this.replymsgbody="";
        })
      }
    },
    checkstatus(){
      this.replybtndisable = this.replymsgbody?false:true;
    },
    getFirst20(){
      this.lastID = 0;
      this.comment = [];
      this.getComment();
    },
    getComment(){
      this.loading.comment = true;
      this.noMore = false;
      blog.article_view_comment(this.$route.params.id,this.lastID).then(res=>{
        let r = res.data;
        this.comment = this.comment.concat(r);
        this.lastID = r.length>0?r[r.length-1].postID:0;
        if(r.length<20){
          this.noMore = true;
        }
        this.showcomment=res.status?true:false;
        this.loading.comment = false;
        console.log(this.comment,this.loading.comment,this.noMore,this.lastID)
      })
    },
    rewrite(id){
      console.log("reget",id);
      if(!id){
        this.comment.splice(0,1)
      }
      blog.article_view_comment_one(id).then(res=>{
        this.comment.forEach(obj=>{
          if (obj.postID==id){
            let idx = this.comment.indexOf(obj);
            this.comment.splice(idx,1,res.data) 
          }
        })
      })
    },
    // 喜欢
    like(){
      if(this.$refs.dialog.isLogin()){ //权限判断
        if( this.articleDetail.data.postInfo_postID.is_buzz==0){
          this.buzz_add(this.articleDetail.data);
        }else{
          this.buzz_delete(this.articleDetail.data);
        }
      }
    },
    buzz_add(item){
      blog.buzz_add(item.postID).then(res=>{
        console.log(res);
        if(res.status){
          this.articleDetail.data.postInfo_postID.is_buzz=1
        }
      })
    },
    buzz_delete(item){
      blog.buzz_delete(item.postID).then(res=>{
        console.log(res)
        if(res.status){
          this.articleDetail.data.postInfo_postID.is_buzz=0
        }
      })
    },
    // 收藏
    bookmark(){
      if(this.$refs.dialog.isLogin()){ //权限判断
        this.articleDetail.data.postInfo_postID.is_bookmark?this.bookmark_delete(this.articleDetail.data):this.bookmark_add(this.articleDetail.data)
      }
    },
    bookmark_add(item){
      blog.bookmark_add(item.postID).then(res=>{
        // console.log(res);
        if(res.status)this.articleDetail.data.postInfo_postID.is_bookmark=1;
      })
    },
    bookmark_delete(item){
      blog.bookmark_delete(item.postID).then(res=>{
        // console.log(res);
        if(res.status)this.articleDetail.data.postInfo_postID.is_bookmark=0;
      })
    },
    gotoEditor(item){
      let url = '/blog/my/editor/?postid='+item.postID;
      this.$router.push(url);
    },
    article_delete(item){
      blog.article_delete(item.postID,0).then(res=>{
        if(res.status){
          this.$router.push('/blog/')
        }
      })
    },
    user_login_wxc_to_haiwai(token){
      let user =this.$store.state.user;
      if(this.token && !user.userinfo.id){
        user.user_login_wxc_to_haiwai(token).then(res=>{
            console.log(res);
        })
      }
    }
  },
  data() {
    return {
      showLogin:false,
      icons:icons,
      loginuserID:-1,
      showcomment:false,
      articleDetail: {},
      comment:[],
      replymsgbody:"",
      replybtndisable:true,
      lastID:0,
      noMore:false,
      loading:{comment:false},
      share:{
        showShareBar:false,
        wechatQR:false
      },
      shareItem:{
        url:window.location.href,
        title:'',
        description:'',
        QRcode:'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='+window.location.href
      },
      shareNetworks:[
        {
          network:'facebook',
          icon:icons.facebook
        },{
          network:'twitter',
          icon:icons.twitter
        },{
          network:'line',
          icon:icons.line
        },{
          network:'whatsapp',
          icon:icons.whatsapp
        },{
          network:'weibo',
          icon:icons.weibo
        }

      ],
      recommend:{
        authorArticle:[],
        articles:[],
        props:{
          lastID:0,
          tags:''
        }
      },
      token:this.$route.query.haiwai_token,
    };
  },
   filters: {
    formatDate(time) {
    var date = new Date(time);
    return formatDate(date, 'yyyy-MM-dd hh:mm');
    }
  }
 };
</script>
<style>
.fontsize0 .content{
  font-size: 1.1rem;
}
.fontsize1 .content{
  font-size: 1.3rem;
}
.fontsize2 .content{
  font-size: 1.5rem;
}
/* r1 r2 r3*/
.article-page .box {
        background-color: #f6f9fc;
        border-radius: 16px;
        padding: 10px 0
}

.article-page .title
{
padding: 0 18px;
}
.article-page h5{
  margin-top: 15px;
  font-weight:400
}
.article-page .commentlable{
  border-bottom: #ddd 1px solid;
  padding:10px 0;
  margin-bottom: 12px;
}
.article-page .comment textarea{
  border: #ddd 1px solid ;
}

/* r2 */
.shareIcons a:hover{
  text-decoration: none;
}
.shareIcon svg{
  width:28px;
  height:28px;
}
.article-page h4{
  margin-bottom: 0;
}
.article-page .content{
  padding-top:1rem;
}
.article-page .content p img,
.article-page .content p iframe{
  width:85%;
  margin:0 auto;
  display:block;
}
.article-page .blogger-box{
  width:300px
}
.article-page .media-icons .btn{
  font-size: 1.5rem !important;
  margin:-5px 0 0 0;
}

@media (max-width: 575.98px){
  .article-page .blogger-box{
    width:220px
  }
  .article-page .content p img,
  .article-page .content p iframe{
    width:100%;
  }
}
</style>
