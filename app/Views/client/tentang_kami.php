<!-- Header -->
<?php
include __DIR__ . '/../layouts/header.php';

// TODO: Fetch data from controller
// For now, using sample data structure
$labInfo = [
    'title' => 'Tentang Lab Applied Informatics',
    'subtitle' => 'Fokus & Tujuan Kami',
    'description' => 'Laboratorium Applied Informatics merupakan pusat inovasi dan penelitian di bidang teknologi informasi yang berfokus pada pengembangan solusi berbasis data dan kecerdasan buatan. Kami berkomitmen untuk menghasilkan riset berkualitas tinggi dan mengembangkan produk teknologi yang bermanfaat bagi masyarakat dan industri.'
];

// Sample leadership data - replace with actual database query
$leadership = [
    [
        'id' => 1,
        'full_name' => 'Dr. John Doe, S.Kom., M.T.',
        'jabatan' => 'Kepala Laboratorium',
        'foto_profil' => 'uploads/default/image.png',
        'keahlian_list' => 'Artificial Intelligence, Machine Learning'
    ],
    [
        'id' => 2,
        'full_name' => 'Jane Smith, S.Kom., M.Kom.',
        'jabatan' => 'Sekretaris Laboratorium',
        'foto_profil' => 'uploads/dosen/default-avatar.jpg',
        'keahlian_list' => 'Web Development, Database Systems'
    ]
];

// Sample members data - replace with actual database query
$members = [
    [
        'id' => 3,
        'full_name' => 'Dr. Alice Johnson, S.T., M.T.',
        'jabatan' => 'Dosen',
        'foto_profil' => 'uploads/default/image.png',
        'keahlian_list' => 'Data Mining, Big Data'
    ],
    [
        'id' => 4,
        'full_name' => 'Bob Wilson, S.Kom., M.Sc.',
        'jabatan' => 'Dosen',
        'foto_profil' => 'uploads/default/image.png',
        'keahlian_list' => 'Computer Vision, Deep Learning'
    ],
    [
        'id' => 5,
        'full_name' => 'Carol Davis, S.Kom., M.T.',
        'jabatan' => 'Dosen',
        'foto_profil' => 'uploads/default/image.png',
        'keahlian_list' => 'IoT, Embedded Systems'
    ],
    [
        'id' => 6,
        'full_name' => 'David Lee, S.T., M.Kom.',
        'jabatan' => 'Dosen',
        'foto_profil' => 'uploads/default/image.png',
        'keahlian_list' => 'Software Engineering, Cloud Computing'
    ]
];
?>

<!-- Main Content -->
<main class="tentang-kami-page">
    <!-- Page Title Section -->
    <section class="page-title-section">
        <div class="container">
            <h1 class="page-title"><?= htmlspecialchars($labInfo['title']) ?></h1>
        </div>
    </section>

    <!-- About Lab Section -->
    <section class="about-lab-section">
        <div class="container">
            <div class="content-wrapper">
                <h2 class="section-subtitle"><?= htmlspecialchars($labInfo['subtitle']) ?></h2>
                <p class="about-description"><?= htmlspecialchars($labInfo['description']) ?></p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <!-- Section Title -->
            <h2 class="section-title">Anggota Laboratorium</h2>

            <!-- Leadership Subsection -->
            <div class="leadership-subsection">
                <h3 class="subsection-title">Struktur Pimpinan</h3>

                <div class="leadership-grid">
                    <?php foreach ($leadership as $leader): ?>
                        <div class="profile-card">
                            <div class="profile-image-wrapper">
                                <img src="<?= base_url($leader['foto_profil']) ?>"
                                    alt="<?= htmlspecialchars($leader['full_name']) ?>"
                                    class="profile-image">
                            </div>
                            <div class="profile-info">
                                <h4 class="profile-name"><?= htmlspecialchars($leader['full_name']) ?></h4>
                                <p class="profile-position"><?= htmlspecialchars($leader['jabatan']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Members Subsection -->
            <div class="members-subsection">
                <h3 class="subsection-title">Dosen Anggota</h3>

                <div class="members-grid">
                    <?php foreach ($members as $member): ?>
                        <div class="profile-card">
                            <div class="profile-image-wrapper">

                            </div>
                            <div class="profile-info">
                                <h4 class="profile-name"><?= htmlspecialchars($member['full_name']) ?></h4>
                                <div class="profile-expertise">
                                    <?php
                                    $keahlianArray = explode(', ', $member['keahlian_list']);
                                    foreach ($keahlianArray as $keahlian):
                                    ?>
                                        <span class="expertise-tag"><?= htmlspecialchars(trim($keahlian)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Page Specific CSS -->
<link rel="stylesheet" href="<?= asset_url('css/pages/tentang-kami/tentang-kami.css') ?>">

<!-- Footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>