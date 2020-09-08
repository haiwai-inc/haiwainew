/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2009 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * Editor configuration settings.
 *
 * Follow this link for more information:
 * http://docs.fckeditor.net/FCKeditor_2.x/Developers_Guide/Configuration/Configuration_Options
 */

FCKConfig.CustomConfigurationsPath = '' ;

FCKConfig.EditorAreaCSS = FCKConfig.BasePath + 'css/fck_editorarea.css' ;
FCKConfig.EditorAreaStyles = '' ;
FCKConfig.ToolbarComboPreviewCSS = '' ;

FCKConfig.DocType = '' ;

FCKConfig.BaseHref = '' ;

FCKConfig.FullPage = false ;

// The following option determines whether the "Show Blocks" feature is enabled or not at startup.
FCKConfig.StartupShowBlocks = false ;

FCKConfig.Debug = false ;
FCKConfig.AllowQueryStringDebug = true ;

FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/default/' ;
FCKConfig.SkinEditorCSS = '' ;	// FCKConfig.SkinPath + "|<minified css>" ;
FCKConfig.SkinDialogCSS = '' ;	// FCKConfig.SkinPath + "|<minified css>" ;

FCKConfig.PreloadImages = [ FCKConfig.SkinPath + 'images/toolbar.start.gif', FCKConfig.SkinPath + 'images/toolbar.buttonarrow.gif' ] ;

FCKConfig.PluginsPath = FCKConfig.BasePath + 'plugins/' ;

// FCKConfig.Plugins.Add( 'autogrow' ) ;
// FCKConfig.Plugins.Add( 'dragresizetable' );
FCKConfig.AutoGrowMax = 400 ;

// FCKConfig.ProtectedSource.Add( /<%[\s\S]*?%>/g ) ;	// ASP style server side code <%...%>
// FCKConfig.ProtectedSource.Add( /<\?[\s\S]*?\?>/g ) ;	// PHP style server side code
// FCKConfig.ProtectedSource.Add( /(<asp:[^\>]+>[\s|\S]*?<\/asp:[^\>]+>)|(<asp:[^\>]+\/>)/gi ) ;	// ASP.Net style tags <asp:control>
FCKConfig.ProtectedSource.RegexEntries=[/<!--[\s\S]*?-->/g];

FCKConfig.AutoDetectLanguage	= true ;
FCKConfig.DefaultLanguage		= 'en' ;
FCKConfig.ContentLangDirection	= 'ltr' ;

FCKConfig.ProcessHTMLEntities	= true ;
FCKConfig.IncludeLatinEntities	= true ;
FCKConfig.IncludeGreekEntities	= true ;

FCKConfig.ProcessNumericEntities = false ;

FCKConfig.AdditionalNumericEntities = ''  ;		// Single Quote: "'"

FCKConfig.FillEmptyBlocks	= true ;

FCKConfig.FormatSource		= true ;
FCKConfig.FormatOutput		= true ;
FCKConfig.FormatIndentator	= '    ' ;

FCKConfig.EMailProtection = 'none' ; // none | encode | function
FCKConfig.EMailProtectionFunction = 'mt(NAME,DOMAIN,SUBJECT,BODY)' ;

FCKConfig.StartupFocus	= false ;
FCKConfig.ForcePasteAsPlainText	= false ;
FCKConfig.AutoDetectPasteFromWord = true ;	// IE only.
FCKConfig.ShowDropDialog = true ;
FCKConfig.ForceSimpleAmpersand	= false ;
FCKConfig.TabSpaces		= 0 ;
FCKConfig.ShowBorders	= true ;
FCKConfig.SourcePopup	= false ;
FCKConfig.ToolbarStartExpanded	= true ;
FCKConfig.ToolbarCanCollapse	= true ;
FCKConfig.IgnoreEmptyParagraphValue = true ;
FCKConfig.FloatingPanelsZIndex = 10000 ;
FCKConfig.HtmlEncodeOutput = false ;

FCKConfig.TemplateReplaceAll = true ;
FCKConfig.TemplateReplaceCheckbox = true ;

FCKConfig.ToolbarLocation = 'In' ;

