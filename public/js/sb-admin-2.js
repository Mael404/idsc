(function($) {
  "use strict"; // Start of use strict

  // Toggle the sidebar
  $("#sidebarToggle").on("click", function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");

    // If the sidebar is collapsed, close any dropdowns inside it
    if ($(".sidebar").hasClass("toggled")) {
      $(".sidebar .collapse").collapse("hide");
    }
  });

  // Toggle the sidebar in mobile view using the topbar button
  $("#sidebarToggleTop").on("click", function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");

    // If the sidebar is collapsed, close any dropdowns inside it
    if ($(".sidebar").hasClass("toggled")) {
      $(".sidebar .collapse").collapse("hide");
    }
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $(".sidebar .collapse").collapse("hide");
    }

    // Automatically toggle sidebar on small screens
    if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
      $("body").addClass("sidebar-toggled");
      $(".sidebar").addClass("toggled");
      $(".sidebar .collapse").collapse("hide");
    }
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation is hovered over
  $("body.fixed-nav .sidebar").on("mousewheel DOMMouseScroll wheel", function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on("scroll", function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $(".scroll-to-top").fadeIn();
    } else {
      $(".scroll-to-top").fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on("click", "a.scroll-to-top", function(e) {
    var $anchor = $(this);
    $("html, body")
      .stop()
      .animate(
        {
          scrollTop: $($anchor.attr("href")).offset().top
        },
        1000,
        "easeInOutExpo"
      );
    e.preventDefault();
  });
})(jQuery); // End of use strict

// Greeting message logic
function getGreeting() {
  const hour = new Date().getHours();
  let greeting = "Hey there!";

  if (hour < 12) {
    greeting = "🌞 Good Morning, User!";
  } else if (hour < 18) {
    greeting = "☀️ Good Afternoon, User!";
  } else {
    greeting = "🌙 Good Evening, User!";
  }

  document.getElementById("greetingMessage").textContent = greeting;
}

document.addEventListener("DOMContentLoaded", getGreeting);

