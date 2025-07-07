<?php
// Admin page for managing contact messages
require_once '../config/database.php';

// Check if user is admin (you'll need to implement this)
// if (!isAdmin()) {
//     header('Location: ../index.php');
//     exit;
// }

$pageTitle = "Kelola Pesan Kontak - Admin Dapoer Aisyah";

// Handle status updates
if ($_POST['action'] ?? '' === 'update_status') {
    try {
        $pdo = getConnection();
        $messageId = (int)$_POST['message_id'];
        $newStatus = $_POST['status'];
        $adminNotes = $_POST['admin_notes'] ?? '';
        
        $stmt = $pdo->prepare("
            UPDATE contact_messages 
            SET status = ?, admin_notes = ?, updated_at = NOW() 
            WHERE id = ?
        ");
        $stmt->execute([$newStatus, $adminNotes, $messageId]);
        
        $success = "Status pesan berhasil diupdate!";
    } catch(Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Get filter parameters
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

try {
    $pdo = getConnection();
    
    // Build query
    $whereConditions = [];
    $params = [];
    
    if ($status) {
        $whereConditions[] = "status = ?";
        $params[] = $status;
    }
    
    if ($search) {
        $whereConditions[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
        $searchTerm = "%$search%";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }
    
    $whereClause = count($whereConditions) > 0 ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
    
    // Get messages
    $stmt = $pdo->prepare("
        SELECT 
            id, name, email, phone, subject, message, status, admin_notes,
            DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as tanggal_pesan,
            DATE_FORMAT(updated_at, '%d/%m/%Y %H:%i') as tanggal_update
        FROM contact_messages 
        $whereClause
        ORDER BY 
            CASE WHEN status = 'unread' THEN 1 ELSE 2 END,
            created_at DESC
    ");
    $stmt->execute($params);
    $messages = $stmt->fetchAll();
    
    // Get statistics
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'unread' THEN 1 ELSE 0 END) as unread,
            SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read,
            SUM(CASE WHEN status = 'replied' THEN 1 ELSE 0 END) as replied
        FROM contact_messages
    ");
    $stats = $stmt->fetch();
    
} catch(Exception $e) {
    $messages = [];
    $stats = ['total' => 0, 'unread' => 0, 'read' => 0, 'replied' => 0];
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: linear-gradient(135deg, #2c5530, #3e7b3e); color: white; padding: 20px; border-radius: 10px; text-align: center; }
        .stat-number { font-size: 2rem; font-weight: bold; }
        .stat-label { font-size: 0.9rem; opacity: 0.9; }
        .filters { display: flex; gap: 15px; margin-bottom: 20px; align-items: center; }
        .filters input, .filters select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; }
        .messages-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .messages-table th, .messages-table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        .messages-table th { background: #f8f9fa; font-weight: 600; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: 500; }
        .status-unread { background: #ff6b6b; color: white; }
        .status-read { background: #ffd93d; color: #333; }
        .status-replied { background: #6bcf7f; color: white; }
        .message-preview { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }
        .btn-primary { background: #2c5530; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal-content { background: white; margin: 5% auto; padding: 20px; width: 80%; max-width: 600px; border-radius: 10px; max-height: 80vh; overflow-y: auto; }
        .close { float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📬 Kelola Pesan Kontak</h1>
            <a href="index.php" class="btn btn-secondary">← Kembali ke Dashboard</a>
        </div>

        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Total Pesan</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['unread']; ?></div>
                <div class="stat-label">Belum Dibaca</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['read']; ?></div>
                <div class="stat-label">Sudah Dibaca</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['replied']; ?></div>
                <div class="stat-label">Sudah Dibalas</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <form method="GET" style="display: flex; gap: 15px; align-items: center;">
                <select name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="unread" <?php echo $status === 'unread' ? 'selected' : ''; ?>>Belum Dibaca</option>
                    <option value="read" <?php echo $status === 'read' ? 'selected' : ''; ?>>Sudah Dibaca</option>
                    <option value="replied" <?php echo $status === 'replied' ? 'selected' : ''; ?>>Sudah Dibalas</option>
                </select>
                <input type="text" name="search" placeholder="Cari nama, email, atau subjek..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">🔍 Cari</button>
                <?php if ($status || $search): ?>
                    <a href="contact-messages.php" class="btn btn-secondary">✕ Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Messages Table -->
        <table class="messages-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Subjek</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($messages) > 0): ?>
                    <?php foreach($messages as $msg): ?>
                        <tr>
                            <td><?php echo $msg['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($msg['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($msg['email']); ?></td>
                            <td><?php echo htmlspecialchars($msg['subject'] ?: 'Tidak ada subjek'); ?></td>
                            <td class="message-preview"><?php echo htmlspecialchars(substr($msg['message'], 0, 50)) . '...'; ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $msg['status']; ?>">
                                    <?php 
                                    echo match($msg['status']) {
                                        'unread' => '🔴 Belum Dibaca',
                                        'read' => '🟡 Sudah Dibaca', 
                                        'replied' => '🟢 Sudah Dibalas',
                                        default => $msg['status']
                                    };
                                    ?>
                                </span>
                            </td>
                            <td><?php echo $msg['tanggal_pesan']; ?></td>
                            <td>
                                <button onclick="viewMessage(<?php echo $msg['id']; ?>)" class="btn btn-primary">👁️ Lihat</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">
                            <em>Tidak ada pesan yang ditemukan</em>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Message Detail Modal -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="messageDetail">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function viewMessage(id) {
            // In a real implementation, you'd fetch message details via AJAX
            document.getElementById('messageModal').style.display = 'block';
            document.getElementById('messageDetail').innerHTML = '<p>Loading message details for ID: ' + id + '</p>';
        }

        function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('messageModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
