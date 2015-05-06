$(document).ready(function()
{    
  $('.scroll').click(function(){$('body').animate({scrollTop:0},800);});
  $('#toggleside').click(function(){$('#sidebar_panel').toggle('slide'); }); 
 
  var showed = 0;
  $('.sipka').click(function()
  {
    $('.more').css('width', window.innerWidth-10);
    if(showed == 0) 
    {
      $('.more').slideUp();
      showed = 1;
    }
    else
    {
      $('.more').slideDown();
      showed = 0;
    }
  }); 

  var explore_width = (70 / 100) * window.innerWidth + 50;
  $('.explorearea').css('width', explore_width);
  
  $(document).scroll(function() 
  {
    $('.scroll').toggle($(this).scrollTop() > (25 / 100) * window.innerHeight);
    $('#malemenu').toggle($(this).scrollTop() > (25 / 100) * window.innerHeight);
    var middle = (window.innerWidth / 2) - 200;
    $('#cara_panel_midle').css('margin-left', middle);
    
    //if($(window).scrollTop() > 20 / 100 * window.innerHeight) $('.cara_panel').addClass("bottomborder");
    //else $('.cara_panel').removeClass("bottomborder"); 
  });  
});