FCKConfig.ToolbarSets["Default"] = [
	['Source','DocProps','-','Save','NewPage','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','Rule','Smiley','SpecialChar','PageBreak'],
	'/',
	['Style','FontFormat','FontName','FontSize'],
	['TextColor','BGColor'],
	['FitWindow','ShowBlocks','-','About']		// No comma for the last row.
] ;

FCKConfig.ToolbarSets["Basic"] = [
	['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','-','About']
] ;

FCKConfig.ToolbarSets["Blog_basic"] = [										  
	['Bold','Italic','JustifyLeft','JustifyCenter','JustifyRight','TextColor','Undo','Redo','RemoveFormat','-','OrderedList','UnorderedList','Image','Flash','Table','Link','Unlink','-','FitWindow','Smiley']
] ;

FCKConfig.EnterMode = 'p' ;			// p | div | br
FCKConfig.ShiftEnterMode = 'br' ;	// p | div | br

FCKConfig.Keystrokes = [
	[ CTRL + 65 /*A*/, true ],
	[ CTRL + 67 /*C*/, true ],
	[ CTRL + 70 /*F*/, true ],
	[ CTRL + 83 /*S*/, true ],
	[ CTRL + 84 /*T*/, true ],
	[ CTRL + 88 /*X*/, true ],
	[ CTRL + 86 /*V*/, 'Paste' ],
	[ CTRL + 45 /*INS*/, true ],
	[ SHIFT + 45 /*INS*/, 'Paste' ],
	[ CTRL + 88 /*X*/, 'Cut' ],
	[ SHIFT + 46 /*DEL*/, 'Cut' ],
	[ CTRL + 90 /*Z*/, 'Undo' ],
	[ CTRL + 89 /*Y*/, 'Redo' ],
	[ CTRL + SHIFT + 90 /*Z*/, 'Redo' ],
	[ CTRL + 76 /*L*/, 'Link' ],
	[ CTRL + 66 /*B*/, 'Bold' ],
	[ CTRL + 73 /*I*/, 'Italic' ],
	[ CTRL + 85 /*U*/, 'Underline' ],
	[ CTRL + SHIFT + 83 /*S*/, 'Save' ],
	[ CTRL + ALT + 13 /*ENTER*/, 'FitWindow' ],
	[ SHIFT + 32 /*SPACE*/, 'Nbsp' ]
] ;

FCKConfig.ContextMenu = ['Generic','Link','Anchor','Image','Flash','Select','Textarea','Checkbox','Radio','TextField','HiddenField','ImageButton','Button','BulletedList','NumberedList','Table','Form','DivContainer'] ;
FCKConfig.BrowserContextMenuOnCtrl = false ;
FCKConfig.BrowserContextMenu = false ;


FCKConfig.FaceBaseDir = '/images/editor/icon';//'/include/editor/api/skin/face/';
FCKConfig.FaceLists = '001,002,003,004,005,006,007,008,009,010,011,012,013,014,015,016,017,018,019,020,021,022,023,024,025,026,027,028,029,030';
FCKConfig.FaceMsgLists = '微笑,鬼脸,嘻嘻,哈哈,呵呵,得意,加油,服了,财迷,哭,小点声,什么,恨,汗,急,解释,闭嘴,病,晕,睡,怒,鄙视,鼓掌,握手,OK,电话,邮件,元宝,时间,夜深了';

FCKConfig.EnableMoreFontColors = true ;
FCKConfig.FontColors = '000000,993300,333300,003300,003366,000080,333399,333333,800000,FF6600,808000,808080,008080,0000FF,666699,808080,FF0000,FF9900,99CC00,339966,33CCCC,3366FF,800080,999999,FF00FF,FFCC00,FFFF00,00FF00,00FFFF,00CCFF,993366,C0C0C0,FF99CC,FFCC99,FFFF99,CCFFCC,CCFFFF,99CCFF,CC99FF,FFFFFF' ;

FCKConfig.FontFormats	= 'p;h1;h2;h3;h4;h5;h6;pre;address;div' ;
FCKConfig.FontNames		= 'Arial;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana' ;
FCKConfig.FontSizes		= 'smaller;larger;xx-small;x-small;small;medium;large;x-large;xx-large' ;

