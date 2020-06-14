"use strict";

let $orbs = $(".orbs span");
$(".end-right").css("left", "-10%");
$(".end-left").css("left", "110%");
$orbs.velocity(
  { top: "-300px", scaleX: ".2", scaleY: ".2", color: "#990000" },
  0
);
let orb = 0;
let numOrbs = $orbs.length;
$(".end-right").velocity({ left: "50%" }, "easeOutExpo", 1200);
$(".end-left").velocity({ left: "50%" }, "easeOutExpo", 1200);

let dropOrbs = () => {
  $orbs
    .eq(orb)
    .velocity({ top: "70px" }, 400)
    .velocity({ scaleX: 1, scaleY: 1, color: "#fff" }, 1000)
    .css("position", "relative");
  orb = orb + 1;
  if (orb < numOrbs) setTimeout(dropOrbs, 100);
  else {
    setTimeout(() => {
      $(".glow").velocity({ opacity: 1 }, 1200);
    }, 1200);
    setTimeout(
      () => document.querySelector(".logo").classList.add("change"),
      2000
    );
    setTimeout(
      () => document.querySelector(".boutonAccueil").classList.add("change"),
      2500
    );
  }
};

setTimeout(dropOrbs, 400);
