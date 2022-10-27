/**
 * 检查是否是邮箱
 * 参数1，必须 字符串
 */
function isEmail(str){ 
	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
	return reg.test(str); 
}

/**
 * 检查是否是正整数。
 * 参数1，必须 字符串
 */
function isPositiveNum(str){
	if( str== '0' ){
		return true;
	}
	var re = /^[0-9]*[1-9][0-9]*$/;
    return re.test(str); 
} 

/**
 * 检查是否是英文、数字和中文。
 * 参数1，必须 字符串
 */
function isString(str){
	var re = /^[\d|A-z|\u4E00-\u9FFF]+$/;
    return re.test(str); 
} 

/**
 * 检查是否是中文或者字母组合
 * 参数1，必须，字符串
 */
function isName(str){  
     var re =  /^[0-9a-zA-Z|\u4E00-\u9FFF]*$/g;
     return re.test(str);
}

/**
 * 判断是否为手机号
 * 参数1，必须，字符串
 */
function isPhone(str){
	var re = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
	return re.test(str);
}

/**
 * 判断是否为身份证号
 * 参数1，必须，字符串
 */
function isCardId(sId){
	var aCity={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"} 
	var iSum=0;
	var info="";
	if(!/^\d{17}(\d|x)$/i.test(sId)){
		//return "你输入的身份证长度或格式错误";
		return false;
	}
	sId=sId.replace(/x$/i,"a");
	if(aCity[parseInt(sId.substr(0,2))]==null){
		//return "你的身份证地区非法";
		return false;
	}
	sBirthday=sId.substr(6,4)+"-"+Number(sId.substr(10,2))+"-"+Number(sId.substr(12,2));
	var d=new Date(sBirthday.replace(/-/g,"/")) ;
	if(sBirthday!=(d.getFullYear()+"-"+ (d.getMonth()+1) + "-" + d.getDate())){
		//return "身份证上的出生日期非法";
		return false;
	}
	for(var i = 17;i>=0;i --) iSum += (Math.pow(2,i) % 11) * parseInt(sId.charAt(17 - i),11) ;
	if(iSum%11!=1){
		//return "你输入的身份证号非法";
		return false;
	}
	//aCity[parseInt(sId.substr(0,2))]+","+sBirthday+","+(sId.substr(16,1)%2?"男":"女");//此次还可以判断出输入的身份证号的人性别
	return true;
}


/**
 * 将对象转为html
 * 参数1，必须，对象
 * 参数2，必须，内容模版
 * 参数3，选填，前置html代码
 * 参数4，选填，后置html代码
 */
function objToHtml(obj , tpl , before , last ){
	var html=field='';
	tplArr = tpl.match(/{(.*?)}/g);
	for(var o in obj){
		newTpl = tpl;
		for(var i=0;i<tplArr.length;i++){
			field = tplArr[i].replace('{','');
			field = field.replace('}','');
			newTpl = newTpl.replace(tplArr[i],obj[o][field]);
		}
		html = html + newTpl;
    }
	if(typeof(before) != 'undefined' ){
		html = before+html;
	}
	if(typeof(last) != 'undefined' ){
		html = html+last;
	}
	return html;
}


/**
 * 中文字数统计
 * 参数1，必须，需要检查的字符串
 * 参数2，选填，是否返回数组，默认是只返回字数
 */
function wordsNumber(Words,arr){
    var sLen = 0;
	try{
		//先将回车换行符做特殊处理
   		Words = Words.replace(/(\r\n+|\s+|　+)/g,"龘");
		//处理英文字符数字，连续字母、数字、英文符号视为一个单词
		Words = Words.replace(/[\x00-\xff]/g,"m");	
		//合并字符m，连续字母、数字、英文符号视为一个单词
		Words = Words.replace(/m+/g,"*");
   		//去掉回车换行符
		Words = Words.replace(/龘+/g,"");
		//返回字数
		sLen = Words.length;
	}catch(e){
		return 0;
	}
	return sLen;
	
	var W = new Object();
	var Result = new Array();
	var iNumwords = 0;
	var sNumwords = 0;
	var sTotal = 0;
	var iTotal = 0;
	var eTotal = 0;
	var otherTotal = 0;
	var bTotal = 0;
	var inum = 0;
	for (i = 0; i < Words.length; i++) {
		var c = Words.charAt(i);
		if (c.match(/[\u4e00-\u9fa5]/)) {
			if (isNaN(W[c])) {
				iNumwords++;
				W[c] = 1;
			}
			iTotal++;
		}
	}
	for (i = 0; i < Words.length; i++) {
		var c = Words.charAt(i);
		if (c.match(/[^\x00-\xff]/)) {
			if (isNaN(W[c])) {
				sNumwords++;
			}
			sTotal++;
		} else {
			eTotal++;
		}
		if (c.match(/[0-9]/)) {
			inum++;
		}
	}
	var result = {"hanzi":iTotal,"zishu":inum + iTotal,"biaodian":sTotal-iTotal,"zimu":eTotal-inum,"shuzi":inum};
	if( arr===true ){
		return result;
		return {"hanzi":iTotal,"zishu":inum + iTotal,"biaodian":sTotal-iTotal,"zimu":eTotal-inum,"shuzi":inum};
	}else{
		return iTotal + result.zimu + result.biaodian;
	}
}


/*
 *功能： 模拟form表单的提交
 *参数： URL 跳转地址 PARAMTERS 参数
 */
function Post(URL, PARAMTERS ,Type){
	//创建form表单
	var temp_form = document.createElement("form");
	temp_form.action = URL;
	//如需当前窗口打开，form的target属性要设置为'_self'
	if(!arguments[2]) Type = "_blank";
	if(Type=='_blank'){
		temp_form.target = "_blank";
	}else{
		temp_form.target = "_self";
	}
	temp_form.method = "post";
	temp_form.style.display = "none";
	//添加参数
	for (var item in PARAMTERS){
		var opt = document.createElement("input");
		opt.name = item;
		opt.value = PARAMTERS[item];
		temp_form.appendChild(opt);
	}
	document.body.appendChild(temp_form);
	//提交数据
	temp_form.submit();
 }


/**
 * 获取cookie
 * 参数1，必须，cookie名字
 */
function getCookie(name) {
	var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
	arr = document.cookie.match(reg);
	if (arr){
		return arr[2];
	}else {
		return null;
	}
}

/**
 * 设置cookie
 * 参数1，必须，cookie名字
 * 参数2，必须，cookie值
 * 参数3，必须，超时时间
 * 参数4，必须，存储路径
 * 参数5，必须，保存域名
 */
function setCookie(name, value,expires, path, domain ) {
	if(expires){
		expires = new Date(+new Date() + expires);
	}else{
		expires = new Date(+new Date() + 60*60*24*365*1000);
	}
	var tempcookie = name + '=' + escape(value) +
		((expires) ? '; expires=' + expires.toGMTString() : '') +
		((path) ? '; path=' + path : '') +
		((domain) ? '; domain=' + domain : '');
	if(tempcookie.length < 4096) {
		document.cookie = tempcookie;
	}
}

//全屏
function FullScreen() {
  var element = document.documentElement;
  if (element.requestFullscreen) {
      element.requestFullscreen();
  } else if (element.msRequestFullscreen) {
      element.msRequestFullscreen();
  } else if (element.mozRequestFullScreen) {
      element.mozRequestFullScreen();
  } else if (element.webkitRequestFullscreen) {
      element.webkitRequestFullscreen();
  }
}
//退出全屏 
function ExitFullscreen() {
  if (document.exitFullscreen) {
      document.exitFullscreen();
  } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
  } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
  } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
  }
}

