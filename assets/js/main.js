// Global Variables
let isAuthModalOpen = false
let isLoginMode = true
let searchTimeout
let cart = JSON.parse(localStorage.getItem("cart") || "[]")

// DOM Content Loaded
document.addEventListener("DOMContentLoaded", () => {
  initializeApp()
  setupEventListeners()
  updateCartDisplay()
  setupScrollToTop()
})

// Initialize Application
function initializeApp() {
  setupSearch()
  setupAuth()
  loadCart()
  setupUserDropdown()
  checkActiveNavLink()
  setupAnimations()
}

// Setup Event Listeners
function setupEventListeners() {
  // Mobile menu toggle
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle")
  if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener("click", toggleMobileMenu)
  }

  // Overlay click
  const overlay = document.getElementById("overlay")
  if (overlay) {
    overlay.addEventListener("click", closeAllModals)
  }

  // Escape key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeAllModals()
    }
  })

  // Form submissions
  setupFormSubmissions()

  // Scroll events
  window.addEventListener("scroll", handleScroll)

  // Resize events
  window.addEventListener("resize", handleResize)
}

// Setup Form Submissions
function setupFormSubmissions() {
  const loginForm = document.getElementById("loginForm")
  const registerForm = document.getElementById("registerForm")

  if (loginForm) {
    loginForm.addEventListener("submit", handleLogin)
  }

  if (registerForm) {
    registerForm.addEventListener("submit", handleRegister)
  }
}

// Handle Login
async function handleLogin(e) {
  e.preventDefault()
  const form = e.target
  const formData = new FormData(form)
  const submitBtn = form.querySelector(".auth-submit-btn") || form.querySelector('button[type="submit"]')

  // Show loading state
  if (submitBtn) {
    submitBtn.disabled = true
    submitBtn.textContent = "Masuk..."
  }

  try {
    const response = await fetch("auth/login.php", {
      method: "POST",
      body: formData,
    })

    const contentType = response.headers.get("content-type")
    if (contentType && contentType.includes("application/json")) {
      const data = await response.json()
      if (data.success) {
        showNotification("Login berhasil! Selamat datang.", "success")
        setTimeout(() => {
          window.location.reload()
        }, 1500)
      } else {
        showNotification(data.message || "Login gagal. Silakan coba lagi.", "error")
      }
    } else {
      const text = await response.text()
      if (text.includes("berhasil") || text.includes("success")) {
        showNotification("Login berhasil! Selamat datang.", "success")
        setTimeout(() => {
          window.location.reload()
        }, 1500)
      } else {
        showNotification("Login gagal. Silakan coba lagi.", "error")
      }
    }
  } catch (error) {
    console.error("Login error:", error)
    showNotification("Terjadi kesalahan. Silakan coba lagi.", "error")
  } finally {
    if (submitBtn) {
      submitBtn.disabled = false
      submitBtn.textContent = "Masuk"
    }
  }
}

// Handle Register - FIXED VERSION
async function handleRegister(e) {
  e.preventDefault()
  const form = e.target
  const formData = new FormData(form)
  const submitBtn = form.querySelector(".auth-submit-btn") || form.querySelector('button[type="submit"]')

  // Validate passwords match
  const password = formData.get("password")
  const confirmPassword = formData.get("confirm_password")
  if (password !== confirmPassword) {
    showNotification("Password tidak cocok!", "error")
    return
  }

  // Show loading state
  if (submitBtn) {
    submitBtn.disabled = true
    submitBtn.textContent = "Mendaftar..."
  }

  try {
    const response = await fetch("auth/register.php", {
      method: "POST",
      body: formData,
    })

    const contentType = response.headers.get("content-type")
    if (contentType && contentType.includes("application/json")) {
      const data = await response.json()
      console.log("Register response:", data) // Debug log

      if (data.success) {
        showNotification("Pendaftaran berhasil! Selamat datang.", "success")
        setTimeout(() => {
          window.location.href = "index.php"
        }, 1500)
      } else {
        showNotification(data.message || "Pendaftaran gagal. Silakan coba lagi.", "error")
      }
    } else {
      const text = await response.text()
      console.log("Register response (text):", text) // Debug log

      if (text.includes("berhasil") || text.includes("success")) {
        showNotification("Pendaftaran berhasil! Selamat datang.", "success")
        setTimeout(() => {
          window.location.href = "index.php"
        }, 1500)
      } else {
        showNotification("Pendaftaran gagal. Silakan coba lagi.", "error")
      }
    }
  } catch (error) {
    console.error("Register error:", error)
    showNotification("Terjadi kesalahan. Silakan coba lagi.", "error")
  } finally {
    if (submitBtn) {
      submitBtn.disabled = false
      submitBtn.textContent = "Daftar Sekarang"
    }
  }
}

