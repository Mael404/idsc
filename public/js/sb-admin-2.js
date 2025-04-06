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

// Students Per Course Chart
var ctx = document.getElementById("studentsPerCourseChart").getContext('2d');
var studentsPerCourseChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Math", "English", "CS", "Science", "History"],
        datasets: [{
            label: "Students",
            backgroundColor: "#4e73df",
            hoverBackgroundColor: "#2e59d9",
            data: [30, 25, 35, 15, 20]
        }]
    },
    options: {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});

var ctx = document.getElementById('studyResourcesChart').getContext('2d');
var studyResourcesChart = new Chart(ctx, {
    type: 'pie',  // Pie chart type
    data: {
        labels: ['2018-2019', '2019-2020', '2020-2021', '2021-2022', '2022-2023', '2023-2024', '2024-2025'],  // Labels for the years
        datasets: [{
            data: [450, 500, 550, 600, 620, 650, 700],  // Student count for each year
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#f39c12', '#2e59d9'],  // Segment colors
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#f39c12', '#c0392b', '#f39c12', '#8e44ad'],  // Hover colors
        }]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true
    }
});