/*
 *功能： 秒转换为时分秒
 *参数： second秒
 *参数： temp 模版
 */
function FormatSeconds(second,temp){
    let result = parseInt(second)
    let h = Math.floor(result / 3600) < 10 ? '0' + Math.floor(result / 3600) : Math.floor(result / 3600);
    let m = Math.floor((result / 60 % 60)) < 10 ? '0' + Math.floor((result / 60 % 60)) : Math.floor((result / 60 % 60));
    let s = Math.floor((result % 60)) < 10 ? '0' + Math.floor((result % 60)) : Math.floor((result % 60));
    if( !temp ){
    	return h+':'+h+':'+s;
    }else{
    	temp = temp.replace(/h/, h)
    	temp = temp.replace(/m/, m)
    	temp = temp.replace(/s/, s)
    	return temp;
    }
}

/**
 * 格式化时间戳
 * 参数1，必须，时间戳
 * 参数2，必须，模版
 */
function FormatTime(time,temp){
	if( /^\d+$/.test(time) !== true ){
		time = new Date().getTime();
	}else{
		time = time*1000;
	}
	// 简单的一句代码
	var date = new Date(time); //获取一个时间对象
	var Y = date.getFullYear(); // 获取完整的年份(4位,1970)
	var m = date.getMonth()+1; // 获取月份(0-11,0代表1月,用的时候记得加上1)
	var d = date.getDate(); // 获取日(1-31)
	var h = date.getHours(); // 获取小时数(0-23)
	var i = date.getMinutes(); // 获取分钟数(0-59)
	var s = date.getSeconds(); // 获取秒数(0-59)
	m = m<10?'0'+m:m;
	d = d<10?'0'+d:d;
	H = h<10?'0'+h:h;
	i = i<10?'0'+i:i;
	s = s<10?'0'+s:s;
	if( !temp ){
		return Y+'-'+m+'-'+d+' '+H+':'+i+':'+s;
	}else{
		temp = temp.replace(/Y/, Y)
		temp = temp.replace(/m/, m)
		temp = temp.replace(/d/, d)
		temp = temp.replace(/H/, H)
		temp = temp.replace(/h/, h)
		temp = temp.replace(/i/, i)
		temp = temp.replace(/s/, s)
		return temp;
	}
}

