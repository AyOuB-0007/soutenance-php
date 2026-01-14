// Animation au scroll - Version standalone
document.addEventListener('DOMContentLoaded', function() {
    console.log('Scroll animations loaded')
    
    function setupScrollAnimations() {
        const animatedElements = document.querySelectorAll('.animate-on-scroll, .animate-fade-up')
        console.log('Found animated elements:', animatedElements.length)
        
        animatedElements.forEach(element => {
            element.classList.remove('visible')
        })
    }

    function handleScroll() {
        const animatedElements = document.querySelectorAll('.animate-on-scroll, .animate-fade-up')
        
        animatedElements.forEach(element => {
            if (isElementInViewport(element)) {
                element.classList.add('visible')
            }
        })
    }

    function isElementInViewport(element) {
        const rect = element.getBoundingClientRect()
        const windowHeight = window.innerHeight || document.documentElement.clientHeight
        
        return (
            rect.top <= windowHeight * 0.8 &&
            rect.bottom >= 0
        )
    }

    // Initialiser
    setupScrollAnimations()
    handleScroll()
    
    // Écouter le scroll
    window.addEventListener('scroll', handleScroll)
    
    // Déclencher après un court délai
    setTimeout(handleScroll, 100)
})