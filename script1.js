let currentSlide = 0;

function moveSlide(direction) {
    const slides = document.querySelectorAll('.slide_div');
    const totalSlides = slides.length;
    currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
    const offset = -currentSlide * 100; // Adjust this value if necessary
    document.getElementById('imgContainer').style.transform = 'translateX(' + offset + '%)';
}

// Function to display motivational messages
const messages = [
    "Recycling: It's good for the planet!",
    "Every recycled item makes a difference.",
    "Choose to recycle, choose a better future.",
    "Together, let's reduce waste and make a change.",
    "Recycling is easy, make it a habit!"
];

function updateMessage() {
    const positivityLine = document.getElementById('positivity-line');

    // Fade out the current message
    positivityLine.classList.add('fade-out');

    // After the fade-out, change the message and fade it back in
    setTimeout(() => {
        const message = messages[Math.floor(Math.random() * messages.length)];
        positivityLine.textContent = message;
        positivityLine.classList.remove('fade-out');
        positivityLine.classList.add('fade-in');
    }, 1000); // Match this timeout to the CSS animation duration

    // Remove the fade-in class after the animation duration to reset for the next cycle
    setTimeout(() => {
        positivityLine.classList.remove('fade-in');
    }, 2000); // Match this timeout to the CSS animation duration

    // Call the function again after 5 seconds to continue the cycle
    setTimeout(updateMessage, 5000);
}

// Initial call to set the first message
updateMessage();

// Function to find nearby recycling centers
function findRecyclingCenter() {
    const city = document.getElementById('location').value;

    if (!city) {
        document.getElementById('results').innerHTML = 'Please enter a city.';
        return;
    }

    fetch('fetch_recycling_centers.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `city=${city}`
    })
    .then(response => response.json())
    .then(data => {
        const resultsContainer = document.getElementById('results');
        resultsContainer.innerHTML = ''; // Clear previous results

        if (data.length > 0) {
            // Display centers
            data.forEach(center => {
                const centerDiv = document.createElement('div');
                centerDiv.classList.add('center');

                centerDiv.innerHTML = `
                    <h3>${center.name}</h3>
                    <p>Address: ${center.address}</p>
                    <p>City: ${center.city}</p>
                    <p>Pincode: ${center.pincode}</p>
                    <p>Phone Number: ${center.num}</p>
                `;
                resultsContainer.appendChild(centerDiv);
            });
        } else {
            resultsContainer.innerHTML = 'No recycling centers found for this city.';
        }
    })
    .catch(error => {
        console.error('Error fetching recycling centers:', error);
        document.getElementById('results').innerHTML = 'Error fetching recycling centers. Please try again later.';
    });
}

// Updated calculatePrice function to handle points calculation and leaderboard update
function calculatePrice() {
    const itemType = document.getElementById('itemType').value;
    const quantity = parseFloat(document.getElementById('quantity').value);

    let pricePerKg;
    let points;

    switch (itemType) {
        case 'plastic':
            pricePerKg = 10;
            points = quantity * 2; // Award 2 points per kg for plastic
            break;
        case 'paper':
            pricePerKg = 5;
            points = quantity * 1.5; // Award 1.5 points per kg for paper
            break;
        case 'glass':
            pricePerKg = 8;
            points = quantity * 3; // Award 3 points per kg for glass
            break;
        case 'metal':
            pricePerKg = 15;
            points = quantity * 5; // Award 5 points per kg for metal
            break;
        case 'electronics':
            pricePerKg = 12;
            points = quantity * 4; // Award 4 points per kg for electronics
            break;
        default:
            pricePerKg = 0;
            points = 0;
    }

    const totalPrice = pricePerKg * quantity;
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = `Total Price: ₹${totalPrice.toFixed(2)}`;

    const appreciationDiv = document.getElementById('appreciation');
    appreciationDiv.innerHTML = "Thank you for recycling and helping the environment!";

    // Send points data to server-side PHP for updating user points
    const currentUser = 'logged_in_user'; // Replace this with the actual logged-in username
    fetch('update_points.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `points=${points}&username=${currentUser}`
    })
    .then(response => response.text())
    .then(data => {
        appreciationDiv.innerHTML += `<br>${data}`; // Display server response for points update

        // Refresh the leaderboard to reflect the updated points
        fetchLeaderboard();
    });
}
function updatePoints(points) {
    // Create an AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_points.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText); // Display success or error message
            // Optionally update points display
            if (xhr.responseText.includes("success")) {
                var currentPoints = parseInt(document.getElementById("points").textContent);
                document.getElementById("points").textContent = currentPoints + points;
            }
        }
    };

    xhr.send("points=" + points);
}
// Function to fetch and display the leaderboard
function fetchLeaderboard() {
    fetch('fetch_leaderboard.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('top-recyclers').innerHTML = data;
        });
}

// Initial call to load the leaderboard when the page loads
document.addEventListener('DOMContentLoaded', fetchLeaderboard);
