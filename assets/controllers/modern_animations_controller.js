import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["animateOnScroll", "countUp", "floatingCard"]

    connect() {
        this.setupScrollAnimations()
        this.setupCountUpAnimations()
        this.setupFloatingCards()
    }

    setupScrollAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-slide-up')
                }
            })
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        })

        this.animateOnScrollTargets.forEach(target => {
            observer.observe(target)
        })
    }

    setupCountUpAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCountUp(entry.target)
                    observer.unobserve(entry.target)
                }
            })
        }, { threshold: 0.5 })

        this.countUpTargets.forEach(target => {
            observer.observe(target)
        })
    }

    animateCountUp(element) {
        const finalValue = parseInt(element.textContent) || 0
        const duration = 2000
        const startTime = performance.now()
        
        const updateCount = (currentTime) => {
            const elapsed = currentTime - startTime
            const progress = Math.min(elapsed / duration, 1)
            
            // Easing function for smooth animation
            const easeOutQuart = 1 - Math.pow(1 - progress, 4)
            const currentValue = Math.floor(finalValue * easeOutQuart)
            
            element.textContent = currentValue
            
            if (progress < 1) {
                requestAnimationFrame(updateCount)
            } else {
                element.textContent = finalValue
            }
        }
        
        requestAnimationFrame(updateCount)
    }

    setupFloatingCards() {
        this.floatingCardTargets.forEach((card, index) => {
            // Add staggered animation delay
            card.style.animationDelay = `${index * 0.2}s`
            
            // Add hover effect
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px) scale(1.02)'
            })
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)'
            })
        })
    }

    // Method to show notifications
    showNotification(message, type = 'success') {
        const notification = document.createElement('div')
        notification.className = `notification ${type}`
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'}"></i>
                <span>${message}</span>
            </div>
        `
        
        document.body.appendChild(notification)
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.opacity = '0'
            notification.style.transform = 'translateX(100%)'
            setTimeout(() => {
                document.body.removeChild(notification)
            }, 300)
        }, 5000)
    }

    // Method to handle loading states
    setLoading(element, isLoading) {
        if (isLoading) {
            element.classList.add('loading')
            element.disabled = true
        } else {
            element.classList.remove('loading')
            element.disabled = false
        }
    }
}