// Auth Modal Functions
function openAuthModal() {
  const authModal = document.getElementById("authModal")
  const overlay = document.getElementById("overlay")
  if (authModal && overlay) {
    authModal.classList.add("active")
    overlay.classList.add("active")
    document.body.style.overflow = "hidden"
    isAuthModalOpen = true
  }
}

function closeAuthModal() {
  const authModal = document.getElementById("authModal")
  const overlay = document.getElementById("overlay")
  if (authModal && overlay) {
    authModal.classList.remove("active")
    overlay.classList.remove("active")
    document.body.style.overflow = "auto"
    isAuthModalOpen = false
  }
}

function switchToLogin() {
  const loginForm = document.getElementById("loginForm")
  const registerForm = document.getElementById("registerForm")
  const authTitle = document.getElementById("authTitle")
  const authSubtitle = document.getElementById("authSubtitle")
  const loginSwitch = document.getElementById("loginSwitch")
  const registerSwitch = document.getElementById("registerSwitch")

  if (loginForm) loginForm.classList.add("active")
  if (registerForm) registerForm.classList.remove("active")
  if (authTitle) authTitle.textContent = "Selamat Datang!"
  if (authSubtitle) authSubtitle.textContent = "Masuk ke akun Anda"
  if (loginSwitch) loginSwitch.classList.add("active")
  if (registerSwitch) registerSwitch.classList.remove("active")

  isLoginMode = true
}

function switchToRegister() {
  const loginForm = document.getElementById("loginForm")
  const registerForm = document.getElementById("registerForm")
  const authTitle = document.getElementById("authTitle")
  const authSubtitle = document.getElementById("authSubtitle")
  const loginSwitch = document.getElementById("loginSwitch")
  const registerSwitch = document.getElementById("registerSwitch")

  if (loginForm) loginForm.classList.remove("active")
  if (registerForm) registerForm.classList.add("active")
  if (authTitle) authTitle.textContent = "Bergabung dengan Kami!"
  if (authSubtitle) authSubtitle.textContent = "Buat akun baru Anda"
  if (loginSwitch) loginSwitch.classList.remove("active")
  if (registerSwitch) registerSwitch.classList.add("active")

  isLoginMode = false
}

// Password Toggle - FIXED VERSION
function togglePassword(button) {
  const input = button.parentElement.querySelector("input[type='password'], input[type='text']")
  const icon = button.querySelector("i")

  if (input && icon) {
    if (input.type === "password") {
      input.type = "text"
      icon.classList.remove("fa-eye")
      icon.classList.add("fa-eye-slash")
      button.setAttribute("aria-label", "Hide password")
    } else {
      input.type = "password"
      icon.classList.remove("fa-eye-slash")
      icon.classList.add("fa-eye")
      button.setAttribute("aria-label", "Show password")
    }
  }
}

// Search Functions - FIXED VERSION
function setupSearch() {
  const searchInput = document.getElementById("searchInput")
  const mobileSearchInput = document.querySelector(".mobile-search-input")

  if (searchInput) {
    searchInput.addEventListener("input", handleSearch)
    searchInput.addEventListener("focus", showSearchResults)
    searchInput.addEventListener("blur", () => {
      setTimeout(hideSearchResults, 200)
    })
  }

  if (mobileSearchInput) {
    mobileSearchInput.addEventListener("input", handleSearch)
  }
}

function toggleSearch() {
  const searchDropdown = document.getElementById("searchDropdown")
  const searchInput = document.getElementById("searchInput")

  if (searchDropdown) {
    const isActive = searchDropdown.classList.contains("active")

    if (isActive) {
      searchDropdown.classList.remove("active")
      searchDropdown.setAttribute("aria-expanded", "false")
    } else {
      searchDropdown.classList.add("active")
      searchDropdown.setAttribute("aria-expanded", "true")
      if (searchInput) {
        searchInput.focus()
      }
    }
  }
}

function handleSearch(e) {
  const query = e.target.value.trim()
  clearTimeout(searchTimeout)

  if (query.length < 2) {
    hideSearchResults()
    return
  }

  searchTimeout = setTimeout(() => {
    performSearch(query)
  }, 300)
}

async function performSearch(query) {
  try {
    const response = await fetch(`api/search.php?q=${encodeURIComponent(query)}`)
    const data = await response.json()
    displaySearchResults(data)
  } catch (error) {
    console.error("Search error:", error)
    displaySearchResults([])
  }
}

