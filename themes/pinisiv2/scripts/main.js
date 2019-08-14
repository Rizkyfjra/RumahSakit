$(document).ready(function () {
  $('.collapsible-row').readmore({
    speed: 500,
    collapsedHeight: 20,
    moreLink: '<a class="clickable-more-row" href="#">Read more</a>',
    lessLink: '<div class="clearfix"><a class="btn btn-danger btn-sm" style="margin-top: 10px;" href="#"><i class="fa fa-times-circle"></i> Tutup Soal</a></div>'
  });
});
