(function() {
    document.addEventListener("DOMContentLoaded", () => {
        
        const burgerBtn = document.getElementById('burger-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (!burgerBtn || !mobileMenu) {
            console.warn("Menu burger introuvable dans le DOM.");
            return;
        }

        const lines = {
            l1: burgerBtn.querySelector('.line-1'),
            l2: burgerBtn.querySelector('.line-2'),
            l3: burgerBtn.querySelector('.line-3')
        };
        
        const mobileLinks = document.querySelectorAll('.mobile-link');
        let isOpen = false;

        burgerBtn.addEventListener('click', () => {
            isOpen = !isOpen;

            if (isOpen) {
                
                lines.l1.classList.add('rotate-45', 'translate-y-2');
                lines.l2.classList.add('opacity-0');
                lines.l3.classList.add('-rotate-45', '-translate-y-2');

                mobileMenu.classList.remove('translate-x-full');
                mobileLinks.forEach(link => {
                    link.classList.remove('opacity-0', 'translate-y-4');
                });
                
                document.body.style.overflow = 'hidden';

            } else {
                
                lines.l1.classList.remove('rotate-45', 'translate-y-2');
                lines.l2.classList.remove('opacity-0');
                lines.l3.classList.remove('-rotate-45', '-translate-y-2');
                
                mobileMenu.classList.add('translate-x-full');
                mobileLinks.forEach(link => {
                    link.classList.add('opacity-0', 'translate-y-4');
                });

                document.body.style.overflow = '';
            }
        });
    });
})();