<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn {
            background: #2e7d32;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        canvas {
            max-height: 300px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Voting Reports</h1>
            <div>
                <a href="index.php?page=dashboard">Dashboard</a>
                <a href="index.php?page=admin&action=manage_candidates">Candidates</a>
                <a href="index.php?page=admin&action=manage_users">Users</a>
                <a href="index.php?page=logout">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <h2>Voting Statistics & Reports</h2>
        
        <div class="dashboard-grid">
            <div class="card">
                <h3>General Election Statistics</h3>
                <p>Total Students: <strong><?php echo $total_users; ?></strong></p>
                <p>Voted in General: <strong><?php echo $general_voters; ?></strong></p>
                <p>Turnout: <strong><?php echo $total_users > 0 ? round(($general_voters / $total_users) * 100, 2) : 0; ?>%</strong></p>
                <a href="index.php?page=admin&action=download_report&type=general" class="btn">Download CSV</a>
            </div>
            
            <div class="card">
                <h3>Faculty-wise Statistics</h3>
                <canvas id="facultyChart"></canvas>
                <a href="index.php?page=admin&action=download_report&type=faculty" class="btn">Download Faculty Report</a>
            </div>
            
            <div class="card">
                <h3>Regional Statistics</h3>
                <canvas id="regionalChart"></canvas>
                <a href="index.php?page=admin&action=download_report&type=regional" class="btn">Download Regional Report</a>
            </div>
            
            <div class="card">
                <h3>Course-wise Statistics</h3>
                <canvas id="courseChart"></canvas>
                <a href="index.php?page=admin&action=download_report&type=course" class="btn">Download Course Report</a>
            </div>
        </div>
    </div>
    
    <script>
        // Faculty Chart
        const facultyCtx = document.getElementById('facultyChart').getContext('2d');
        new Chart(facultyCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($faculty_voting, 'faculty')); ?>,
                datasets: [{
                    label: 'Number of Voters',
                    data: <?php echo json_encode(array_column($faculty_voting, 'voted')); ?>,
                    backgroundColor: '#4caf50'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
        
        // Regional Chart
        const regionalCtx = document.getElementById('regionalChart').getContext('2d');
        new Chart(regionalCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($regional_voting, 'address_type')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($regional_voting, 'voted')); ?>,
                    backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#9c27b0']
                }]
            },
            options: { responsive: true }
        });
        
        // Course Chart
        const courseCtx = document.getElementById('courseChart').getContext('2d');
        new Chart(courseCtx, {
            type: 'horizontalBar',
            data: {
                labels: <?php echo json_encode(array_slice(array_column($course_voting, 'course'), 0, 10)); ?>,
                datasets: [{
                    label: 'Voters',
                    data: <?php echo json_encode(array_slice(array_column($course_voting, 'voted'), 0, 10)); ?>,
                    backgroundColor: '#2196f3'
                }]
            },
            options: { responsive: true, indexAxis: 'y' }
        });
    </script>
</body>
</html>