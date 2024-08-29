// Mobile Detection


//###############################################	
// Oh, look, they're using a mobile device!
//###############################################	
var deviceIphone = "iphone";
var deviceIpod = "ipod";
var deviceIpad = "ipad";
var deviceAndroid = "android";
var isMobile = false;
 
//Initialize our user agent string to lower case.
var uagent = navigator.userAgent.toLowerCase();
 
// Detects if the current device is an iPhone.
function DetectIphone() {
	return (uagent.search(deviceIphone) > -1)
}
 
// Detects if the current device is an iPad.
function DetectIpad() {
	return (uagent.search(deviceIpad) > -1)
}
 
// Detects if the current device is an iPod Touch.
function DetectIpod() {
	return (uagent.search(deviceIpod) > -1)
}

// Detects if the current device is an Android.
function DetectAndroid() {
	return (uagent.search(deviceAndroid) > -1)
}
 
// Detects if the current device is mobile
function detectMobile() {
	if (DetectIphone())
		return true;
	else if (DetectIpod())
		return true;
	else if (DetectIpad())
		return true;
	else if (DetectAndroid())
		return true;
	else
		return false;
}