<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $pdo = getConnection();
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            handleGetRequest($pdo);
            break;
        case 'POST':
            handlePostRequest($pdo);
            break;
        case 'PUT':
            handlePutRequest($pdo);
            break;
        case 'DELETE':
            handleDeleteRequest($pdo);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}

function handleGetRequest($pdo) {
    // Get single product by ID
    if (isset($_GET['id'])) {
        $productId = (int)$_GET['id'];
        
        $stmt = $pdo->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug,
                   AVG(r.rating) as avg_rating,
                   COUNT(r.id) as review_count
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            LEFT JOIN reviews r ON p.id = r.product_id
            WHERE p.id = ? AND p.status = 'active'
            GROUP BY p.id
        ");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            return;
        }
        
        // Get product reviews
        $stmt = $pdo->prepare("
            SELECT r.*, u.name as user_name 
            FROM reviews r 
            LEFT JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ? 
            ORDER BY r.created_at DESC 
            LIMIT 10
        ");
        $stmt->execute([$productId]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get related products
        $stmt = $pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.category_id = ? AND p.id != ? AND p.status = 'active' 
            ORDER BY RAND() 
            LIMIT 4
        ");
        $stmt->execute([$product['category_id'], $productId]);
        $relatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $product['reviews'] = $reviews;
        $product['related_products'] = $relatedProducts;
        $product['avg_rating'] = $product['avg_rating'] ? round($product['avg_rating'], 1) : 0;
        
        echo json_encode($product);
        return;
    }
    
    // Get products list with filters
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? min(50, max(1, (int)$_GET['limit'])) : 12;
    $offset = ($page - 1) * $limit;
    
    $category = isset($_GET['category']) ? cleanInput($_GET['category']) : '';
    $search = isset($_GET['search']) ? cleanInput($_GET['search']) : '';
    $sort = isset($_GET['sort']) ? cleanInput($_GET['sort']) : 'name';
    $order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';
    $minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
    $maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 0;
    
    // Build WHERE clause
    $whereConditions = ["p.status = 'active'"];
    $params = [];
    
    if ($category) {
        $whereConditions[] = "c.slug = ?";
        $params[] = $category;
    }
    
    if ($search) {
        $whereConditions[] = "(p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    if ($minPrice > 0) {
        $whereConditions[] = "p.price >= ?";
        $params[] = $minPrice;
    }
    
    if ($maxPrice > 0) {
        $whereConditions[] = "p.price <= ?";
        $params[] = $maxPrice;
    }
    
    $whereClause = implode(' AND ', $whereConditions);
    
    // Validate sort field
    $allowedSortFields = ['name', 'price', 'created_at', 'avg_rating'];
    if (!in_array($sort, $allowedSortFields)) {
        $sort = 'name';
    }
    
    // Get total count
    $countSql = "
        SELECT COUNT(DISTINCT p.id) as total
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE {$whereClause}
    ";
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Get products
    $sql = "
        SELECT p.*, c.name as category_name, c.slug as category_slug,
               AVG(r.rating) as avg_rating,
               COUNT(r.id) as review_count
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        LEFT JOIN reviews r ON p.id = r.product_id
        WHERE {$whereClause}
        GROUP BY p.id
        ORDER BY {$sort} {$order}
        LIMIT {$limit} OFFSET {$offset}
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format products
    foreach ($products as &$product) {
        $product['avg_rating'] = $product['avg_rating'] ? round($product['avg_rating'], 1) : 0;
        $product['review_count'] = (int)$product['review_count'];
        $product['price'] = (float)$product['price'];
        $product['is_featured'] = (bool)$product['is_featured'];
        $product['image'] = $product['image'] ?: '/placeholder.svg?height=300&width=300';
    }
    
    $totalPages = ceil($totalProducts / $limit);
    
    echo json_encode([
        'products' => $products,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_products' => (int)$totalProducts,
            'per_page' => $limit,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ],
        'filters' => [
            'category' => $category,
            'search' => $search,
            'sort' => $sort,
            'order' => $order,
            'min_price' => $minPrice,
            'max_price' => $maxPrice
        ]
    ]);
}

