import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["image"]

    connect() {
        this.setupImageFallbacks()
    }

    setupImageFallbacks() {
        // Gérer les images dans les cartes d'articles
        const articleImages = document.querySelectorAll('.article-image img')
        articleImages.forEach(img => {
            // Pré-charger l'image pour éviter le clignotement
            this.preloadImage(img)
            
            img.addEventListener('error', () => {
                this.handleImageError(img, 'article')
            })
            
            img.addEventListener('load', () => {
                img.parentElement.classList.remove('error')
                img.style.opacity = '1'
            })
        })

        // Gérer les images dans la pancarte flottante
        const menuImages = document.querySelectorAll('.item-image img')
        menuImages.forEach(img => {
            this.preloadImage(img)
            
            img.addEventListener('error', () => {
                this.handleImageError(img, 'menu')
            })
            
            img.addEventListener('load', () => {
                img.parentElement.classList.remove('error')
                img.style.opacity = '1'
            })
        })
    }

    preloadImage(img) {
        // Commencer avec l'image transparente
        img.style.opacity = '0'
        img.style.transition = 'opacity 0.3s ease'
        
        // Créer une nouvelle image pour pré-charger
        const preloader = new Image()
        preloader.onload = () => {
            img.style.opacity = '1'
        }
        preloader.onerror = () => {
            this.handleImageError(img, img.closest('.article-image') ? 'article' : 'menu')
        }
        preloader.src = img.src
    }

    handleImageError(img, type) {
        console.log(`Image failed to load: ${img.src}`)
        
        // Cacher l'image cassée
        img.style.display = 'none'
        img.style.opacity = '0'
        
        // Ajouter la classe error au conteneur parent
        img.parentElement.classList.add('error')
        
        // Utiliser des images de fallback plus fiables - vraies images de sandwichs
        const reliableImages = [
            'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=400&fit=crop&crop=center', // Sandwich baguette au poulet (comme votre photo)
            'https://images.unsplash.com/photo-1509722747041-616f39b57569?w=400&h=400&fit=crop&crop=center', // Sandwich saucisse (l'ancienne bonne photo)
            'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=400&h=400&fit=crop&crop=center', // Sandwich baguette jambon
            'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=400&h=400&fit=crop&crop=center', // Club sandwich
            'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400&h=400&fit=crop&crop=center'  // Pizza (en dernier recours)
        ]
        
        this.tryFallbackImage(img, reliableImages)
    }

    tryFallbackImage(img, fallbackUrls) {
        if (fallbackUrls.length === 0) {
            // Si toutes les images échouent, laisser le CSS gérer avec l'emoji
            return
        }

        const fallbackUrl = fallbackUrls.shift()
        
        const testImg = new Image()
        testImg.onload = () => {
            img.src = fallbackUrl
            img.style.display = 'block'
            img.style.opacity = '1'
            img.parentElement.classList.remove('error')
        }
        
        testImg.onerror = () => {
            this.tryFallbackImage(img, fallbackUrls)
        }
        
        testImg.src = fallbackUrl
    }
}