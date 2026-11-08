<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Personal Page | Marthin</title>

    <link rel="stylesheet" href="members/m1/style.css?v=<?php echo filemtime(__DIR__ . '/style.css'); ?>">
</head>
<body>
    <?php include dirname(__DIR__, 2) . '/app/partials/served_by.php'; ?>
    <?php include dirname(__DIR__, 2) . '/app/partials/node_nav.php'; ?>

    <header class="navbar">
        <a href="#home" class="logo" aria-label="Marthin Tambunan - Home">
            <span class="logo-mark" aria-hidden="true">
                <span class="logo-letter logo-letter-m">M</span>
                <span class="logo-letter logo-letter-t">T</span>
            </span>
            <span class="logo-name">
                <span>Marthin</span>
                <span class="logo-last-name">Tambunan</span>
            </span>
        </a>

        <nav>
            <a href="#home">Home</a>
            <a href="#education">Education</a>
            <a href="#experience">Experience</a>
            <a href="#skills">Skills</a>
            <a href="#contact">Contact</a>
        </nav>
    </header>

    <main>
        <section id="home" class="hero">
            <div class="hero-text">
                <p class="greeting">HELLO, WELCOME TO MY PAGE</p>

                <h1>Hi, I'm <span>Marthin Tambunan</span></h1>

                <p class="nim">NIM: 103012580055</p>

                <h2>Informatics Student</h2>

                <p class="description">
                    I am an Informatics student at Telkom University with an interest in UI/UX Design, Front-End Development, and Software Development. I have gained hands-on experience through projects such as Martel Asphalt and KulinerKuy, where I developed my skills in designing and building digital products using tools such as Figma and Laravel. I previously worked as a Frontend Developer Intern at PT Karya Murni Perkasa and currently serve as Managing Director at CV. Bukit Tjahaya. These experiences have helped me develop both my technical and leadership skills. I enjoy learning new things, solving problems, and turning ideas into practical solutions. I am always looking for opportunities to improve my skills, gain new experiences, and grow professionally in the technology industry.
                </p>

                <a href="#education" class="button">View My Background</a>
            </div>

            <div class="hero-image">
                <img
                    src="members/m1/images/marthin1.jpg"
                    alt="Foto profil Marthin Tambunan"
                    class="profile-photo"
                >
            </div>
        </section>

        <section id="education" class="section section-alt">
            <div class="education-skills-layout">
                <div class="education-column">
                    <p class="section-label">MY BACKGROUND</p>
                    <h2>Education</h2>

                    <div class="education-grid">
                        <article class="education-card">
                            <p class="education-category">Undergraduate Program</p>
                            <h3>Bachelor's Degree in Informatics</h3>
                            <p class="education-date">2025 &ndash; Present</p>
                            <p class="education-campus">Telkom University</p>
                        </article>

                        <article class="education-card">
                            <p class="education-category">Diploma Program</p>
                            <h3>Diploma in Application Software Engineering</h3>
                            <p class="education-date">2022 &ndash; 2025</p>
                            <p class="education-campus">Telkom University</p>
                        </article>
                    </div>
                </div>

                <aside id="skills" class="skills-panel">
                    <p class="section-label">WHAT I CAN DO</p>
                    <h2>My Skills</h2>

                    <div class="skills-container">
                        <span class="skill">HTML</span>
                        <span class="skill">CSS</span>
                        <span class="skill">JavaScript</span>
                        <span class="skill">PHP</span>
                        <span class="skill">MySQL</span>
                        <span class="skill">Problem Solving</span>
                    </div>
                </aside>
            </div>
        </section>

        <section id="experience" class="section">
            <p class="section-label">PROJECTS</p>
            <h2>What I Have Built</h2>

            <p class="section-description experience-intro">
                Selected projects in web development, mobile application design,
                and digital business solutions.
            </p>

            <div class="experience-grid">
                <article class="experience-card">
                    <figure class="project-image">
                        <button type="button" class="project-image-button" aria-label="Perbesar gambar Martel Asphalt">
                            <img
                                src="members/m1/images/martel-asphalt.png"
                                alt="Tampilan website Martel Asphalt"
                                loading="lazy"
                            >
                            <span class="project-image-hint" aria-hidden="true">View</span>
                        </button>
                    </figure>

                    <p class="project-category">Web Development</p>
                    <h3>Martel Asphalt</h3>
                    <p class="project-date">Sep 2024 &ndash; Feb 2025</p>
                    <p class="project-description">
                        An integrated construction-services platform for road
                        paving and professional heavy-equipment rental in Sumatra.
                    </p>

                    <p class="project-meta">PT Karya Murni Perkasa</p>

                    <div class="experience-skills" aria-label="Skills used">
                        <span>Front-End Design</span>
                        <span>Front-End Coding</span>
                    </div>

                    <a href="https://www.linkedin.com/in/marthin-tambunan-98842829b/"
                       target="_blank" rel="noopener noreferrer"
                       class="project-arrow" aria-label="View Martel Asphalt on LinkedIn">&rarr;</a>
                </article>

                <article class="experience-card">
                    <figure class="project-image project-image-contain">
                        <button type="button" class="project-image-button" aria-label="Perbesar gambar KulinerKuy">
                            <img
                                src="members/m1/images/kulinarkuy.png"
                                alt="Tampilan aplikasi KulinerKuy"
                                loading="lazy"
                            >
                            <span class="project-image-hint" aria-hidden="true">View</span>
                        </button>
                    </figure>

                    <p class="project-category">Mobile App Design</p>
                    <h3>KulinerKuy</h3>
                    <p class="project-date">UI/UX Project</p>
                    <p class="project-description">
                        An intuitive mobile app that helps culinary enthusiasts
                        discover, organize, and explore popular dining spots in Bandung.
                    </p>

                    <div class="experience-skills" aria-label="Skills used">
                        <span>Figma</span>
                        <span>User Interface Design</span>
                    </div>

                    <a href="https://www.linkedin.com/in/marthin-tambunan-98842829b/"
                       target="_blank" rel="noopener noreferrer"
                       class="project-arrow" aria-label="View KulinerKuy on LinkedIn">&rarr;</a>
                </article>

                <article class="experience-card">
                    <figure class="project-image">
                        <button type="button" class="project-image-button" aria-label="Perbesar gambar TJAHAYABIZ">
                            <img
                                src="members/m1/images/tjahaya-biz.png"
                                alt="Tampilan website TJAHAYABIZ"
                                loading="lazy"
                            >
                            <span class="project-image-hint" aria-hidden="true">View</span>
                        </button>
                    </figure>

                    <p class="project-category">B2B Web Application</p>
                    <h3>TJAHAYABIZ</h3>
                    <p class="project-date">Business Digitalization</p>
                    <p class="project-description">
                        A platform for product information, pricing, ordering,
                        inventory management, and administration for CV Bukit Tjahaya.
                    </p>

                    <p class="project-meta">Contributor: Samuel</p>

                    <div class="experience-skills" aria-label="Skills used">
                        <span>UI Design</span>
                        <span>Front-End Design</span>
                    </div>

                    <a href="https://www.linkedin.com/in/marthin-tambunan-98842829b/"
                       target="_blank" rel="noopener noreferrer"
                       class="project-arrow" aria-label="View TJAHAYABIZ on LinkedIn">&rarr;</a>
                </article>
            </div>

        </section>

        <section id="contact" class="section section-alt">
            <p class="section-label">LET'S CONNECT</p>
            <h2>Contact Me</h2>

            <p class="section-description">
                Feel free to connect with me for collaboration,
                learning, or sharing ideas.
            </p>

            <a
                href="https://www.linkedin.com/in/marthin-tambunan-98842829b/"
                target="_blank"
                rel="noopener noreferrer"
                class="button"
            >
                Visit LinkedIn
            </a>
        </section>
    </main>

    <footer>
        <p>
            &copy; <?php echo date("Y"); ?>
            Marthin Tambunan. Built with HTML, CSS, and PHP.
        </p>
    </footer>

    <div
        id="projectLightbox"
        class="image-lightbox"
        role="dialog"
        aria-modal="true"
        aria-label="Project image preview"
        aria-hidden="true"
        hidden
    >
        <button type="button" class="lightbox-close" aria-label="Tutup gambar">&times;</button>
        <div class="lightbox-content">
            <img id="lightboxImage" src="" alt="">
            <p id="lightboxCaption"></p>
        </div>
    </div>

    <script src="members/m1/main.js?v=<?php echo filemtime(__DIR__ . '/main.js'); ?>"></script>
</body>
</html>
