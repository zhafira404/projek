<?php
$pageTitle = 'Menu Lengkap - Dapoer Aisyah';
require_once 'config/database.php';
require_once 'includes/header.php';

// Get filter parameters
$category = isset($_GET['category']) ? cleanInput($_GET['category']) : '';
$search = isset($_GET['search']) ? cleanInput($_GET['search']) : '';
$sort = isset($_GET['sort']) ? cleanInput($_GET['sort']) : 'name';

try {
    $pdo = getConnection();
    
    // Get categories for filter
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE is_active = 1 ORDER BY name");
    $stmt->execute();
    $categories = $stmt->fetchAll();
    
    // Build products query
    $whereConditions = ["p.is_active = 1"];
    $params = [];
    
    if ($category) {
        $whereConditions[] = "c.slug = ?";
        $params[] = $category;
    }
    
    if ($search) {
        $whereConditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $whereClause = implode(' AND ', $whereConditions);
    
    // Determine sort order
    $orderBy = "p.name ASC";
    switch($sort) {
        case 'price-low':
            $orderBy = "p.price ASC";
            break;
        case 'price-high':
            $orderBy = "p.price DESC";
            break;
        case 'rating':
            $orderBy = "p.rating DESC";
            break;
    }
    
    $sql = "
        SELECT p.*, c.name as category_name, c.slug as category_slug
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE $whereClause
        ORDER BY $orderBy
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $products = [];
    $categories = [];
}
?>

<main>
    <!-- Menu Page -->
    <section class="menu-page">
        <div class="container">
            <!-- Page Header -->
            <div class="section-header">
                <h2>Menu Lengkap</h2>
                <p>Temukan berbagai pilihan menu lezat dari Dapoer Aisyah</p>
            </div>
            
            <!-- Filters -->
            <div class="menu-filters">
                <div class="filter-container">
                    <!-- Search Filter -->
                    <div class="search-filter">
                        <form method="GET" class="search-form">
                            <div class="search-input-wrapper">
                                <input type="text" name="search" placeholder="Cari menu favorit..."
                                       value="<?php echo htmlspecialchars($search); ?>" class="filter-search-input">
                                <button type="submit" class="filter-search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <?php if ($category): ?>
                                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                            <?php endif; ?>
                            <?php if ($sort): ?>
                                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
                            <?php endif; ?>
                        </form>
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="category-filter">
                        <div class="filter-tabs">
                            <a href="menu.php" class="filter-tab <?php echo !$category ? 'active' : ''; ?>">
                                <i class="fas fa-th-large"></i>
                                Semua Menu
                            </a>
                            <?php foreach($categories as $cat): ?>
                                <a href="menu.php?category=<?php echo $cat['slug']; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $sort ? '&sort=' . $sort : ''; ?>"
                                   class="filter-tab <?php echo $category === $cat['slug'] ? 'active' : ''; ?>">
                                    <i class="<?php echo $cat['icon'] ?: 'fas fa-utensils'; ?>"></i>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Sort Filter -->
                    <div class="sort-filter">
                        <div class="sort-wrapper">
                            <label for="sortSelect">
                                <i class="fas fa-sort"></i>
                                Urutkan:
                            </label>
                            <select id="sortSelect" name="sort" onchange="changeSortOrder(this.value)" class="sort-select">
                                <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Nama A-Z</option>
                                <option value="price-low" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Harga Terendah</option>
                                <option value="price-high" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Harga Tertinggi</option>
                                <option value="rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Rating Tertinggi</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Results Info -->
            <?php if ($search || $category): ?>
                <div class="results-info">
                    <p>
                        Menampilkan <?php echo count($products); ?> hasil
                        <?php if ($search): ?>
                            untuk "<strong><?php echo htmlspecialchars($search); ?></strong>"
                        <?php endif; ?>
                        <?php if ($category): ?>
                            <?php 
                            $categoryName = '';
                            foreach($categories as $cat) {
                                if ($cat['slug'] === $category) {
                                    $categoryName = $cat['name'];
                                    break;
                                }
                            }
                            ?>
                            dalam kategori "<strong><?php echo htmlspecialchars($categoryName); ?></strong>"
                        <?php endif; ?>
                    </p>
                    <a href="menu.php" class="clear-filters">
                        <i class="fas fa-times"></i>
                        Hapus Filter
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Products Grid -->
            <?php if (count($products) > 0): ?>
                <div class="products-grid">
                    <?php foreach($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo $product['image'] ?: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=300&h=200&fit=crop'; ?>"
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     onerror="this.src='https://via.placeholder.com/300x200/2c5530/ffffff?text=<?php echo urlencode($product['name']); ?>'">
                                <div class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></div>
                                <button class="product-favorite" onclick="toggleFavorite(<?php echo $product['id']; ?>)">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-description"><?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?></p>
                                <div class="product-rating">
                                    <div class="rating-stars">
                                        <?php
                                        $rating = $product['rating'];
                                        for($i = 1; $i <= 5; $i++):
                                            echo $i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                        endfor;
                                        ?>
                                    </div>
                                    <span class="rating-text"><?php echo $rating; ?> (<?php echo rand(5, 50); ?> ulasan)</span>
                                </div>
                                <div class="product-footer">
                                    <div class="product-price">
                                        <span class="price-label">Mulai dari</span>
                                        <span class="price-amount">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                                    </div>
                                    <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo $product['image']; ?>')">
                                        <i class="fas fa-cart-plus"></i>
                                        <span>Tambah</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <div class="no-products-content">
                        <i class="fas fa-search no-products-icon"></i>
                        <h3>Tidak ada menu yang ditemukan</h3>
                        <p>Coba ubah filter atau kata kunci pencarian Anda</p>
                        <a href="menu.php" class="btn btn-primary">
                            <i class="fas fa-utensils"></i>
                            Lihat Semua Menu
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
/* Menu Page Specific Styles */
.menu-page {
    padding: 2rem 0;
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    min-height: 80vh;
}

.menu-filters {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 3rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.filter-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

.search-filter {
    display: flex;
    justify-content: center;
}

.search-input-wrapper {
    display: flex;
    max-width: 400px;
    width: 100%;
}

.filter-search-input {
    flex: 1;
    padding: 1rem 1.5rem;
    border: 2px solid #e1e5e9;
    border-radius: 15px 0 0 15px;
    outline: none;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.filter-search-input:focus {
    border-color: #2c5530;
    box-shadow: 0 0 0 3px rgba(44, 85, 48, 0.1);
}

.filter-search-btn {
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    color: white;
    border: none;
    padding: 1rem 1.5rem;
    border-radius: 0 15px 15px 0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(44, 85, 48, 0.3);
}

.filter-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    justify-content: center;
}

.filter-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid #e1e5e9;
    border-radius: 25px;
    text-decoration: none;
    color: #666;
    font-weight: 500;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.filter-tab:hover,
.filter-tab.active {
    background: linear-gradient(135deg, #2c5530, #3e7b3e);
    color: white;
    border-color: #2c5530;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(44, 85, 48, 0.3);
}

.sort-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
    justify-content: center;
}

.sort-wrapper label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    color: #333;
}

.sort-select {
    padding: 0.75rem 1rem;
    border: 2px solid #e1e5e9;
    border-radius: 12px;
    outline: none;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.sort-select:focus {
    border-color: #2c5530;
    box-shadow: 0 0 0 3px rgba(44, 85, 48, 0.1);
}

.results-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1rem 1.5rem;
    background: rgba(44, 85, 48, 0.05);
    border-radius: 12px;
    border-left: 4px solid #2c5530;
}

.results-info p {
    margin: 0;
    color: #333;
    font-weight: 500;
}

.clear-filters {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #e74c3c;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.clear-filters:hover {
    color: #c0392b;
}

.no-products {
    background: white;
    border-radius: 20px;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.no-products-icon {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1.5rem;
}

.no-products h3 {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.no-products p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .filter-container {
        gap: 1.5rem;
    }
    
    .filter-tabs {
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }
    
    .results-info {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .sort-wrapper {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<script>
function changeSortOrder(sortValue) {
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    window.location.href = url.toString();
}
</script>

<?php require_once 'includes/footer.php'; ?>
