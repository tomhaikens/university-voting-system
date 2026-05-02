<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Election Monitor - MUST Voting System</title>
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
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
        }
        .result-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .result-card h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        .candidate-result {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin: 5px 0;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .candidate-name {
            font-weight: bold;
        }
        .vote-count {
            background: #2e7d32;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        .progress-bar {
            width: 100%;
            height: 30px;
            background: #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            margin-top: 10px;
        }
        .progress-fill {
            height: 100%;
            background: #4caf50;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-weight: bold;
            transition: width 0.5s ease;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        .refresh-time {
            text-align: center;
            color: #666;
            margin-top: 20px;
            font-size: 12px;
        }
        canvas {
            max-height: 300px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>📊 Live Election Monitor</h1>
            <div>
                <a href="index.php?page=dashboard">Dashboard</a>
                <a href="index.php?page=logout">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="card">
            <h2>Real-time Election Results</h2>
            <p>Results update automatically every 10 seconds</p>
        </div>
        
        <div id="results-container" class="results-grid">
            <!-- Results will be loaded here dynamically -->
        </div>
        
        <div class="refresh-time" id="refreshTime">
            Last updated: Just now
        </div>
    </div>
    
    <script>
        let charts = {};
        
        async function loadResults() {
            try {
                const response = await fetch('index.php?page=api&action=get_stats');
                const data = await response.json();
                displayResults(data);
                updateRefreshTime();
            } catch (error) {
                console.error('Error loading results:', error);
            }
        }
        
        function displayResults(data) {
            const container = document.getElementById('results-container');
            container.innerHTML = '';
            
            for (const [postName, candidates] of Object.entries(data)) {
                const totalVotes = Object.values(candidates).reduce((a, b) => a + b, 0);
                
                const resultCard = document.createElement('div');
                resultCard.className = 'result-card';
                
                let candidatesHtml = '<div class="candidate-list">';
                for (const [candidateName, votes] of Object.entries(candidates)) {
                    const percentage = totalVotes > 0 ? (votes / totalVotes * 100).toFixed(1) : 0;
                    candidatesHtml += `
                        <div class="candidate-result">
                            <span class="candidate-name">${candidateName}</span>
                            <div style="flex: 1; margin: 0 15px;">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: ${percentage}%;">
                                        ${percentage}%
                                    </div>
                                </div>
                            </div>
                            <span class="vote-count">${votes} votes</span>
                        </div>
                    `;
                }
                candidatesHtml += '</div>';
                
                resultCard.innerHTML = `
                    <h3>${postName}</h3>
                    ${candidatesHtml}
                    <p style="margin-top: 15px; text-align: center; color: #666;">
                        Total Votes: <strong>${totalVotes}</strong>
                    </p>
                `;
                
                container.appendChild(resultCard);
            }
        }
        
        function updateRefreshTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            document.getElementById('refreshTime').innerHTML = `Last updated: ${timeString} (auto-refreshes every 10 seconds)`;
        }
        
        // Load results immediately and then every 10 seconds
        loadResults();
        setInterval(loadResults, 10000);
    </script>
</body>
</html>