window.addEventListener('load', function(){
	
    var btnNav = document.getElementById("mobile-nav-button");
 
    btnNav.addEventListener('click', function(){
        var mainNav = document.getElementById("main-nav");
        var styleObj = window.getComputedStyle(mainNav);
        // note that when you use window.getComputedStyle() it returns an obj
        // that has all the computed styles, BUT you can only 'get' the settings, you cannot 'set' them
        // To 'set' them you need to use mainNav.style....
        var currentDisplay = styleObj.display;
        //alert(display);
        if(currentDisplay == "none"){
            mainNav.style.display = "block";
        }else{
            mainNav.style.display = "none";
        }

    });
});