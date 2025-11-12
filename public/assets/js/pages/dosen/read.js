/**
 * Data Dosen - Detail/Read Page Scripts
 */

(function() {
    'use strict';

    // Delete confirmation
    window.confirmDelete = function() {
        if (confirm('Apakah Anda yakin ingin menghapus data dosen ini?')) {
            // Add your delete logic here
            console.log('Delete dosen');
            // Example: window.location.href = 'delete.php?id=1';
            alert('Data dosen telah dihapus');
            window.location.href = 'index.php';
        }
    };

})();