//获得当前窗口区域高度
function GetClientHeight(){
  var clientHeight=0;
  if(document.body.clientHeight&&document.documentElement.clientHeight){
      clientHeight = (document.body.clientHeight<document.documentElement.clientHeight)?document.body.clientHeight:document.documentElement.clientHeight;
  } else {
      clientHeight = (document.body.clientHeight>document.documentElement.clientHeight)?document.body.clientHeight:document.documentElement.clientHeight;
  }
  return clientHeight;
}

/**
 * 检查内容中的常用错别字
 * 参数1，必须，内容
 */
function CheckWords(content) {
	var n = 0
	var rtval = '';
	var keys = "恶耗|报筹|爆乱|喝采|呕气|照像机|雄纠纠|绝不罢休|军事布署|得不尝失|按装|趁心如意|自抱自弃|针贬|泊来品|脉博|松驰|一愁莫展|穿流不息|精萃|重迭|渡假村|防碍|幅射|一幅对联|天翻地复|言简意骇|气慨|一股作气|悬梁刺骨|粗旷|食不裹腹|震憾|凑和|侯车室|迫不急待|既使|一如继往|草管人命|娇揉造作|挖墙角|一诺千斤|不径而走|峻工|不落巢臼|烩炙人口|打腊|死皮癞脸|兰天白云|鼎立相助|再接再励|老俩口|黄梁美梦|了望|水笼头|杀戳|痉孪|美仑美奂|罗唆|蛛丝蚂迹|萎糜不振|沉缅|名信片|默守成规|大姆指|沤心沥血|凭添|出奇不意|修茸|亲睐|磬竹难书|入场卷|声名雀起|发韧|搔痒病|欣尝|谈笑风声|人情事故|有持无恐|额首|称庆|追朔|鬼鬼崇崇|金榜提名|走头无路|趋之若骛|迁徒|洁白无暇|九宵|渲泄|寒喧|弦律|尤如猛虎|膺品|不能自己|竭泽而鱼|滥芋充数|世外桃园|脏款|醮水|蜇伏|装祯|饮鸠止渴|坐阵|旁证博引|灸手可热|九洲|床第之私|姿意妄为|编篡|做月子|我得|我地|络绎不决|别出新裁|疏疏郎郎|渭然成风|浮想连篇|心干情愿|妄费心机|凶神恶刹|沧海一栗|汗流颊背|别巨匠心|锐不可挡|慎时度势|出人投地|错落有至|变本加利|两全齐美|怨天忧人|攻城虐地|戒骄戒燥|常年累月|事在必行|反躬自醒|开宗明意|义气用事|歪门斜道|唇枪舌箭|失口否认|苦心孤意|大喜过忘|布署|莫可明状|开宗名义|按步就班|安祥|大喜过旺|意味伸长|云筹帷幄|偏心则暗|大刹风景|明哞善睐|前鞠后恭|如愿已偿|和中共济|山青水秀|加官劲爵|养遵处优|饥肠漉漉|略见一班|依马当先|损身不恤|一张一驰|煽然泪下|篷荜生辉|防患未燃|手不失卷|原形必露|待价而估|情不自尽|不能自己|涣然一新|明辩是非|没精打彩|精兵减政|陈词烂调|迫在眉劫|甘败下风|暄宾夺主|报仇血恨|伤心病狂|风糜一时|敷衍塞职|宠然大物|破锭百出|鞠躬尽粹|脑羞成怒|恂私枉法|气势凶凶|阴谋鬼计|高瞻远嘱|天花乱醉|理曲词穷|偷机取巧|改斜归正|迫不及戴|应接不瑕|头晕目炫|不径而走|分道扬镖|说长到短|万事具备|新新向荣|专心至志|语重心常|弱不经风|暗然失色|重峦跌嶂|巧舌如黄|瞒心寐己";
	var key = keys.split('|');
	for (var i = 0; i < key.length; i++) {
		var rng = content;
		if (rng.indexOf(key[i]) > 0) {
			if (rtval == ''){
				rtval = "发现可疑别字：" + key[i];
			}else{
				rtval = rtval + "，" + key[i];
			}
		}
	}
	if (rtval == ''){
	  return false;
	}else{
	  return rtval;
	}
}

