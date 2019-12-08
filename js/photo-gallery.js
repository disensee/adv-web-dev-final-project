window.addEventListener('load', function(){

  var images = [
    "images/openroad.jpg",
    "images/caferacer.jpg",
    "images/city.jpg",
    "images/keyboard.jpg",
    "images/meal.jpg"
  ];

  // set up a counter to keep track of the current img
  var currentImg = 0;
  // the pictures will be displayed inside the #mainImg img tag
  var mainImg = document.getElementById("mainImg");
  // show the first picture in the images array
  mainImg.src = images[currentImg];
  
  // set up event handlers for when the buttons get pressed  
  var btnPrev = document.getElementById("btnPrev");
  var btnNext = document.getElementById("btnNext");

  btnNext.addEventListener('click', function(){
    // move to the next image (or the first image if we have gone out of bounds)
    currentImg++;
    if(currentImg == images.length){
       currentImg = 0;     
    }
    mainImg.src = images[currentImg];
  });

  btnPrev.addEventListener('click', function(){
    // move to the previous image (or the last image if we have gone out of bounds)
    currentImg--;
    if(currentImg == -1){
        currentImg = images.length -1;    
    }
    mainImg.src = images[currentImg];
  });


});
