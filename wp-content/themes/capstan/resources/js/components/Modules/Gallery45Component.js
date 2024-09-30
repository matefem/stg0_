import { StaticComponent } from 'libs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export class Gallery45Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.container 	= this.element.querySelector('.image-container')
		this.inner 		= this.container.querySelector('.inner')
		this.image 		= this.container.querySelector('img')
		this.slides		= Array.from(this.element.querySelectorAll('.gallery li')).map((elem, i) => {
			return {
				elem,
				i,
				img: elem.querySelector('img'),
				over: null
			}
		});

		this.slides.forEach(slide => {
			slide.over = this.handleOver.bind(this, slide);
		});
		this.scrollY = 0;
		this.top = 0;

		this.t = gsap.timeline()

		this.eventsTriggered = false;
    }

	attach(){
		this.viewport.on('media', this.handleMedia)
		this.viewport.on('resize', this.handleResize)
		window.addEventListener('scroll', this.handleScroll)

		this.handleMedia()
		this.handleScroll()
		this.handleResize()

		ScrollTrigger.create({
			trigger: this.element,
			onEnter: this.handleResize
		})
	}

	detach(){
		this.viewport.off('media', this.handleMedia)
		this.viewport.off('resize', this.handleResize)
		window.removeEventListener('scroll', this.handleScroll)

		this.element.removeEventListener('mousemove', this.handleMove)
		this.element.removeEventListener('mouseleave', this.handleLeave)

		this.slides.forEach(slide => {
			slide.elem.removeEventListener('mouseenter', slide.over)
		});
	}

	handleMedia = () => {
		if(this.viewport.medias['desktop']) {
			if(!this.eventsTriggered) {
				this.eventsTriggered = true;
				this.element.addEventListener('mousemove', this.handleMove)
				this.element.addEventListener('mouseleave', this.handleLeave)
				this.slides.forEach(slide => {
					slide.elem.addEventListener('mouseenter', slide.over)
				});
			}
		} else {
			this.eventsTriggered = false;
			this.element.removeEventListener('mousemove', this.handleMove)
			this.element.removeEventListener('mouseleave', this.handleLeave)

			this.slides.forEach(slide => {
				slide.elem.removeEventListener('mouseenter', slide.over)
			});
		}
	}

	handleResize = () => {
		this.top = this.element.getBoundingClientRect().top + this.scrollY;
	}
	handleScroll = () => {
		this.scrollY = window.scrollY;
	}

	handleMove = (e) => {
		this.container.style.transform = 'translate3d('+e.clientX+'px, '+(e.clientY + this.scrollY - this.top)+'px, 0px)';
	}

	handleOver = (slide) => {
		this.container.style.display = 'block';

		if(this.t != null) this.t.kill()
		this.t = gsap.timeline()
		this.t.to(this.inner, {opacity: 0, duration: 0.2, ease: 'none'});
		this.t.add(() => {
			this.image.src = slide.img.src;
		})
		this.t.set(this.inner, {scale: 0.8, rotation: -20})
		this.t.to(this.inner, {scale: 1, rotation: 6, duration: 1, ease: 'expo.out'}, 0.2)
		this.t.to(this.inner, {opacity: 1, duration: .5, ease: 'none'}, 0.2)
	}

	handleLeave = () => {
		this.container.style.display = 'none';
	}
}