<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Settings - Admin</title>
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
            max-width: 600px;
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
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .btn {
            background: #2e7d32;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .btn:hover {
            background: #1b5e20;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .current-time {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
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
            <h1>Election Settings</h1>
            <div>
                <a href="index.php?page=dashboard">Dashboard</a>
                <a href="index.php?page=admin&action=manage_candidates">Candidates</a>
                <a href="index.php?page=admin&action=manage_users">Users</a>
                <a href="index.php?page=logout">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="current-time">
                <strong>Current Server Time:</strong> <?php echo date('h:i:s A'); ?>
            </div>
            
            <h2>Set Voting Hours</h2>
            <form method="POST" action="index.php?page=admin&action=settings">
                <div class="form-group">
                    <label>Start Time</label>
                    <input type="time" name="start_time" value="<?php echo $settings['start_time']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>End Time</label>
                    <input type="time" name="end_time" value="<?php echo $settings['end_time']; ?>" required>
                </div>
                
                <button type="submit" class="btn">Update Voting Hours</button>
            </form>
            
            <hr style="margin: 30px 0;">
            
            <h3>Current Settings</h3>
            <p>Voting is open from <strong><?php echo date('h:i A', strtotime($settings['start_time'])); ?></strong> 
               to <strong><?php echo date('h:i A', strtotime($settings['end_time'])); ?></strong></p>
        </div>
    </div>
</body>
</html>