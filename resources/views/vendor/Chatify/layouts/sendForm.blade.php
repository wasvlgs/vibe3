<div class="messenger-sendCard">
    <form id="message-form" method="POST" action="{{ route('send.message') }}" enctype="multipart/form-data">
        @csrf

        <!-- Send Location Button -->
        <button type="button" id="send-location" class="send-location-btn">
            <i class="fas fa-location-arrow"></i> Send Location
        </button>

        <label>
            <span class="fas fa-plus-circle"></span>
            <input disabled='disabled' type="file" class="upload-attachment" name="file" 
                accept=".{{implode(', .',config('chatify.attachments.allowed_images'))}}, 
                        .{{implode(', .',config('chatify.attachments.allowed_files'))}}" />
        </label>
        <button class="emoji-button"><span class="fas fa-smile"></span></button>
        <textarea readonly='readonly' name="message" class="m-send app-scroll" placeholder="Type a message.."></textarea>
        <button disabled='disabled' class="send-button"><span class="fas fa-paper-plane"></span></button>
    </form>
</div>

<script>
document.getElementById('send-location').addEventListener('click', function (event) {
    event.preventDefault(); // Prevent any unintended form submission

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            
            console.log("User location:", lat, lon); // Debugging log
            
            // Show loading feedback (Optional)
            document.getElementById('send-location').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            // Send location data via AJAX
            fetch("{{ route('send.message') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    message: `https://www.google.com/maps?q=${lat},${lon}`,
                    id: "{{ request()->route('id') }}", // Get conversation ID dynamically
                    temporaryMsgId: Date.now()
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Location sent successfully!", data);

                // Reset button text
                document.getElementById('send-location').innerHTML = '<i class="fas fa-location-arrow"></i> Send Location';

                if (data.status === "200") {
                    alert("Location sent successfully!");
                } else {
                    alert("Error: " + data.error.message);
                }
            })
            .catch(error => {
                console.error("Error sending location:", error);
                alert("Failed to send location.");
            });
        }, function (error) {
            alert('Error getting location: ' + error.message);
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
});
</script>
