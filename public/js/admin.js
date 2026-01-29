/**
 * IT Hub Zavidovići - Admin Panel JavaScript
 */

document.addEventListener("DOMContentLoaded", function () {
  // Mobile sidebar toggle
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.querySelector(".sidebar");
  const sidebarOverlay = document.getElementById("sidebarOverlay");

  if (sidebarToggle && sidebar && sidebarOverlay) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("active");
      sidebarOverlay.classList.toggle("active");
      document.body.style.overflow = sidebar.classList.contains("active")
        ? "hidden"
        : "";
    });

    sidebarOverlay.addEventListener("click", function () {
      sidebar.classList.remove("active");
      sidebarOverlay.classList.remove("active");
      document.body.style.overflow = "";
    });
  }

  // File upload preview
  const fileInputs = document.querySelectorAll(
    'input[type="file"][data-preview]',
  );
  fileInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const previewId = this.dataset.preview;
      const preview = document.getElementById(previewId);

      if (preview && this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          preview.style.display = "block";
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  });

  // Drag and drop file upload
  const dropZones = document.querySelectorAll(".file-upload");
  dropZones.forEach((zone) => {
    const input = zone.querySelector('input[type="file"]');

    if (zone.tagName !== "LABEL") {
      zone.addEventListener("click", () => input?.click());
    }

    zone.addEventListener("dragover", (e) => {
      e.preventDefault();
      zone.classList.add("dragover");
    });

    zone.addEventListener("dragleave", () => {
      zone.classList.remove("dragover");
    });

    zone.addEventListener("drop", (e) => {
      e.preventDefault();
      zone.classList.remove("dragover");

      if (input && e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        input.dispatchEvent(new Event("change"));
      }
    });
  });

  // Confirm delete (for non-custom modals)
  const deleteButtons = document.querySelectorAll("[data-confirm]");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      const message = this.dataset.confirm || "Jeste li sigurni?";
      if (!confirm(message)) {
        e.preventDefault();
      }
    });
  });

  // Modal functionality
  const modalTriggers = document.querySelectorAll("[data-modal]");
  modalTriggers.forEach((trigger) => {
    trigger.addEventListener("click", function (e) {
      e.preventDefault();
      const modalId = this.dataset.modal;
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.classList.add("active");
        document.body.style.overflow = "hidden";
      }
    });
  });

  const modalCloses = document.querySelectorAll(
    ".modal-close, [data-modal-close]",
  );
  modalCloses.forEach((close) => {
    close.addEventListener("click", function () {
      const modal = this.closest(".modal");
      if (modal) {
        modal.classList.remove("active");
        document.body.style.overflow = "";
      }
    });
  });

  const modals = document.querySelectorAll(".modal");
  modals.forEach((modal) => {
    modal.addEventListener("click", function (e) {
      if (e.target === this) {
        this.classList.remove("active");
        document.body.style.overflow = "";
      }
    });
  });

  // Auto-hide alerts
  const alerts = document.querySelectorAll(".alert[data-auto-hide]");
  alerts.forEach((alert) => {
    const delay = parseInt(alert.dataset.autoHide) || 5000;
    setTimeout(() => {
      alert.style.opacity = "0";
      setTimeout(() => alert.remove(), 300);
    }, delay);
  });

  // Toggle switch handler
  const toggles = document.querySelectorAll('.toggle input[type="checkbox"]');
  toggles.forEach((toggle) => {
    toggle.addEventListener("change", function () {
      const url = this.dataset.url;
      if (url) {
        fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: JSON.stringify({ active: this.checked }),
        }).catch((err) => console.error("Toggle error:", err));
      }
    });
  });

  // Form validation
  const forms = document.querySelectorAll("form[data-validate]");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const requiredFields = this.querySelectorAll("[required]");
      let isValid = true;

      requiredFields.forEach((field) => {
        const errorEl = field.parentElement.querySelector(".form-error");
        if (!field.value.trim()) {
          isValid = false;
          field.classList.add("error");
          if (errorEl) errorEl.textContent = "Ovo polje je obavezno";
        } else {
          field.classList.remove("error");
          if (errorEl) errorEl.textContent = "";
        }
      });

      if (!isValid) {
        e.preventDefault();
      }
    });
  });

  // Tabs functionality
  const tabButtons = document.querySelectorAll("[data-tab]");
  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const tabGroup = this.closest(".tabs");
      const tabId = this.dataset.tab;

      tabGroup
        .querySelectorAll("[data-tab]")
        .forEach((btn) => btn.classList.remove("active"));
      this.classList.add("active");

      const panels = document.querySelectorAll("[data-tab-panel]");
      panels.forEach((panel) => {
        panel.style.display =
          panel.dataset.tabPanel === tabId ? "block" : "none";
      });
    });
  });

  // Character counter for textareas
  const textareas = document.querySelectorAll("textarea[data-max-length]");
  textareas.forEach((textarea) => {
    const maxLength = parseInt(textarea.dataset.maxLength);
    const counter = document.createElement("div");
    counter.className = "form-hint";
    counter.textContent = `0 / ${maxLength}`;
    textarea.parentElement.appendChild(counter);

    textarea.addEventListener("input", function () {
      const length = this.value.length;
      counter.textContent = `${length} / ${maxLength}`;
      counter.style.color = length > maxLength ? "var(--danger)" : "";
    });
  });

  // Partners drag & drop reorder
  initPartnersSortable();
});

/**
 * Smooth Partners Drag & Drop
 */