function displaySearchResults(results) {
  const searchResults = document.getElementById("searchResults")
  if (!searchResults) return

  if (results.length === 0) {
    searchResults.innerHTML = `
      <div class="search-no-results">
        <i class="fas fa-search"></i>
        <p>Tidak ada hasil ditemukan</p>
      </div>
    `
  } else {
    let html = ""
    results.forEach((item) => {
      html += `
        <div class="search-result-item" onclick="goToProduct(${item.id})">
          <div class="search-result-image">
            <img src="${item.image || "https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=50&h=50&fit=crop"}" alt="${item.name}">
          </div>
          <div class="search-result-info">
            <h4>${item.name}</h4>
            <p>${item.category_name}</p>
            <span class="price">Rp ${formatPrice(item.price)}</span>
          </div>
        </div>
      `
    })
    searchResults.innerHTML = html
  }

  showSearchResults()
}

function showSearchResults() {
  const searchResults = document.getElementById("searchResults")
  if (searchResults) {
    searchResults.style.display = "block"
    searchResults.setAttribute("aria-hidden", "false")
  }
}

function hideSearchResults() {
  const searchResults = document.getElementById("searchResults")
  if (searchResults) {
    searchResults.style.display = "none"
    searchResults.setAttribute("aria-hidden", "true")
  }
}

function goToProduct(productId) {
  window.location.href = `menu.php?product=${productId}`
}

// Mobile Menu
function toggleMobileMenu() {
  const mobileNav = document.getElementById("mobileNav")
  const hamburger = document.querySelector(".mobile-menu-toggle")
  const overlay = document.getElementById("overlay")

  if (mobileNav && hamburger) {
    const isActive = mobileNav.classList.contains("active")

    mobileNav.classList.toggle("active")
    hamburger.classList.toggle("active")

    if (!isActive) {
      document.body.style.overflow = "hidden"
      if (overlay) overlay.classList.add("active")
      mobileNav.setAttribute("aria-expanded", "true")
    } else {
      document.body.style.overflow = "auto"
      if (overlay) overlay.classList.remove("active")
      mobileNav.setAttribute("aria-expanded", "false")
    }
  }
}

// User Dropdown
function setupUserDropdown() {
  const userDropdown = document.querySelector(".user-dropdown")
  if (userDropdown) {
    const userBtn = userDropdown.querySelector(".user-btn")

    if (userBtn) {
      userBtn.addEventListener("click", (e) => {
        e.stopPropagation()
        const isActive = userDropdown.classList.contains("active")
        userDropdown.classList.toggle("active")
        userBtn.setAttribute("aria-expanded", !isActive)
      })
    }

    document.addEventListener("click", () => {
      userDropdown.classList.remove("active")
      if (userBtn) {
        userBtn.setAttribute("aria-expanded", "false")
      }
    })
  }
}

// Cart Functions
function toggleCart() {
  const cartSidebar = document.getElementById("cartSidebar")
  const overlay = document.getElementById("overlay")

  if (cartSidebar && overlay) {
    const isActive = cartSidebar.classList.contains("active")

    cartSidebar.classList.toggle("active")
    overlay.classList.toggle("active")

    if (!isActive) {
      document.body.style.overflow = "hidden"
      loadCart()
      cartSidebar.setAttribute("aria-hidden", "false")
    } else {
      document.body.style.overflow = "auto"
      cartSidebar.setAttribute("aria-hidden", "true")
    }
  }
}

function loadCart() {
  const cartContent = document.getElementById("cartContent")
  if (!cartContent) return

  if (cart.length === 0) {
    cartContent.innerHTML = `
      <div class="empty-cart">
        <i class="fas fa-shopping-cart"></i>
        <p>Keranjang masih kosong</p>
        <small>Yuk, pilih menu favorit kamu!</small>
      </div>
    `
  } else {
    let html = ""
    cart.forEach((item) => {
      html += `
        <div class="cart-item">
          <div class="cart-item-image">
            <img src="${item.image || "https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=60&h=60&fit=crop"}" alt="${item.name}">
          </div>
          <div class="cart-item-info">
            <h4>${item.name}</h4>
            <p class="cart-item-price">Rp ${formatPrice(item.price)}</p>
            <div class="cart-item-controls">
              <button class="qty-btn minus" onclick="updateCartQuantity(${item.id}, -1)" aria-label="Kurangi jumlah">
                <i class="fas fa-minus"></i>
              </button>
              <span class="quantity">${item.quantity}</span>
              <button class="qty-btn plus" onclick="updateCartQuantity(${item.id}, 1)" aria-label="Tambah jumlah">
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
          <button class="remove-item" onclick="removeFromCart(${item.id})" title="Hapus item" aria-label="Hapus ${item.name} dari keranjang">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      `
    })
    cartContent.innerHTML = html
  }

  updateCartTotal()
}

