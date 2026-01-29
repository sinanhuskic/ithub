// ============================================
// IT Hub Zavidovići - JavaScript
// ============================================

// Check if user prefers reduced motion
const prefersReducedMotion = window.matchMedia(
  "(prefers-reduced-motion: reduce)",
).matches;

// Hard reload - uvijek kreni od vrha stranice
if ("scrollRestoration" in history) {
  history.scrollRestoration = "manual";
}
window.scrollTo(0, 0);

document.addEventListener("DOMContentLoaded", () => {
  initCursorGlow();
  initParticles();
  initNavbar();
  initMobileMenu();
  initSmoothScroll();
  initCounterAnimation();
  initScrollAnimations();
  initTiltEffect();
  initActiveNavLink();
  initCodeTyping();
  initTerminalTyping();
  initProgramAccordion();
  initProgramsToggle();
  initLightbox();
});

// Cursor Glow Effect
function initCursorGlow() {
  const cursor = document.querySelector(".cursor-glow");
  if (!cursor) return;

  let mouseX = 0,
    mouseY = 0,
    cursorX = 0,
    cursorY = 0;

  document.addEventListener("mousemove", (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
  });

  function animateCursor() {
    const speed = 0.15;
    cursorX += (mouseX - cursorX) * speed;
    cursorY += (mouseY - cursorY) * speed;
    cursor.style.left = cursorX + "px";
    cursor.style.top = cursorY + "px";
    requestAnimationFrame(animateCursor);
  }
  animateCursor();
}

// Floating Particles - optimized for performance
function initParticles() {
  const container = document.getElementById("particles");
  if (!container) return;

  // Skip particles if user prefers reduced motion
  if (prefersReducedMotion) return;

  // Reduce particles based on screen size for better performance
  const screenWidth = window.innerWidth;
  let particleCount;

  if (screenWidth < 480) {
    particleCount = 15; // Very small screens
  } else if (screenWidth < 768) {
    particleCount = 20; // Mobile
  } else {
    particleCount = 40; // Desktop
  }

  // Use DocumentFragment for better performance
  const fragment = document.createDocumentFragment();

  for (let i = 0; i < particleCount; i++) {
    const particle = document.createElement("div");
    particle.className = "particle";
    const size = Math.random() * 4 + 2;
    particle.style.cssText = `
            width: ${size}px;
            height: ${size}px;
            left: ${Math.random() * 100}%;
            animation-delay: ${Math.random() * 5}s;
            animation-duration: ${Math.random() * 8 + 6}s;
        `;
    fragment.appendChild(particle);
  }
  container.appendChild(fragment);
}

// Navbar Scroll Effect
function initNavbar() {
  const navbar = document.getElementById("navbar");
  if (!navbar) return;

  // Check scroll position on page load
  function checkScroll() {
    if (window.pageYOffset > 50) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  }

  // Run on load
  checkScroll();

  // Run on scroll
  window.addEventListener("scroll", checkScroll);
}

// Mobile Menu
function initMobileMenu() {
  const menuBtn = document.getElementById("mobileMenuBtn");
  const menuOverlay = document.getElementById("mobileMenuOverlay");
  const menuClose = document.getElementById("mobileMenuClose");
  const menuLinks = document.querySelectorAll(".mobile-menu-link");

  if (!menuBtn || !menuOverlay) return;

  function openMenu() {
    menuOverlay.classList.add("active");
    menuBtn.setAttribute("aria-expanded", "true");
    document.body.style.overflow = "hidden";
  }

  function closeMenu() {
    menuOverlay.classList.remove("active");
    menuBtn.setAttribute("aria-expanded", "false");
    document.body.style.overflow = "";
  }

  // Open menu on hamburger click
  menuBtn.addEventListener("click", openMenu);

  // Close menu on X button click
  menuClose?.addEventListener("click", closeMenu);

  // Close menu on overlay background click
  menuOverlay.addEventListener("click", (e) => {
    if (e.target === menuOverlay) closeMenu();
  });

  // Close menu on link click
  menuLinks.forEach((link) => {
    link.addEventListener("click", closeMenu);
  });

  // Close menu on Escape key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && menuOverlay.classList.contains("active")) {
      closeMenu();
    }
  });
}

// Smooth Scroll
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        window.scrollTo({
          top: target.offsetTop - 80,
          behavior: "smooth",
        });
      }
    });
  });
}

// Counter Animation
function initCounterAnimation() {
  const statItems = document.querySelectorAll(".stat-item");
  if (!statItems.length) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.5 },
  );

  statItems.forEach((item) => observer.observe(item));
}

