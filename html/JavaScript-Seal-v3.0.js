<!--
/* CertiSur Seal - JavaScript version 3.0 - Norton */

function showSeal(host_name,lang,transparent,folder,filename,swfwidth,swfheight,loadURL)
{
	if (transparent == "yes"){
		var trans = "transparent";
	} else {
		var trans = "window";
	}
	document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+swfwidth+'" height="'+swfheight+'" id="'+filename+'" align="middle">\n');
	document.write('<param name="movie" value="'+folder+filename+'.swf?host_name='+host_name+'&amp;lang='+lang+'&amp;loadURL='+loadURL+'&amp;ca=norton" />\n');
	document.write('<param name="loop" value="false" />\n');
	document.write('<param name="menu" value="false" />\n');
	document.write('<param name="quality" value="best" />\n');
	document.write('<param name="wmode" value="'+trans+'" />\n');
	document.write('<param name="bgcolor" value="#ffffff" />\n');
	document.write('<param name="allowScriptAccess" value="always" />\n');
	document.write('<embed src="'+folder+filename+'.swf?host_name='+host_name+'&amp;lang='+lang+'&amp;loadURL='+loadURL+'&amp;ca=norton" loop="false" menu="false" quality="best" wmode="'+trans+'" bgcolor="#ffffff" width="'+swfwidth+'" height="'+swfheight+'" name="'+filename+'" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="https://www.macromedia.com/go/getflashplayer" />\n');
	document.write('</object>\n');
}

function Seal_Certificado(host_name,lang,version,domain)
{
	u1="https://seal.certisur.com/getseal?host_name="+host_name+"&lang="+lang+"&version="+version+"&domain="+domain+"&ca=norton";
	if (opener && !opener.closed) {
		opener.focus();
	} else {
		w = 862;
		h = 642;
		x = (screen.width/2) - (w/2);
		y = (screen.height/2) - (h/2) - 30;
		tbar = 'location=yes,status=yes,resizable=yes,scrollbars=yes,width='+w+',height='+h+',left='+x+',top='+y;
		var sw = window.open(u1,'CS_Seal',tbar);
		if (sw) {
			sw.focus();
			opener=sw;
		}
	}
}
// -->