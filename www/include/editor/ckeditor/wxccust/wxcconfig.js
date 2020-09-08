CKEDITOR.editorConfig = function( config ) {
        // Define changes to default configuration here. For example:
        // config.language = 'fr';
        // config.uiColor = '#AADC6E';
        //config.extraPlugins = 'base64image','youtube','DlgPicture';
        config.extraPlugins = 'DlgPicture,DlgVideo,DlgMp3,iframedialog,youtube,autolink';

        config.toolbar = [
                                { name:'part1', items:['Bold','Italic','Underline','Strike',
                                'PasteText','PasteFromWord',
                                'JustifyLeft','JustifyCenter','JustifyRight',
                                'Image','Link','Unlink']},
                                //{ name: 'insert', items: ['base64image', 'Youtube','DlgPicture']}, //图片 视频 音乐    
                                { name: 'insert', items: ['DlgPicture','DlgVideo','DlgMp3']}, //图片 视频 音乐(ckeditor's youtube plugin:'Youtube')
                                {name:'part2',items:['Smiley','FontSize','TextColor','BGColor',
                                'NumberedList','BulletedList',
                                'RemoveFormat','Preview','Maximize']} //此处不要加‘,’，否则IE会报错  //"Source" 拿到编辑器toolbar上面
                              ];

        config.language = 'zh-cn'; //chinese simple
        config.removePlugins = 'elementspath'; //remove the bottom bar
        config.resize_enabled = false; //remove the bottom bar
        config.height = 600;
        config.width = '100%';
        config.extraAllowedContent = 'iframe[*]'; //不允许，就不能在编辑器中加youtube视频的iframe tag
        config.extraAllowedContent = 'div(*)';
        config.allowedContent = {
                $1: {
                    // Use the ability to specify elements as an object.
                    elements: CKEDITOR.dtd,
                    attributes: true,
                    styles: true,
                    classes: true
                }
            };
        config.removePlugins = 'iframe'; //不remove就不会在编辑器中显示视频内容。
        
        config.fontSize_sizes = '中号/16px;大号/24px;';
        
        config.smiley_path=CKEDITOR.basePath+'plugins/smiley/images/wxc/';
        config.smiley_images=['001.gif', '002.gif', '003.gif', '004.gif', '005.gif', '006.gif', '007.gif', '008.gif', '009.gif', '010.gif', '011.gif', '012.gif', '013.gif', '014.gif', '015.gif', '016.gif', '017.gif', '018.gif', '019.gif', '020.gif', '021.gif', '022.gif','023.gif', '024.gif', '025.gif', '026.gif', '027.gif', '028.gif', '029.gif', '030.gif'];
        config.smiley_descriptions=['微笑', '鬼脸', '嘻嘻', '哈哈', '呵呵', '得意', '加油', '财迷', '服了', '哭', '小声点', '什么', '恨', '汗', '急', '解释', '闭嘴', '病', '晕', '睡', '怒', '鄙视','鼓掌','握手','OK','电话','邮件','元宝','时间','夜深了'];
};
/*
 * http://www.minwt.com/php/2848.html
 * 工具列參數列表：
'Source'：原始碼
'Save'：儲存
'NewPage'：開新檔案
'Preview'：預覽
'Templates'：樣版

'Cut'：剪下
'Copy'：複製
'Paste'：貼上
'PasteText'：貼為文字格式
'PasteFromWord'：從word 貼上
'Print'：列印
'SpellChecker'：拼字檢查
'Scayt'：即時拼寫檢查

'Undo'：上一步
'Redo'：重作
'Find'：尋找
'Replace'：取代
'SelectAll'：全選
'RemoveFormat'：清除格式

'Form'：表單
'Checkbox'：核取方塊
'Radio'：單選按鈕
'TextField'：文字方塊
'Textarea'：文字區域
'Select'：選單
'Button'：按鈕
'ImageButton'：影像按鈕
'HiddenField'：隱藏欄位

'Bold'：粗體
'Italic'：斜體
'Underline'：底線
'Strike'：刪除線
'Subscript'：下標
'Superscript'：上標
'NumberedList'：編號清單
'BulletedList'：項目清單
'Outdent'：減少縮排
'Indent'：增加縮排
'Blockquote'：引用文字

'JustifyLeft'：靠左對齊
'JustifyCenter'：置中
'JustifyRight'：靠右對齊
'JustifyBlock'：左右對齊

'Link'：超連結
'Unlink'：移除超連結
'Anchor'：錨點

'Image'：圖片影像
'Flash'：Flash
'Table'：表格
'HorizontalRule'：水平線
'Smiley'：表情符號
'SpecialChar'：特殊符號
'PageBreak'：分頁符號

'Styles'：樣式
'Format'：格式
'Font'：字體
'FontSize'：大小

'TextColor'：文字顏色
'BGColor'：背景顏色

'Maximize'：最大化
'ShowBlocks'：顯示區塊
'About'：關於CKEditor
 */