function handlePostRequest($pdo) {
    // Check if user is admin
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        return;
    }
    
    // Validate required fields
    $requiredFields = ['name', 'description', 'price', 'category_id'];
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['error' => "Field '{$field}' is required"]);
            return;
        }
    }
    
    // Validate category exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
    $stmt->execute([$input['category_id']]);
    if (!$stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid category ID']);
        return;
    }
    
    // Create slug from name
    $slug = createSlug($input['name']);
    
    // Check if slug already exists
    $stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ?");
    $stmt->execute([$slug]);
    if ($stmt->fetch()) {
        $slug .= '-' . time();
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO products (name, slug, description, price, category_id, image, is_featured, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'active', NOW())
        ");
        
        $stmt->execute([
            cleanInput($input['name']),
            $slug,
            cleanInput($input['description']),
            (float)$input['price'],
            (int)$input['category_id'],
            isset($input['image']) ? cleanInput($input['image']) : null,
            isset($input['is_featured']) ? (bool)$input['is_featured'] : false
        ]);
        
        $productId = $pdo->lastInsertId();
        
        // Get the created product
        $stmt = $pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        http_response_code(201);
        echo json_encode([
            'message' => 'Product created successfully',
            'product' => $product
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create product: ' . $e->getMessage()]);
    }
}

function handlePutRequest($pdo) {
    // Check if user is admin
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }
    
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is required']);
        return;
    }
    
    $productId = (int)$_GET['id'];
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        return;
    }
    
    // Check if product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$existingProduct) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
        return;
    }
    
    // Build update query dynamically
    $updateFields = [];
    $params = [];
    
    $allowedFields = ['name', 'description', 'price', 'category_id', 'image', 'is_featured', 'status'];
    
    foreach ($allowedFields as $field) {
        if (isset($input[$field])) {
            if ($field === 'name') {
                $updateFields[] = "name = ?, slug = ?";
                $params[] = cleanInput($input[$field]);
                $params[] = createSlug($input[$field]);
            } elseif ($field === 'category_id') {
                // Validate category exists
                $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
                $stmt->execute([$input[$field]]);
                if (!$stmt->fetch()) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Invalid category ID']);
                    return;
                }
                $updateFields[] = "category_id = ?";
                $params[] = (int)$input[$field];
            } elseif ($field === 'price') {
                $updateFields[] = "price = ?";
                $params[] = (float)$input[$field];
            } elseif ($field === 'is_featured') {
                $updateFields[] = "is_featured = ?";
                $params[] = (bool)$input[$field];
            } else {
                $updateFields[] = "{$field} = ?";
                $params[] = cleanInput($input[$field]);
            }
        }
    }
    
    if (empty($updateFields)) {
        http_response_code(400);
        echo json_encode(['error' => 'No valid fields to update']);
        return;
    }
    
    $updateFields[] = "updated_at = NOW()";
    $params[] = $productId;
    
    try {
        $sql = "UPDATE products SET " . implode(', ', $updateFields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        // Get updated product
        $stmt = $pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update product: ' . $e->getMessage()]);
    }
}

function handleDeleteRequest($pdo) {
    // Check if user is admin
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }
    
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is required']);
        return;
    }
    
    $productId = (int)$_GET['id'];
    
    // Check if product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
        return;
    }
    
    try {
        // Soft delete - just change status to 'deleted'
        $stmt = $pdo->prepare("UPDATE products SET status = 'deleted', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$productId]);
        
        echo json_encode([
            'message' => 'Product deleted successfully',
            'product_id' => $productId
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete product: ' . $e->getMessage()]);
    }
}

function createSlug($text) {
    // Convert to lowercase
    $text = strtolower($text);
    
    // Replace spaces and special characters with hyphens
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    
    // Remove leading/trailing hyphens
    $text = trim($text, '-');
    
    return $text;
}
?>
