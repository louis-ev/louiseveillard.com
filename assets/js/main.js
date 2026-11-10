"use strict";

/*
  Plain JS, no dependencies (replaces jQuery, jquery-pjax, Isotope, lazysizes
  and FastClick). Navigation between pages: see `pjax` below.
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
    this.headings = this.items.filter(function (item) { return item.classList.contains("module--projetList--heading"); });

    // initial order: latest projects, by number
    this.items.forEach(function (item, i) { item.dataset.position = i; });
    this.sort(null, false);

    var links = document.querySelectorAll('.module--intro a[href^="#"]');
    var self = this;

    // toggle buttons for assistive technologies
    links.forEach(function (link) {
      link.setAttribute("role", "button");
      link.setAttribute("aria-pressed", "false");
      link.setAttribute("aria-controls", self.list.id);

      link.addEventListener("click", function (event) {
        event.preventDefault();

        links.forEach(function (other) {
          if (other !== link) {
            other.classList.remove("is--active");
            other.setAttribute("aria-pressed", "false");
          }
        });
        var active = link.classList.toggle("is--active");
        link.setAttribute("aria-pressed", active ? "true" : "false");

        self.sort(active ? link.getAttribute("href").slice(1) : null, true);
      });

      // buttons are also activated with the space bar
      link.addEventListener("keydown", function (event) {
        if (event.key === " ") {
          event.preventDefault();
          link.click();
        }
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
        heading.setAttribute("aria-hidden", hidden ? "true" : "false");
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
 * Wide screens: hovering a project shows its cover in full screen and a glow
 * follows the mouse. Clicking a project marks its cover (`is--visuelProjet`)
 * so that pjax opens the project zoomed on it.
 */
var projectHover = {
  init: function (section) {
    if (section.dataset.hoverInit) return;
    section.dataset.hoverInit = "true";

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

    titles.addEventListener("click", function (event) {
      var link = event.target.closest(".module--projetList--titles--projetName--links");
      if (!link || !pjax.isPjaxLink(link, event)) return;

      var visuel = visuelFor(link.parentElement);
      if (!visuel || !visuel.currentSrc) return;

      document.querySelectorAll(".is--visuelProjet").forEach(function (el) { el.classList.remove("is--visuelProjet"); });
      visuel.classList.add("is--visuelProjet");
    });
  },
};

/**
 * Project page: cover fades out on scroll, click on the cover toggles full
 * screen. Opened from the project list, the page starts zoomed on the cover,
 * then the header rises over it.
 */
var projectView = {
  figure: null,
  ticking: false,

  zoomIn: function (zoom) {
    document.body.dataset.visuel = zoom ? "zoomedIn" : "";
    document.body.style.overflow = zoom ? "hidden" : "";
    if (window.scrollY > 0) {
      window.scrollTo({ top: 0, behavior: reducedMotion ? "auto" : "smooth" });
    }
  },

  init: function () {
    var self = this;
    var cover = document.querySelector(".module--projet_full .module--projet--visuel");
    var page = pjax.page;

    this.figure = null;

    if (!cover) {
      document.body.dataset.visuel = "";
      document.body.style.overflow = "";
      return;
    }

    cover.addEventListener("click", function (event) {
      event.preventDefault();
      self.zoomIn(document.body.dataset.visuel !== "zoomedIn");
    });

    // arrived from the project list (see pjax.load)
    if (document.body.dataset.visuel === "zoomedIn") {
      setTimeout(function () {
        document.querySelectorAll(".is--visuelProjet").forEach(function (el) { el.classList.remove("is--visuelProjet"); });
        setTimeout(function () {
          if (pjax.page === page) self.zoomIn(false);
        }, reducedMotion ? 0 : 400);
      }, reducedMotion ? 0 : 600);
    }

    if (window.innerWidth > WIDE_SCREEN) {
      this.figure = cover.querySelector(".FigureImage-lazy");
      this.update();

      if (!this.listening) {
        this.listening = true;
        window.addEventListener("scroll", function () {
          if (!self.ticking) {
            self.ticking = true;
            requestAnimationFrame(function () { self.update(); });
          }
        }, { passive: true });
      }
    }
  },

  update: function () {
    this.ticking = false;
    if (!this.figure) return;
    var progress = window.scrollY / (window.innerHeight * 0.44);
    this.figure.style.opacity = window.scrollY < window.innerHeight ? clamp(1 - progress, 0, 1) : 0;
  },
};

