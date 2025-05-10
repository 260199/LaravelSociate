// // Konfigurasi Echo
// const echo = new Echo({
//     broadcaster: 'pusher',
//     key: '0e6943fcf6b465eff50f',
//     cluster: 'ap1',
//     forceTLS: true
// });
// echo.channel('renstra-channel')
//     .listen('.renstra.created', (e) => {
//         console.log("Notifikasi masuk 🚀", e);

//         // Menampilkan pesan notifikasi
//         const renstraAlert = document.getElementById('renstraAlert');
//         renstraAlert.innerHTML = '<strong>Berhasil!</strong> Renstra baru ditambahkan: ' + e.renstra.nama;

//         // Menampilkan notifikasi (alert)
//         renstraAlert.style.display = 'block';

//         // Menyembunyikan notifikasi setelah beberapa detik
//         setTimeout(() => {
//             renstraAlert.style.display = 'none';
//         }, 5000);  // Menyembunyikan setelah 5 detik
//     });