function animateCounter(element) {
  const numberEl = element.querySelector(".stat-current");
  const barFill = element.querySelector(".stat-bar-fill");
  if (!numberEl) return;

  const target = parseInt(numberEl.dataset.count);
  const targetWidth = barFill ? parseInt(barFill.dataset.width) : 0;
  const duration = 2000;
  const startTime = performance.now();

  function updateNumber(currentTime) {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);
    const easeOutQuart = 1 - Math.pow(1 - progress, 4);

    // Animate number
    numberEl.textContent = Math.floor(easeOutQuart * target);

    // Animate progress bar
    if (barFill) {
      barFill.style.width = easeOutQuart * targetWidth + "%";
    }

    if (progress < 1) {
      requestAnimationFrame(updateNumber);
    } else {
      numberEl.textContent = target;
      if (barFill) {
        barFill.style.width = targetWidth + "%";
      }
    }
  }
  requestAnimationFrame(updateNumber);
}

// Scroll Animations - elementi ulaze i izlaze pri svakom scrollu
function initScrollAnimations() {
  // Selektori za elemente IZVAN hero sekcije
  const elements = document.querySelectorAll(
    ".section-header, .about-content > *, .program-card, .gallery-item, " +
      ".testimonial-card, .tech-item, .partner-logo, .contact-item",
  );

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        // Samo dodaj klasu, ne uklanjaj (spriječava flickering)
        if (entry.isIntersecting) {
          entry.target.classList.add("animate-visible");
        }
      });
    },
    { threshold: 0.15, rootMargin: "0px 0px -50px 0px" },
  );

  elements.forEach((el) => {
    el.classList.add("animate-on-scroll");
    observer.observe(el);
  });

  const style = document.createElement("style");
  style.textContent = `
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .animate-on-scroll.animate-visible {
            opacity: 1;
            transform: translateY(0);
        }
    `;
  document.head.appendChild(style);
}

