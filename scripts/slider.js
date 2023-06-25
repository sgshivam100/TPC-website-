var responsiveSlider = function () {

  var slider = document.getElementById("slider");
  var sliderWidth = slider.offsetWidth;
  var slideList = document.getElementById("slideWrap");
  var count = 1;
  var items = slideList.querySelectorAll("li").length;

  window.addEventListener('resize', function () {
    sliderWidth = slider.offsetWidth;
  });



  var nextSlide = function () {
    if (count < items) {
      slideList.style.left = "-" + count * sliderWidth + "px";
      count++;
    }
    else if (count = items) {
      slideList.style.left = "0px";
      count = 1;
    }
  };



  setInterval(function () {
    nextSlide()
  }, 2000);

};

window.onload = function () {
  responsiveSlider();
}