var store_lang = fpBtnVars.store_lang;
var store_lang = store_lang.replace("-", "_"); 
var followpriceEnv = fpBtnVars.followpriceEnv;
var pluginVer = fpBtnVars.pluginVer;
var platformVer = fpBtnVars.platformVer;
var platformName = fpBtnVars.platformName;
var platformDepVer = fpBtnVars.platformDepVer;
var platformDepName = fpBtnVars.platformDepName;

(function() { var _f = document.createElement("script");_f.type = "text/javascript"; _f.async = true; _f.src =followpriceEnv+"/followbutton.js?plugin_ver="+pluginVer+"&platform_ver="+platformVer+"&platform_name="+platformName+"&platform_dep_ver="+platformDepVer+"&platform_dep_name="+platformDepName+"&locale="+store_lang; var s =document.getElementsByTagName("script")[0];s.parentNode.insertBefore(_f, s); })();