function addToCart(productId, productName, productPrice, productImage) {
  const existingItem = cart.find((item) => item.id === productId)

  if (existingItem) {
    existingItem.quantity += 1
  } else {
    cart.push({
      id: productId,
      name: productName,
      price: productPrice,
      image: productImage,
      quantity: 1,
    })
  }

  localStorage.setItem("cart", JSON.stringify(cart))
  updateCartDisplay()
  showNotification("Produk berhasil ditambahkan ke keranjang!", "success")
  animateCartIcon()
}

function updateCartQuantity(productId, change) {
  const itemIndex = cart.findIndex((item) => item.id === productId)
  if (itemIndex !== -1) {
    cart[itemIndex].quantity += change
    if (cart[itemIndex].quantity <= 0) {
      cart.splice(itemIndex, 1)
    }
    localStorage.setItem("cart", JSON.stringify(cart))
    updateCartDisplay()
    loadCart()
  }
}

function removeFromCart(productId) {
  cart = cart.filter((item) => item.id !== productId)
  localStorage.setItem("cart", JSON.stringify(cart))
  updateCartDisplay()
  loadCart()
  showNotification("Produk dihapus dari keranjang", "info")
}

function updateCartDisplay() {
  updateCartCount()
  updateCartTotal()
}

function updateCartCount() {
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0)
  const cartCount = document.getElementById("cartCount")
  if (cartCount) {
    cartCount.textContent = totalItems
    cartCount.style.display = totalItems > 0 ? "flex" : "none"
  }
}

function updateCartTotal() {
  const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0)
  const cartTotal = document.getElementById("cartTotal")
  if (cartTotal) {
    cartTotal.textContent = formatPrice(total)
  }
}

function animateCartIcon() {
  const cartBtn = document.querySelector(".cart-btn")
  if (cartBtn) {
    cartBtn.classList.add("animate")
    setTimeout(() => {
      cartBtn.classList.remove("animate")
    }, 600)
  }
}

// Favorite Functions
function toggleFavorite(productId) {
  const favorites = JSON.parse(localStorage.getItem("favorites") || "[]")
  const index = favorites.indexOf(productId)

  if (index > -1) {
    favorites.splice(index, 1)
    showNotification("Dihapus dari favorit", "info")
  } else {
    favorites.push(productId)
    showNotification("Ditambahkan ke favorit", "success")
  }

  localStorage.setItem("favorites", JSON.stringify(favorites))
  updateFavoriteIcons()
}

function updateFavoriteIcons() {
  const favorites = JSON.parse(localStorage.getItem("favorites") || "[]")
  const favoriteButtons = document.querySelectorAll(".product-favorite")

  favoriteButtons.forEach((button) => {
    const productId = Number.parseInt(button.getAttribute("onclick").match(/\d+/)[0])
    const icon = button.querySelector("i")

    if (favorites.includes(productId)) {
      icon.classList.remove("far")
      icon.classList.add("fas")
      button.style.color = "#e74c3c"
    } else {
      icon.classList.remove("fas")
      icon.classList.add("far")
      button.style.color = "#666"
    }
  })
}

// Utility Functions
function formatPrice(price) {
  return new Intl.NumberFormat("id-ID").format(price)
}

function showNotification(message, type = "info") {
  const container = document.getElementById("notificationContainer")
  if (!container) {
    const newContainer = document.createElement("div")
    newContainer.id = "notificationContainer"
    newContainer.className = "notification-container"
    newContainer.setAttribute("aria-live", "polite")
    document.body.appendChild(newContainer)
  }

  const notification = document.createElement("div")
  notification.className = `notification notification-${type}`
  notification.setAttribute("role", "alert")

  const icons = {
    success: "fas fa-check-circle",
    error: "fas fa-exclamation-circle",
    warning: "fas fa-exclamation-triangle",
    info: "fas fa-info-circle",
  }

  notification.innerHTML = `
    <div class="notification-content">
      <i class="${icons[type]}"></i>
      <span>${message}</span>
      <button class="notification-close" onclick="this.parentElement.parentElement.remove()" aria-label="Tutup notifikasi">
        <i class="fas fa-times"></i>
      </button>
    </div>
  `

  const finalContainer = document.getElementById("notificationContainer")
  finalContainer.appendChild(notification)

  setTimeout(() => {
    if (notification.parentElement) {
      notification.remove()
    }
  }, 5000)

  setTimeout(() => {
    notification.classList.add("show")
  }, 100)
}

