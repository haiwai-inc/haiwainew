( function() {
    CKEDITOR.plugins.add( 'DlgVideo',
    {
        requires: [ 'iframedialog' ], 
        
        init: function( editor )
        {
           var iframeWindow = null; //用于获取iframe window 上的控件
           var me = this;
           CKEDITOR.dialog.add( 'DlgVideo', function (){
              return {
                 title : '插入视频',
                 minWidth : 400,
                 minHeight : 260,
                 resizable : CKEDITOR.DIALOG_RESIZE_NONE,
                 contents :
                       [
                          {
                             id : 'iframe',
                             label : '插入视频',
                             elements :
                                   [
                                      {
                                         type : 'iframe',
                                         src : me.path + 'dialogs/fck_video.html',
                                         width : '100%',
                                         height : '260px',
                                         onContentLoad : function() { //获取iframe window
                                            // Iframe is loaded...                                      	 
                                        	 	var iframe = document.getElementById( this._.frameId );                                       	 
                                        	 	iframeWindow = iframe.contentWindow;
                                         }
                                      }
                                   ]
                          }
                       ], //end of contents
              
                 onOk : function(){      
                    // Notify your iframe scripts here...
                	
                		var html = "";               		
                		var w,h,url;
            			url = iframeWindow.document.getElementById("video-url").value;
            			url = iframeWindow.videoCore.getEmbedCode(url);
            			if(!url) return;
            			
            			w = iframeWindow.document.getElementById("video-w").value;
            			if(w==''){
            				w=420;
            			}else{
            				w=parseInt(w);
            				if(w==0)w=420;
            			}
            			
            			h = iframeWindow.document.getElementById("video-h").value;
            			if(h==''){
            				h=360;
            			}else{
            				h=parseInt(h);
            				if(h==0)h=360;
            			}
            			
                		html = '<p><iframe width="'+w+'" height="'+h+'" src="'+url+'" frameborder="0"  scrolling="no" allowfullscreen></iframe></p><br>';
            			
            			editor.insertHtml(html);
            			CKEDITOR.dialog.getCurrent().hide();
                 }
              };
              
           } );
          
           editor.addCommand( 'DlgVideo', new CKEDITOR.dialogCommand( 'DlgVideo' ) );

           editor.ui.addButton( 'DlgVideo',
            {
                label: '视频',
                command: 'DlgVideo',
                icon: this.path + 'vid.gif'
            } );
        } //end of init
    } );
   
} )();