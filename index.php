<?php
require_once 'includes/header.php';

// Fetch featured products from database
$featured_products = [];
try {
    $sql = "SELECT * FROM products WHERE featured = 1 OR status = 'active' ORDER BY created_at DESC LIMIT 4";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $featured_products = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    echo "<!-- Database error: " . $e->getMessage() . " -->";
}

// If no featured products, get any 4 products
if (empty($featured_products)) {
    try {
        $sql = "SELECT * FROM products LIMIT 4";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $featured_products = $result->fetch_all(MYSQLI_ASSOC);
        }
    } catch (Exception $e) {
        echo "<!-- Database error: " . $e->getMessage() . " -->";
    }
}
?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-orange-400 via-red-500 to-orange-600 text-white">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative container mx-auto px-4 py-20 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-center lg:text-left fade-in">
                <h1 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
                    Authentic Indian Spices<br>
                    <span class="text-yellow-300">Straight to Your Kitchen</span>
                </h1>
                <p class="text-xl mb-8 text-gray-100">
                    Discover the rich flavors of India with Pisal Masala's premium quality spices and masalas. 
                    100% authentic, fresh, and packed with love.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="products.php" class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-yellow-300 transition transform hover:scale-105 shadow-lg">
                        <i class="fas fa-shopping-bag mr-2"></i>Shop Now
                    </a>
                    <a href="#featured" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-gray-900 transition">
                        <i class="fas fa-star mr-2"></i>View Products
                    </a>
                </div>
            </div>
            <div class="relative">
                <img src="assets/images/hero-spices.jpg" alt="Pisal Masala Products" class="rounded-lg shadow-2xl">
                <div class="absolute -bottom-6 -left-6 bg-yellow-400 text-gray-900 p-4 rounded-lg shadow-xl">
                    <div class="text-3xl font-bold">500+</div>
                    <div class="text-sm font-semibold">Premium Products</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-6 hover:shadow-lg transition rounded-lg">
                <div class="text-orange-600 text-4xl mb-4">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Free Shipping</h3>
                <p class="text-gray-600">On orders above ₹499</p>
            </div>
            <div class="text-center p-6 hover:shadow-lg transition rounded-lg">
                <div class="text-orange-600 text-4xl mb-4">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">100% Authentic</h3>
                <p class="text-gray-600">Pure quality guaranteed</p>
            </div>
            <div class="text-center p-6 hover:shadow-lg transition rounded-lg">
                <div class="text-orange-600 text-4xl mb-4">
                    <i class="fas fa-undo"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Easy Returns</h3>
                <p class="text-gray-600">7-day return policy</p>
            </div>
            <div class="text-center p-6 hover:shadow-lg transition rounded-lg">
                <div class="text-orange-600 text-4xl mb-4">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">24/7 Support</h3>
                <p class="text-gray-600">Dedicated customer service</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section id="featured" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-star text-yellow-400 mr-2"></i>Featured Products
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Handpicked selection of our best-selling spices and masalas
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (!empty($featured_products)): ?>
                <?php foreach ($featured_products as $product): ?>
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition transform hover:scale-105">
                        <div class="relative">
                            <img src="assets/images/products/<?php echo !empty($product['image']) ? htmlspecialchars($product['image']) : 'default-product.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>" 
                                 class="w-full h-48 object-cover rounded-t-lg">
                            <?php if (!empty($product['badge'])): ?>
                                <span class="absolute top-2 left-2 bg-<?php echo htmlspecialchars($product['badge_color'] ?? 'red'); ?>-500 text-white px-2 py-1 text-xs rounded">
                                    <?php echo htmlspecialchars($product['badge']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2"><?php echo htmlspecialchars($product['name'] ?? 'Product Name'); ?></h3>
                            <div class="text-yellow-400 mb-2">
                                <?php 
                                $rating = $product['rating'] ?? 4.5;
                                for ($i = 1; $i <= 5; $i++): 
                                    if ($i <= floor($rating)): 
                                        echo '<i class="fas fa-star"></i>';
                                    elseif ($i - 0.5 <= $rating): 
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    else: 
                                        echo '<i class="far fa-star"></i>';
                                    endif;
                                endfor; 
                                ?>
                                <span class="text-gray-600 text-sm ml-1">(<?php echo number_format($rating, 1); ?>)</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars($product['description'] ?? 'Premium quality product'); ?></p>
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-2xl font-bold text-orange-600">₹<?php echo number_format($product['price'] ?? 0); ?></span>
                                    <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                        <span class="text-gray-400 line-through text-sm ml-2">₹<?php echo number_format($product['original_price']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <button onclick="addToCart(<?php echo $product['id'] ?? 0; ?>)" class="bg-orange-600 text-white px-3 py-2 rounded hover:bg-orange-700 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback static products if database is empty -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition transform hover:scale-105">
                    <div class="relative">
                        <img src="assets/images/turmeric-powder.jpg" alt="Turmeric Powder" class="w-full h-48 object-cover rounded-t-lg">
                        <span class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 text-xs rounded">Bestseller</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Premium Turmeric Powder</h3>
                        <div class="text-yellow-400 mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="text-gray-600 text-sm ml-1">(4.5)</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">Pure, organic turmeric powder with high curcumin content</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-orange-600">₹120</span>
                                <span class="text-gray-400 line-through text-sm ml-2">₹150</span>
                            </div>
                            <button onclick="addToCart(1)" class="bg-orange-600 text-white px-3 py-2 rounded hover:bg-orange-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition transform hover:scale-105">
                    <div class="relative">
                        <img src="assets/images/garam-masala.jpg" alt="Garam Masala" class="w-full h-48 object-cover rounded-t-lg">
                        <span class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 text-xs rounded">Organic</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Authentic Garam Masala</h3>
                        <div class="text-yellow-400 mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span class="text-gray-600 text-sm ml-1">(5.0)</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">Traditional blend of aromatic spices for perfect curries</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-orange-600">₹180</span>
                                <span class="text-gray-400 line-through text-sm ml-2">₹220</span>
                            </div>
                            <button onclick="addToCart(2)" class="bg-orange-600 text-white px-3 py-2 rounded hover:bg-orange-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition transform hover:scale-105">
                    <div class="relative">
                        <img src="assets/images/red-chilli.jpg" alt="Red Chilli Powder" class="w-full h-48 object-cover rounded-t-lg">
                        <span class="absolute top-2 left-2 bg-orange-500 text-white px-2 py-1 text-xs rounded">Hot</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Kashmiri Red Chilli Powder</h3>
                        <div class="text-yellow-400 mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <span class="text-gray-600 text-sm ml-1">(4.0)</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">Mild yet flavorful red chilli powder with vibrant color</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-orange-600">₹95</span>
                                <span class="text-gray-400 line-through text-sm ml-2">₹120</span>
                            </div>
                            <button onclick="addToCart(3)" class="bg-orange-600 text-white px-3 py-2 rounded hover:bg-orange-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition transform hover:scale-105">
                    <div class="relative">
                        <img src="assets/images/jeera.jpg" alt="Cumin Seeds" class="w-full h-48 object-cover rounded-t-lg">
                        <span class="absolute top-2 left-2 bg-purple-500 text-white px-2 py-1 text-xs rounded">Premium</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Premium Cumin Seeds</h3>
                        <div class="text-yellow-400 mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="text-gray-600 text-sm ml-1">(4.7)</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">Aromatic and flavorful whole cumin seeds</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-orange-600">₹160</span>
                                <span class="text-gray-400 line-through text-sm ml-2">₹200</span>
                            </div>
                            <button onclick="addToCart(4)" class="bg-orange-600 text-white px-3 py-2 rounded hover:bg-orange-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-8">
            <a href="products.php" class="bg-orange-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-700 transition transform hover:scale-105">
                View All Products <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                Shop by Category
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Explore our wide range of spice categories
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="products.php?category=powders" class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition">
                <img src="assets/images/spice-powders.jpg" alt="Spice Powders" class="w-full h-48 object-cover group-hover:scale-110 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                    <div class="p-4 text-white">
                        <h3 class="font-semibold text-lg">Spice Powders</h3>
                        <p class="text-sm">50+ Products</p>
                    </div>
                </div>
            </a>

            <a href="products.php?category=masalas" class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition">
                <img src="assets/images/ready-masalas.jpg" alt="Ready Masalas" class="w-full h-48 object-cover group-hover:scale-110 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                    <div class="p-4 text-white">
                        <h3 class="font-semibold text-lg">Ready Masalas</h3>
                        <p class="text-sm">30+ Products</p>
                    </div>
                </div>
            </a>

            <a href="products.php?category=whole-spices" class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition">
                <img src="assets/images/whole-spices.jpg" alt="Whole Spices" class="w-full h-48 object-cover group-hover:scale-110 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                    <div class="p-4 text-white">
                        <h3 class="font-semibold text-lg">Whole Spices</h3>
                        <p class="text-sm">40+ Products</p>
                    </div>
                </div>
            </a>

            <a href="products.php?category=special" class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition">
                <img src="assets/images/special-blends.jpg" alt="Special Blends" class="w-full h-48 object-cover group-hover:scale-110 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                    <div class="p-4 text-white">
                        <h3 class="font-semibold text-lg">Special Blends</h3>
                        <p class="text-sm">20+ Products</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                What Our Customers Say
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Real reviews from our valued customers
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-yellow-400 mb-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-600 mb-4 italic">
                    "Amazing quality spices! The turmeric powder is so fresh and aromatic. 
                    I've been using Pisal Masala for over a year now and never disappointed."
                </p>
                <div class="flex items-center">
                    <img src="assets/images/customer1.jpg" alt="Customer" class="w-12 h-12 rounded-full mr-3">
                    <div>
                        <h4 class="font-semibold">Priya Sharma</h4>
                        <p class="text-gray-500 text-sm">Mumbai, Maharashtra</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-yellow-400 mb-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-600 mb-4 italic">
                    "The garam masala blend is perfect! It reminds me of my grandmother's cooking. 
                    Fast delivery and excellent packaging. Highly recommend!"
                </p>
                <div class="flex items-center">
                    <img src="assets/images/customer2.jpg" alt="Customer" class="w-12 h-12 rounded-full mr-3">
                    <div>
                        <h4 class="font-semibold">Rahul Verma</h4>
                        <p class="text-gray-500 text-sm">Delhi, NCR</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-yellow-400 mb-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="text-gray-600 mb-4 italic">
                    "Great quality at reasonable prices. The organic spice collection is my favorite. 
                    Customer service is also very responsive. Will definitely order again!"
                </p>
                <div class="flex items-center">
                    <img src="assets/images/customer3.jpg" alt="Customer" class="w-12 h-12 rounded-full mr-3">
                    <div>
                        <h4 class="font-semibold">Anita Patel</h4>
                        <p class="text-gray-500 text-sm">Ahmedabad, Gujarat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Special Offers Banner -->
<section class="py-12 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="mb-6 md:mb-0">
                <h2 class="text-3xl font-bold mb-2">
                    <i class="fas fa-percentage mr-2"></i>Special Offer!
                </h2>
                <p class="text-xl">Get 20% off on all organic products this week</p>
            </div>
            <div class="flex space-x-4">
                <a href="offers.php" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    View Offers
                </a>
                <a href="products.php?category=organic" class="bg-yellow-400 text-gray-900 px-6 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition">
                    Shop Organic
                </a>
            </div>
        </div>
    </div>
</section>

<script>
function addToCart(productId) {
    // Add to cart logic here
    updateCartCount(1);
    
    // Show success message
    const message = document.createElement('div');
    message.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 fade-in';
    message.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Product added to cart!';
    document.body.appendChild(message);
    
    setTimeout(() => {
        message.remove();
    }, 3000);
}
</script>

<?php require_once 'includes/footer.php'; ?>
