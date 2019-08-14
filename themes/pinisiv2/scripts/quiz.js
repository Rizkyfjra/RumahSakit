if ($(window).width() > 960) {
  $("#wrapper").toggleClass("toggled");
}

  // 15 days from now!
  function get15dayFromNow() {
    return new Date(new Date().valueOf() + 24 * 60 * 60 * 1000);
  }

  var $clock = $('#clock');

  $clock.countdown(get15dayFromNow(), function(event) {
    $(this).html(event.strftime('%H:%M:%S'));
  });
