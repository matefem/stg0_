import { StaticComponent } from 'libs';
import { gsap } from 'gsap';
import { SplitText } from "gsap/SplitText";

import SphereSlideshow from '../../libs/SphereSlideshow.js';

export class HomeSlideshow extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.handleMove 		= this.handleMove.bind(this);
		this.handleWheel 		= this.handleWheel.bind(this);
		this.handleResize 		= this.handleResize.bind(this);
		this.handleUp 			= this.handleUp.bind(this);
		this.handleDown 		= this.handleDown.bind(this);
		this.handleDownTrigger 	= this.handleDownTrigger.bind(this);
		this.checkStillDown 	= this.checkStillDown.bind(this);
		this.closeSlideshow 	= this.closeSlideshow.bind(this);
		this.scrollTo 		= this.scrollTo.bind(this);

		this.canvas			= this.element.querySelector('.slideshow-canvas');
		this.pointer		= this.element.querySelector('.hold-container');
		this.pointerInner	= this.pointer.querySelector('.inner');
		this.close			= this.element.querySelector('.close');
		this.title			= this.element.querySelector('.title');
		this.number			= this.element.querySelector('.number');
		this.numberInner	= this.number.querySelector('.inner');

		this.pointerBBox = this.pointer.getBoundingClientRect();

		this.scrollY 	 	= 0;
		this.current 	 	= null;
		this.mouse 		 	= [0, 0];
		this.start 		 	= [0, 0];
		this.down 			= false;
		this.target 		= 0;
		this.initial 		= 0;
		this.opened 		= false;
		this.titleOpened	= false;
		this.delta			= 0;

		this.tl 			= null;

		this.slides = Array.from(document.querySelectorAll('[data-menu="1"]')).map((elem, i) => {
			return {
				elem,
				img: 		elem.getAttribute('data-menu-img'),
				content: 	elem.getAttribute('data-menu-content'),
				n: 			(i < 10 ? '0':'')+(i+1)
			}
		});


		this.slideshow = new SphereSlideshow(
			this.canvas,
			this.slides.map(e => e.img)
		);
    }
	
	openSlideshow(){
		if(this.opened) return;
		this.handleResize();

		this.title.innerHTML = '';

		const t = gsap.timeline();
		this.target = 0;

		t.set(this.element, {display: 'block'}, 0);
		t.call(this.slideshow.open);
		t.call(this.handleResize);

		t.fromTo(this.element, {opacity: 0}, {opacity: 1}, 0);
		t.fromTo(this.slideshow, {opacity: 0}, {opacity: 1}, 0);
		t.fromTo(this.slideshow, {progress: -2}, {progress: 0, ease: 'power2.inOut', duration: 1}, 0.3);

		t.add(() => {
			this.showTitle(this.slides[0])
		})
		window.addEventListener('wheel', this.handleWheel, {passive: false});
		this.element.addEventListener('mousemove', this.handleMove);
		this.close.addEventListener('click', this.closeSlideshow);
		this.title.addEventListener('click', this.scrollTo);
		this.viewport.on('resize', this.handleResize)

		this.opened = true;

		return t;
	}

	closeSlideshow(){
		if(!this.opened) return;

		this.hideTitle();
		window.removeEventListener('wheel', this.handleWheel);
		this.element.removeEventListener('mousemove', this.handleMove);
		this.close.removeEventListener('click', this.closeSlideshow);
		this.viewport.off('resize', this.handleResize)

		this.opened = false;

		const t = gsap.timeline();

		t.to(this.slideshow, {progress: this.slides.length, ease: 'power2.inOut', duration: 1}, 0);
		t.to(this.element, {opacity: 0}, 0.4);
		t.to(this.slideshow, {opacity: 0}, 0.4);

		t.set(this.slideshow, {progress: -2});
		t.add(this.slideshow.close);
		t.set(this.element, {display: 'none'});

		return t;
	}

	scrollTo() {
		this.closeSlideshow();

		gsap.to(window, {
			duration: 1,
			ease: 'power.out',
			scrollTo: {
				y: this.slides[Math.round(this.target)].elem,
				ease: 'power.inOut'
				// offsetY: 100
			}
		});
	}

	showTitle(slide) {
		if(this.titleOpened) return;
		this.titleOpened = true

		this.current = slide;
		const t = gsap.timeline();

		this.numberInner.innerHTML 	= slide.n;
		this.title.innerHTML 		= slide.content;

		const split = new SplitText(this.title, {type:"lines"});
		const lines = split.lines.map(line => {
			line.classList.add('line-hi')
			return new SplitText(line, {type:"words"});
		});

		t.fromTo(this.numberInner, {opacity: 0}, {opacity: 1});
		t.fromTo(this.numberInner, {y: 40}, {y: 0, duration: 0.5, ease:'power2.out'}, 0);
		t.fromTo(this.title, {opacity: 0}, {opacity: 1}, 0.2);

		lines.forEach((line) => {
			t.set(line.words, {y: -120, rotation: -12}, 0)
			t.to(line.words, {y: 0, rotation: 0, ease:'power2.out', duration:0.5, stagger: 0.04}, 0.2)
		});

		return t;
	}

	hideTitle() {
		if(!this.titleOpened) return;
		this.titleOpened = false

		const t = gsap.timeline();

		t.to(this.numberInner, {opacity: 0, duration: 0.2}, 0);
		t.to(this.title, {opacity: 0, duration: 0.2}, 0);

		return t;
	}

	hideThenShowTitle(slide) {
		if(this.tl) this.tl.clear();
		this.tl = gsap.timeline();

		this.tl.add(this.hideTitle());
		this.tl.add(this.showTitle(slide));

	}

	attach(){
		if(!this.slides.length) return;
		this.slides[0].elem.addEventListener('mousedown', this.handleDownTrigger);
		this.element.addEventListener('mousedown', this.handleDown);
		window.addEventListener('mouseup', this.handleUp);


		this.slideshow.attach();
		// if(this.viewport.medias['desktop']) {
		// 	this.openSlideshow()
		// }
	}
	detach(){
		if(!this.slides.length) return;
		this.slides[0].elem.removeEventListener('mousedown', this.handleDownTrigger);
		this.element.removeEventListener('mousedown', this.handleDown);
		window.removeEventListener('mouseup', this.handleUp);

		this.slideshow.detach();
	}

	handleResize() {
		this.pointerBBox = this.pointer.getBoundingClientRect();

		this.slideshow.resize()

		if(this.current) {
			this.title.innerHTML = this.current.content;
			const split = new SplitText(this.title, {type:"lines"});
		}
	}

	handleWheel(e) {
		e.preventDefault();
		e.stopPropagation();
		
		if(Math.abs(e.deltaY) <= 2) {
			return;
		}

		this.target = Math.max(Math.min(this.target + e.deltaY * 0.001, this.slides.length - 1), 0);
		this.slideshow.target = Math.round(this.target);

		if(this.slides[Math.round(this.target)] != this.current) {
			this.hideThenShowTitle(this.slides[Math.round(this.target)])
		}
	}

	handleMove(e) {
		this.mouse = [e.clientX, e.clientY];
		if(this.down) {
			if(this.titleOpened && Math.abs(this.start[1] - this.mouse[1]) > 5) {
				this.hideTitle(this.slides[Math.round(this.target)])
			}

			this.target = Math.max(Math.min(this.initial + (this.start[1] - this.mouse[1]) / this.viewport.height * 2.0, this.slides.length - 1), 0);
			this.slideshow.target = this.target
		}

		this.transformCursor(this.mouse);
	}

	handleDownTrigger(e) {
		this.down = true;
		if(!this.viewport.medias.desktop) return;

		if(!this.opened) setTimeout(this.checkStillDown, 1000)
	}

	handleDown(e) {
		this.deltaY = 0;
		this.down = true;
		if(!this.viewport.medias.desktop) return;
		if(this.opened)  {
			this.start = [e.clientX, e.clientY]
			this.initial = this.target;
			this.pointer.classList.add('click');
		}
	}

	handleUp() {
		this.down = false;
		this.pointerInner.style.transform = ''
		this.slideshow.target = this.target = Math.round(this.target)
		this.transformCursor(this.mouse);
		this.pointer.classList.remove('click');
		
		this.showTitle(this.slides[this.target])

	}

	checkStillDown() {
		if(this.down) {
			this.openSlideshow();
		}
	}


	transformCursor(m) {
		this.pointer.style.transform = 'translate3d('+(m[0] - this.pointerBBox.width * 0.5)+'px, '+(m[1] - this.pointerBBox.height * 0.5)+'px, 0)'
	}
}