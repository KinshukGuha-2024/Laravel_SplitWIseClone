<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toast Demo</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; }
        button { padding: 0.6rem 1rem; font-size: 1rem; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Web Toast</h1>
    <p>Click the button if you don’t see a notification.</p>
    <button id="trigger">Show Toast</button>

    <script>
        const trigger = document.getElementById('trigger');

        const showToast = () => {
            new Notification('Laravel Alert', { body: 'Your task is completed' });
        };

        const requestAndShow = async () => {
            if (!('Notification' in window)) {
                alert('Notifications are not supported in this browser.');
                return;
            }

            const permission = await Notification.requestPermission();

            if (permission === 'granted') {
                showToast();
            } else {
                alert('Notification permission was denied.');
            }
        };

        // Try immediately; browsers that require a gesture can use the button.
        requestAndShow().catch(console.error);
        trigger.addEventListener('click', requestAndShow);
    </script>
</body>
</html>
