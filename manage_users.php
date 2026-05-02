<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
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
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #2e7d32;
            color: white;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 2px;
        }
        .btn-admin {
            background: #ff9800;
            color: white;
        }
        .btn-user {
            background: #2196f3;
            color: white;
        }
        .btn-block {
            background: #dc3545;
            color: white;
        }
        .btn-unblock {
            background: #28a745;
            color: white;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Manage Users</h1>
            <div>
                <a href="index.php?page=dashboard">Dashboard</a>
                <a href="index.php?page=admin&action=manage_candidates">Candidates</a>
                <a href="index.php?page=admin&action=reports">Reports</a>
                <a href="index.php?page=logout">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Registered Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Faculty</th>
                        <th>Course</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['faculty']; ?></td>
                            <td><?php echo $user['course']; ?></td>
                            <td><?php echo ucfirst($user['role']); ?></td>
                            <td><?php echo $user['is_blocked'] ? 'Blocked' : 'Active'; ?></td>
                            <td>
                                <?php if($user['email'] != '2024bcs195@std.must.ac.ug'): ?>
                                    <a href="index.php?page=admin&action=toggle_admin&id=<?php echo $user['id']; ?>" 
                                       class="btn <?php echo $user['role'] == 'admin' ? 'btn-user' : 'btn-admin'; ?>">
                                        <?php echo $user['role'] == 'admin' ? 'Remove Admin' : 'Make Admin'; ?>
                                    </a>
                                    <a href="index.php?page=admin&action=block_user&id=<?php echo $user['id']; ?>" 
                                       class="btn <?php echo $user['is_blocked'] ? 'btn-unblock' : 'btn-block'; ?>">
                                        <?php echo $user['is_blocked'] ? 'Unblock' : 'Block'; ?>
                                    </a>
                                <?php else: ?>
                                    <span>Super Admin</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>