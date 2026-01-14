import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    connect() {
        console.log('Scroll animation controller connected')
        this.setupScrollAnimations()
        this.handleScroll()
        window.addEventListener('scroll', this.handleScroll.bind(this))
        
        // Déclencher immédiatement pour les éléments déjà visibles
        setTimeout(() => {
            this.handleScroll()
        }, 100)
    }

    disconnect() {
        window.removeEventListener('scroll', this.handleScroll.bind(this))
    }

    setupScrollAnimations() {
        // Initialiser tous les éléments animés comme invisibles
        const animatedElements = document.querySelectorAll('.animate-on-scroll, .animate-fade-up')
        console.log('Found animated elements:', animatedElements.length)
        
        animatedElements.forEach(element => {
            element.classList.remove('visible')
        })
    }

    handleScroll() {
        const animatedElements = document.querySelectorAll('.animate-on-scroll, .animate-fade-up')
        
        animatedElements.forEach(element => {
            if (this.isElementInViewport(element)) {
                element.classList.add('visible')
                console.log('Element became visible:', element)
            }
        })
    }

    isElementInViewport(element) {
        const rect = element.getBoundingClientRect()
        const windowHeight = window.innerHeight || document.documentElement.clientHeight
        
        // L'élément est visible s'il est à 80% dans la viewport
        return (
            rect.top <= windowHeight * 0.8 &&
            rect.bottom >= 0
        )
    }
}