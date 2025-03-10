<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="max-w-xl">
        <h3 class="text-lg font-semibold mb-4">Inviter un ami</h3>

        <button onclick="generateLink()" class="px-4 py-2 bg-blue-500 text-white rounded">Générer le lien d'invitation</button>
        <button onclick="generateQRCode()" class="px-4 py-2 bg-green-500 text-white rounded ml-2">Générer le code QR</button>

        <!-- Notification -->
        <div id="notification" class="mt-4 p-2 text-white hidden rounded"></div>
    </div>
</div>

<script>
    function showNotification(message, isSuccess = true) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = `mt-4 p-2 rounded ${isSuccess ? 'bg-green-500' : 'bg-red-500'}`;
        notification.style.display = 'block';

        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    async function generateLink() {
        try {
            const response = await fetch("{{ route('generate.invite.link') }}");
            const data = await response.json();

            if (response.ok) {
                await navigator.clipboard.writeText(data.link);
                showNotification('Lien copié avec succès !', true);
            } else {
                showNotification(data.message || 'Erreur lors de la génération du lien.', false);
            }
        } catch (error) {
            showNotification('Erreur de connexion au serveur.', false);
        }
    }

    async function generateQRCode() {
        try {
            const response = await fetch("{{ route('generate.qr.code') }}");
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'qr_code.png';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                showNotification('Code QR téléchargé avec succès !', true);
            } else {
                showNotification('Erreur lors du téléchargement du code QR.', false);
            }
        } catch (error) {
            showNotification('Erreur de connexion au serveur.', false);
        }
    }
</script>
