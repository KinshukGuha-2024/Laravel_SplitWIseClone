<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

const firebaseConfig = {
  apiKey: "AIzaSyABU02id_bDNa0tKkr2ociBA0mERh0Hz14",
  authDomain: "test-project-37959.firebaseapp.com",
  databaseURL: "https://test-project-37959-default-rtdb.firebaseio.com",
  projectId: "test-project-37959",
  storageBucket: "test-project-37959.firebasestorage.app",
  messagingSenderId: "327215588065",
  appId: "1:327215588065:web:d095a5dfbe7432679c4da0",
  measurementId: "G-RZWFDCQJK6"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

if ("serviceWorker" in navigator) {
  const registration = await navigator.serviceWorker.register(
    "/firebase-messaging-sw.js"
  );

  const permission = await Notification.requestPermission();

  if (permission === "granted") {
    const token = await getToken(messaging, {
        vapidKey: "BDhl9TahizkoWJIfjA2671riTa6EE112eKal1K0--4RRc73eFK9xS2PgImk2U0XWRG-8c_CdBi0E0eUxx7UDEsw",
        serviceWorkerRegistration: registration
    });


    console.log("✅ FCM Token:", token);
  } else {
    console.log("❌ Notification permission denied");
  }
}
</script>