FCKConfig.StylesXmlPath		= FCKConfig.EditorPath + 'fckstyles.xml' ;
FCKConfig.TemplatesXmlPath	= FCKConfig.EditorPath + 'fcktemplates.xml' ;

FCKConfig.SpellChecker			= 'WSC' ;	// 'WSC' | 'SCAYT' | 'SpellerPages' | 'ieSpell'
FCKConfig.IeSpellDownloadUrl	= 'http://www.iespell.com/download.php' ;
FCKConfig.SpellerPagesServerScript = 'server-scripts/spellchecker.php' ;	// Available extension: .php .cfm .pl
FCKConfig.FirefoxSpellChecker	= false ;

FCKConfig.MaxUndoLevels = 15 ;

FCKConfig.DisableObjectResizing = false ;
FCKConfig.DisableFFTableHandles = true ;

FCKConfig.LinkDlgHideTarget		= false ;
FCKConfig.LinkDlgHideAdvanced	= false ;

FCKConfig.ImageDlgHideLink		= false ;
FCKConfig.ImageDlgHideAdvanced	= false ;

FCKConfig.FlashDlgHideAdvanced	= false ;

FCKConfig.ProtectedTags = 'script' ;

// This will be applied to the body element of the editor
FCKConfig.BodyId = '' ;
FCKConfig.BodyClass = '' ;

FCKConfig.DefaultStyleLabel = '' ;
FCKConfig.DefaultFontFormatLabel = '' ;
FCKConfig.DefaultFontLabel = '' ;
FCKConfig.DefaultFontSizeLabel = '' ;

FCKConfig.DefaultLinkTarget = '' ;

// The option switches between trying to keep the html structure or do the changes so the content looks like it was in Word
FCKConfig.CleanWordKeepsStructure = false ;

// Only inline elements are valid.
FCKConfig.RemoveFormatTags = 'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var' ;

// Attributes that will be removed
FCKConfig.RemoveAttributes = 'class,style,lang,width,height,align,hspace,valign' ;

FCKConfig.CustomStyles =
{
	'Red Title'	: { Element : 'h3', Styles : { 'color' : 'Red' } }
};

// Do not add, rename or remove styles here. Only apply definition changes.
FCKConfig.CoreStyles =
{
	// Basic Inline Styles.
	'Bold'			: { Element : 'strong', Overrides : 'b' },
	'Italic'		: { Element : 'em', Overrides : 'i' },
	'Underline'		: { Element : 'u' },
	'StrikeThrough'	: { Element : 'strike' },
	'Subscript'		: { Element : 'sub' },
	'Superscript'	: { Element : 'sup' },

	// Basic Block Styles (Font Format Combo).
	'p'				: { Element : 'p' },
	'div'			: { Element : 'div' },
	'pre'			: { Element : 'pre' },
	'address'		: { Element : 'address' },
	'h1'			: { Element : 'h1' },
	'h2'			: { Element : 'h2' },
	'h3'			: { Element : 'h3' },
	'h4'			: { Element : 'h4' },
	'h5'			: { Element : 'h5' },
	'h6'			: { Element : 'h6' },

	// Other formatting features.
	'FontFace' :
	{
		Element		: 'span',
		Styles		: { 'font-family' : '#("Font")' },
		Overrides	: [ { Element : 'font', Attributes : { 'face' : null } } ]
	},

	'Size' :
	{
		Element		: 'span',
		Styles		: { 'font-size' : '#("Size","fontSize")' },
		Overrides	: [ { Element : 'font', Attributes : { 'size' : null } } ]
	},

	'Color' :
	{
		Element		: 'span',
		Styles		: { 'color' : '#("Color","color")' },
		Overrides	: [ { Element : 'font', Attributes : { 'color' : null } } ]
	},

	'BackColor'		: { Element : 'span', Styles : { 'background-color' : '#("Color","color")' } },

	'SelectionHighlight' : { Element : 'span', Styles : { 'background-color' : 'navy', 'color' : 'white' } }
};

// The distance of an indentation step.
FCKConfig.IndentLength = 40 ;
FCKConfig.IndentUnit = 'px' ;

// Alternatively, FCKeditor allows the use of CSS classes for block indentation.
// This overrides the IndentLength/IndentUnit settings.
FCKConfig.IndentClasses = [] ;

