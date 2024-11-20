// script.js
const bookButtons = document.querySelectorAll('.btn-outline');
const modal = document.createElement('div');
modal.classList.add('modal');
modal.innerHTML = `
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Book Your Yoga Session</h2>
        <p>Thank you for choosing Yoga Guru! Please fill in your details, and we will confirm your booking shortly.</p>
        <form id="bookingForm">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <label for="session">Session:</label><br>
            <input type="text" id="session" name="session" required><br><br>
            <button type="submit" class="btn">Confirm Booking</button>
        </form>
    </div>
`;
document.body.appendChild(modal);

// Open modal when "Book Now" button is clicked
bookButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        modal.style.display = 'block';
        document.getElementById('session').value = e.target.previousElementSibling.previousElementSibling.textContent.trim();
    });
});

// Close modal when clicking the close button
modal.addEventListener('click', (e) => {
    if (e.target.classList.contains('close-btn')) {
        modal.style.display = 'none';
    }
});

// Close modal when clicking outside the modal content
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

// Placeholder form submission handling
document.getElementById('bookingForm').addEventListener('submit', (e) => {
    e.preventDefault();
    alert('Booking confirmed! We will contact you soon.');
    modal.style.display = 'none';
});
