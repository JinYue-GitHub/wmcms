var httpSe,config;  
if(window.XMLHttpRequest){  
    httpSer = new XMLHttpRequest();  
}else if(window.ActiveXObject){  
    httpSer = new window.ActiveXObject();  
}else{  
    alert("请升级至最新版本的浏览器");  
}  
if(httpSer !=null){  
    httpSer.open("GET","/files/face/editor.json",false);  
    httpSer.send(null);  
	config=JSON.parse(httpSer.responseText);
}
window.onload = function () {
    editor.setOpt({
        emotionLocalization:false
    });
	
	
	var tabHtml = boxHtml = boxListHtml = url = '';
	var i=tabNum=0;
	for(var o in config){
		boxListHtml = '';
		tabHtml += '<span onClick="tabs('+i+')">'+config[o].title+'</span>';
		
		for(var j=1;j<=config[o].number;j++){
			url = '/files/face/'+config[o].floder+'/'+config[o]['url'].replace('{i}',j);
			boxListHtml += '<td class="jd" border="1" width="3%" style="border-collapse:collapse;" align="center" bgcolor="transparent" onclick="InsertSmiley(\''+url+'\',event)" onmouseover="over(this,\''+url+'\',\''+j+'\')" onmouseout="out(this)"><span><img style="background-position:left -1px;" src="'+url+'" width="35" height="35"></span></td>';
			if(!(j%11)){
				boxListHtml += '</tr><tr>';
			}
		}
		boxHtml += '<div id="tab'+i+'" style="display: none;"><table class="smileytable"><tbody><tr>'+boxListHtml+'</tr></tbody></table></div>';
		i++;
	}
	document.getElementById('tabHeads').innerHTML = tabHtml;
	document.getElementById('tabHeads').getElementsByTagName('span')[0].className = 'focus';
	document.getElementById('tabBodys').innerHTML = boxHtml;
	document.getElementById('tab0').style.display = 'block';
};

function over( td, srcPath, posFlag ) {
    td.style.backgroundColor = "#ACCD3C";
    document.getElementById( 'faceReview' ).style.backgroundImage = "url(" + srcPath + ")";
    document.getElementById( 'faceReview' ).style.backgroundSize = "110px 110px";
    
    if ( (posFlag%11) > 6 || (posFlag%11) == 0) document.getElementById( "tabIconReview" ).className = "show";
    document.getElementById( "tabIconReview" ).style.display = 'block';
}

function out( td ) {
    td.style.backgroundColor = "transparent";
    var tabIconRevew = document.getElementById( "tabIconReview" );
    tabIconRevew.className = "";
    tabIconRevew.style.display = 'none';
}

function InsertSmiley( url, evt ) {
    var obj = {
        src:url
    };
    obj._src = obj.src;
    editor.execCommand( 'insertimage', obj );
    if ( !evt.ctrlKey ) {
        dialog.popup.hide();
    }
}

function tabs(index){
	var boxObj = document.getElementById('tabHeads').getElementsByTagName('span');
	var listObj = document.getElementById('tabBodys').getElementsByTagName('div');
	for(var i =0;i<boxObj.length;i++){
		boxObj[i].className = '';
	}
	for(var i =0;i<listObj.length;i++){
		listObj[i].style.display = 'none';
	}
	boxObj[index].className = 'focus';
	listObj[index].style.display = 'block';
}