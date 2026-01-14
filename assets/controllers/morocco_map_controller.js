import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["marker", "tooltip"]

    connect() {
        console.log('Morocco map controller connected')
        this.setupMapInteractions()
        this.setupResponsiveMap()
        this.setupImageFallback()
        
        // Initialiser les tooltips
        this.hideAllTooltips()
        this.showMainTooltip()
    }

    setupImageFallback() {
        const svgImage = this.element.querySelector('.morocco-svg')
        if (svgImage) {
            svgImage.addEventListener('error', () => {
                console.log('SVG failed to load, creating fallback')
                this.createFallbackMap()
            })
            
            svgImage.addEventListener('load', () => {
                console.log('SVG loaded successfully')
            })
        }
    }

    createFallbackMap() {
        const mapWrapper = this.element.querySelector('.map-wrapper')
        const svgImage = mapWrapper.querySelector('.morocco-svg')
        
        // Créer une carte de fallback avec CSS
        svgImage.style.display = 'none'
        mapWrapper.style.background = `
            linear-gradient(135deg, #64748b 0%, #475569 100%),
            radial-gradient(ellipse 60% 80% at 30% 50%, #94a3b8 0%, transparent 50%)
        `
        mapWrapper.style.position = 'relative'
        
        // Ajouter du texte de fallback
        const fallbackText = document.createElement('div')
        fallbackText.innerHTML = `
            <div style="
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: white;
                text-align: center;
                font-size: 18px;
                font-weight: 600;
            ">
                <i class="fas fa-map-marked-alt" style="font-size: 48px; margin-bottom: 16px; opacity: 0.7;"></i>
                <br>
                Carte du Maroc
            </div>
        `
        mapWrapper.appendChild(fallbackText)
    }

    setupMapInteractions() {
        // Gérer les clics sur les marqueurs
        const markers = this.element.querySelectorAll('.city-marker')
        
        markers.forEach(marker => {
            marker.addEventListener('mouseenter', (e) => {
                this.showTooltip(e.currentTarget)
                this.highlightMarker(e.currentTarget)
            })
            
            marker.addEventListener('mouseleave', (e) => {
                if (!e.currentTarget.classList.contains('casablanca-main')) {
                    this.hideTooltip(e.currentTarget)
                }
                this.unhighlightMarker(e.currentTarget)
            })
            
            marker.addEventListener('click', (e) => {
                this.handleMarkerClick(e.currentTarget)
            })
        })
    }

    setupResponsiveMap() {
        // Ajuster la taille de la carte selon l'écran
        const mapWrapper = this.element.querySelector('.map-wrapper')
        const updateMapSize = () => {
            const containerWidth = this.element.offsetWidth
            if (containerWidth < 500) {
                mapWrapper.style.height = '350px'
            } else if (containerWidth < 700) {
                mapWrapper.style.height = '400px'
            } else {
                mapWrapper.style.height = '500px'
            }
        }
        
        updateMapSize()
        window.addEventListener('resize', updateMapSize)
    }

    showTooltip(marker) {
        const tooltip = marker.querySelector('.city-tooltip')
        if (tooltip) {
            tooltip.classList.add('active')
        }
    }

    hideTooltip(marker) {
        const tooltip = marker.querySelector('.city-tooltip')
        if (tooltip) {
            tooltip.classList.remove('active')
        }
    }

    hideAllTooltips() {
        const tooltips = this.element.querySelectorAll('.city-tooltip')
        tooltips.forEach(tooltip => {
            tooltip.classList.remove('active')
        })
    }

    showMainTooltip() {
        const mainTooltip = this.element.querySelector('.casablanca-main .city-tooltip')
        if (mainTooltip) {
            setTimeout(() => {
                mainTooltip.classList.add('active')
            }, 1000)
        }
    }

    highlightMarker(marker) {
        marker.style.zIndex = '50'
        
        // Effet de zoom sur le marqueur
        const markerDot = marker.querySelector('.marker-dot, .marker-pin')
        if (markerDot) {
            markerDot.style.transform = 'scale(1.1)'
        }
    }

    unhighlightMarker(marker) {
        marker.style.zIndex = '20'
        
        const markerDot = marker.querySelector('.marker-dot, .marker-pin')
        if (markerDot) {
            markerDot.style.transform = 'scale(1)'
        }
    }

    handleMarkerClick(marker) {
        const cityName = marker.dataset.city
        console.log(`Clicked on ${cityName}`)
        
        if (marker.classList.contains('casablanca-main')) {
            // Action spéciale pour Casablanca (restaurant)
            this.showRestaurantInfo()
        } else {
            // Action pour les autres villes
            this.showCityInfo(cityName)
        }
        
        // Animation de clic
        this.animateMarkerClick(marker)
    }

    showRestaurantInfo() {
        // Afficher plus d'informations sur le restaurant
        const tooltip = this.element.querySelector('.casablanca-main .city-tooltip')
        if (tooltip) {
            tooltip.style.transform = 'translateX(-50%) translateY(-10px) scale(1.05)'
            setTimeout(() => {
                tooltip.style.transform = 'translateX(-50%) translateY(-5px) scale(1)'
            }, 200)
        }
    }

    showCityInfo(cityName) {
        // Afficher des informations sur la ville
        console.log(`Showing info for ${cityName}`)
    }

    animateMarkerClick(marker) {
        const markerElement = marker.querySelector('.marker-dot, .pin-head')
        if (markerElement) {
            markerElement.style.animation = 'none'
            setTimeout(() => {
                markerElement.style.animation = 'bounce-pin 0.6s ease'
            }, 10)
        }
    }

    disconnect() {
        window.removeEventListener('resize', this.updateMapSize)
    }
}