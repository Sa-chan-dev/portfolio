'use strict';   // Mode strict du JavaScript
//quand la page est chargée
$(function() {
    $( document ).ready(function() {

        //au clic l'objet reste en hover
        $('.card-container').on('click', function() {
            $(this).toggleClass("active");
        });


        //au scroll de la timeline
        var items = document.querySelectorAll(".item_xp");

        //on regarde si l'element est dans le dom est visible sert pour le scroll
        function isElementInViewport(el) {
            //retourne la taille de l'élement et sa position par rapport à la zone d'affichage
            var rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
        //fonction permettant d'ajouter une classe show pour chque element item 
        function showBloc() {
            for (var i = 0; i < items.length; i++) {
                if (isElementInViewport(items[i])) {
                    items[i].classList.add("show");
                }
            }
        }
        
          // a chaque evenement  on lance la fonction
            window.addEventListener("load", showBloc);
            window.addEventListener("scroll", showBloc);
        
        });
    
        //fermeture du message flash

        $('.close').on('click', function() {
            $('.alert-success').addClass("hide-alert");
            $('.hide-alert').removeClass("alert-success");
        });
    });
