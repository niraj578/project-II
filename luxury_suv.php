<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="luxury.css">
    <title>Luxury SUV Rental</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #2c3e50, #bdc3c7); /* Gradient from dark blue to light gray */
            color: #fff; /* White text for better contrast */
            line-height: 1.6;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.5em;
            color: #007BFF; /* Primary color for headings */
        }

        h2 {
            margin-top: 20px;
            font-size: 2em;
            color: #0056b3; /* Darker shade for subheadings */
        }

        .car-images {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .car-images img {
            margin: 10px;
            border-radius: 8px; /* Rounded corners for images */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            transition: transform 0.3s;
        }

        .car-images img:hover {
            transform: scale(1.05); /* Slight zoom effect on hover */
        }

        ul {
            list-style-type: none; /* Remove default list styling */
            padding: 0;
            margin: 20px 0;
        }

        li {
            background: #e9ecef; /* Light gray background for list items */
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px; /* Rounded corners for list items */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .book-now {
            display: inline-block;
            background: #007BFF; /* Primary color for buttons */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            margin-top: 20px;
            text-align: center;
        }

        .book-now:hover {
            background: #0056b3; /* Darker shade on hover */
        }

        #booking-options {
            background: #fff; /* White background for booking options */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 15px;
            background: #007BFF; /* Primary color for buttons */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #0056b3; /* Darker shade on hover */
        }

        .message {
            margin-top: 20px;
            color: green;
            font-weight: bold;
            text-align: center;
        }

        /* Add styles for modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.8); /* Black w/ opacity */
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: color 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #f1f1f1;
            text-decoration: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7); /* Optional: Add a semi-transparent black background for readability */
            padding: 30px; /* Padding inside the container */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Shadow effect */
        }
    </style>
</head>
<body>
<h1>Luxury SUV</h1>
    <!-- Car Images Section -->
    <div class="car-images">
    <img src="pictures/HD-wallpaper-toyota-land-cruiser-prado-2018-luxury-suv-exterior-silver-prado-front-view-japanese-cars-thumbnail.jpg" alt="Front View" style="width:300px; height:auto;" onclick="openModal(this.src)">
    <img src="pictures/2015_Toyota_Land_Cruiser_Prado_(KDJ150R)_GXL_5-door_wagon_(2016-07-07)_01.jpg" alt="Side View" style="width:300px; height:auto;" onclick="openModal(this.src)">
    <img src="pictures/405d5cbbc44b9256a5363461d13bfc33.jpg" alt="Dashboard View" style="width:300px; height:auto;" onclick="openModal(this.src)">
    <img src="pictures/Toyota_land_cruiser_prado_2017_rear_(cropped) (1).jpg" alt="Rear View" style="width:300px; height:auto;" onclick="openModal(this.src)">
    </div>


    <h2>Toyota Prado TX Features:</h2>
    <table>
        <thead>
            <tr>
                <th>Feature</th>
                <th>Specification</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Engine Power</td>
                <td>163 hp, 2.7L 4-cylinder, 245 Nm torque.</td>
            </tr>
            <tr>
                <td>Seating Capacity</td>
                <td>7 passengers.</td>
            </tr>
            <tr>
                <td>Fuel Efficiency</td>
                <td>10-12 km/l.</td>
            </tr>
            <tr>
                <td>Boot Space</td>
                <td>120 liters (seats up), up to 500 liters (seats folded).</td>
            </tr>
            <tr>
                <td>Transmission</td>
                <td>Automatic (with some manual options in certain regions).</td>
            </tr>
            <tr>
                <td>Color Options</td>
                <td>White, silver, black, gray, blue, beige.</td>
            </tr>
            <tr>
                <td>Trim Options</td>
                <td>Full-option features for comfort and technology.</td>
            </tr>
            <tr>
                <td>Price</td>
                <td>$100/day</td>
            </tr>
        </tbody>
    </table>


<a href="#" class="book-now" onclick="redirectToBooking()">Book Now</a>

    <div id="booking-options" style="display:none;">
        <h2>Booking Options</h2>
        <form id="booking-form">
            <label>Name: <input type="text" required></label><br>
            <label>Email: <input type="email" required></label><br>
            <label>Phone Number: <input type="tel" required></label><br>
            <label>Age: <input type="number" required></label><br>
            <label>Driver's License: <input type="text" required></label><br>
            <label>Pickup Location: <input type="text" required></label><br>
            <label>Drop-off Location: <input type="text" required></label><br>
            <label>Date: <input type="date" required></label><br>
            <label>Time: <input type="time" required></label><br>
            <p>Total Price: $100/day</p>
            <button type="button" onclick="confirmBooking()">Confirm Booking</button>
            <button type="button" onclick="cancelBooking()">Cancel Booking</button>
        </form>
    </div>

<!-- Modal for Fullscreen Image -->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="img01">
</div>

<script>
    function openModal(src) {
        const modal = document.getElementById("myModal");
        const modalImg = document.getElementById("img01");
        modal.style.display = "block";
        modalImg.src = src;
    }

    function closeModal() {
        const modal = document.getElementById("myModal");
        modal.style.display = "none";
    }

    function redirectToBooking() {
        // Redirect to add_booking.php
        window.location.href = "add_booking.php";
    }

    function showBookingOptions() {
        document.getElementById('booking-options').style.display = 'block'; // Show the booking options
    }

    function confirmBooking() {
        const form = document.getElementById('booking-form');
        if (form.checkValidity()) {
            alert('Your booking has been confirmed!');
        } else {
            alert('Please fill out all required fields before confirming.');
        }
    }

    function cancelBooking() {
        // Implement the logic to cancel the booking
        alert('Booking cancelled.');
    }

    // Function to show images one by one, disappear, and then show all
    function showImagesSequentially() {
        const images = document.querySelectorAll('.car-images img');
        let delay = 0;

        images.forEach((img, index) => {
            // Show each image
            setTimeout(() => {
                img.style.opacity = 1; // Make the image visible
                img.style.transform = 'translateX(0)'; // Move to original position
            }, delay);

            // Hide each image after a short time
            setTimeout(() => {
                img.style.opacity = 0; // Make the image invisible
                img.style.transform = 'translateX(-100%)'; // Move off-screen
            }, delay + 1500); // Show for 1.5 seconds before hiding

            // Increase delay for the next image
            delay += 2000; // Total delay for each image (1.5s show + 0.5s transition)
        });

        // Show all images together after the last one disappears
        setTimeout(() => {
            images.forEach((img) => {
                img.style.opacity = 1; // Make all images visible
                img.style.transform = 'translateX(0)'; // Move to original position
            });
        }, delay);
    }

    // Call the function to show images when the page loads
    window.onload = showImagesSequentially;
</script>
</body>
</html>