function closeAllModals() {
  closeAuthModal()

  const cartSidebar = document.getElementById("cartSidebar")
  if (cartSidebar && cartSidebar.classList.contains("active")) {
    toggleCart()
  }

  const mobileNav = document.getElementById("mobileNav")
  if (mobileNav && mobileNav.classList.contains("active")) {
    toggleMobileMenu()
  }

  const searchDropdown = document.getElementById("searchDropdown")
  if (searchDropdown && searchDropdown.classList.contains("active")) {
    searchDropdown.classList.remove("active")
    searchDropdown.setAttribute("aria-expanded", "false")
  }
}

// Scroll Functions
function setupScrollToTop() {
  const scrollBtn = document.getElementById("scrollToTop")
  if (scrollBtn) {
    scrollBtn.addEventListener("click", scrollToTop)
  }
}

function handleScroll() {
  const scrollBtn = document.getElementById("scrollToTop")
  const header = document.querySelector(".header")

  if (scrollBtn) {
    if (window.pageYOffset > 300) {
      scrollBtn.classList.add("show")
    } else {
      scrollBtn.classList.remove("show")
    }
  }

  if (header) {
    if (window.pageYOffset > 100) {
      header.style.background = "rgba(255, 255, 255, 0.95)"
      header.style.boxShadow = "0 2px 30px rgba(0, 0, 0, 0.15)"
    } else {
      header.style.background = "rgba(255, 255, 255, 0.98)"
      header.style.boxShadow = "0 2px 30px rgba(0, 0, 0, 0.08)"
    }
  }
}

function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  })
}

function handleResize() {
  if (window.innerWidth > 768) {
    const mobileNav = document.getElementById("mobileNav")
    const hamburger = document.querySelector(".mobile-menu-toggle")
    const overlay = document.getElementById("overlay")

    if (mobileNav && mobileNav.classList.contains("active")) {
      mobileNav.classList.remove("active")
      if (hamburger) hamburger.classList.remove("active")
      if (overlay) overlay.classList.remove("active")
      document.body.style.overflow = "auto"
    }
  }
}

function checkActiveNavLink() {
  const currentPage = window.location.pathname.split("/").pop() || "index.php"
  const navLinks = document.querySelectorAll(".nav-link, .mobile-nav-link")

  navLinks.forEach((link) => {
    const href = link.getAttribute("href")
    if (href === currentPage || (currentPage === "" && href === "index.php")) {
      link.classList.add("active")
    }
  })
}

function setupAuth() {
  isLoginMode = true

  const passwordInputs = document.querySelectorAll('input[type="password"]')
  passwordInputs.forEach((input) => {
    if (input.name === "password") {
      input.addEventListener("input", checkPasswordStrength)
    }
  })

  const phoneInputs = document.querySelectorAll('input[type="tel"]')
  phoneInputs.forEach((input) => {
    input.addEventListener("input", formatPhoneNumber)
  })
}

function checkPasswordStrength(e) {
  const password = e.target.value
  if (password.length < 6) {
    e.target.setCustomValidity("Password minimal 6 karakter")
  } else {
    e.target.setCustomValidity("")
  }
}

function formatPhoneNumber(e) {
  let value = e.target.value.replace(/\D/g, "")
  if (value.startsWith("8")) {
    value = "0" + value
  }

  if (value.length > 4) {
    value = value.substring(0, 4) + "-" + value.substring(4)
  }
  if (value.length > 9) {
    value = value.substring(0, 9) + "-" + value.substring(9, 13)
  }

  e.target.value = value
}

function setupAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1"
        entry.target.style.transform = "translateY(0)"
      }
    })
  }, observerOptions)

  const animateElements = document.querySelectorAll(".product-card, .category-card, .testimonial-card, .feature-item")
  animateElements.forEach((element) => {
    element.style.opacity = "0"
    element.style.transform = "translateY(30px)"
    element.style.transition = "all 0.6s ease"
    observer.observe(element)
  })

  updateFavoriteIcons()
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initializeApp)
} else {
  initializeApp()
}

// Export functions for global access
window.addToCart = addToCart
window.updateCartQuantity = updateCartQuantity
window.removeFromCart = removeFromCart
window.toggleCart = toggleCart
window.toggleFavorite = toggleFavorite
window.openAuthModal = openAuthModal
window.closeAuthModal = closeAuthModal
window.switchToLogin = switchToLogin
window.switchToRegister = switchToRegister
window.togglePassword = togglePassword
window.toggleSearch = toggleSearch
window.toggleMobileMenu = toggleMobileMenu
window.scrollToTop = scrollToTop
window.goToProduct = goToProduct
