<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cast Your Vote - MUST Voting System</title>
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
        .card.voted {
            opacity: 0.7;
            background: #f5f5f5;
        }
        .candidate-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .candidate-card {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        .candidate-card:hover:not(.disabled) {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .candidate-card.selected {
            border-color: #2e7d32;
            background: #e8f5e9;
            box-shadow: 0 5px 20px rgba(46,125,50,0.2);
        }
        .candidate-card.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .candidate-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #2e7d32;
        }
        .no-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            font-size: 48px;
        }
        .candidate-name {
            font-size: 20px;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 5px;
        }
        .candidate-course {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .manifesto {
            font-size: 14px;
            color: #555;
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 8px;
            text-align: left;
        }
        .btn-vote {
            background: #2e7d32;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            width: 100%;
        }
        .btn-vote:hover:not(:disabled) {
            background: #1b5e20;
        }
        .btn-vote:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .btn-vote.voted-btn {
            background: #6c757d;
            cursor: not-allowed;
        }
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back:hover {
            background: #5a6268;
        }
        .btn-done {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }
        .vote-status {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2e7d32;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .progress-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .progress-bar-container {
            background: #e0e0e0;
            border-radius: 25px;
            height: 30px;
            overflow: hidden;
            margin-top: 10px;
        }
        .progress-fill {
            background: #4caf50;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            transition: width 0.3s ease;
        }
        .completed-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            margin-left: 10px;
        }
        h2 {
            color: #2e7d32;
            margin-bottom: 10px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        @media (max-width: 768px) {
            .candidate-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>🎓 Cast Your Vote</h1>
            <div>
                <a href="index.php?page=dashboard">Dashboard</a>
                <a href="index.php?page=monitor">Monitor</a>
                <a href="index.php?page=logout">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="index.php?page=dashboard" class="btn-back">← Back to Dashboard</a>
            <button onclick="finishVoting()" class="btn-done" id="doneBtn">✓ Finish & Return to Dashboard</button>
        </div>
        
        <div class="progress-container">
            <h3>Your Voting Progress</h3>
            <div id="progressText">Voting progress: 0/<?php echo count($posts); ?> positions voted</div>
            <div class="progress-bar-container">
                <div class="progress-fill" id="progressFill" style="width: 0%">0%</div>
            </div>
        </div>
        
        <?php foreach($posts as $index => $post): ?>
            <div class="card" id="post-<?php echo $post['id']; ?>" data-post-id="<?php echo $post['id']; ?>" data-voted="false">
                <h3>
                    <?php echo htmlspecialchars($post['name']); ?>
                    <span class="completed-badge" id="badge-<?php echo $post['id']; ?>" style="display: none;">✓ Voted</span>
                </h3>
                <p style="color: #666; margin-bottom: 15px;"><?php echo htmlspecialchars($post['description']); ?></p>
                
                <div class="candidate-grid">
                    <?php if(isset($candidatesByPost[$post['id']]) && count($candidatesByPost[$post['id']]) > 0): ?>
                        <?php foreach($candidatesByPost[$post['id']] as $candidate): ?>
                            <div class="candidate-card" onclick="selectCandidate(<?php echo $post['id']; ?>, <?php echo $candidate['id']; ?>, this)">
                                <?php if($candidate['image_url']): ?>
                                    <img src="<?php echo $candidate['image_url']; ?>" alt="<?php echo $candidate['name']; ?>" class="candidate-photo">
                                <?php else: ?>
                                    <div class="no-photo">👤</div>
                                <?php endif; ?>
                                <div class="candidate-name"><?php echo htmlspecialchars($candidate['name']); ?></div>
                                <div class="candidate-course"><?php echo htmlspecialchars($candidate['course']); ?> | <?php echo htmlspecialchars($candidate['faculty']); ?></div>
                                <?php if($candidate['manifesto']): ?>
                                    <div class="manifesto">
                                        <strong>📜 Manifesto:</strong><br>
                                        <?php echo nl2br(htmlspecialchars(substr($candidate['manifesto'], 0, 150))); ?>
                                        <?php if(strlen($candidate['manifesto']) > 150) echo '...'; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: #999;">No candidates available for this position yet.</p>
                    <?php endif; ?>
                </div>
                
                <input type="hidden" id="selected_<?php echo $post['id']; ?>" value="">
                <button class="btn-vote" onclick="submitVote(<?php echo $post['id']; ?>)" id="voteBtn_<?php echo $post['id']; ?>" disabled>
                    Vote for this Position
                </button>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="vote-status" id="voteStatus"></div>
    
    <script>
        let selectedCandidates = {};
        let votedPosts = {};
        let totalPosts = <?php echo count($posts); ?>;
        let votedCount = 0;
        
        function updateProgress() {
            const percentage = (votedCount / totalPosts) * 100;
            document.getElementById('progressFill').style.width = percentage + '%';
            document.getElementById('progressFill').innerHTML = Math.round(percentage) + '%';
            document.getElementById('progressText').innerHTML = `Voting progress: ${votedCount}/${totalPosts} positions voted`;
            
            // Show done button if all votes are cast
            if (votedCount === totalPosts) {
                document.getElementById('doneBtn').style.background = '#28a745';
                document.getElementById('doneBtn').innerHTML = '✓ All Votes Cast - Return to Dashboard';
                const statusDiv = document.getElementById('voteStatus');
                statusDiv.innerHTML = '🎉 You have voted in all positions! Click "Finish & Return" to go back.';
                statusDiv.style.background = '#28a745';
                setTimeout(() => {
                    if (votedCount === totalPosts) {
                        // Don't auto-hide this message
                    } else {
                        setTimeout(() => {
                            statusDiv.innerHTML = '';
                        }, 3000);
                    }
                }, 5000);
            }
        }
        
        function selectCandidate(postId, candidateId, element) {
            // Check if this post has already been voted for
            if (votedPosts[postId]) {
                const statusDiv = document.getElementById('voteStatus');
                statusDiv.innerHTML = '⚠️ You have already voted for this position!';
                statusDiv.style.background = '#ff9800';
                setTimeout(() => {
                    statusDiv.innerHTML = '';
                }, 2000);
                return;
            }
            
            // Remove selected class from all candidates in this post
            const postCard = document.getElementById(`post-${postId}`);
            const candidates = postCard.querySelectorAll('.candidate-card');
            candidates.forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked candidate
            element.classList.add('selected');
            
            // Store selected candidate
            selectedCandidates[postId] = candidateId;
            
            // Enable the vote button
            const voteBtn = document.getElementById(`voteBtn_${postId}`);
            voteBtn.disabled = false;
            voteBtn.style.opacity = '1';
            
            // Show status
            const statusDiv = document.getElementById('voteStatus');
            statusDiv.innerHTML = `✅ Candidate selected for this position`;
            statusDiv.style.background = '#2e7d32';
            setTimeout(() => {
                statusDiv.innerHTML = '';
            }, 2000);
        }
        
        async function submitVote(postId) {
            const candidateId = selectedCandidates[postId];
            
            if (!candidateId) {
                alert('Please select a candidate first');
                return;
            }
            
            if (votedPosts[postId]) {
                alert('You have already voted for this position!');
                return;
            }
            
            // Check voting time
            const timeCheck = await fetch('index.php?page=api&action=check_time');
            const timeData = await timeCheck.json();
            
            if (!timeData.can_vote) {
                alert(`Voting is only allowed between ${timeData.start_time} and ${timeData.end_time}`);
                return;
            }
            
            // Confirm vote
            if (!confirm('Are you sure you want to vote for this candidate? You cannot change your vote once submitted!')) {
                return;
            }
            
            const statusDiv = document.getElementById('voteStatus');
            statusDiv.innerHTML = '⏳ Submitting your vote...';
            statusDiv.style.background = '#ff9800';
            
            // Submit vote
            const formData = new FormData();
            formData.append('post_id', postId);
            formData.append('candidate_id', candidateId);
            
            try {
                const response = await fetch('index.php?page=vote&action=cast', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    // Mark this post as voted
                    votedPosts[postId] = true;
                    votedCount++;
                    
                    statusDiv.innerHTML = '✅ ' + result.message;
                    statusDiv.style.background = '#2e7d32';
                    
                    // Disable the vote button for this post
                    const voteBtn = document.getElementById(`voteBtn_${postId}`);
                    voteBtn.disabled = true;
                    voteBtn.innerText = '✓ Voted';
                    voteBtn.classList.add('voted-btn');
                    
                    // Show the voted badge
                    document.getElementById(`badge-${postId}`).style.display = 'inline-block';
                    
                    // Add voted class to card
                    const card = document.getElementById(`post-${postId}`);
                    card.classList.add('voted');
                    
                    // Disable all candidate cards in this post
                    const postCard = document.getElementById(`post-${postId}`);
                    const candidates = postCard.querySelectorAll('.candidate-card');
                    candidates.forEach(card => {
                        card.classList.add('disabled');
                        card.style.opacity = '0.6';
                        card.style.cursor = 'not-allowed';
                        card.onclick = null;
                    });
                    
                    // Update progress
                    updateProgress();
                    
                    setTimeout(() => {
                        if (votedCount !== totalPosts) {
                            statusDiv.innerHTML = '✓ Vote recorded! Continue voting for other positions.';
                            setTimeout(() => {
                                statusDiv.innerHTML = '';
                            }, 2000);
                        }
                    }, 2000);
                    
                } else {
                    statusDiv.innerHTML = '❌ ' + result.message;
                    statusDiv.style.background = '#dc3545';
                    setTimeout(() => {
                        statusDiv.innerHTML = '';
                    }, 3000);
                }
            } catch (error) {
                statusDiv.innerHTML = '❌ Error submitting vote';
                statusDiv.style.background = '#dc3545';
                setTimeout(() => {
                    statusDiv.innerHTML = '';
                }, 3000);
            }
        }
        
        function finishVoting() {
            if (votedCount === totalPosts) {
                window.location.href = 'index.php?page=dashboard';
            } else {
                const remaining = totalPosts - votedCount;
                if (confirm(`You have ${remaining} position(s) left to vote. Are you sure you want to finish? You cannot vote for them later.`)) {
                    window.location.href = 'index.php?page=dashboard';
                }
            }
        }
        
        // Check voting time on page load
        async function checkVotingTimeOnLoad() {
            const response = await fetch('index.php?page=api&action=check_time');
            const data = await response.json();
            
            if (!data.can_vote) {
                const alertDiv = document.createElement('div');
                alertDiv.style.position = 'fixed';
                alertDiv.style.top = '20px';
                alertDiv.style.left = '50%';
                alertDiv.style.transform = 'translateX(-50%)';
                alertDiv.style.background = '#dc3545';
                alertDiv.style.color = 'white';
                alertDiv.style.padding = '15px 25px';
                alertDiv.style.borderRadius = '8px';
                alertDiv.style.zIndex = '9999';
                alertDiv.innerHTML = `⚠️ Voting is only allowed between ${data.start_time} and ${data.end_time}. You can view candidates but cannot vote.`;
                document.body.appendChild(alertDiv);
                
                // Disable all vote buttons
                const allButtons = document.querySelectorAll('.btn-vote');
                allButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.innerText = 'Voting Closed';
                });
                
                // Disable candidate selection
                const allCandidates = document.querySelectorAll('.candidate-card');
                allCandidates.forEach(card => {
                    card.style.cursor = 'not-allowed';
                    card.onclick = null;
                });
            }
        }
        
        checkVotingTimeOnLoad();
    </script>
</body>
</html>