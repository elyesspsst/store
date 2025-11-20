// Menu click button
const btn = document.getElementById("menubtn");
const content = document.getElementById("men_list");

if (btn && content) {
  btn.addEventListener("click", function (e) {
    e.stopPropagation();
    content.classList.toggle("show");
  });

  // Close menu when clicking outside
  window.addEventListener("click", function (e) {
    if (!btn.contains(e.target) && !content.contains(e.target)) {
      content.classList.remove("show");
    }
  });
}

// Caret icon toggle
const caret = document.getElementById("caret");

if (caret) {
  caret.addEventListener("click", () => {
    caret.classList.toggle("down");
  });
}

// Initialize Swiper only if element exists and Swiper is loaded
let swiper;
const swiperElement = document.querySelector(".home");

if (swiperElement && typeof Swiper !== 'undefined') {
  swiper = new Swiper(".home", {
    loop: true,
    autoplay: {
      delay: 8000,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    speed: 800,
    effect: "fade",
    fadeEffect: {
      crossFade: true,
    },
  });
  console.log("✓ Swiper initialized successfully");
} else if (!swiperElement) {
  console.warn("Swiper element with class '.home' not found");
} else {
  console.error("Swiper library not loaded");
}

// Mobile menu toggle
const menuIcon = document.getElementById("menu-icon");
const navbar = document.querySelector(".navbar");

if (menuIcon && navbar) {
  menuIcon.addEventListener("click", (e) => {
    e.stopPropagation();
    navbar.classList.toggle("active");
  });

  // Close menu when clicking outside
  document.addEventListener("click", (e) => {
    if (!menuIcon.contains(e.target) && !navbar.contains(e.target)) {
      navbar.classList.remove("active");
    }
  });
}

// Smooth scrolling for navigation links (ONLY for anchor links)
if (navbar) {
  document.querySelectorAll(".navbar a").forEach((link) => {
    link.addEventListener("click", (e) => {
      const href = link.getAttribute("href");
      
      // Only prevent default for anchor links (starting with #) but NOT #home if it's a Swiper
      if (href && href.startsWith("#")) {
        const targetId = href.slice(1);
        const target = document.getElementById(targetId);
        
        // Don't interfere with Swiper navigation
        if (target && !target.classList.contains("swiper")) {
          e.preventDefault();
          target.scrollIntoView({ behavior: "smooth" });
          navbar.classList.remove("active");
        } else if (target) {
          // For sections with Swiper, just close navbar
          navbar.classList.remove("active");
        }
      }
      // For regular links (.html files), let them work normally
    });
  });
}

// Update active nav link on scroll
let scrollTimeout;
window.addEventListener("scroll", () => {
  clearTimeout(scrollTimeout);
  scrollTimeout = setTimeout(() => {
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".navbar a");

    let current = "";
    sections.forEach((section) => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.clientHeight;
      if (window.pageYOffset >= sectionTop - 200) {
        current = section.getAttribute("id");
      }
    });

    navLinks.forEach((link) => {
      link.classList.remove("home-active");
      const href = link.getAttribute("href");
      if (href && href.slice(1) === current) {
        link.classList.add("home-active");
      }
    });
  }, 50);
});

// Enhanced Cart functionality
class CartManager {
  constructor() {
    this.total = 0;
    this.items = [];
    this.totalElement = document.getElementById("total");
    this.init();
  }

  init() {
    this.attachCommanderButtons();
    this.attachCloseButton();
    this.attachCheckoutButton();
  }

