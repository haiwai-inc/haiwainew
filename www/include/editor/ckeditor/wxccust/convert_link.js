var editor = CKEDITOR.instances.editor1;
editor.on( 'key', function( evt ){
	//get evt key
	
	//if(key==enter or key==space)
		//getData & convert links;
   //alert(evt.editor.getData());
}, editor.element.$ );

CKEDITOR.instances.editor1.on('contentDom', function() {
    CKEDITOR.instances.editor1.document.on('keyup', function(event) {/*your instructions*/});
  });

editor.on('contentDom', function()
		{
		    editor.document.on('keydown', function( event )
		    {
		        if ( !event.data.$.ctrlKey && !event.data.$.metaKey )
		            somethingChanged();
		    }); 
		});