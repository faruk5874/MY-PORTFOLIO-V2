document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const closeBtn = document.querySelector('.mobile-menu .close-btn');
    const mobileMenuLinks = document.querySelectorAll('.mobile-menu a[href^="#"]');

    // Get the search button
    const searchBtn = document.querySelector('.search-btn');


    // Toggle mobile menu
    if (menuToggle && mobileMenu && closeBtn) {
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent body scrolling
        });

        closeBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            document.body.style.overflow = ''; // Re-enable body scrolling
        });

        // Close menu if clicked outside
        document.addEventListener('click', (event) => {
            const isClickOutsideMenu = !mobileMenu.contains(event.target);
            const isClickOnMenuToggle = menuToggle.contains(event.target);

            // Ensure the menu is active and the click is truly outside both the menu and its toggle button
            if (mobileMenu.classList.contains('active') && isClickOutsideMenu && !isClickOnMenuToggle) {
                mobileMenu.classList.remove('active');
                document.body.style.overflow = ''; // Re-enable body scrolling
            }
        });

        // Close mobile menu when a link is clicked
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (mobileMenu.classList.contains('active')) {
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = ''; // Re-enable body scrolling
                }
            });
        });
    }

    // Add functionality to the search button
    if (searchBtn) {
        searchBtn.addEventListener('click', () => {
            console.log('Search button clicked!');
            // IMPORTANT: Do NOT use alert(). Use a custom message box or modal instead.
            // For now, I'm replacing it with a console log as a placeholder.
            console.log('Search functionality will appear here!');
        });
    }

    // Timeline functionality (your existing code) - Keep as is if you plan to re-introduce a timeline
    const timelineButtons = document.querySelectorAll('.timeline-btn');
    const timelineItems = document.querySelectorAll('.timeline-item');

    timelineButtons.forEach(button => {
        button.addEventListener('click', () => {
            timelineButtons.forEach(btn => btn.classList.remove('active'));
            timelineItems.forEach(item => item.classList.remove('active'));

            button.classList.add('active');

            const period = button.dataset.period;
            const activeItem = document.querySelector(`.timeline-item.${period}`);
            if (activeItem) {
                activeItem.classList.add('active');
            }
        });
    });

    if (timelineButtons.length > 0 && timelineItems.length > 0) {
        timelineButtons[0].classList.add('active');
        timelineItems[0].classList.add('active');
    }

    // New: Intersection Observer for scroll animations
    const sectionsToAnimate = document.querySelectorAll(
        '.content-blocks, .education-section, .skills-section, .projects-section, .contact-info-section'
    );

    const observerOptions = {
        root: null, // viewport
        rootMargin: '0px',
        threshold: 0.1 // Trigger when 10% of the section is visible
    };

    const sectionObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target); // Stop observing once animated
            }
        });
    }, observerOptions);

    sectionsToAnimate.forEach(section => {
        section.classList.add('fade-in-up'); // Add initial class for transition
        sectionObserver.observe(section);
    });

    // Animate individual items within sections (e.g., project cards)
    const projectCards = document.querySelectorAll('.project-card');
    const educationItems = document.querySelectorAll('.education-item');
    const skillItems = document.querySelectorAll('.skill-item');
    const contactItems = document.querySelectorAll('.contact-item');


    const itemObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px', threshold: 0.2 }); // Adjust threshold as needed

    projectCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) ${index * 0.1}s, transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) ${index * 0.1}s`;
        itemObserver.observe(card);
    });

    educationItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = `opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) ${index * 0.1}s, transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) ${index * 0.1}s`;
        itemObserver.observe(item);
    });

    skillItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = `opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) ${index * 0.1}s, transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) ${index * 0.1}s`;
        itemObserver.observe(item);

        // Add click animation for skill items
        item.addEventListener('click', () => {
            item.classList.remove('animate-click'); // Remove to re-trigger animation if clicked rapidly
            void item.offsetWidth; // Trigger reflow
            item.classList.add('animate-click');
        });
    });

    contactItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = `opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) ${index * 0.1}s, transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) ${index * 0.1}s`;
        itemObserver.observe(item);
    });

    // Typing Effect for Hero Subtitle (Modified for sequential typing without deletion)
    const typingTextElement = document.getElementById('typing-text');
    const phrases = [
        "Research Enthusiast",
        " | Data Analyst",
        " | Insights Explorer"
    ];
    let currentPhraseIndex = 0;
    let currentCharIndex = 0;
    let fullTextDisplayed = "";
    let typingSpeed = 100; // milliseconds per character

    function typeWriterSequential() {
        if (currentPhraseIndex < phrases.length) {
            const currentPhrase = phrases[currentPhraseIndex];

            if (currentCharIndex < currentPhrase.length) {
                fullTextDisplayed += currentPhrase.charAt(currentCharIndex);
                typingTextElement.textContent = fullTextDisplayed;
                currentCharIndex++;
                setTimeout(typeWriterSequential, typingSpeed);
            } else {
                // Phrase finished, move to next after a pause
                currentCharIndex = 0;
                currentPhraseIndex++;
                setTimeout(typeWriterSequential, 700); // Pause before next phrase
            }
        } else {
            // All phrases typed, keep cursor blinking
            typingTextElement.style.borderRight = '.15em solid orange'; // Ensure cursor stays
        }
    }

    // Start typing effect for hero subtitle
    typingTextElement.style.animation = 'blink-caret .75s step-end infinite';
    typeWriterSequential();

    // Typing Effect for About Section Heading ("I'am Md. Omar Faruk")
    const aboutNameTypingElement = document.getElementById('about-name-typing-text');
    const aboutWords = [
        "I'am",
        "Md.",
        "Omar",
        "Faruk"
    ];
    let aboutWordIndex = 0;
    let aboutCharIndex = 0;
    let currentAboutNameText = "";
    let aboutTypingSpeed = 100; // milliseconds per character
    let aboutDeletionSpeed = 50; // milliseconds per character
    let aboutPauseBetweenWords = 300; // Pause after typing a word before next word
    let aboutPauseBeforeLoopReset = 2000; // Pause after full name and before clearing for loop

    function typeAboutNameSegment() {
        if (aboutWordIndex < aboutWords.length) {
            const currentWord = aboutWords[aboutWordIndex];

            if (aboutCharIndex < currentWord.length) {
                currentAboutNameText += currentWord.charAt(aboutCharIndex);
                aboutNameTypingElement.textContent = currentAboutNameText;
                aboutCharIndex++;
                setTimeout(typeAboutNameSegment, aboutTypingSpeed);
            } else {
                // Word finished, add space if not last word
                if (aboutWordIndex < aboutWords.length - 1) {
                    currentAboutNameText += " "; // Add space between words
                    aboutNameTypingElement.textContent = currentAboutNameText;
                }
                aboutCharIndex = 0; // Reset for next word
                aboutWordIndex++;
                setTimeout(typeAboutNameSegment, aboutPauseBetweenWords);
            }
        } else {
            // All words typed, pause then clear for loop
            setTimeout(clearAboutNameSegment, aboutPauseBeforeLoopReset);
        }
    }

    function clearAboutNameSegment() {
        if (currentAboutNameText.length > 0) {
            currentAboutNameText = currentAboutNameText.slice(0, -1); // Remove last character
            aboutNameTypingElement.textContent = currentAboutNameText;
            setTimeout(clearAboutNameSegment, aboutDeletionSpeed);
        } else {
            // All text cleared, restart typing
            aboutWordIndex = 0;
            setTimeout(typeAboutNameSegment, 500); // Small pause before re-typing
        }
    }

    // Start typing effect for about section name, but only when it's visible.
    // However, the user wants it to run continuously regardless of visibility,
    // so we'll start it directly.
    if (aboutNameTypingElement) {
        aboutNameTypingElement.style.animation = 'blink-caret .75s step-end infinite'; // Keep cursor blinking
        typeAboutNameSegment();
    }
});