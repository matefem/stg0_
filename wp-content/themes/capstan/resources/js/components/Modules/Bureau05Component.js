import { StaticComponent } from 'libs';
import { gsap } from 'gsap';


export class Bureau05Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.next 			= this.next.bind(this);
		this.handleResize 	= this.handleResize.bind(this);

		this.gallery 	= this.element.querySelector('.gallery');
		this.line 		= this.element.querySelector('.line');
		this.infos 		= Array.from(this.element.querySelectorAll('.infos'));
		this.slides 	= Array.from(this.gallery.querySelectorAll('.item')).map((elem, i) => {
			return {
				elem,
				img: elem.querySelector('img'),
				n: i,
				infos: {
					elem: this.infos[i],
					logo: this.infos[i].querySelector('.logo>span'),
					adresse: this.infos[i].querySelector('.adresse>span')
				}
			}
		});

		this.slides.forEach(slide => {
			gsap.set(slide.elem, { zIndex: 1});
			gsap.set(slide.infos.elem, {display: 'none'}, 0);
		});

		this.tl			= null;
		this.running 	= false;
		this.current 	= -1;
		this.length 	= this.slides.length;
		this.width		= 1;
    }
	
	open(){
	}

	close(){
	}

	attach(){
		this.handleResize();
		this.viewport.on('resize', this.handleResize)

		this.slides.forEach(elem => {
			gsap.set(elem.elem, { x: -this.width * 2});
		})

		this.run();
	}

	detach(){
		this.viewport.off('resize', this.handleResize)
	}

	handleResize() {
		this.width = this.gallery.getBoundingClientRect().width

		this.slides.forEach(slide => {
			gsap.set(slide.img, {x: -this.width}, 0);
		});
	}

	next(){
		if(!this.running) return
		if(this.tl) this.tl.clear();
		this.tl = gsap.timeline();

		const cur 	= this.slides[this.current];
		const n 	= (this.current+1) % this.length;


		const next 	= this.slides[n];

		this.tl.set(next.elem, {zIndex: 1, x: 0});
		this.tl.set(next.img, {x: 0});
		if(cur) this.tl.set(cur.elem, {display: 'block', zIndex: 2});
		this.tl.set(this.line, {scaleX: (n + 0.5) / (this.length - 0.5)}, 0);

		const skew = this.viewport.medias.desktop ? 20 : 5;

		if(cur) this.tl.fromTo(cur.infos.logo, {y: 0}, {y: -50, ease: 'power2.in', duration: 0.5}, 0);
		if(cur) this.tl.fromTo(cur.infos.adresse, {y: 0}, {y: -200, ease: 'power2.in', duration: 0.5}, 0);

		if(cur) this.tl.set(cur.infos.elem, {display: 'none'}, 0.5);
		this.tl.set(next.infos.elem, {display: ''}, 0.5);

		this.tl.fromTo(next.infos.logo, {y: 50}, {y: 0, ease: 'power2.out', duration: 0.5}, 0.5);
		this.tl.fromTo(next.infos.adresse, {y: 200}, {y: 0, ease: 'power2.out', duration: 0.5}, 0.5);


		if(cur) this.tl.fromTo(cur.elem, {x: 0, skewX: -skew}, {x: -this.width * 2, skewX: 0, duration: 1, ease: 'sine.inOut'}, 0)
		if(cur) this.tl.fromTo(cur.img, {x: 0, skewX: skew}, {x: this.width * 0.5, skewX: 0, duration: 1, ease: 'sine.inOut'}, 0)

		this.current = n;

		this.tl.add(() => {
			if(this.running) this.next()
		}, '+=4');
	}

	run(){
		this.running = true;
		this.next()
	}
	stop(){
		this.running = false;
	}
}