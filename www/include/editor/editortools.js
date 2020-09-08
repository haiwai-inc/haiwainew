const center_string = 'display:block; margin-left:auto; margin-right:auto';
const patternList = [
                 	{key:/<img  *"[^"]*"/ig, target:"<img"}
				 ];
const htmlTagList = [
	{key:/<\/p>/ig,target:"</p>"},
	{key:/<\/figure>/ig,target:"</figure>"}
]

var editortools = {
	addWrap2Html:function(string){
		for(var i=0;i<htmlTagList.length;i++){
			string = string.replace(htmlTagList[i].key,htmlTagList[i].target+"\r\n\r\n");
		}
		return string;
	},
	doCorrectImg: function() {
		var code = "";

		if (typeof (CKEDITOR) != 'undefined') {
			code = CKEDITOR.instances.editor1.getData();
		} else {
			code = editor.Instance.GetHTML(true);
		}
		
		for(var i=0;i<patternList.length;i++){
			code = code.replace(patternList[i].key, patternList[i].target);
		}
		
//		for(var pattern of patternList){
//			code = code.replace(pattern.key, pattern.target);
//		}
		
		// if(navigator.userAgent.match(/msie [4-8]/i)){
		if (typeof (CKEDITOR) != 'undefined') {

			CKEDITOR.instances.editor1.setData(code);

		} else {
			editor.Instance.SetHTML(code);
		}
	},
	
	doCheckBeforeChangeMode:function(){
		this.doCorrectImg();
	},
		
	// center all image (looking for better solution)
	doCenterAllImage : function() {
		var code = "";
		var instance;

		if (typeof (CKEDITOR) != 'undefined') {
			instance = CKEDITOR.instances.editor1;

		} else {
			instance = editor.Instance;
		}
		var rule = {
			elements : {
				img : function(element) {
					var style = element.attributes.style;

					if (!style) {
						element.attributes.style = center_string + ";";
					} else {
						if (!style.includes(center_string)) {
							element.attributes.style = element.attributes.style
									+ center_string + ";";
						}
					}
				}
			}
		};

		// make img center using filter rule
		instance.dataProcessor.htmlFilter.addRules(rule);
		instance.updateElement();
		var code = instance.getData();
		instance.setData(code);
		// remove rule so future code won't be affect
		instance.dataProcessor.htmlFilter.elementsRules.img.rules.pop();
	},
	unDoCenterAllImage : function() {
		var code = "";
		var instance;

		if (typeof (CKEDITOR) != 'undefined') {
			instance = CKEDITOR.instances.editor1;

		} else {
			instance = editor.Instance;
		}
		var rule = {
			elements : {
				img : function(element) {
					var style = element.attributes.style;

					if (!style) {
						return;
					} else {
						if (style.includes(center_string)) {
							style = style.replace(center_string + ';', "");
							style = style.replace(center_string, "");
							element.attributes.style = style;
						}
					}
				}
			}
		};

		// make img center using filter rule
		instance.dataProcessor.htmlFilter.addRules(rule);
		instance.updateElement();
		var code = instance.getData();
		instance.setData(code);
		// remove rule so future code won't be affect
		instance.dataProcessor.htmlFilter.elementsRules.img.rules.pop();
	},
	// center selected image (looking for better solution)
	doCenterImage : function() {
		var code = "";
		var instance;
		if (typeof (CKEDITOR) != 'undefined') {
			instance = CKEDITOR.instances.editor1;

		} else {
			instance = editor.Instance;
		}
		// use style to center image
		var style = new CKEDITOR.style({
			element : 'img',
			attributes : {
				style : center_string
			}
		});
		style.apply(instance);
		var code = instance.getData();
		instance.setData(code);
	},
	// center selected image (looking for better solution)
	unDoCenterImage : function() {
		var code = "";
		var instance;
		if (typeof (CKEDITOR) != 'undefined') {
			instance = CKEDITOR.instances.editor1;

		} else {
			instance = editor.Instance;
		}
		// use style to center image
		var style = new CKEDITOR.style({
			element : 'img',
			attributes : {
				style : center_string
			}
		});
		style.remove(instance);
		var code = instance.getData();
		instance.setData(code);
	},
	doadd : function() {
		var code = "";

		if (typeof (CKEDITOR) != 'undefined') {
			code = CKEDITOR.instances.editor1.getData();
		} else {
			code = editor.Instance.GetHTML(true);
		}

		code = code.replace(/<br \/>/ig, "<br /><br />");

		// if(navigator.userAgent.match(/msie [4-8]/i)){
		if (typeof (CKEDITOR) != 'undefined') {

			CKEDITOR.instances.editor1.setData(code);

		} else {
			editor.Instance.SetHTML(code);
		}
	},
	doclear : function() {
		var code = ""

		if (typeof (CKEDITOR) != 'undefined') {
			code = CKEDITOR.instances.editor1.getData();

		} else {
			code = editor.Instance.GetHTML(true);
		}

		code = this.striptag('span', code);
		code = this.striptag('div', code);
		code = this.striptag('p', code);
		code = this.dodel(code);

		if (typeof (CKEDITOR) != 'undefined') {
			CKEDITOR.instances.editor1.setData(code);
		} else {
			editor.Instance.SetHTML(code);
		}
	},
	striptag : function(tag, code) {
		var startRule = new RegExp("<" + tag + "[^>]*" + ">", "ig");
		var endRule = new RegExp("</" + tag + ">", "ig");

		code = code.replace(startRule, "");
		code = code.replace(endRule, "<br />");

		return code;
	},
	dodel : function(code) {
		code = code.replace(/\&nbsp;<br \/>\n/ig, "<br />");
		code = code.replace(/<br \/>\n/ig, "<br />");

		code = code.replace(
				/<br \/><br \/><br \/><br \/><br \/><br \/><br \/><br \/>/ig,
				"<br />");
		code = code
				.replace(/<br \/><br \/><br \/><br \/><br \/><br \/><br \/>/ig,
						"<br />");
		code = code.replace(/<br \/><br \/><br \/><br \/><br \/><br \/>/ig,
				"<br />");
		code = code.replace(/<br \/><br \/><br \/><br \/><br \/>/ig, "<br />");
		code = code.replace(/<br \/><br \/><br \/><br \/>/ig, "<br />");
		code = code.replace(/<br \/><br \/><br \/>/ig, "<br />");
		code = code.replace(/<br \/><br \/>/ig, "<br />");

		code = code.replace(/<br \/>/ig, "<br />\n");

		return code;
	}
}
		
var cke5tools = {
	toggleMode : function(type){
		if(type==0){console.log(html_obj)
			editor_obj.hide();
			html_obj.show();
		}else{
			editor_obj.show();
			html_obj.hide();
		}
	}
}