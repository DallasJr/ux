let offset = 0;
const limit = 20;
const productList = document.getElementById('product-list');
const loading = document.getElementById('loading');
let isLoading = false;

async function loadProducts() {
    if (isLoading) return;

    isLoading = true;
    loading.style.display = 'block';

    try {
        const response = await fetch(`api.php?offset=${offset}`);
        const products = await response.json();

        console.log('API Response:', products);

        if (products.length === 0) {
            console.log('No more products to load.');
            window.removeEventListener('scroll', handleScroll);
            return;
        }
        products.forEach(product => {
            const card = document.createElement('div');
            card.classList.add('product-card');
            card.innerHTML = `
                <img src="${product.image_url}" alt="${product.name}">
                <div class="product-info">
                    <h3>${product.name}</h3>
                    <p>${product.description}</p>
                    <div class="product-price">${product.price} €</div>
                </div>
            `;
            productList.appendChild(card);
            setTimeout(() => {
                card.classList.add('animate');
            }, 100);
        });
        offset += limit;
    } catch (error) {
        console.error('Error loading products:', error);
    } finally {
        isLoading = false;
        loading.style.display = 'none';
    }
}

function handleScroll() {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
        loadProducts();
    }
}

window.addEventListener('scroll', handleScroll);

loadProducts();
