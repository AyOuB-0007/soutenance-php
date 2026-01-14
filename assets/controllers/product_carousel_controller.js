// assets/controllers/product_carousel_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["slide"];
    static values = { index: { type: Number, default: 0 } };

    connect() {
        this.showCurrentSlide();
        this.startAutoplay();
    }

    startAutoplay() {
        this.autoplayInterval = setInterval(() => {
            this.next();
        }, 5000); // Change slide every 5 seconds
    }

    stopAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
        }
    }

    next() {
        this.indexValue++;
        if (this.indexValue >= this.slideTargets.length) {
            this.indexValue = 0;
        }
        this.showCurrentSlide();
    }

    previous() {
        this.indexValue--;
        if (this.indexValue < 0) {
            this.indexValue = this.slideTargets.length - 1;
        }
        this.showCurrentSlide();
    }

    showCurrentSlide() {
        this.slideTargets.forEach((element, index) => {
            element.classList.toggle("active", index === this.indexValue);
        });
    }

    disconnect() {
        this.stopAutoplay();
    }
}