/**
 * "Site archivé" notice: closed for the rest of the browsing session
 */
function initArchiveNotice() {
  var notice = document.querySelector(".module--archive");
  if (!notice || storage("get", "lev:archiveClosed")) return;

  notice.hidden = false;
  notice.querySelector(".module--archive--close").addEventListener("click", function () {
    notice.hidden = true;
    storage("set", "lev:archiveClosed", true);
  });
}

/**
 * "Projets" in the navbar scrolls to the project list when there is one
 * (registered before pjax, which then ignores the click)
 */
function initNavbar() {
  document.addEventListener("click", function (event) {
    var link = event.target.closest('.module--navbar a[data-goto="projets"]');
    var list = document.querySelector(".module--projetList");
    if (!link || !list || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

    event.preventDefault();
    list.scrollIntoView({ behavior: reducedMotion ? "auto" : "smooth" });
  });
}

/**
 * Pjax navigation (replaces jquery-pjax): internal links load the next page
 * with fetch and only swap #main-container; the navbar, the archive notice
 * and the project list stay in place (the list is added or removed when
 * needed). Any problem falls back to a regular page load.
 */
var pjax = {
  page: 0,
  controller: null,
  delay: 400,

  init: function () {
    if (!window.fetch || !window.DOMParser || !history.pushState) return;

    var self = this;
    history.replaceState({ pjax: true, scrollY: window.scrollY }, "");
    if ("scrollRestoration" in history) history.scrollRestoration = "manual";

    document.addEventListener("click", function (event) {
      var link = event.target.closest("a");
      if (!self.isPjaxLink(link, event)) return;
      event.preventDefault();
      self.load(link.href, { push: true });
    });

    window.addEventListener("popstate", function (event) {
      if (!event.state || !event.state.pjax) return;
      self.load(location.href, { push: false, scrollY: event.state.scrollY });
    });

    // remember the scroll position of the current entry (back button)
    var saveScroll = function () {
      history.replaceState(Object.assign({}, history.state, { pjax: true, scrollY: window.scrollY }), "");
    };
    var scrollTimer;
    window.addEventListener("scroll", function () {
      clearTimeout(scrollTimer);
      scrollTimer = setTimeout(saveScroll, 150);
    }, { passive: true });
  },

  isPjaxLink: function (link, event) {
    if (!link || !link.href || event.defaultPrevented) return false;
    if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return false;
    if ((link.target && link.target !== "_self") || link.hasAttribute("download")) return false;
    if (link.origin !== location.origin) return false;

    // files, the Panel, and anchors within the current page
    var path = link.pathname;
    if (/^\/(panel|api|media|content|assets)(\/|$)/.test(path) || /\.[a-z0-9]{2,5}$/i.test(path)) return false;
    if (path === location.pathname && link.search === location.search && link.hash) return false;

    return true;
  },

  load: function (url, options) {
    var self = this;
    var body = document.body;
    var page = ++this.page;

    if (this.controller) this.controller.abort();
    this.controller = window.AbortController ? new AbortController() : null;

    var zoom = options.push && document.querySelector(".is--visuelProjet") !== null;
    body.classList.add("is--ajax_loading");

    var wait = new Promise(function (resolve) { setTimeout(resolve, reducedMotion ? 0 : self.delay); });
    var request = fetch(url, {
      credentials: "same-origin",
      signal: this.controller ? this.controller.signal : undefined,
    }).then(function (response) {
      var type = response.headers.get("Content-Type") || "";
      if ((!response.ok && response.status !== 404) || type.indexOf("text/html") === -1) {
        throw new Error("not a page");
      }
      return response.text();
    });

    Promise.all([request, wait]).then(function (results) {
      if (page !== self.page) return;

      var doc = new DOMParser().parseFromString(results[0], "text/html");
      if (!doc.querySelector("#main-container")) throw new Error("no #main-container");

      if (options.push) {
        history.pushState({ pjax: true, scrollY: 0 }, "", url);
      }

      self.swap(doc, zoom);
      body.classList.remove("is--ajax_loading");

      var hash = new URL(url).hash;
      var target = hash && document.getElementById(decodeURIComponent(hash.slice(1)));
      if (target) {
        target.scrollIntoView();
      } else {
        window.scrollTo(0, options.scrollY || 0);
      }

      pageInit();
    }).catch(function (error) {
      if (error && error.name === "AbortError") return;
      window.location.href = url;
    });
  },

  swap: function (doc, zoom) {
    var body = document.body;

    document.title = doc.title;

    ["template", "intendedTemplate", "rubrique", "index"].forEach(function (key) {
      body.dataset[key] = doc.body.dataset[key] || "";
    });
    body.dataset.visuel = zoom && doc.querySelector(".module--projet--visuel") ? "zoomedIn" : "";
    body.style.overflow = body.dataset.visuel ? "hidden" : "";
    if (!body.dataset.visuel) {
      document.querySelectorAll(".is--visuelProjet").forEach(function (el) { el.classList.remove("is--visuelProjet"); });
    }

    // navbar: active item
    var navList = document.querySelector(".module--navbar--list");
    var newNavList = doc.querySelector(".module--navbar--list");
    if (navList && newNavList) navList.replaceWith(document.adoptNode(newNavList));

    // main content
    var main = document.querySelector("#main-container");
    var newMain = document.adoptNode(doc.querySelector("#main-container"));
    main.replaceWith(newMain);
    this.runScripts(newMain);

    // project list: kept in place (with its loaded covers), or added / removed
    var list = document.querySelector(".module--projetList");
    var newList = doc.querySelector(".module--projetList");

    if (list && newList) {
      list.querySelectorAll(".module--projetList--titles--projetName").forEach(function (item) {
        var newItem = newList.querySelector('.module--projetList--titles--projetName[data-index="' + item.dataset.index + '"]');
        item.classList.toggle("is--active", !!newItem && newItem.classList.contains("is--active"));
      });
    } else if (newList) {
      newMain.after(document.adoptNode(newList));
    } else if (list) {
      list.remove();
    }

    newMain.setAttribute("tabindex", "-1");
    newMain.focus({ preventScroll: true });
  },

  // scripts inserted with the content (e.g. embeds) don't run by themselves
  runScripts: function (container) {
    container.querySelectorAll("script").forEach(function (old) {
      var script = document.createElement("script");
      Array.from(old.attributes).forEach(function (attr) { script.setAttribute(attr.name, attr.value); });
      script.textContent = old.textContent;
      old.replaceWith(script);
    });
  },
};

/**
 * Everything that depends on the page content, run on load and after each
 * pjax navigation
 */
function pageInit() {
  initDayNight();
  initImages();
  initVideos();
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
}

document.addEventListener("DOMContentLoaded", function () {
  initNavbar();
  initArchiveNotice();
  pjax.init();
  pageInit();

  // end of the fade-in
  requestAnimationFrame(function () {
    document.body.classList.remove("is--loading");
  });
});

// coming back with the back button (bfcache): reset the loading state
window.addEventListener("pageshow", function (event) {
  if (!event.persisted) return;
  document.body.classList.remove("is--ajax_loading");
  document.querySelectorAll(".is--visuelProjet").forEach(function (el) { el.classList.remove("is--visuelProjet"); });
});
