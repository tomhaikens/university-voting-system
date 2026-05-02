<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Candidate - Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        .header {
            background: #2e7d32;
            color: white;
            padding: 20px;
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2e7d32;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #4caf50;
        }
        .btn {
            background: #2e7d32;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: #1b5e20;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .current-image {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .current-image img {
            max-width: 200px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .info-box {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>✏️ Edit Candidate</h1>
            <div class="nav-links">
                <a href="index.php?page=dashboard">Dashboard</a>
                <a href="index.php?page=admin&action=manage_candidates">← Back to Candidates</a>
                <a href="index.php?page=logout">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert-success">✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert-error">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Edit Candidate Information</h2>
            
            <div class="info-box">
                ℹ️ <strong>Post:</strong> <?php echo $candidate['post_name']; ?> (<?php echo ucfirst($candidate['category']); ?>)
            </div>
            
            <!-- Current Image Display -->
            <?php if($candidate['image_url']): ?>
                <div class="current-image">
                    <h3>Current Photo</h3>
                    <img src="<?php echo $candidate['image_url']; ?>" alt="Candidate Photo">
                    <br><br>
                    <a href="index.php?page=admin&action=delete_candidate_image&id=<?php echo $candidate['id']; ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('Remove this photo?')">
                        🗑️ Remove Current Photo
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Edit Form -->
            <form method="POST" action="index.php?page=admin&action=update_candidate" enctype="multipart/form-data">
                <input type="hidden" name="candidate_id" value="<?php echo $candidate['id']; ?>">
                
                <div class="form-group">
                    <label>Candidate Name *</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($candidate['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($candidate['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Course *</label>
                    <input type="text" name="course" value="<?php echo htmlspecialchars($candidate['course']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Faculty *</label>
                    <input type="text" name="faculty" value="<?php echo htmlspecialchars($candidate['faculty']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Manifesto</label>
                    <textarea name="manifesto" rows="5"><?php echo htmlspecialchars($candidate['manifesto']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Update Photo</label>
                    <input type="file" name="image" accept="image/*">
                    <small style="color: #666;">Leave empty to keep current photo. Supported: JPG, PNG, GIF (Max 2MB)</small>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn">💾 Save Changes</button>
                    <a href="index.php?page=admin&action=manage_candidates" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>