// [ Left, Center, Right, Justified ]
FCKConfig.JustifyClasses = [] ;

// The following value defines which File Browser connector and Quick Upload
// "uploader" to use. It is valid for the default implementaion and it is here
// just to make this configuration file cleaner.
// It is not possible to change this value using an external file or even
// inline when creating the editor instance. In that cases you must set the
// values of LinkBrowserURL, ImageBrowserURL and so on.
// Custom implementations should just ignore it.
var _FileBrowserLanguage	= 'php' ;	// asp | aspx | cfm | lasso | perl | php | py
var _QuickUploadLanguage	= 'php' ;	// asp | aspx | cfm | lasso | perl | php | py

// Don't care about the following two lines. It just calculates the correct connector
// extension to use for the default File Browser (Perl uses "cgi").
var _FileBrowserExtension = _FileBrowserLanguage == 'perl' ? 'cgi' : _FileBrowserLanguage ;
var _QuickUploadExtension = _QuickUploadLanguage == 'perl' ? 'cgi' : _QuickUploadLanguage ;

FCKConfig.LinkBrowser = false ;
FCKConfig.LinkBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Connector=' + encodeURIComponent( FCKConfig.BasePath + 'filemanager/connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension ) ;
FCKConfig.LinkBrowserWindowWidth	= FCKConfig.ScreenWidth * 0.7 ;		// 70%
FCKConfig.LinkBrowserWindowHeight	= FCKConfig.ScreenHeight * 0.7 ;	// 70%

FCKConfig.ImageBrowser = false ;
FCKConfig.ImageBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Image&Connector=' + encodeURIComponent( FCKConfig.BasePath + 'filemanager/connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension ) ;
FCKConfig.ImageBrowserWindowWidth  = FCKConfig.ScreenWidth * 0.7 ;	// 70% ;
FCKConfig.ImageBrowserWindowHeight = FCKConfig.ScreenHeight * 0.7 ;	// 70% ;

FCKConfig.FlashBrowser = false ;
FCKConfig.FlashBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Flash&Connector=' + encodeURIComponent( FCKConfig.BasePath + 'filemanager/connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension ) ;
FCKConfig.FlashBrowserWindowWidth  = FCKConfig.ScreenWidth * 0.7 ;	//70% ;
FCKConfig.FlashBrowserWindowHeight = FCKConfig.ScreenHeight * 0.7 ;	//70% ;

FCKConfig.LinkUpload = true ;
FCKConfig.LinkUploadURL = FCKConfig.BasePath + 'filemanager/connectors/' + _QuickUploadLanguage + '/upload.' + _QuickUploadExtension ;
FCKConfig.LinkUploadAllowedExtensions	= ".(7z|aiff|asf|avi|bmp|csv|doc|fla|flv|gif|gz|gzip|jpeg|jpg|mid|mov|mp3|mp4|mpc|mpeg|mpg|ods|odt|pdf|png|ppt|pxd|qt|ram|rar|rm|rmi|rmvb|rtf|sdc|sitd|swf|sxc|sxw|tar|tgz|tif|tiff|txt|vsd|wav|wma|wmv|xls|xml|zip)$" ;			// empty for all
FCKConfig.LinkUploadDeniedExtensions	= "" ;	// empty for no one

FCKConfig.ImageUpload = true ;
FCKConfig.ImageUploadURL = FCKConfig.BasePath + 'filemanager/connectors/' + _QuickUploadLanguage + '/upload.' + _QuickUploadExtension + '?Type=Image' ;
FCKConfig.ImageUploadAllowedExtensions	= ".(jpg|gif|jpeg|png|bmp)$" ;		// empty for all
FCKConfig.ImageUploadDeniedExtensions	= "" ;							// empty for no one

FCKConfig.FlashUpload = true ;
FCKConfig.FlashUploadURL = FCKConfig.BasePath + 'filemanager/connectors/' + _QuickUploadLanguage + '/upload.' + _QuickUploadExtension + '?Type=Flash' ;
FCKConfig.FlashUploadAllowedExtensions	= ".(swf|flv)$" ;		// empty for all
FCKConfig.FlashUploadDeniedExtensions	= "" ;					// empty for no one

