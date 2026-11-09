"use strict";

/*
  Plain JS, no dependencies (replaces jQuery, pjax, Isotope, lazysizes and
  FastClick). Navigation between pages uses cross-document view transitions
  (see main.css).
*/

var WIDE_SCREEN = 700;
var reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

function clamp(value, min, max) {
  return Math.min(Math.max(value, min), max);
}

function storage(action, key, value) {
  try {
    if (action === "get") return JSON.parse(sessionStorage.getItem(key));
    sessionStorage.setItem(key, JSON.stringify(value));
  } catch (e) {}
  return null;
}

/**
 * (jour: Bonjour nuit: Bonsoir): night variant between 18:00 and 05:59
 */
function initDayNight() {
  var hours = new Date().getHours();
  if (hours > 5 && hours < 18) return;

  document.querySelectorAll(".js--isJour").forEach(function (el) { el.remove(); });
  document.querySelectorAll(".js--isNuit").forEach(function (el) { el.hidden = false; });
}

/**
 * Images fade in once loaded
 */
function initImages() {
  var markLoaded = function (img) { img.classList.add("is--loaded"); };

  document.querySelectorAll(".FigureImage-lazy img").forEach(function (img) {
    if (img.complete && img.naturalWidth > 0) {
      markLoaded(img);
    } else {
      img.addEventListener("load", function () { markLoaded(img); }, { once: true });
      img.addEventListener("error", function () { markLoaded(img); }, { once: true });
    }
  });
}

/**
 * Videos with `autoplay: true` are muted and only play while visible
 */
function initVideos() {
  var videos = document.querySelectorAll("video[data-autoplay]");
  if (videos.length === 0) return;

  if (!("IntersectionObserver" in window)) {
    videos.forEach(function (video) { video.autoplay = true; video.play().catch(function () {}); });
    return;
  }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      var video = entry.target;
      if (entry.isIntersecting) {
        video.preload = "auto";
        video.play().catch(function () {});
      } else {
        video.pause();
      }
    });
  }, { rootMargin: "100px 0px" });

  videos.forEach(function (video) { observer.observe(video); });
}

/**
 * Projects already opened during this session (outlined numbers)
 */
var visited = {
  key: "lev:visited",

  add: function (path) {
    var paths = storage("get", this.key) || [];
    if (paths.indexOf(path) === -1) paths.push(path);
    storage("set", this.key, paths);
  },

  apply: function () {
    var paths = storage("get", this.key) || [];
    document.querySelectorAll(".module--projetList--titles--projetName").forEach(function (item) {
      var link = item.querySelector("a");
      if (link && paths.indexOf(new URL(link.href).pathname) !== -1) {
        item.classList.add("is--visited");
      }
    });
  },
};

/**
 * Home intro links (`#sites`, `#vr`…) sort the project list by category:
 * the selected category first, then the others, each under its heading.
 * Replaces Isotope: items get a CSS `order`, animated with a view transition.
 */
var projectFilter = {
  init: function (list) {
    this.list = list;
    this.items = Array.from(list.querySelectorAll(".isotope--item"));
    this.headings = this.items.filter(function (item) { return item.tagName === "H3"; });

    // initial order: latest projects, by number
    this.items.forEach(function (item, i) { item.dataset.position = i; });
    this.sort(null, false);

    var links = document.querySelectorAll('.module--intro a[href^="#"]');
    var self = this;

    links.forEach(function (link) {
      link.addEventListener("click", function (event) {
        event.preventDefault();

        links.forEach(function (other) {
          if (other !== link) other.classList.remove("is--active");
        });
        link.classList.toggle("is--active");

        var type = link.classList.contains("is--active") ? link.getAttribute("href").slice(1) : null;
        self.sort(type, true);
      });
    });
  },

  sort: function (type, animate) {
    var self = this;
    var number = function (item, key) { return parseInt(item.dataset[key], 10) || 0; };

    var sorted = this.items.slice().sort(function (a, b) {
      if (type === null) {
        // latest projects: by index, then category
        return number(a, "index") - number(b, "index")
          || number(a, "num") - number(b, "num")
          || number(a, "position") - number(b, "position");
      }

      // selected category first, then by category, heading before its projects
      var selected = function (item) { return item.dataset.type === type ? 0 : 1; };
      return selected(a) - selected(b)
        || number(a, "num") - number(b, "num")
        || number(a, "index") - number(b, "index")
        || number(a, "position") - number(b, "position");
    });

    var update = function () {
      sorted.forEach(function (item, i) { item.style.order = i; });

      self.headings.forEach(function (heading) {
        var hidden = type === null ? heading.dataset.type !== undefined : heading.dataset.type === undefined;
        heading.classList.toggle("is--hidden", hidden);
      });

      self.list.classList.toggle("is--sorted", type !== null);
    };

    if (!animate || reducedMotion || !document.startViewTransition) {
      update();
      return;
    }

    // each visible item morphs to its new position
    var visible = this.items.filter(function (item) {
      var rect = item.getBoundingClientRect();
      return rect.bottom > 0 && rect.top < window.innerHeight;
    });
    visible.forEach(function (item, i) { item.style.viewTransitionName = "projet-item-" + i; });

    document.startViewTransition(update).finished.finally(function () {
      visible.forEach(function (item) { item.style.viewTransitionName = ""; });
    });
  },
};

/**
 * Wide screens: hovering a project shows its cover in full screen, a glow
 * follows the mouse, and opening a project zooms on its cover
 */
