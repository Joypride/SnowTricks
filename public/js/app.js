/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';


$(document).ready(function(){
  $(".trick-content").slice(0, 6).show();
  $("#loadMore").on("click", function(e){
    e.preventDefault();
    $(".trick-content:hidden").slice(0, 6).slideDown();
    if($(".trick-content:hidden").length == 0) {
      $("#loadMore").text("No Content").addClass("noContent");
    }
  });
  
})

/**
   * Porfolio isotope and filter
   */
 window.addEventListener('load', () => {
    let carouselContainer = select('.carousel-container');
    if (carouselContainer) {
      let carouselIsotope = new Isotope(carouselContainer, {
        itemSelector: '.trick-item',
        layoutMode: 'fitRows'
      });

      let carouselFilters = select('#carousel-filters li', true);

      on('click', '#carousel-filters li', function(e) {
        e.preventDefault();
        carouselFilters.forEach(function(el) {
          el.classList.remove('filter-active');
        });
        this.classList.add('filter-active');

        carouselIsotope.arrange({
          filter: this.getAttribute('data-filter')
        });
        carouselIsotope.on('arrangeComplete', function() {
          AOS.refresh()
        });
      }, true);
    }

  });