FCKConfig.SmileyPath	= FCKConfig.BasePath + 'images/smiley/msn/' ;
FCKConfig.SmileyImages	= ['regular_smile.gif','sad_smile.gif','wink_smile.gif','teeth_smile.gif','confused_smile.gif','tounge_smile.gif','embaressed_smile.gif','omg_smile.gif','whatchutalkingabout_smile.gif','angry_smile.gif','angel_smile.gif','shades_smile.gif','devil_smile.gif','cry_smile.gif','lightbulb.gif','thumbs_down.gif','thumbs_up.gif','heart.gif','broken_heart.gif','kiss.gif','envelope.gif'] ;
FCKConfig.SmileyColumns = 8 ;
FCKConfig.SmileyWindowWidth		= 320 ;
FCKConfig.SmileyWindowHeight	= 210 ;

FCKConfig.BackgroundBlockerColor = '#ffffff' ;
FCKConfig.BackgroundBlockerOpacity = 0.50 ;

FCKConfig.MsWebBrowserControlCompat = false ;

FCKConfig.PreventSubmitHandler = false ;

FCKXHtml._AppendNode = function (A, B) {
    if (!B) return false;
    switch (B.nodeType) {
        case 1:
            if (FCKBrowserInfo.IsGecko && B.tagName.toLowerCase() == 'br' && B.parentNode.tagName.toLowerCase() == 'pre') {
                var C = '\r';
                if (B == B.parentNode.firstChild) C += '\r';
                return FCKXHtml._AppendNode(A, this.XML.createTextNode(C));
            };
            if (B.getAttribute('_fckfakelement')) return FCKXHtml._AppendNode(A, FCK.GetRealElement(B));
            if (FCKBrowserInfo.IsGecko && (B.hasAttribute('_moz_editor_bogus_node') || B.getAttribute('type') == '_moz')) {
                if (B.nextSibling) return false;
                else {
                    B.removeAttribute('_moz_editor_bogus_node');
                    B.removeAttribute('type');
                }
            };
            if (B.getAttribute('_fcktemp')) return false;
            var D = B.tagName.toLowerCase();
            var preserved=false;
            if (FCKBrowserInfo.IsIE) {
                if (B.scopeName && B.scopeName != 'HTML' && B.scopeName != 'FCK') D = B.scopeName.toLowerCase() + ':' + D;
                else {
                    if (D.StartsWith('fck:')) {
                        D = D.Remove(0, 4);
                    }
                    
                }
            } else {
                if (D.StartsWith('fck:')) D = D.Remove(0, 4);
            };
            if (!FCKRegexLib.ElementName.test(D)) return false;
            if (B._fckxhtmljob && B._fckxhtmljob == FCKXHtml.CurrentJobNum) return false;
            var E = this.XML.createElement(D);
            FCKXHtml._AppendAttributes(A, B, E, D);
            B._fckxhtmljob = FCKXHtml.CurrentJobNum;
            var F = FCKXHtml.TagProcessors[D];
            if (F) E = F(E, B, A);
            else E = this._AppendChildNodes(E, B, Boolean(FCKListsLib.NonEmptyBlockElements[D]));
            if (!E) return false;
            A.appendChild(E);
            
            break;
        case 3:
            if (B.parentNode && B.parentNode.nodeName.IEquals('pre')) return this._AppendTextNode(A, B.nodeValue);
            return this._AppendTextNode(A, B.nodeValue.ReplaceNewLineChars(' '));
        case 8:
            if (FCKBrowserInfo.IsIE && !B.innerHTML) break;
            try {
                A.appendChild(this.XML.createComment(B.nodeValue));
            } catch (e) {};
            break;
        default:
            A.appendChild(this.XML.createComment("Element not supported - Type: " + B.nodeType + " Name: " + B.nodeName));
            break;
    };
    return true;
};

