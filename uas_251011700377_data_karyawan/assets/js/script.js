/**
 * script.js
 * Sistem Informasi Data Karyawan
 * Berisi logika UI umum: toggle sidebar (mobile) & konfirmasi logout
 */

document.addEventListener("DOMContentLoaded", function () {
    // Toggle sidebar untuk tampilan mobile
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", function () {
            sidebar.classList.toggle("show");
        });
    }

    // Konfirmasi Logout dengan SweetAlert2 (tombol di navbar)
    const btnLogout = document.getElementById("btnLogout");
    if (btnLogout) {
        btnLogout.addEventListener("click", function (e) {
            e.preventDefault();
            confirmLogout();
        });
    }

    // Konfirmasi Logout dengan SweetAlert2 (tombol di sidebar)
    const btnLogoutSidebar = document.getElementById("btnLogoutSidebar");
    if (btnLogoutSidebar) {
        btnLogoutSidebar.addEventListener("click", function (e) {
            e.preventDefault();
            confirmLogout();
        });
    }

    function confirmLogout() {
        Swal.fire({
            icon: "question",
            title: "Keluar dari sistem?",
            text: "Anda akan diarahkan kembali ke halaman login.",
            showCancelButton: true,
            confirmButtonText: "Ya, Logout",
            cancelButtonText: "Batal",
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#6c757d"
        }).then((result) => {
            if (result.isConfirmed) {
                const base = (typeof BASE_URL !== "undefined") ? BASE_URL : "";
                window.location.href = base + "logout.php";
            }
        });
    }
});
