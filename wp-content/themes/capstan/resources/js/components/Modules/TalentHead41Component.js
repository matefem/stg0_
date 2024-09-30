import { StaticComponent } from 'libs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Fit } from 'libs';
import { titleReveal } from '../../animations'

export class TalentHead41Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.container 	= this.element.querySelector('.container')
		this.main		= this.element.querySelector('.main')
		this.background	= this.element.querySelector('.main-bg')
		this.img		= this.background.querySelector('img')
		this.columns	= Array.from(this.element.querySelectorAll('.column'));
		this.images		= Array.from(this.element.querySelectorAll('.secondary-img'));
		this.career 	= this.element.querySelector('.careers');
		this.text 		= this.element.querySelector('.text');

		this.scrollCta 	= this.element.querySelector('.scroll-cta');

		this.st 	= null;
		this.t 		= null;
		this.enter 	= null;
    }

	attach(){
		this.scrollCta.addEventListener('click', this.scrollNext);

		this.viewport.on('resize', this.handleResize)
		this.viewport.on('media', this.handleMedia)

		setTimeout(this.computeAnimations, 20)
	}

	detach(){
		this.scrollCta.removeEventListener('click', this.scrollNext);

		this.viewport.off('resize', this.handleResize)
		this.viewport.off('media', this.handleMedia)

		this.reset();
	}

	open() {
	}

	reset() {
		// if(this.enter) this.enter.clear();
		// if(this.t) this.t.clear();
		if(this.st) {this.st.disable(); this.st = null;}

		gsap.set(this.images, {clearProps: 'all'})
		gsap.set(this.background, {clearProps: 'all'})
		gsap.set(this.career, {clearProps: 'all'})
		gsap.set(this.columns, {clearProps: 'all'})
		gsap.set(this.text, {clearProps: 'all'})

		if(this.titleReveal)
			this.titleReveal.reset()

	}

	computeAnimations = () => {
		this.reset();

		if(!this.viewport.medias.desktop) {
			return;
		}
		this.enter = gsap.timeline();
		this.enter.fromTo(this.images, {y: 500}, {y: 0, duration: 0.5, ease: 'power2.out', stagger: {each: 0.02, from: 'random'}}, 0)

		
		this.t = gsap.timeline({paused: true})
		this.t.fromTo(this.background, {scale: 0.5}, {scale: 1, duration: 1, ease: 'expo.inOut'}, 0);
		
		this.t.fromTo(this.career, {y: 0, opacity: 1}, {y: 200, duration: 0.5, opacity: 0, ease: 'power2.in'}, 0);

		this.t.fromTo([this.columns[0], this.columns[1]], {x:0, scale: 1}, {x: () => -this.viewport.width * 0.5, duration: 0.8, scale: 1.5, ease: 'power2.inOut'}, 0.2);
		this.t.fromTo([this.columns[3], this.columns[4]], {x:0, scale: 1}, {x: () => this.viewport.width * 0.5, duration: 0.8, scale: 1.5, ease: 'power2.inOut'}, 0.2);


		this.t.fromTo([this.columns[0], this.columns[3]], {y: 0}, {y: () => (this.viewport.height - this.columns[0].offsetHeight - 150), duration: 1, ease: 'linear'}, 0);
		this.t.fromTo([this.columns[1], this.columns[4]], {y: () => (this.viewport.height - this.columns[0].offsetHeight)}, {y: 0, duration: 1, ease: 'linear'}, 0);

		this.t.fromTo(this.text, {opacity: 0}, {opacity: 1, ease: 'linear', duration: 0.1}, 0.7)
		this.titleReveal = new titleReveal(this.text);

		this.t.add(() => this.titleReveal.timeline(), 0.7);

		this.st = ScrollTrigger.create({
			trigger: this.container,
			animation: this.t,
			start: 'top top',
			end: '+=3000',
			scrub: 0.5,
			pin: true,
			pinSpacing:false,
			anticipatePin: 1,
			invalidateOnRefresh: true
		});
	}


	handleResize = () => {
		if(this.t)
			this.t.invalidate()
	}

	handleMedia = () => {
		this.computeAnimations();
	}

	scrollNext = (e) => {
		gsap.to(window, {
			duration: 1,
			ease: 'power2.inOut',
			scrollTo: 3000 + this.viewport.height
		});
	}
}