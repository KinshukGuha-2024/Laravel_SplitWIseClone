importScripts("https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js");

firebase.initializeApp({
  apiKey: "AIzaSyABU02id_bDNa0tKkr2ociBA0mERh0Hz14",
  authDomain: "test-project-37959.firebaseapp.com",
  databaseURL: "https://test-project-37959-default-rtdb.firebaseio.com",
  projectId: "test-project-37959",
  storageBucket: "test-project-37959.firebasestorage.app",
  messagingSenderId: "327215588065",
  appId: "1:327215588065:web:d095a5dfbe7432679c4da0",
  measurementId: "G-RZWFDCQJK6"
});

const messaging = firebase.messaging();