  attachCommanderButtons() {
    const commanderButtons = document.querySelectorAll(".boutn .btn");

    commanderButtons.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();

        const orderCard = btn.closest(".order-card");
        if (orderCard) {
          const priceElement = orderCard.querySelector(".price");
          const nameElement = orderCard.querySelector("h4:not(.price)");
          
          if (priceElement && nameElement) {
            const priceText = priceElement.textContent;
            const itemName = nameElement.textContent;
            const price = parseFloat(priceText.replace(/[^\d.]/g, ""));

            if (!isNaN(price)) {
              this.addItem(itemName, price);
              this.showFeedback(btn, "Ajouté! ✓");
            }
          }
        }
      });
    });
  }

  addItem(name, price) {
    this.items.push({ name, price });
    this.total += price;
    this.updateDisplay();
  }

  updateDisplay() {
    if (this.totalElement) {
      this.totalElement.textContent = this.total.toFixed(2);

      // Add animation effect
      const parent = this.totalElement.parentElement;
      if (parent) {
        parent.classList.add("pulse");
        setTimeout(() => {
          parent.classList.remove("pulse");
        }, 300);
      }
    }
  }

  showFeedback(btn, message) {
    const originalText = btn.textContent;
    const originalBg = btn.style.background;

    btn.textContent = message;
    btn.style.background = "#27ae60";
    btn.style.transform = "scale(0.95)";

    setTimeout(() => {
      btn.textContent = originalText;
      btn.style.background = originalBg;
      btn.style.transform = "";
    }, 1200);
  }

  attachCloseButton() {
    const closeBtn = document.querySelector(".close-btn");
    if (closeBtn) {
      closeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        this.clearCart();
      });
    }
  }

  clearCart() {
    this.total = 0;
    this.items = [];
    this.updateDisplay();

    // Show confirmation
    if (this.totalElement) {
      const container = this.totalElement.closest(".total-container");
      if (container) {
        const originalText = container.innerHTML;
        container.innerHTML = '<p style="margin:0;">Panier vidé! 🗑️</p>';

        setTimeout(() => {
          container.innerHTML = originalText;
          this.totalElement = document.getElementById("total");
          if (this.totalElement) {
            this.totalElement.textContent = "0.00";
          }
        }, 1500);
      }
    }
  }

  attachCheckoutButton() {
    const checkoutBtn = document.querySelector(
      ".btn-container .btn:not(.close-btn)"
    );
    if (checkoutBtn) {
      checkoutBtn.addEventListener("click", (e) => {
        e.preventDefault();
        if (this.total > 0) {
          this.checkout();
        } else {
          alert("Votre panier est vide!");
        }
      });
    }
  }

  checkout() {
    const itemsList = this.items
      .map((item) => `${item.name}: ${item.price} DT`)
      .join("\n");

    alert(
      `Commande confirmée!\n\nArticles:\n${itemsList}\n\nTotal: ${this.total.toFixed(
        2
      )} DT\n\nMerci pour votre commande!`
    );

    this.clearCart();
  }
}

// Initialize cart manager only if we're on a page with cart elements
if (document.getElementById("total")) {
  const cart = new CartManager();
}

// Add smooth header scroll effect
let lastScroll = 0;
const header = document.querySelector("header");

if (header) {
  window.addEventListener("scroll", () => {
    const currentScroll = window.pageYOffset;

    if (currentScroll <= 0) {
      header.classList.remove("scroll-up");
      header.style.transform = "translateY(0)";
      return;
    }

    if (currentScroll > lastScroll && currentScroll > 100) {
      // Scrolling down
      header.style.transform = "translateY(-100%)";
    } else {
      // Scrolling up
      header.style.transform = "translateY(0)";
      header.classList.add("scroll-up");
    }

    lastScroll = currentScroll;
  });
}

// Add pulse animation for total price
const style = document.createElement("style");
style.textContent = `
  @keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
  }
  .pulse {
    animation: pulse 0.3s ease;
  }
  header {
    transition: transform 0.3s ease;
  }
`;
document.head.appendChild(style);

// Image lazy loading fallback
document.querySelectorAll("img").forEach((img) => {
  img.addEventListener("error", function () {
    this.style.background = "linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)";
    this.style.display = "flex";
    this.style.alignItems = "center";
    this.style.justifyContent = "center";
  });
});

console.log("🍴 Sip & Gossip Restaurant Website Loaded Successfully!");

// register

document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // جلب البيانات
    const fullname = document.getElementById('fullname').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const terms = document.getElementById('terms').checked;
    
    // التحقق من كلمة المرور
    if (password.length < 8) {
        alert('❌ Password must be at least 8 characters!');
        return;
    }
    
    if (password !== confirmPassword) {
        alert('❌ Passwords do not match!');
        return;
    }
    
    if (!terms) {
        alert('❌ Please accept the Terms of Service!');
        return;
    }
    
    // إذا كانت البيانات صحيحة
    console.log('✅ Form Data:', {
        fullname,
        email,
        phone,
        password
    });
    
    alert('✅ Account created successfully!');
    
    // هنا تقدر تبعث البيانات للـ backend
    // fetch('/api/signup', { method: 'POST', body: JSON.stringify(data) })
});