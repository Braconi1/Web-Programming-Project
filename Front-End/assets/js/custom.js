$(document).ready(function() {

  $("main#spapp > section").height($(document).height() - 60);

  var app = $.spapp({
    defaultView: "home",  
    pageNotFound: "error_404",
    templateDir: "Front-End/tpl/" 
  });

  app.route({ view: "home", load: "home.html" });
  app.route({ view: "vote", load: "vote.html" });
  app.route({ view: "about", load: "about.html" });
  app.route({ view: "stranke", load: "stranke.html" });
  app.route({ view: "contact", load: "contact.html" });
  app.route({ view: "login", load: "login.html" });
  app.route({ view: "sign-up", load: "sign-up.html" });
  app.route({ view: "admin-panel", load: "admin-panel.html" });

  app.run();
});




// NAVBAR SCRIPT
window.addEventListener('DOMContentLoaded', event => {

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink')
        } else {
            navbarCollapsible.classList.add('navbar-shrink')
        }

    };

    // Shrink the navbar 
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener('scroll', navbarShrink);

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            rootMargin: '0px 0px -40%',
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

    // Activate SimpleLightbox plugin for portfolio items
    new SimpleLightbox({
        elements: '#portfolio a.portfolio-box'
    });

});

function showSidebar(){
      const sidebar = document.querySelector('.sidebar')
      sidebar.style.display = 'flex'
    }
    function hideSidebar(){
      const sidebar = document.querySelector('.sidebar')
      sidebar.style.display = 'none'
    }

let lastScroll = 0;
  const footer = document.querySelector('.footer-container');

  window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;

    if (currentScroll > lastScroll) {
      
      footer.classList.add('visible');
    } else {
      footer.classList.remove('visible');
    }

    lastScroll = currentScroll;
  });
