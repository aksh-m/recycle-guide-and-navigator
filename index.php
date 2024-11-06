<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recycle Guide and Navigator</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header>
        <div class="recycle-symbol">♻</div>

        <?php if (isset($_SESSION['username'])): ?>
            <!-- Display for logged-in users -->
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>Your points: <span id="points"><?php echo htmlspecialchars($_SESSION['points']); ?></span></p>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <!-- Display for guests -->
            <h1>Recycle Guide and Navigator!</h1>
            <a href="login.html" class="redirect" align='left'>Login</a>
            &nbsp;&nbsp;
            <a href="register.html" class="redirect">Register</a>
        <?php endif; ?>
        
        <div id="positivity-line"></div>
    </header>

    <main>
        <!-- Rest of your page content here -->
        <section id="guide">
            <h2>Recycling Guide</h2>
            <div class="container">
                <button class="nav-button prev" onclick="moveSlide(-1)">❮</button>
                <div class="imgContainer" id="imgContainer">
                    <div class="slide_div" id="slide_1">
                        <img src="assets/images/paper.jpg" alt="Paper" class="img" width="900" height="180">
                        <a href="paper.html" class="link">Paper</a>
                    </div>
                    <div class="slide_div" id="slide_2">
                        <img src="assets/images/plastic.jpeg" alt="Plastic" class="img" width="900" height="180">
                        <a href="plastic.html" class="link">Plastic</a>
                    </div>
                    <div class="slide_div" id="slide_3">
                        <img src="assets/images/glass.png" alt="Glass" class="img" width="900" height="180">
                        <a href="glass.html" class="link">Glass</a>
                    </div>
                    <div class="slide_div" id="slide_4">
                        <img src="assets/images/metal.png" alt="Metal" class="img" width="900" height="180">
                        <a href="metal.html" class="link">Metal</a>
                    </div>
                    <div class="slide_div" id="slide_5">
                        <img src="assets/images/electronic.png" alt="Electronics" class="img" width="900" height="180">
                        <a href="electronics.html" class="link">Electronics</a>
                    </div>
                </div>
                <button class="nav-button next" onclick="moveSlide(1)">❯</button>
            </div>
            <center><a href="question.html" id="openQuestionnaire">Assess Your Waste Management Skills</a></center>
        </section>

        <section id="price">
            <div class="calc">
                <h1>Recycling Price Calculator</h1>
                <form id="recyclingForm" method="POST" action="calculate_price.php">
                    <center>
                        <label for="itemType">Select the type of recycling item:</label>
                        <select id="itemType" name="itemType" required>
                            <option value="plastic">Plastic</option>
                            <option value="paper">Paper</option>
                            <option value="glass">Glass</option>
                            <option value="metal">Metal</option>
                            <option value="electronics">Electronics</option>
                        </select>

                        <label for="quantity"><br><br>Enter quantity (in kg):</label>
                        <input type="number" id="quantity" name="quantity" min="0.1" step="0.1" required><br><br>

                        <button type="button" onclick="calculatePrice()">Calculate Price</button>
                    </center>
                </form>

                <div id="result" class="result"></div>
                <div id="appreciation" class="appreciation"></div>
            </div>
        </section>

        <!-- Leaderboard Section -->
        <section id="leaderboard">
            <h2>Top Recyclers</h2>
            <div id="top-recyclers">
                <!-- The top users will be dynamically loaded here -->
            </div>
        </section>
        
        <section id="navigator">
            <h2>Find a Recycling Center</h2>
            <center><input type="text" id="location" placeholder="Enter your location">
                <button onclick="findRecyclingCenter()">Search</button>
            </center>
            <div id="results"></div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Recycle Guide. All rights reserved.</p>
            <nav>
                <ul>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="contact.html">Contact Us</a></li>
                    <li><a href="#">Services</a></li>
                </ul>
            </nav>
        </div>
    </footer>

    <script src="script1.js"></script>
    <script>
        // Load the leaderboard data when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            fetch('fetch_leaderboard.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('top-recyclers').innerHTML = data;
                });
        });

        function calculatePrice() {
            let itemType = document.getElementById('itemType').value;
            let quantity = parseFloat(document.getElementById('quantity').value);
            let price = 0;

            switch (itemType) {
                case 'plastic': price = quantity * 5; break;
                case 'paper': price = quantity * 3; break;
                case 'glass': price = quantity * 2; break;
                case 'metal': price = quantity * 8; break;
                case 'electronics': price = quantity * 10; break;
            }

            document.getElementById('result').innerHTML = `Estimated Price: ₹${price}`;

            // Update points if logged in
            if ('<?php echo isset($_SESSION['username']); ?>' === '1') {
                fetch('update_points.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `points=${quantity}&user=<?php echo $_SESSION['username']; ?>`
                }).then(response => response.text()).then(data => {
                    document.getElementById('appreciation').innerHTML = data;
                });
            }
        }
    </script>
</body>
</html>
