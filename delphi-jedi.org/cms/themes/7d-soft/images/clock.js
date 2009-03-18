var dayarray=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday")
var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
function getthedate()
{
	var mydate=new Date()
	var year=mydate.getYear()
	if (year < 1000)
		year+=1900
	
	var day=mydate.getDay()
	var month=mydate.getMonth()
	var daym=mydate.getDate()
	if (daym<10)
		daym="0"+daym
	
	var hours=mydate.getHours()
	var minutes=mydate.getMinutes()
	var seconds=mydate.getSeconds()
	var dn=""
	
	
	{
 		d = new Date();
 		Time24H = new Date();
 		Time24H.setTime(d.getTime() + (d.getTimezoneOffset()*60000) + 3600000);
 		InternetTime = Math.round((Time24H.getHours()*60+Time24H.getMinutes()) / 1.44);
 		if (InternetTime < 10) InternetTime = "00"+InternetTime;
 		else if (InternetTime < 100) InternetTime = "0"+InternetTime;
	}
	if (hours==0)
		hours=12
	if (minutes<=9)
		minutes="0"+minutes
	if (seconds<=9)
		seconds="0"+seconds
	//change font size here
	var cdate=daym+" "+montharray[month]+"  "+year+" | "+hours+":"+minutes+":"+seconds+" "+dn+""
	if (document.all)
		document.all.clock.innerHTML=cdate
	else if (document.getElementById)
		document.getElementById("clock").innerHTML=cdate
	else
		document.write(cdate)
	}

	if (!document.all&&!document.getElementById)
	getthedate()

function goforit()
{
	if (document.all||document.getElementById)
		setInterval("getthedate()",1000)
}
window.onload=goforit
