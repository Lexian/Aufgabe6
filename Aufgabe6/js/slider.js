
function initSlide() {
    "use strict";
    if (slideObject) {
    slideObject.addSlide({zone: {left: -35, top: -11, right: 227, bottom: -11}, updateFunction: updateDisplay});
    }
}
addOnLoadEvent(initSlide);