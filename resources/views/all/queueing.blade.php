<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <title>Queue Display</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body,
    html {
      width: 100%;
      height: 100%;
      font-family: Arial, sans-serif;
      overflow: hidden;
      background: #f4f4f4;
    }

    .left-side,
    .right-side {
      height: 100vh;
    }

    .left-side {
      position: absolute;
      width: 40%;
      left: 0;
      top: 0;
      background: #ffffffcc;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      z-index: 2;
    }

    .right-side {
      position: absolute;
      width: 60%;
      right: 0;
      top: 0;
      background: #1f1f1f;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
    }

    .card {
      background: white;
      border-radius: 12px;
      padding: 1rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      width: 100%;
    }

    .card .card-body {
      text-align: center;
    }

    .bg-primary {
      background-color: #007bff !important;
    }

    .text-white {
      color: white;
    }

    h1#squeue {
      font-display: block;
      color: #dc3545;
      font-size: 5rem;
      font-weight: bold;
      animation: blink 1.2s infinite;
    }

    @keyframes blink {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.2;
      }
    }

    .slideShow {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      perspective: 1000px;
    }

    .slideShow img,
    .slideShow video {
      max-width: 100%;
      max-height: 100%;
      opacity: 0;
      border-radius: 16px;
      transition: all 0.6s ease-in-out;
    }

    .blinking-slide {
      animation: flipSlide 10s ease-in-out infinite;
      backface-visibility: hidden;
      transform-style: preserve-3d;
    }

    @keyframes flipSlide {
      0% {
        transform: rotateY(0deg) scale(1);
        opacity: 1;
        filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.7));
      }

      50% {
        transform: rotateY(180deg) scale(1.05);
        opacity: 0.5;
        filter: drop-shadow(0 0 20px rgba(255, 255, 255, 1));
      }

      100% {
        transform: rotateY(360deg) scale(1);
        opacity: 1;
        filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.7));
      }
    }

    a.btn {
      z-index: 99999;
      position: fixed;
      top: 1rem;
      left: 1rem;
      background: #28a745;
      color: white;
      padding: 0.5rem 1rem;
      text-decoration: none;
      border-radius: 5px;
      font-size: 0.9rem;
    }
  </style>
  <script>
    function speak(text) {
      console.log("Speaking:", text);
      const msg = new SpeechSynthesisUtterance(text);
      msg.lang = 'en-US';
      msg.rate = 1;
      window.speechSynthesis.speak(msg);
    }
  </script>
  <script>
    function get_queueing() {

      $.ajax({
        url: '/all/queueing/latest',
        method: "GET",
        success: function(resp) {

          if (resp.status === 1) {

            speak(`Queue number ${resp.data.queue_no}, named ${resp.data.name}, please proceed to the window ${resp.data.window_id}. Again, Queue number ${resp.data.queue_no}, please proceed to the window ${resp.data.window_id}, thank you.`);
            $("#cashierno").text(resp.data.transaction_id < 3 ? "CASHIER" : "REGISTRAR");
            $("#sname").text(resp.data.name);
            $("#squeue").html(`<font size='10rem'>${resp.data.queue_no}</font>`);
            $("#window").text(`WINDOW #${resp.data.window_id}`);
          } else {
            // $("#cashierno").text("");
            // $("#sname").text("");
            // $("#squeue").html("NO QUERY FOR NOW");
            // $("#window").text("");
            // speak("NO QUERY FOR NOW");
            console.log("No queue data or status 0.");
          }
        },
        error: function(err) {
          console.error("AJAX error:", err);
        }
      });
    }
  </script>
</head>