//英文符号快速转中文符号替换
function SymbolReplace(content){
	content = content.replace(/,/g,"，");
	content = content.replace(/\.\.\.\.\.\./g,"……");
	content = content.replace(/。。。。。。/g,"……");
	content = content.replace(/\?/g,"？");
	content = content.replace(/\./g,"。");
	content = content.replace(/\;/g,"；");
	content = content.replace(/\:/g,"：");
	content = content.replace(/\!/g,"！");
	content = content.replace(/\(/g,"（");
	content = content.replace(/\)/g,"）");
	content = content.replace(/&ldquo;/g,"“");
	content = content.replace(/&rdquo;/g,"”");
	return content;
}

/**
 * 查找或替换常用内容
 * 参数1，必须，需要替换的内容
 * 参数2，必须，被替换的关键字
 * 参数3，选填，0为替换所有，其他数字为索引
 * 参数4，必须，替换后的关键字
 */
function Find(content,keyword,index,replaceContent)
{
	if( !content ){
		return false;
	}
	//如果查找关键字为空
	else if( !keyword ){
		var element = $('<wmcms_editor>'+content+'</wmcms_editor>');
		element.find("span").contents().unwrap();//remove span elements
		return {"count":0,"content":element.html()};
	}
	//查找关键字不为空
	var element = $('<wmcms_editor>'+content+'</wmcms_editor>');
	element.find("span").contents().unwrap();//remove span elements
	content = element.html();
    var textSplit = content.split(keyword);
    //如果index是0，并且存在内容就是全部替换
    if( index===0 && replaceContent && replaceContent!=keyword){
    	count = 0;
		content = textSplit.join(replaceContent);
	}else{
    	count = textSplit.length-1;
		content = textSplit.join('<span class="keywordBg">' + keyword + '</span>');
		//如果索引大于0，并且替换内容不为空
		if(index>0 && replaceContent && replaceContent!=keyword) {
			var element = $('<wmcms_editor>'+content+'</wmcms_editor>');
			element.find('.keywordBg').eq(index-1).html(replaceContent);
			element.find('.keywordBg').eq(index-1).contents().unwrap();
			content = element.html();
			count--;
		}
	}
	return {"count":count,"content":content};
}