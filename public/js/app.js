/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
// import './styles/app.css';

// start the Stimulus application
// import './bootstrap';

/**
  * Load More Tricks
  */

$(document).ready(function(){
  $(".trick-content").slice(0, 6).show();
  $("#loadMore").on("click", function(e){
    e.preventDefault();
    $(".trick-content:hidden").slice(0, 6).slideDown();
    if($(".trick-content:hidden").length == 0) {
      $("#loadMore").hide();
    }
  });
  
})

/**
  * Load More Comments
  */

 $(document).ready(function(){
  $(".comment-content").slice(0, 6).show();
  $("#loadMoreComment").on("click", function(e){
    e.preventDefault();
    $(".comment-content:hidden").slice(0, 6).slideDown();
    if($(".comment-content:hidden").length == 0) {
      $("#loadMoreComment").hide();
    }
  });
  
})

  /**
   * Easy on scroll event listener 
   */
   const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Back to top button
   */
   let backtotop = document.querySelector('.back-to-top')
   if (backtotop) {
     const toggleBacktotop = () => {
       if (window.scrollY > 1200) {
         backtotop.classList.add('active')
       } else {
         backtotop.classList.remove('active')
       }
     }
     window.addEventListener('load', toggleBacktotop)
     onscroll(document, toggleBacktotop)
   }


   /**
    * Add lines in create trick form
    */

     const addTagLink = document.createElement('a')
      addTagLink.classList.add('add_tag_list')
      addTagLink.href='#'
      addTagLink.innerText='Ajouter une image ou une vidéo'
      addTagLink.dataset.collectionHolderClass='tags'
      
      const newLinkLi = document.createElement('li').append(addTagLink)
      
      const collectionHolder = document.querySelector('ul.tags')
      collectionHolder.appendChild(addTagLink)
      
      const addFormToCollection = (e) => {
        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
      
            const item = document.createElement('li');
      
            item.innerHTML = collectionHolder
              .dataset
              .prototype
              .replace(
                /__name__/g,
                collectionHolder.dataset.index
              );
      
            collectionHolder.appendChild(item);
      
            collectionHolder.dataset.index++;
      }

      addTagLink.addEventListener("click", function(e) {
        e.preventDefault();
      })
      
      addTagLink.addEventListener("click", addFormToCollection)