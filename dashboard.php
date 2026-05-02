<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - MUST Voting System</title>
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
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            font-size: 24px;
        }
        .btn {
            background: #2e7d32;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
            display: inline-block;
            text-decoration: none;
        }
        .btn:hover {
            background: #1b5e20;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }
        .info-text {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
        }
        a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        .stats-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-top: 30px;
        }
        canvas {
            max-height: 400px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>🎓 MUST Online Voting System</h1>
            <div>
                <span>Welcome, <?php echo $_SESSION['user_name']; ?> (<?php echo $_SESSION['course']; ?>)</span>
                <a href="index.php?page=monitor">📊 Live Monitor</a>
                <a href="index.php?page=logout">🚪 Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="info-text">
            ⏰ Voting Hours: 9:00 AM - 4:00 PM daily
        </div>
        
        <h2>Cast Your Vote</h2>
        <div class="dashboard-grid">
            <div class="card">
                <h3>🎯 General Elections</h3>
                <p>Vote for Guild President, Vice President, and other university leaders</p>
                <?php if(!$has_voted_general): ?>
                    <a href="index.php?page=vote&category=general" class="btn">Vote Now →</a>
                <?php else: ?>
                    <div class="alert-success">✓ You have already voted in General Elections</div>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <h3>📚 Faculty Elections</h3>
                <p>Vote for GRC and Secretary (<?php echo $_SESSION['course']; ?>)</p>
                <?php if(!$has_voted_faculty): ?>
                    <a href="index.php?page=vote&category=faculty" class="btn">Vote Now →</a>
                <?php else: ?>
                    <div class="alert-success">✓ You have already voted in Faculty Elections</div>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <h3>🏘️ Regional Elections</h3>
                <p>Vote for Regional GRC and Secretary (<?php echo $_SESSION['address']; ?>)</p>
                <?php if(!$has_voted_regional): ?>
                    <a href="index.php?page=vote&category=regional" class="btn">Vote Now →</a>
                <?php else: ?>
                    <div class="alert-success">✓ You have already voted in Regional Elections</div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="stats-container">
            <h3>📈 Live Election Progress</h3>
            <canvas id="liveChart"></canvas>
        </div>
    </div>
    
    <script>
        async function loadStats() {
            try {
                const response = await fetch('index.php?page=api&action=get_stats');
                const data = await response.json();
                updateChart(data);
            } catch(e) {
                console.error('Error loading stats:', e);
            }
        }
        
        function updateChart(data) {
            const ctx = document.getElementById('liveChart').getContext('2d');
            if (window.myChart) window.myChart.destroy();
            
            const labels = [];
            const votes = [];
            
            for(let post in data) {
                for(let candidate in data[post]) {
                    labels.push(candidate);
                    votes.push(data[post][candidate]);
                }
            }
            
            window.myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.slice(0, 10),
                    datasets: [{
                        label: 'Votes Received',
                        data: votes.slice(0, 10),
                        backgroundColor: '#4caf50',
                        borderColor: '#2e7d32',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Number of Votes' } }
                    }
                }
            });
        }
        
        loadStats();
        setInterval(loadStats, 10000);
    </script>
</body>
</html>