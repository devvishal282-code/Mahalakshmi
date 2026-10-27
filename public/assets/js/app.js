const strip = document.querySelector('#lookStrip');
const previousButton = document.querySelector('.slide-prev');
const nextButton = document.querySelector('.slide-next');

if (strip) {
    const getSlideWidth = () => {
        const card = strip.querySelector('.look-card');

        if (!card) return 0;

        const gap = Number.parseFloat(getComputedStyle(strip).gap) || 0;
        return card.getBoundingClientRect().width + gap;
    };

    nextButton?.addEventListener('click', () => {
        strip.scrollBy({ left: getSlideWidth(), behavior: 'smooth' });
    });

    previousButton?.addEventListener('click', () => {
        strip.scrollBy({ left: -getSlideWidth(), behavior: 'smooth' });
    });
}

const wishlistStorageKey = 'mahalakshmi-wishlist';

const readWishlist = () => {
  try {
    const storedWishlist = JSON.parse(window.localStorage.getItem(wishlistStorageKey) || '[]');

    if (!Array.isArray(storedWishlist)) {
      return [];
    }

    return storedWishlist.filter((product) => (
      product
      && typeof product.id === 'string'
      && typeof product.name === 'string'
      && typeof product.image === 'string'
      && (product.image.startsWith('https://') || product.image.startsWith('/'))
    ));
  } catch (error) {
    console.error('Unable to read the wishlist from browser storage.', error);
    return [];
  }
};

const saveWishlist = (wishlist) => {
  try {
    window.localStorage.setItem(wishlistStorageKey, JSON.stringify(wishlist));
  } catch (error) {
    console.error('Unable to save the wishlist to browser storage.', error);
  }
};

const getWishlistProduct = (button) => {
  const card = button.closest('.product-card, .festive-product-card');
  const title = card?.querySelector('h2, h3')?.textContent?.trim();
  const image = card?.querySelector('.product-image img, img');

  if (!title || !image?.src) {
    return null;
  }

  return {
    id: title.toLocaleLowerCase().replace(/\s+/g, ' '),
    name: title,
    image: image.src,
    alt: image.alt || title
  };
};

const updateWishlistCount = (wishlist) => {
  document.querySelectorAll('[data-wishlist-count]').forEach((count) => {
    count.textContent = String(wishlist.length);
  });
};

const updateWishlistButtons = (wishlist) => {
  const savedProductIds = new Set(wishlist.map((product) => product.id));

  document.querySelectorAll('.heart').forEach((button) => {
    const product = getWishlistProduct(button);

    if (!product) {
      return;
    }

    const isSaved = savedProductIds.has(product.id);
    const icon = button.querySelector('i');

    button.classList.toggle('wishlist-added', isSaved);
    button.setAttribute('aria-pressed', String(isSaved));
    button.setAttribute('aria-label', `${isSaved ? 'Remove' : 'Add'} ${product.name} ${isSaved ? 'from' : 'to'} wishlist`);
    icon?.classList.toggle('fa-solid', isSaved);
    icon?.classList.toggle('fa-regular', !isSaved);
  });
};

const renderWishlistPage = (wishlist) => {
  const grid = document.querySelector('[data-wishlist-grid]');
  const emptyMessage = document.querySelector('[data-wishlist-empty]');

  if (!grid || !emptyMessage) {
    return;
  }

  grid.replaceChildren();
  emptyMessage.hidden = wishlist.length > 0;

  wishlist.forEach((product) => {
    const card = document.createElement('article');
    const image = document.createElement('img');
    const title = document.createElement('h2');
    const removeButton = document.createElement('button');
    const icon = document.createElement('i');

    card.className = 'festive-product-card';
    image.src = product.image;
    image.alt = product.alt;
    title.textContent = product.name;
    removeButton.type = 'button';
    removeButton.className = 'festive-wishlist heart wishlist-added';
    removeButton.setAttribute('aria-label', `Remove ${product.name} from wishlist`);
    removeButton.setAttribute('aria-pressed', 'true');
    icon.className = 'fa-solid fa-heart';
    icon.setAttribute('aria-hidden', 'true');
    removeButton.append(icon, document.createTextNode(' Remove'));
    removeButton.addEventListener('click', () => {
      const updatedWishlist = readWishlist().filter((savedProduct) => savedProduct.id !== product.id);

      saveWishlist(updatedWishlist);
      updateWishlistCount(updatedWishlist);
      renderWishlistPage(updatedWishlist);
    });

    card.append(image, removeButton, title);
    grid.append(card);
  });
};

document.querySelectorAll('.heart').forEach((button) => {
  button.addEventListener('click', () => {
    const product = getWishlistProduct(button);

    if (!product) {
      return;
    }

    const wishlist = readWishlist();
    const isSaved = wishlist.some((savedProduct) => savedProduct.id === product.id);
    const updatedWishlist = isSaved
      ? wishlist.filter((savedProduct) => savedProduct.id !== product.id)
      : [...wishlist, product];

    saveWishlist(updatedWishlist);
    updateWishlistCount(updatedWishlist);
    updateWishlistButtons(updatedWishlist);
    renderWishlistPage(updatedWishlist);
  });
});

const initialWishlist = readWishlist();
updateWishlistCount(initialWishlist);
updateWishlistButtons(initialWishlist);
renderWishlistPage(initialWishlist);

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('in-view');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal').forEach((section) => revealObserver.observe(section));

const backTop = document.querySelector('.back-top');
window.addEventListener('scroll', () => backTop.classList.toggle('show', window.scrollY > 500), { passive: true });
backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

document.querySelector('.menu-toggle')?.addEventListener('click', () => {
  document.querySelector('.main-nav').classList.toggle('mobile-open');
});





document.addEventListener('DOMContentLoaded', function () {

    const categoryLinks = document.querySelectorAll('.category-links a');
    const tabPanes = document.querySelectorAll('#products .tab-content .tab-pane');

    categoryLinks.forEach(function (link) {

        link.addEventListener('click', function (event) {
            event.preventDefault();

            const targetId = this.getAttribute('href');
            const targetPane = document.querySelector(targetId);

            if (!targetPane || !targetPane.classList.contains('tab-pane')) {
                return;
            }

            // Remove active from ALL category links
            categoryLinks.forEach(function (item) {
                item.classList.remove('active');
                item.setAttribute('aria-selected', 'false');
            });

            // Add active only to the clicked link
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');

            // Remove active from ALL product tabs
            tabPanes.forEach(function (pane) {
                pane.classList.remove('active', 'show');
                pane.style.display = 'none';
            });

            // Show only the selected product tab
            targetPane.classList.add('active', 'show');
            targetPane.style.display = 'block';
        });

    });

});