// Code Typing Animation
function initCodeTyping() {
  const codeElement = document.getElementById("typed-code");
  if (!codeElement) return;

  // Skip typing animation if user prefers reduced motion
  if (prefersReducedMotion) {
    // Show final state immediately
    codeElement.innerHTML = `<span class="code-comment">// IT Hub Zavidovići - Est. 15.09.2025</span>
<span class="code-keyword">const</span> <span class="code-variable">ithub</span> = { <span class="code-property">status</span>: <span class="code-string">"Aktivan"</span> };
<span class="code-success">✓ IT Hub aktivan već: <span id="age-output" class="age-counter"></span></span>`;
    const ageOutput = document.getElementById("age-output");
    if (ageOutput) {
      setInterval(() => {
        const founded = new Date("2025-09-15T10:00:00");
        const now = new Date();
        const diff = now - founded;
        if (diff < 0) {
          ageOutput.textContent = "Uskoro krećemo!";
          return;
        }
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
        const mins = Math.floor((diff / (1000 * 60)) % 60);
        const secs = Math.floor((diff / 1000) % 60);
        ageOutput.textContent = `${days}d ${hours}h ${mins}m ${secs}s`;
      }, 1000);
    }
    return;
  }

  const founded = new Date("2025-09-15T10:00:00");

  function getAge() {
    const now = new Date();
    const diff = now - founded;
    if (diff < 0) return "Uskoro krećemo!";

    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const months = Math.floor(days / 30.44);
    const years = Math.floor(days / 365.25);

    if (years > 0) {
      const remainingMonths = months - years * 12;
      const remainingDays = days - Math.floor(years * 365.25);
      return `${years}g ${remainingMonths}mj ${remainingDays % 30}d ${hours % 24}h ${minutes % 60}m ${seconds % 60}s`;
    } else if (months > 0) {
      return `${months}mj ${days % 30}d ${hours % 24}h ${minutes % 60}m ${seconds % 60}s`;
    } else if (days > 0) {
      return `${days}d ${hours % 24}h ${minutes % 60}m ${seconds % 60}s`;
    } else {
      return `${hours}h ${minutes % 60}m ${seconds % 60}s`;
    }
  }

  const codeLines = [
    {
      text: '<span class="code-comment">// IT Hub Zavidovići - Est. 15.09.2025</span>',
      delay: 25,
    },
    { text: "", delay: 30 },
    {
      text: '<span class="code-keyword">const</span> <span class="code-variable">ithub</span> = {',
      delay: 25,
    },
    {
      text: '  <span class="code-property">osnovano</span>: <span class="code-string">"15.09.2025"</span>,',
      delay: 20,
    },
    {
      text: '  <span class="code-property">lokacija</span>: <span class="code-string">"Zavidovići, BiH"</span>,',
      delay: 20,
    },
    {
      text: '  <span class="code-property">misija</span>: <span class="code-string">"Edukacija za budućnost"</span>,',
      delay: 20,
    },
    {
      text: '  <span class="code-property">status</span>: <span class="code-string">"Aktivan"</span>',
      delay: 20,
    },
    { text: "};", delay: 25 },
    { text: "", delay: 30 },
    {
      text: '<span class="code-comment">// Koliko dugo gradimo IT zajednicu?</span>',
      delay: 25,
    },
    {
      text: '<span class="code-variable">ithub</span>.<span class="code-function">izracunajVrijeme</span>();',
      delay: 20,
    },
    { text: "", delay: 30 },
    {
      text: '<span class="code-terminal">› Izvršavam...</span>',
      delay: 0,
      instant: true,
      wait: 300,
    },
    {
      text: '<span class="code-terminal">› Povezivanje na server...</span>',
      delay: 0,
      instant: true,
      wait: 400,
    },
    {
      text: '<span class="code-terminal">› Učitavanje podataka...</span>',
      delay: 0,
      instant: true,
      wait: 500,
    },
    {
      text: '<span class="code-terminal">› Računam vrijeme...</span>',
      delay: 0,
      instant: true,
      wait: 400,
    },
    { text: "", delay: 30 },
    {
      text: '<span class="code-success">✓ IT Hub aktivan već: <span id="age-output" class="age-counter"></span></span>',
      delay: 0,
      instant: true,
      final: true,
    },
  ];

  let lineIndex = 0;
  let charIndex = 0;
  let currentText = "";
  let isTag = false;
  let tagBuffer = "";
  let completedLines = [];

  function renderCode() {
    let html = completedLines.join("\n");
    if (currentText) {
      if (html) html += "\n";
      html += currentText;
    }
    codeElement.innerHTML = html;
  }

  function typeCode() {
    if (lineIndex >= codeLines.length) {
      // Start age counter after typing is done
      updateAge();
      setInterval(updateAge, 1000);
      return;
    }

    const line = codeLines[lineIndex];

    // Handle instant lines (terminal output)
    if (line.instant) {
      completedLines.push(line.text);
      renderCode();
      lineIndex++;

      if (line.final) {
        // Final line - start counter
        setTimeout(() => {
          updateAge();
          setInterval(updateAge, 1000);
        }, 100);
        return;
      }

      setTimeout(typeCode, line.wait || 150);
      return;
    }

    const fullText = line.text;

    if (charIndex < fullText.length) {
      const char = fullText[charIndex];

      // Handle HTML tags - type them instantly
      if (char === "<") {
        isTag = true;
        tagBuffer = "<";
      } else if (char === ">" && isTag) {
        tagBuffer += ">";
        currentText += tagBuffer;
        tagBuffer = "";
        isTag = false;
      } else if (isTag) {
        tagBuffer += char;
      } else {
        currentText += char;
      }

      charIndex++;
      renderCode();

      setTimeout(typeCode, isTag ? 0 : line.delay);
    } else {
      // Move to next line
      completedLines.push(line.text);
      lineIndex++;
      charIndex = 0;
      currentText = "";
      setTimeout(typeCode, 100);
    }
  }

  function updateAge() {
    const ageOutput = document.getElementById("age-output");
    if (ageOutput) {
      ageOutput.textContent = getAge();
    }
  }

  // Start typing after a short delay
  setTimeout(typeCode, 600);
}

// Tilt Effect for Cards
function initTiltEffect() {
  const cards = document.querySelectorAll("[data-tilt]");
  if (!cards.length) return;

  cards.forEach((card) => {
    card.addEventListener("mousemove", (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      const rotateX = (y - centerY) / 20;
      const rotateY = (centerX - x) / 20;
      card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
    });

    card.addEventListener("mouseleave", () => {
      card.style.transform =
        "perspective(1000px) rotateX(0) rotateY(0) translateY(0)";
    });
  });
}

// Active Navigation Link
function initActiveNavLink() {
  const sections = document.querySelectorAll("section[id]");
  const navLinks = document.querySelectorAll(".nav-link");

  function updateActiveLink() {
    const scrollPosition = window.scrollY + 150;

    sections.forEach((section) => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.offsetHeight;
      const sectionId = section.getAttribute("id");

      if (
        scrollPosition >= sectionTop &&
        scrollPosition < sectionTop + sectionHeight
      ) {
        navLinks.forEach((link) => {
          link.classList.remove("active");
          if (link.getAttribute("href") === `#${sectionId}`) {
            link.classList.add("active");
          }
        });
      }
    });
  }

  window.addEventListener("scroll", updateActiveLink);
  updateActiveLink();
}

