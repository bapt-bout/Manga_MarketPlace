

// la gestion des footers pour la page d'accueil
document.addEventListener("DOMContentLoaded", function() {
    // Détection de la page d'accueil
    if (window.location.pathname === "/") {
      // Afficher le footer de la page d'accueil
      document.getElementById("homepage-footer").style.display = "block";
      // Masquer le footer des pages fixes
      document.getElementById("fixed-pages-footer").style.display = "none";
    } else {
      // Masquer le footer de la page d'accueil
      document.getElementById("homepage-footer").style.display = "none";
      // Afficher le footer des pages fixes
      document.getElementById("fixed-pages-footer").style.display = "block";
    }
  });
  
  
  // Affichage ou masque le footer en fonction du défilement
  document.addEventListener("DOMContentLoaded", function() {
    const footer = document.querySelector("footer");

    function showFooter() {
        const scrollY = window.scrollY || window.pageYOffset;
        const windowHeight = window.innerHeight;
        const bodyHeight = document.body.scrollHeight;

        if (scrollY + windowHeight >= bodyHeight) {
            footer.style.display = "block";
        } else {
            footer.style.display = "none";
        }
    }

    // Appelez la fonction pour afficher ou masquer le footer au chargement de la page
    showFooter();

    // Appelez la fonction lorsque l'utilisateur fait défiler la page
    window.addEventListener("scroll", showFooter);
});


window.addEventListener('scroll', function(event) {
  var topDistance = window.pageYOffset;
  var layers = document.querySelectorAll("[data-type='parallax']");
  
  for (var i = 0; i < layers.length; i++) {
    var layer = layers[i];
    var depth = layer.getAttribute('data-depth');
    var movement = -(topDistance * depth);
    var translate3d = 'translate3d(0, ' + movement + 'px, 0)';
    layer.style['-webkit-transform'] = translate3d;
    layer.style['-moz-transform'] = translate3d;
    layer.style['-ms-transform'] = translate3d;
    layer.style['-o-transform'] = translate3d;
    layer.style.transform = translate3d;
  }
});
