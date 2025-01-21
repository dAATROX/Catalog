/**
 * Powered by turn.js
 * http://www.turnjs.com/
 */
$(".magazine").turn({
  width: 922,
  height: 600,
  elevation: 50,
  gradients: true,
  autoCenter: true,
});

// Keyboard controls to turn pages
$(document).keydown(function (e) {
  switch (e.keyCode) {
    case 37: // left
      $(".magazine").turn("previous");
      e.preventDefault();
      break;
    case 39: // right
      $(".magazine").turn("next");
      e.preventDefault();
      break;
  }
});

// Button controls
$(".prev-btn").on("click", function () {
  $(".magazine").turn("previous");
});

$(".next-btn").on("click", function () {
  $(".magazine").turn("next");
});

if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
  $("aside p").css("font-weight", "normal");
}
