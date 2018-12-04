var elIndex = 'index.php/';
var urlServer = '/generarestci/';

function cargaScript(url, callback){
	var d = new Date();
	var script = document.createElement('script');
	if(script.readyState){/* IE */
		script.onreadystatechange = function(){
			if(script.readyState === 'loaded' || script.readyState === 'complete'){
				script.onreadystatechange = null;
				callback();
			}
			var d = new Date();
		};
	}else{/* Others */
		script.onload = function(){
			callback();
		};
	}
	script.src = urlServer + url + "?fecha=" + d.getTime() * 15873;
	document.getElementsByTagName('head')[0].appendChild(script);
}