( function() {
    CKEDITOR.plugins.add( 'DlgMp3',
    {
        requires: [ 'iframedialog' ], 
        
        init: function( editor )
        {
           var iframeWindow = null; //用于获取iframe window 上的控件
           var me = this;
           CKEDITOR.dialog.add( 'DlgMp3', function (){
              return {
                 title : '插入音乐',
                 minWidth : 600,
                 minHeight : 150,
                 resizable : CKEDITOR.DIALOG_RESIZE_NONE,
                 contents :
                       [
                          {
                             id : 'iframe',
                             label : '插入音乐',
                             elements :
                                   [
                                      {
                                         type : 'iframe',
                                         src : me.path + 'dialogs/fck_music.html',
                                         width : '100%',
                                         height : '150px',
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
            			
            			var url = iframeWindow.document.getElementById("filePathInfo").value;
            			var autostart = "yes";
            			var ck_auto = iframeWindow.document.getElementById("autostart");
            			if(!ck_auto.checked){
            				autostart = 'no';
            			}         			
            			var html = '<p><iframe width="100%" height="60px" valign="center"  clear="both" frameborder="0" scrolling="no" src="/include/editor/api/script/audio_ckeditor.php?url='+ url +'&autostart='+autostart+'"></iframe></p><br>';
            			editor.insertHtml(html); 
            			CKEDITOR.dialog.getCurrent().hide();
                 }
              };
              
           } );
          
           editor.addCommand( 'DlgMp3', new CKEDITOR.dialogCommand( 'DlgMp3' ) );

           editor.ui.addButton( 'DlgMp3',
            {
                label: '音乐',
                command: 'DlgMp3',
                icon: this.path + 'mus.gif'
            } );
        } //end of init
    } );
   
} )();