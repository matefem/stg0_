import { gsap } from 'gsap';
import { Animation } from "./Animation";

export class imageReveal extends Animation {

	constructor(elem, props=null) {
		super(elem, props);

		this.img = elem.querySelector('img')

		this.t = null;
	}

	timeline() {
		if(this.t) this.t.clear();
		this.t = gsap.timeline();

		this.h = this.elem.getBoundingClientRect().height
		// this.t.set(this.img, {scale: 1});

		this.t.fromTo(this.img, {y: this.h}, {y: 0, duration: 1, ease: 'power2.out'}, 0);
		this.t.fromTo(this.elem, {y: this.h * 0.5}, {y: 0, duration: 1, ease: 'power2.out'}, 0);

		return this.t;
	}
}