// Terminal Typing Animation
function initTerminalTyping() {
  const terminalElement = document.getElementById("terminal-typed");
  if (!terminalElement) return;

  // Skip typing animation if user prefers reduced motion
  if (prefersReducedMotion) {
    terminalElement.innerHTML = `<div class="terminal-line"><span class="terminal-prompt">$</span> npm run start-future</div>
<div class="terminal-output"><span class="terminal-success">+ programming@latest</span></div>
<div class="terminal-output"><span class="terminal-success">+ creativity@latest</span></div>
<div class="terminal-output"><span class="terminal-success">+ teamwork@latest</span></div>
<div class="terminal-output"><span class="success-msg">Ready to code the future!</span></div>`;
    return;
  }

  const terminalLines = [
    { type: "command", prompt: "$", text: "cd /it-hub-zavidovici", delay: 40 },
    { type: "empty", delay: 300 },
    { type: "command", prompt: "$", text: "npm install skills", delay: 40 },
    { type: "empty", delay: 200 },
    {
      type: "output",
      text: "+ programming@latest",
      class: "terminal-success",
      delay: 0,
      wait: 150,
    },
    {
      type: "output",
      text: "+ creativity@latest",
      class: "terminal-success",
      delay: 0,
      wait: 150,
    },
    {
      type: "output",
      text: "+ teamwork@latest",
      class: "terminal-success",
      delay: 0,
      wait: 150,
    },
    { type: "empty", delay: 300 },
    { type: "command", prompt: "$", text: "npm run start-future", delay: 40 },
    { type: "empty", delay: 400 },
    {
      type: "output",
      text: "Ready to code the future!",
      class: "success-msg",
      delay: 0,
      wait: 100,
      final: true,
    },
  ];

  let lineIndex = 0;
  let charIndex = 0;
  let currentLineElement = null;
  let hasStarted = false;

  function createLineElement(line) {
    if (line.type === "empty") {
      return null;
    }

    const div = document.createElement("div");

    if (line.type === "command") {
      div.className = "terminal-line";
      div.innerHTML = `<span class="terminal-prompt">${line.prompt}</span> <span class="terminal-command"></span><span class="terminal-cursor">|</span>`;
    } else if (line.type === "output") {
      div.className = "terminal-output";
      div.innerHTML = `<span class="${line.class || ""}"></span>`;
    }

    return div;
  }

  function typeTerminal() {
    if (lineIndex >= terminalLines.length) {
      // Remove cursor when done
      const cursor = terminalElement.querySelector(".terminal-cursor");
      if (cursor) cursor.remove();
      return;
    }

    const line = terminalLines[lineIndex];

    // Handle empty lines (just a pause)
    if (line.type === "empty") {
      lineIndex++;
      setTimeout(typeTerminal, line.delay);
      return;
    }

    // Create new line element if needed
    if (!currentLineElement) {
      currentLineElement = createLineElement(line);
      if (currentLineElement) {
        terminalElement.appendChild(currentLineElement);
      }
    }

    // Handle output lines (instant)
    if (line.type === "output") {
      const textSpan = currentLineElement.querySelector("span");
      textSpan.textContent = line.text;

      lineIndex++;
      charIndex = 0;
      currentLineElement = null;

      if (line.final) {
        const cursor = terminalElement.querySelector(".terminal-cursor");
        if (cursor) cursor.remove();
        return;
      }

      setTimeout(typeTerminal, line.wait || 150);
      return;
    }

    // Handle command lines (typed character by character)
    if (line.type === "command") {
      const commandSpan = currentLineElement.querySelector(".terminal-command");

      if (charIndex < line.text.length) {
        commandSpan.textContent += line.text[charIndex];
        charIndex++;
        setTimeout(typeTerminal, line.delay);
      } else {
        // Move cursor to end, then go to next line
        const cursor = currentLineElement.querySelector(".terminal-cursor");
        if (cursor) cursor.remove();

        lineIndex++;
        charIndex = 0;
        currentLineElement = null;
        setTimeout(typeTerminal, 200);
      }
    }
  }

  // Start typing when terminal is visible
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting && !hasStarted) {
          hasStarted = true;
          setTimeout(typeTerminal, 500);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.5 },
  );

  observer.observe(terminalElement);
}