FCKXHtml.TagProcessors = {
    a: function (A, B) {
        if (B.innerHTML.Trim().length == 0 && !B.name) return false;
        var C = B.getAttribute('_fcksavedurl');
        if (C != null) FCKXHtml._AppendAttribute(A, 'href', C);
        if (FCKBrowserInfo.IsIE) {
            if (B.name) FCKXHtml._AppendAttribute(A, 'name', B.name);
        };
        A = FCKXHtml._AppendChildNodes(A, B, false);
        return A;
    },
    area: function (A, B) {
        var C = B.getAttribute('_fcksavedurl');
        if (C != null) FCKXHtml._AppendAttribute(A, 'href', C);
        if (FCKBrowserInfo.IsIE) {
            if (!A.attributes.getNamedItem('coords')) {
                var D = B.getAttribute('coords', 2);
                if (D && D != '0,0,0') FCKXHtml._AppendAttribute(A, 'coords', D);
            };
            if (!A.attributes.getNamedItem('shape')) {
                var E = B.getAttribute('shape', 2);
                if (E && E.length > 0) FCKXHtml._AppendAttribute(A, 'shape', E.toLowerCase());
            }
        };
        return A;
    },
    body: function (A, B) {
        A = FCKXHtml._AppendChildNodes(A, B, false);
        A.removeAttribute('spellcheck');
        return A;
    },
    iframe: function (A, B) {
        var C = B.innerHTML;
        if (FCKBrowserInfo.IsGecko) C = FCKTools.HTMLDecode(C);
        C = C.replace(/\s_fcksavedurl="[^"]*"/g, '');
        A.appendChild(FCKXHtml.XML.createTextNode(FCKXHtml._AppendSpecialItem(C)));
        return A;
    },
    img: function (A, B) {
        if (!A.attributes.getNamedItem('alt')) FCKXHtml._AppendAttribute(A, 'alt', '');
        var C = B.getAttribute('_fcksavedurl');
        if (C != null) FCKXHtml._AppendAttribute(A, 'src', C);
        if (B.style.width) A.removeAttribute('width');
        if (B.style.height) A.removeAttribute('height');
        return A;
    },
    li: function (A, B, C) {
        if (C.nodeName.IEquals(['ul', 'ol'])) return FCKXHtml._AppendChildNodes(A, B, true);
        var D = FCKXHtml.XML.createElement('ul');
        B._fckxhtmljob = null;
        do {
            FCKXHtml._AppendNode(D, B);
            do {
                B = FCKDomTools.GetNextSibling(B);
            } while (B && B.nodeType == 3 && B.nodeValue.Trim().length == 0)
        } while (B && B.nodeName.toLowerCase() == 'li') return D;
    },
    ol: function (A, B, C) {
        if (B.innerHTML.Trim().length == 0) return false;
        var D = C.lastChild;
        if (D && D.nodeType == 3) D = D.previousSibling;
        if (D && D.nodeName.toUpperCase() == 'LI') {
            B._fckxhtmljob = null;
            FCKXHtml._AppendNode(D, B);
            return false;
        };
        A = FCKXHtml._AppendChildNodes(A, B);
        return A;
    },
    pre: function (A, B) {
        var C = B.firstChild;
        if (C && C.nodeType == 3) A.appendChild(FCKXHtml.XML.createTextNode(FCKXHtml._AppendSpecialItem('\r\n')));
        FCKXHtml._AppendChildNodes(A, B, true);
        return A;
    },
    script: function (A, B) {
        if (!A.attributes.getNamedItem('type')) FCKXHtml._AppendAttribute(A, 'type', 'text/javascript');
        if(B.text)
        A.appendChild(FCKXHtml.XML.createTextNode(FCKXHtml._AppendSpecialItem(B.text)));
        else 
        A.appendChild(FCKXHtml.XML.createTextNode(FCKXHtml._AppendSpecialItem('')));
        return A;
    },
    span: function (A, B) {
        if (B.innerHTML.length == 0) return false;
        A = FCKXHtml._AppendChildNodes(A, B, false);
        return A;
    },
    style: function (A, B) {
        if (!A.attributes.getNamedItem('type')) FCKXHtml._AppendAttribute(A, 'type', 'text/css');
        var C = B.innerHTML;
        if (FCKBrowserInfo.IsIE) C = C.replace(/^(\r\n|\n|\r)/, '');
        A.appendChild(FCKXHtml.XML.createTextNode(FCKXHtml._AppendSpecialItem(C)));
        return A;
    },
    title: function (A, B) {
        A.appendChild(FCKXHtml.XML.createTextNode(FCK.EditorDocument.title));
        return A;
    }
};
