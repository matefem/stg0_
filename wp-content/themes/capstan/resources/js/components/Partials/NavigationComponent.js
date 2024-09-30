import { StaticComponent } from 'libs';
import { gsap } from 'gsap';

export class NavigationComponent extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.handleResize 	= this.handleResize.bind(this)
		this.handleScroll 	= this.handleScroll.bind(this)
		this.handleUp		= this.handleUp.bind(this)
		this.handleDown 	= this.handleDown.bind(this)

		this.homeSlideshow = document.querySelector('.home-slideshow');

		this.info 		= this.element.querySelector('.info');
		this.dots 		= this.element.querySelector('.dots');
		// this.up 		= this.element.querySelector('.arrows .arrow');
		// this.down 		= this.element.querySelector('.arrows .arrow.down');
		this.menu 		= this.element.querySelector('.menu')
		this.contact 	= this.element.querySelector('.contact-us .inner');

		this.parts = Array.from(document.querySelectorAll('[data-menu="1"]')).map((elem, i) => {
			const dot = document.createElement('div');
			dot.classList.add('dot')
			dot.appendChild(document.createElement('span'));
			this.dots.appendChild(dot);

			return {
				elem,
				dot,
				n: i,
				event: null,
				top: 0
			}
		});

		this.backgrounds = Array.from(document.querySelectorAll('[data-background]')).map(elem => {
			return {
				elem,
				top: 	0,
				bottom: 0,
				value: 	elem.getAttribute('data-background')
			}
		});

		this.parts.forEach(part => {
			part.event = this.handleClick.bind(this, part)
		});

		this.scrollY 	= 0;
		this.current 	= 0;
		this.lastBg		= 0;
		this.currentBg	= 0;
		this.length 	= (this.parts.length<10?'0':'')+this.parts.length+'';

		this.info.innerHTML = '1/' + this.length;
    }
	
	open(){
		if(this.viewport.medias.desktop) {
			const t = gsap.timeline();

			t.fromTo(this.element, {opacity: 0}, {opacity: 1, duration: 0.2}, 0.5);
			t.fromTo(this.contact, {y: 60}, {y: 0, duration: 0.4, ease: 'power2.out'}, 1);
			t.fromTo(this.menu, {y: 60}, {y: 0, duration: 0.4, ease: 'power2.out'}, 1.1);

			this.parts.forEach((part, i) => {
				t.fromTo(part.dot, {opacity: 0}, {opacity: 1, duration: 0.4}, 0.7 + i * 0.2);
			});
		}
	}
	close(){

	}

	attach(){
		this.handleResize()
		this.handleScroll()
		this.viewport.on('resize', this.handleResize)

		this.parts.forEach(part => {
			part.dot.addEventListener('click', part.event)
		});

		// this.up.addEventListener('click', this.handleUp);
		// this.down.addEventListener('click', this.handleDown);

		this.menu.addEventListener('click', this.openSlideshow)

		window.addEventListener('scroll', this.handleScroll);

		this.open()
		// this.checkArrows();
	}
	detach(){
		this.viewport.off('resize', this.handleResize)

		this.parts.forEach(part => {
			part.dot.removeEventListener('click', part.event)
		});

		// this.up.removeEventListener('click', this.handleUp);
		// this.down.removeEventListener('click', this.handleDown);

		this.menu.removeEventListener('click', this.openSlideshow)

		window.removeEventListener('scroll', this.handleScroll);
	}

	handleResize() {
		this.parts.forEach(part => {
			part.top = part.elem.getBoundingClientRect().top - this.viewport.height * 0.5 - this.scrollY;
		});
		this.backgrounds.forEach(bg => {
			const bbox = bg.elem.getBoundingClientRect();

			bg.top 	= bbox.top - this.scrollY - this.viewport.height + 120;
			bg.bottom = bg.top + bbox.height;
		});
	}

	handleClick(part) {
		this.scrollTo(part.elem)
	}

	handleScroll() {
		this.scrollY = window.scrollY;

		let last = 0
		this.parts.forEach((part, i) => {
			if(part.top < scrollY) {
				last = i
				if(!part.dot.classList.contains('active'))
					part.dot.classList.add('active')
			} else if(part.dot.classList.contains('active')) {
				part.dot.classList.remove('active')
			}
		});

		if(this.current != last) {
			this.current = last;

			this.info.innerHTML = (this.current+1)+'/' + this.length;
			// this.checkArrows();
		}

		// let trigger = false;
		this.backgrounds.forEach(bg => {
			if(this.scrollY > bg.top && this.scrollY < bg.bottom) {
				this.currentBg = bg;
				// trigger = true;
			}
		});
		// if(!trigger) this.currentBg = null

		if(this.lastBg.value != this.currentBg.value) {
			if(this.lastBg?.value != this.currentBg?.value) {
				if(this.element.classList.contains(this.lastBg?.value)) 	this.element.classList.remove(this.lastBg?.value)
				if(!this.element.classList.contains(this.currentBg?.value)) this.element.classList.add(this.currentBg?.value)
			}

			this.lastBg = this.currentBg;
		}
	}

	scrollTo(target) {
		gsap.to(window, {
			duration: 1,
			ease: 'power2.inOut',
			scrollTo: target
		});
	}

	checkArrows() {
		if(this.parts[this.current - 1]) {
			if(this.up.classList.contains('disabled')) this.up.classList.remove('disabled')
		} else {
			if(!this.up.classList.contains('disabled')) this.up.classList.add('disabled')
		}

		if(this.parts[this.current + 1]) {
			if(this.down.classList.contains('disabled')) this.down.classList.remove('disabled')
		} else {
			if(!this.down.classList.contains('disabled')) this.down.classList.add('disabled')
		}
	}

	handleUp() {
		let target = null;

		if(this.scrollY > this.parts[this.current].top + this.viewport.height)
			target = this.parts[this.current]
		else if(this.parts[this.current - 1]) 
			target = this.parts[this.current - 1]
		

		if(target && target.elem)
			this.scrollTo(target.elem);

		this.checkArrows();
	}

	handleDown() {
		if(this.current + 1 < this.parts.length && this.parts[this.current + 1].elem)
			this.scrollTo(this.parts[this.current + 1].elem);

		this.checkArrows();
	}

	openSlideshow = () => {
		if(this.homeSlideshow) {
			this.bootstrap.components.get(this.homeSlideshow).openSlideshow();
		}
	}
}