var projectHover = {
  init: function (section) {
    var visuels = section.querySelectorAll(".module--projetList--visuelWrapper--visuel");
    var titles = section.querySelector(".module--projetList--titles");

    var visuelFor = function (item) {
      return section.querySelector('.module--projetList--visuelWrapper--visuel[data-index="' + item.dataset.index + '"]');
    };

    // covers are loaded once the list comes near the viewport
    var loadCovers = function () {
      visuels.forEach(function (img) {
        if (img.dataset.srcset) {
          img.srcset = img.dataset.srcset;
          delete img.dataset.srcset;
        }
      });
    };

    if ("IntersectionObserver" in window) {
      var observer = new IntersectionObserver(function (entries) {
        if (entries.some(function (entry) { return entry.isIntersecting; })) {
          loadCovers();
          observer.disconnect();
        }
      }, { rootMargin: "50% 0px" });
      observer.observe(titles);
    } else {
      loadCovers();
    }

    titles.addEventListener("mouseover", function (event) {
      var link = event.target.closest(".module--projetList--titles--projetName--links");
      if (!link || link.contains(event.relatedTarget)) return;
      var visuel = visuelFor(link.parentElement);
      if (visuel) {
        visuel.classList.remove("was--shown");
        visuel.classList.add("is--shown");
      }
    });

    titles.addEventListener("mouseout", function (event) {
      var link = event.target.closest(".module--projetList--titles--projetName--links");
      if (!link || link.contains(event.relatedTarget)) return;
      var visuel = visuelFor(link.parentElement);
      if (visuel) {
        visuel.classList.remove("is--shown");
        visuel.classList.add("was--shown");
      }
    });

    titles.addEventListener("mousemove", function (event) {
      var item = event.target.closest(".module--projetList--titles--projetName");
      if (!item) return;
      var rect = item.getBoundingClientRect();
      var corners = item.querySelector(".module--projetList--titles--projetName--links--corners");
      corners.style.setProperty("--x", event.clientX - rect.left + "px");
      corners.style.setProperty("--y", event.clientY - rect.top + "px");
    });

    // opening a project: its cover goes full screen, then morphs into the
    // project page cover (view transition), which starts zoomed in
    titles.addEventListener("click", function (event) {
      var link = event.target.closest(".module--projetList--titles--projetName--links");
      if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

      var visuel = visuelFor(link.parentElement);
      if (!visuel || !visuel.currentSrc) return;

      event.preventDefault();
      // read by the inline script at the top of <body> (header.php)
      try { sessionStorage.setItem("lev:zoomIn", new URL(link.href).pathname); } catch (e) {}
      document.querySelectorAll(".is--visuelProjet").forEach(function (el) { el.classList.remove("is--visuelProjet"); });
      visuel.classList.add("is--visuelProjet");
      document.body.classList.add("is--leaving");

      setTimeout(function () { window.location.href = link.href; }, reducedMotion ? 0 : 400);
    });
  },
};

/**
 * Project page: cover fades out on scroll, click on the cover toggles full screen
 */
var projectView = {
  init: function () {
    var cover = document.querySelector(".module--projet_full .module--projet--visuel");
    if (!cover) return;

    var body = document.body;

    var zoomIn = function (zoom) {
      body.dataset.visuel = zoom ? "zoomedIn" : "";
      body.style.overflow = zoom ? "hidden" : "";
      if (window.scrollY > 0) {
        window.scrollTo({ top: 0, behavior: reducedMotion ? "auto" : "smooth" });
      }
    };

    cover.addEventListener("click", function (event) {
      event.preventDefault();
      zoomIn(body.dataset.visuel !== "zoomedIn");
    });

    // arrived from the project list: zoomed in, then reveal the header
    if (body.dataset.visuel === "zoomedIn") {
      body.style.overflow = "hidden";
      setTimeout(function () { zoomIn(false); }, reducedMotion ? 0 : 1000);
    }

    if (window.innerWidth > WIDE_SCREEN) {
      var figure = cover.querySelector(".FigureImage-lazy");
      var ticking = false;

      var update = function () {
        ticking = false;
        var progress = window.scrollY / (window.innerHeight * 0.44);
        figure.style.opacity = window.scrollY < window.innerHeight ? clamp(1 - progress, 0, 1) : 0;
      };

      window.addEventListener("scroll", function () {
        if (!ticking) {
          ticking = true;
          requestAnimationFrame(update);
        }
      }, { passive: true });
    }
  },
};

/**
 * "Projets" in the navbar scrolls to the project list when there is one
 */
function initNavbar() {
  var link = document.querySelector('.module--navbar a[data-goto="projets"]');
  var list = document.querySelector(".module--projetList");
  if (!link || !list) return;

  link.addEventListener("click", function (event) {
    event.preventDefault();
    list.scrollIntoView({ behavior: reducedMotion ? "auto" : "smooth" });
  });
}

document.addEventListener("DOMContentLoaded", function () {
  initDayNight();
  initImages();
  initVideos();
  initNavbar();
  projectView.init();

  if (document.body.dataset.intendedTemplate === "projet") {
    visited.add(location.pathname);
  }
  visited.apply();

  var list = document.querySelector(".module--projetList");
  if (list) {
    projectFilter.init(list.querySelector(".module--projetList--titles"));

    if (window.innerWidth > WIDE_SCREEN) {
      projectHover.init(list);
    }
  }

  // end of the fade-in
  requestAnimationFrame(function () {
    document.body.classList.remove("is--loading");
  });
});

// coming back with the back button (bfcache): reset the leaving state
window.addEventListener("pageshow", function (event) {
  if (!event.persisted) return;
  document.body.classList.remove("is--leaving");
  document.querySelectorAll(".is--visuelProjet").forEach(function (el) { el.classList.remove("is--visuelProjet"); });
});
