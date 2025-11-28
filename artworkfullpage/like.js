document.addEventListener('DOMContentLoaded', function () {
    // Adding event listener for logged-in users
    document.querySelectorAll('.heart-checkbox').forEach(checkbox => {
        checkbox.addEventListener('click', (event) => {
            const artId = event.target.dataset.artId;
            const liked = event.target.checked;

            // Check if the checkbox is disabled
            if (event.target.disabled) {
                alert("You need to log in as an audience member to like artworks.");
                event.preventDefault();
                return;
            }

            console.log('Clicked artwork ID:', artId);
            console.log('Like status:', liked);

            fetch('like_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ artId, liked })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Like status updated successfully');
                } else {
                    console.error('Failed to update like status:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Adding event listener for users not logged in
    document.querySelectorAll('.heart.not-logged-in').forEach(heart => {
        heart.addEventListener('click', function (event) {
            event.preventDefault();
            alert('Please log in first to like this artwork.');
        });
    });
});
