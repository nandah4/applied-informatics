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
    <!-- Breadcrumb -->
    <div class="container">
        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Tentang Kami</span>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">Tentang Kami</div>
                <h1 class="hero-title"><?= htmlspecialchars($labInfo['title']) ?></h1>
                <p class="hero-description"><?= htmlspecialchars($labInfo['description']) ?></p>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="leadership-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Struktur Pimpinan</h2>
            </div>

            <div class="leadership-cards">
                <?php foreach ($leadership as $leader): ?>
                    <div class="leader-card">
                        <div class="leader-card-inner">
                            <div class="leader-image-container">
                                <img src="<?= base_url($leader['foto_profil']) ?>"
                                    alt="<?= htmlspecialchars($leader['full_name']) ?>"
                                    class="leader-image">
                                <div class="leader-image-overlay"></div>
                            </div>
                            <div class="leader-content">
                                <div class="leader-position-badge"><?= htmlspecialchars($leader['jabatan']) ?></div>
                                <h3 class="leader-name"><?= htmlspecialchars($leader['full_name']) ?></h3>
                                <div class="leader-expertise">
                                    <?php
                                    $keahlianArray = explode(', ', $leader['keahlian_list']);
                                    foreach ($keahlianArray as $keahlian):
                                    ?>
                                        <span class="expertise-badge"><?= htmlspecialchars(trim($keahlian)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Members Section -->
    <section class="members-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Dosen Anggota</h2>
            </div>

            <div class="members-grid">
                <?php foreach ($members as $member): ?>
                    <div class="member-card">
                        <div class="member-photo">
                            <img src="<?= base_url($member['foto_profil']) ?>"
                                alt="<?= htmlspecialchars($member['full_name']) ?>"
                                class="member-image">
                        </div>
                        <div class="member-info">
                            <h3 class="member-name"><?= htmlspecialchars($member['full_name']) ?></h3>
                            <p class="member-position"><?= htmlspecialchars($member['jabatan']) ?></p>
                            <div class="member-expertise">
                                <?php
                                $keahlianArray = explode(', ', $member['keahlian_list']);
                                foreach ($keahlianArray as $keahlian):
                                ?>
                                    <span class="expertise-badge secondary"><?= htmlspecialchars(trim($keahlian)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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