<body>

  <div id="sidetop">
    <a href="{{ route('all.dashboard') }}" class="btn"><i class="fa fa-home"></i> Home</a>
    <a href="#" class="btn" id="fullscreenmode" style="margin-left:100px;" onclick="goFullscreen()"><i class="fa fa-tv"></i></a>
  </div>
  <div class="left-side">
    <div class="card">
      <div class="card-body bg-primary">
        <h4 class="text-white" id="cashierno"><b><?= ((isset($data['transaction_id']) && $data['transaction_id'] < 3) ? "CASHIER" : "REGISTRAR") ?></b></h4>
      </div>
      <div class="card-body">
        <h3 class="text-center">Now Serving</h3>
        <h4 class="text-center" id="sname"><?= ((isset($data['name'])) ? $data['name'] : "") ?></h4>
        <hr>
        <h1 class="text-center" id="squeue"><?= ((isset($data['queue_no'])) ? "<font size='10rem'>" . $data['queue_no'] . "</font>" : "NO QUERY FOR NOW") ?></h1>
        <hr>
        <h5 class="text-center" id="window">WINDOW # <?= ((isset($data['window_id'])) ? $data['window_id'] : "") ?></h5>
        <audio id="voiceplay">
          <source src="<?= ((isset($audioFile)) ? $audioFile : "NO QUEUE FOR NOW") ?>" type="audio/mpeg">
        </audio>
      </div>
    </div>
  </div>
  <style type="text/css">
    .slideShow {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      perspective: 1000px;
      background: linear-gradient(270deg, #ff416c, #ff4b2b, #ff6a00, #f9d423, #4facfe, #00f2fe, #43e97b, #38f9d7);
      background-size: 1600% 1600%;
      animation: bgGradient 20s ease infinite;
    }

    .slideShow img,
    .slideShow video {
      max-width: 100%;
      max-height: 100%;
      opacity: 0;
      transition: all 0.6s ease-in-out;
      border-radius: 0;
      /* <-- Remove rounding if present */
    }


    @keyframes bgGradient {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }
  </style>
  <div class="right-side">
    <div class="slideShow">
      <!-- Slides will be appended here -->
    </div>
  </div>

  <style type="text/css">
    #slide {
      opacity: 0;
      transition: opacity 0.5s ease-in-out;
    }
  </style>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- <script type="module" src="utils.js"></script> -->
  <script>
    var slides = [
      "{{ asset('img/idslogo.png') }}",
      "{{ asset('img/idslogo.png') }}",
      "{{ asset('videos/sample3.mp4') }}"
    ];
    var scount = slides.length;

    if (scount > 0) {
      $(document).ready(function() {
        render_slides(0);
      });
    }

    function render_slides(k) {

      if (k >= scount) k = 0;
      var src = slides[k];
      k++;
      var t = src.split('.').pop();
      var file;
      if (t === 'mp4') {
        file = $("<video id='slide' class='blinking-slide' src='" + src + "' autoplay muted></video>");
      } else {
        file = $("<img id='slide' class='blinking-slide' src='" + src + "' />");
      }

      $('.slideShow').html(file); // replace content
    }


    function slideInterval(i = 0) {
      setTimeout(function() {
        render_slides(i);
      }, 1);
    }

    function goFullscreen() {
      const element = document.documentElement; // Full page
      element.requestFullscreen();
    }

    function goFullscreen() {
      const element = document.documentElement; // Full page
      if (element.requestFullscreen) {
        element.requestFullscreen();
      } else if (element.webkitRequestFullscreen) { // Safari
        element.webkitRequestFullscreen();
      } else if (element.msRequestFullscreen) { // IE11
        element.msRequestFullscreen();
      }
    }

    let selectedVoice = null;

    function loadVoices() {
      const voices = speechSynthesis.getVoices();
      if (voices.length) {
        selectedVoice = voices.find(voice =>
          voice.name.toLowerCase().includes("zira") ||
          voice.name.toLowerCase().includes("samantha") ||
          voice.name.toLowerCase().includes("google us") ||
          voice.name.toLowerCase().includes("female")
        ) || voices[0];
        console.log("Voice loaded:", selectedVoice.name);
      } else {

        speechSynthesis.onvoiceschanged = loadVoices;
      }
    }




    $(document).ready(function() {

      get_queueing();
      setInterval(() => {
        setTimeout(() => {
          get_queueing();
        }, 1000);
      }, 10000);
    });
  </script>

</body>

</html>