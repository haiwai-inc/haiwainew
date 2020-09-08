( function() {
    CKEDITOR.plugins.add( 'DlgPicture',
    {
        requires: [ 'iframedialog' ], 
        
        init: function( editor )
        {
           var iframeWindow = null; //用于获取iframe window 上的控件
           var me = this;
           
           CKEDITOR.dialog.add( 'DlgPicture', function (){
              return {
                 title : '插入图片',
                 minWidth : 400,
                 minHeight : 260,
                 resizable : CKEDITOR.DIALOG_RESIZE_NONE,
                 contents :
                       [
                          {
                             id : 'iframe',
                             label : '插入图片',
                             elements :
                                   [
                                      {
                                         type : 'iframe',
                                         src : me.path + 'dialogs/fck_picture.html',
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
                	 	var filePathInfo = iframeWindow.document.getElementById('filePathInfo').value;
                	 	var align= iframeWindow.document.getElementById('picalign').value;                		
                		//设置对齐
                		var html = "";               		
                		if(align=='default'){
                			html='<img border="0" src="'+filePathInfo+'">';
                		}else{
                			html='<img border="0" src="'+filePathInfo+'" align="'+align+'">';
                		}
                		
                	 	editor.insertHtml(html);  
                	 	
                 }
              };
              
           } );
          
           editor.addCommand( 'DlgPicture', new CKEDITOR.dialogCommand( 'DlgPicture' ) );

           editor.ui.addButton( 'DlgPicture',
            {
                label: '图片',
                command: 'DlgPicture',
                icon: this.path + 'im.gif'
            } );
        } //end of init
    } );
} )();