var editor={
		Instance: null,
		
		OnComplete:function(editorInstance){
			
			this.Instance = editorInstance;
		},

		htmlMode:function(){		
			this.Instance.setMode( 'source' ); 
			var id = this.Instance.id;
			$("#"+id+"_top").hide();
			
			return;
		},
		
		editorMode:function(){	
			this.Instance.setMode( 'wysiwyg' ); 
			var id = this.Instance.id;
			$("#"+id+"_top").show();
			
			return;
		},
		
};
