<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Candidates - Admin</title>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2e7d32;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #4caf50;
        }
        .btn {
            background: #2e7d32;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
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
        .btn-edit {
            background: #2196f3;
        }
        .btn-edit:hover {
            background: #0b7dda;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #2e7d32;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background: #f5f5f5;
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
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background: #1b5e20;
        }
        h2 {
            color: #2e7d32;
            margin-bottom: 20px;
        }
        h3 {
            color: #2e7d32;
            margin: 20px 0 10px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        .candidate-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .no-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>🎓 MUST Voting System - Manage Candidates</h1>
            <div class="nav-links">
                <a href="index.php?page=dashboard">Dashboard</a>
                <a href="index.php?page=admin&action=manage_users">Users</a>
                <a href="index.php?page=admin&action=reports">Reports</a>
                <a href="index.php?page=admin&action=settings">Settings</a>
                <a href="index.php?page=logout">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert-success">
                ✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert-error">
                ❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Add New Candidate Form -->
        <div class="card">
            <h2>➕ Add New Candidate</h2>
            <form method="POST" action="index.php?page=admin&action=add_candidate" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label>Select Post *</label>
                        <select name="post_id" required>
                            <option value="">-- Select Post --</option>
                            <?php foreach($posts as $post): ?>
                                <option value="<?php echo $post['id']; ?>">
                                    <?php echo strtoupper($post['category']); ?>: <?php echo $post['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Candidate Name *</label>
                        <input type="text" name="name" placeholder="Full name" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" placeholder="candidate@must.ac.ug" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Course *</label>
                        <input type="text" name="course" placeholder="e.g., BCS, BSE, MBChB" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Faculty *</label>
                        <input type="text" name="faculty" placeholder="e.g., Computing and Informatics" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Candidate Photo</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Manifesto</label>
                    <textarea name="manifesto" rows="4" placeholder="What the candidate promises to do..."></textarea>
                </div>
                
                <button type="submit" class="btn">➕ Add Candidate</button>
            </form>
        </div>
        
        <!-- Current Candidates List -->
        <div class="card">
            <h2>📋 Current Candidates</h2>
            <?php foreach($posts as $post): ?>
                <h3>
                    <?php 
                        $icon = '';
                        if($post['category'] == 'general') $icon = '🎯';
                        elseif($post['category'] == 'faculty') $icon = '📚';
                        else $icon = '🏘️';
                        echo $icon . ' ' . $post['name']; 
                    ?>
                    <span style="font-size: 12px; color: #666; font-weight: normal;">
                        (<?php echo ucfirst($post['category']); ?>)
                    </span>
                </h3>
                <?php if(isset($candidates[$post['id']]) && count($candidates[$post['id']]) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Faculty</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($candidates[$post['id']] as $candidate): ?>
                                <tr>
                                    <td>
                                        <?php if($candidate['image_url']): ?>
                                            <img src="<?php echo $candidate['image_url']; ?>" alt="Photo" class="candidate-photo">
                                        <?php else: ?>
                                            <div class="no-photo">📷</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($candidate['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($candidate['course']); ?></td>
                                    <td><?php echo htmlspecialchars($candidate['faculty']); ?></td>
                                    <td><?php echo htmlspecialchars($candidate['email']); ?></td>
                                    <td class="action-buttons">
                                        <a href="index.php?page=admin&action=edit_candidate&id=<?php echo $candidate['id']; ?>" 
                                           class="btn btn-edit btn-sm">
                                            ✏️ Edit
                                        </a>
                                        <a href="index.php?page=admin&action=remove_candidate&id=<?php echo $candidate['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to remove <?php echo addslashes($candidate['name']); ?>? This action cannot be undone.')" 
                                           class="btn btn-danger btn-sm">
                                            🗑️ Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="padding: 20px; text-align: center; color: #999; background: #f9f9f9; border-radius: 8px;">
                        No candidates added for this post yet.
                    </p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>