function initPartnersSortable() {
  const grid = document.getElementById("partnersGrid");
  if (!grid) return;

  let state = {
    dragging: null,
    startY: 0,
    startIndex: 0,
    currentIndex: 0,
    cards: [],
    rects: [],
    cardHeight: 0,
  };

  // Setup
  grid.querySelectorAll(".partner-card").forEach((card) => {
    const handle = card.querySelector(".partner-card-drag");
    if (!handle) return;

    handle.addEventListener("mousedown", (e) => startDrag(e, card));
    handle.addEventListener("touchstart", (e) => startDrag(e, card), {
      passive: false,
    });
  });

  function startDrag(e, card) {
    e.preventDefault();

    state.dragging = card;
    state.cards = Array.from(grid.querySelectorAll(".partner-card"));
    state.startIndex = state.cards.indexOf(card);
    state.currentIndex = state.startIndex;
    state.startY = e.clientY || e.touches[0].clientY;

    // Get all card positions
    state.rects = state.cards.map((c) => c.getBoundingClientRect());
    state.cardHeight = state.rects[0].height + 16; // Include gap

    // Style dragged card
    card.classList.add("dragging");
    card.style.zIndex = "1000";

    // Enable smooth transitions on other cards
    state.cards.forEach((c, i) => {
      if (i !== state.startIndex) {
        c.classList.add("animate");
      }
    });

    document.addEventListener("mousemove", onDrag);
    document.addEventListener("mouseup", endDrag);
    document.addEventListener("touchmove", onDrag, { passive: false });
    document.addEventListener("touchend", endDrag);
  }

  function onDrag(e) {
    if (!state.dragging) return;
    e.preventDefault();

    const clientY = e.clientY || (e.touches?.[0]?.clientY ?? 0);
    const deltaY = clientY - state.startY;

    // Move dragged card visually
    state.dragging.style.transform = `translateY(${deltaY}px) scale(1.01)`;

    // Calculate which position we're hovering over
    const draggedCenterY =
      state.rects[state.startIndex].top +
      state.rects[state.startIndex].height / 2 +
      deltaY;

    let newIndex = state.startIndex;

    for (let i = 0; i < state.cards.length; i++) {
      const cardCenterY = state.rects[i].top + state.rects[i].height / 2;

      if (i < state.startIndex && draggedCenterY < cardCenterY) {
        newIndex = i;
        break;
      } else if (i > state.startIndex && draggedCenterY > cardCenterY) {
        newIndex = i;
      }
    }

    // Update other cards if position changed
    if (newIndex !== state.currentIndex) {
      state.currentIndex = newIndex;

      state.cards.forEach((card, i) => {
        if (i === state.startIndex) return;

        let offset = 0;
        if (state.startIndex < state.currentIndex) {
          // Moving down: cards between start and current move up
          if (i > state.startIndex && i <= state.currentIndex) {
            offset = -state.cardHeight;
          }
        } else {
          // Moving up: cards between current and start move down
          if (i >= state.currentIndex && i < state.startIndex) {
            offset = state.cardHeight;
          }
        }

        card.style.transform = `translateY(${offset}px)`;
      });
    }
  }

  function endDrag() {
    if (!state.dragging) return;

    document.removeEventListener("mousemove", onDrag);
    document.removeEventListener("mouseup", endDrag);
    document.removeEventListener("touchmove", onDrag);
    document.removeEventListener("touchend", endDrag);

    const draggedCard = state.dragging;
    const fromIndex = state.startIndex;
    const toIndex = state.currentIndex;

    // Animate dragged card back to position
    draggedCard.classList.add("animate");
    draggedCard.style.transform = "";

    // Reset all cards after animation
    setTimeout(() => {
      // Remove transition classes
      state.cards.forEach((c) => {
        c.classList.remove("animate", "dragging");
        c.style.transform = "";
        c.style.zIndex = "";
      });

      // Actually reorder DOM if needed
      if (fromIndex !== toIndex) {
        const referenceCard = state.cards[toIndex];
        if (fromIndex < toIndex) {
          referenceCard.after(draggedCard);
        } else {
          referenceCard.before(draggedCard);
        }
        saveOrder();
      }

      // Reset state
      state = {
        dragging: null,
        startY: 0,
        startIndex: 0,
        currentIndex: 0,
        cards: [],
        rects: [],
        cardHeight: 0,
      };
    }, 300);
  }

  function saveOrder() {
    const url = document.getElementById("reorderUrl")?.value;
    if (!url) return;

    const cards = grid.querySelectorAll(".partner-card[data-id]");
    const order = Array.from(cards).map((card, i) => ({
      id: parseInt(card.dataset.id),
      sort_order: i + 1,
    }));

    fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({ order }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          // Subtle success flash
          cards.forEach((card, i) => {
            setTimeout(() => {
              card.style.borderColor = "var(--secondary)";
              card.style.boxShadow = "0 0 16px rgba(16, 185, 129, 0.3)";
              setTimeout(() => {
                card.style.borderColor = "";
                card.style.boxShadow = "";
              }, 300);
            }, i * 30);
          });
          showNotification("Redoslijed sačuvan", "success");
        } else {
          showNotification(data.message || "Greška pri čuvanju", "danger");
        }
      })
      .catch(() => {
        showNotification("Greška pri čuvanju", "danger");
      });
  }
}

// Show notification
function showNotification(message, type = "success") {
  const notification = document.createElement("div");
  notification.className = `alert alert-${type}`;
  notification.innerHTML = `
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      ${
        type === "success"
          ? '<polyline points="20 6 9 17 4 12"></polyline>'
          : '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>'
      }
    </svg>
    <span>${message}</span>
  `;

  const container = document.querySelector(".page-content");
  if (container) {
    container.insertBefore(notification, container.firstChild);
    setTimeout(() => {
      notification.style.opacity = "0";
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }
}