// Program Cards Accordion
function initProgramAccordion() {
  const programCards = document.querySelectorAll(".program-card");
  if (!programCards.length) return;

  programCards.forEach((card) => {
    // Klik na cijelu karticu
    card.addEventListener("click", (e) => {
      // Ignoriši klik na linkove unutar kartice (npr. "Prijavi se")
      if (e.target.closest("a")) return;

      toggleCard(card);
    });

    // Keyboard accessibility (Enter i Space)
    card.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        toggleCard(card);
      }
    });
  });

  function toggleCard(card) {
    const isExpanded = card.classList.contains("expanded");

    // Zatvori sve ostale kartice (accordion ponašanje)
    document.querySelectorAll(".program-card.expanded").forEach((openCard) => {
      if (openCard !== card) {
        openCard.classList.remove("expanded");
      }
    });

    // Toggle trenutnu karticu
    card.classList.toggle("expanded");

    // Smooth scroll do kartice ako se otvara
    if (!isExpanded) {
      setTimeout(() => {
        card.scrollIntoView({ behavior: "smooth", block: "nearest" });
      }, 100);
    }
  }
}

// Programs Show More/Less Toggle
function initProgramsToggle() {
  const toggleBtn = document.getElementById("toggleProgramsBtn");
  const programsGrid = document.getElementById("programsGrid");
  if (!toggleBtn || !programsGrid) return;

  const btnText = toggleBtn.querySelector(".btn-text");

  toggleBtn.addEventListener("click", () => {
    const isExpanded = programsGrid.classList.contains("expanded");

    if (isExpanded) {
      programsGrid.classList.remove("expanded");
      toggleBtn.classList.remove("expanded");
      btnText.textContent = "Prikaži sve programe";
    } else {
      programsGrid.classList.add("expanded");
      toggleBtn.classList.add("expanded");
      btnText.textContent = "Sakrij programe";
    }
  });
}

// Lightbox Gallery
function initLightbox() {
  const lightbox = document.getElementById("lightbox");
  const lightboxImage = lightbox?.querySelector(".lightbox-image");

  const closeBtn = lightbox?.querySelector(".lightbox-close");
  const prevBtn = lightbox?.querySelector(".lightbox-prev");
  const nextBtn = lightbox?.querySelector(".lightbox-next");
  const galleryItems = document.querySelectorAll("[data-lightbox]");

  if (!lightbox || !galleryItems.length) return;

  let currentIndex = 0;
  const images = Array.from(galleryItems).map((item) => {
    const img = item.querySelector("img");
    const fullSrc = item.dataset.full || img.src;
    return { src: fullSrc, alt: img.alt };
  });

  function openLightbox(index) {
    currentIndex = index;
    updateLightboxImage();
    lightbox.classList.add("active");
    lightbox.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
  }

  function closeLightbox() {
    lightbox.classList.remove("active");
    lightbox.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }

  function updateLightboxImage() {
    // Show loading state
    lightbox.classList.add("loading");

    // Create new image to preload
    const newImg = new Image();
    newImg.onload = () => {
      lightboxImage.src = images[currentIndex].src;
      lightboxImage.alt = images[currentIndex].alt;
      lightbox.classList.remove("loading");
    };
    newImg.onerror = () => {
      lightbox.classList.remove("loading");
    };
    newImg.src = images[currentIndex].src;
  }

  function showPrev() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateLightboxImage();
    preloadAdjacentImages();
  }

  function showNext() {
    currentIndex = (currentIndex + 1) % images.length;
    updateLightboxImage();
    preloadAdjacentImages();
  }

  // Preload adjacent images for smoother navigation
  function preloadAdjacentImages() {
    const prevIndex = (currentIndex - 1 + images.length) % images.length;
    const nextIndex = (currentIndex + 1) % images.length;

    // Preload previous and next images
    const prevImg = new Image();
    prevImg.src = images[prevIndex].src;

    const nextImg = new Image();
    nextImg.src = images[nextIndex].src;
  }

  // Event listeners
  galleryItems.forEach((item, index) => {
    item.style.cursor = "pointer";
    item.addEventListener("click", () => openLightbox(index));
  });

  closeBtn?.addEventListener("click", closeLightbox);
  prevBtn?.addEventListener("click", showPrev);
  nextBtn?.addEventListener("click", showNext);

  // Close on background click
  lightbox.addEventListener("click", (e) => {
    if (e.target === lightbox) closeLightbox();
  });

  // Keyboard navigation
  document.addEventListener("keydown", (e) => {
    if (!lightbox.classList.contains("active")) return;
    if (e.key === "Escape") closeLightbox();
    if (e.key === "ArrowLeft") showPrev();
    if (e.key === "ArrowRight") showNext();
  });
}
